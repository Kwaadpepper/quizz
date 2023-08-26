<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->configureRateLimiting();
        $this->cspReportRoute();

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(150)->by(optional($request->user())->id ?: app('clientIp'));
        });
        RateLimiter::for('web', function (Request $request) {
            return Limit::perMinute(150)->by(optional($request->user())->id ?: app('clientIp'));
        });
    }

    /**
     * * Register CSP report URI
     *
     * Put this function inside RouteServiceProvider,
     * and call it in RouteServiceProvider::boot
     *
     * ! Use this in conjonction with kwaadpepper/exception-handler
     *
     * @return void
     */
    private function cspReportRoute()
    {
        // * CSP REPORT URI, Please add /csp-report to VerifyCsrfToken $except middleware
        \Illuminate\Support\Facades\Route::post('/csp-report', function () {
            $isCspRequest = request()->header('Content-Type') === 'application/csp-report';
            if (!$isCspRequest or !request()->json('csp-report') or !is_array(request()->json('csp-report'))) {
                abort(401);
            }
            // * Flatten if needed
            $cspReport = collect(request()->json('csp-report'))
                ->flatMap(function ($v, $k) {
                    return [$k => is_array($v) ? collect($v)->flatten()->implode(', ') : $v];
                })->all();
            // * Filter array keys
            $cspReport = collect(array_intersect_key($cspReport, [
                'blocked-uri' => '', 'document-uri' => '', 'original-policy' => '',
                'referrer' => '', 'violated-directive' => ''
            ]));
            \Illuminate\Support\Facades\Log::warning(sprintf(
                "CSP REPORT for %s : \n %s",
                $cspReport->get('document-uri'),
                $cspReport->implode("\n")
            ));
        })->middleware('throttle:5,2')
            ->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class)
            ->name('cspReportUri');
    }
}
