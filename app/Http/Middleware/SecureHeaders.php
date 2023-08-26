<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Vite;
use Symfony\Component\HttpFoundation\Response;

class SecureHeaders
{
    /**
     * Enumerate headers which you do not want in your application's responses.
     * Great starting point would be to go check out @Scott_Helme's:
     * https://securityheaders.com/
     *
     * @var array
     */
    private $unwantedHeaderList = [
        'X-Powered-By',
        'Server',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request                                                         $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     * @return \Symfony\Component\HttpFoundation\Response
     * @phpcs:disable Squiz.Commenting.FunctionComment.IncorrectTypeHint
     */
    public function handle(Request $request, Closure $next): Response
    {
        // phpcs:enable
        $host = \parse_url(\config('app.url'), \PHP_URL_HOST);

        View::share('nonce', $nonce = Vite::useCspNonce());
        // * For VueJS dev tool to execute
        $jsDev     = config('app.debug') ? "'unsafe-eval' 'unsafe-inline'" : "'nonce-$nonce' 'strict-dynamic'";
        $scheme    = config('app.env') === 'local' ? 'http' : 'https';
        $reportUri = Route::has('cspReportUri') ?
            \sprintf(' report-uri %s ;', \parse_url(route('cspReportUri'), \PHP_URL_PATH)) : '';
        // * Special Case for mondial relay
        if ($request->routeIs('orders.shipping')) {
            $jsDev = "'unsafe-eval' 'unsafe-inline'";
        }
        $this->removeUnwantedHeaders($this->unwantedHeaderList);
        $response = $next($request);
        $response->headers->set('Referrer-Policy', 'no-referrer-when-downgrade');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains;');
        $cspPolicies = [
            "default-src 'none';",
            "manifest-src 'self';",
            // * CKEDITOR Specific https://ckeditor.com/docs/ckeditor5/latest/installation/advanced/csp.html
            "connect-src 'self' " . implode(' ', [
                // * VITE DEV
                config('app.debug') ? "wss://vite.{$host}:4443" : '',
                'https://widget.mondialrelay.com',
                'https://www.sandbox.paypal.com',
                'https://www.paypal.com',
                'https://www.google.com',
            ]) . ';',
            "img-src * data: blob:;",
            'frame-src ' . implode(' ', [
                'https://www.google.com/recaptcha/',
                'https://recaptcha.google.com/recaptcha/',
                'https://www.google.com/maps/',
                'https://www.paypal.com',
                'https://www.sandbox.paypal.com'
            ]) . ';',
            "font-src 'self' data: " . \implode(' ', [
                'https://fonts.bunny.net',
                'https://fonts.gstatic.com',
                'https://fonts.googleapis.com',
                'https://use.fontawesome.com',
            ]) . ";",
            // ! Using CKEditor will must have unsafe-inline style
            "style-src 'self' 'unsafe-inline' " . \implode(' ', [
                // * VITE DEV
                config('app.debug') ? "https://vite.{$host}:4443" : '',
                'https://cdn.jsdelivr.net',
                // * File Manager
                'https://fonts.bunny.net',
                'https://fonts.googleapis.com',
                'https://cdn.jsdelivr.net',
                'https://use.fontawesome.com',
                'https://unpkg.com/leaflet/dist/leaflet.css',
                'https://widget.mondialrelay.com',
            ]) . ";",

            // ! If you dont use ckeditor use this
            // "style-src 'self' 'nonce-$nonce';",
            "script-src 'self' 'unsafe-eval' $jsDev " . \implode(' ', [
                'blob:',
                // * VITE DEV
                config('app.debug') ? "https://vite.{$host}:4443" : '',
                'https://www.paypal.com',
                'https://cdn.jsdelivr.net',
                'https://www.google.com',
                'https://www.gstatic.com',
                'https://fonts.googleapis.com',
                'https://ajax.googleapis.com',
                'https://unpkg.com/leaflet/dist/leaflet.js',
                'https://widget.mondialrelay.com',
            ]) . ";"
        ];
        $response->headers->set(
            'Content-Security-Policy',
            implode(' ', $cspPolicies) . $reportUri
        );
        $response->headers->set('Feature-Policy', 'fullscreen \'self\'; geolocation \'self\';');
        return $response;
    }

    /**
     * Remove unwanted headers
     *
     * @param array $headerList
     * @return void
     */
    private function removeUnwantedHeaders(array $headerList)
    {
        foreach ($headerList as $header) {
            header_remove($header);
        }
    }
}
