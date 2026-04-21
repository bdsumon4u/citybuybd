<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Support\UtmAttribution;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class CaptureUtmAttribution
{
    /**
     * @param  Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $attribution = UtmAttribution::fromRequest($request);
        $request->session()->put(UtmAttribution::COOKIE_NAME, $attribution);

        $response = $next($request);

        if ($attribution !== []) {
            Cookie::queue(Cookie::make(
                UtmAttribution::COOKIE_NAME,
                json_encode($attribution, JSON_UNESCAPED_SLASHES),
                UtmAttribution::COOKIE_MINUTES,
                '/',
                null,
                $request->isSecure(),
                false,
                false,
                'Lax'
            ));
        }

        return $response;
    }
}
