<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $viteHttp = app()->environment('local') ? ' http://127.0.0.1:5173 http://localhost:5173' : '';
        $viteWs = app()->environment('local') ? ' ws://127.0.0.1:5173 ws://localhost:5173' : '';

        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=(), payment=()');
        $response->headers->set('Content-Security-Policy-Report-Only', implode('; ', [
            "default-src 'self'",
            "img-src 'self' data: https:{$viteHttp}",
            "media-src 'self' https:",
            "style-src 'self' 'unsafe-inline' https://fonts.bunny.net{$viteHttp}",
            "script-src 'self' 'unsafe-inline'{$viteHttp}",
            "font-src 'self' data: https://fonts.bunny.net{$viteHttp}",
            "connect-src 'self'{$viteHttp}{$viteWs}",
            "frame-ancestors 'self'",
            "base-uri 'self'",
            "form-action 'self'",
            "object-src 'none'",
        ]));

        if ($request->isSecure() && app()->isProduction()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        return $response;
    }
}
