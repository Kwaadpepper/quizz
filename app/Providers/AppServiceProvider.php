<?php

namespace App\Providers;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        // * HTTPS FIX
        URL::forceScheme('https');

        // * LOCAL FORCE eg: for Carbon
        // * Remove numeric to prevent float seperator to breaking numeric transactions
        \collect([\LC_TIME, \LC_MESSAGES, \LC_COLLATE, \LC_CTYPE, \LC_MONETARY])
            ->map(function ($contant) {
                setlocale(
                    $contant,
                    \sprintf(
                        '%s_%s.UTF-8',
                        config('app.locale'),
                        \strtoupper(config('app.locale'))
                    )
                );
            });
        Carbon::setLocale(config('app.locale'));

        // * HA PROXY FIX
        App::singleton('clientIp', function () {
            if (config('app.env') === 'local') {
                return request()->getClientIp();
            } else {
                return request()->server('HTTP_X_FORWARDED_FOR') ??
                    request()->getClientIp();
            }
        });
    }
}
