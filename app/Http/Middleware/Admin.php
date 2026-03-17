<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Admin
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->user()->role == 1) {
            if ($redirect = PanelTimeRestriction::check($request)) {
                return $redirect;
            }

            (new TrackUserActivity)->handle($request, fn ($r) => $r);

            return $next($request);

        } elseif (auth()->user()->role == 3) {
            return to_route('employee.dashboard');
        } elseif (auth()->user()->role == 2) {
            return to_route('manager.dashboard');
        }
    }
}
