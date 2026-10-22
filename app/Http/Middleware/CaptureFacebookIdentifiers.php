<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Support\FacebookTracking;
use Closure;
use Illuminate\Http\Request;

class CaptureFacebookIdentifiers
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next)
    {
        // Resolve identifiers on incoming request and queue cookies for outgoing response
        FacebookTracking::queueCookies($request);

        return $next($request);
    }
}
