<?php

namespace App\Http\Middleware;

use App\Http\Middleware\TrackUserActivity;
use Closure;
use Illuminate\Http\Request;

class Manager
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->user()->role == 2) {
            if ($redirect = PanelTimeRestriction::check($request)) {
                return $redirect;
            }

            (new TrackUserActivity())->handle($request, fn ($r) => $r);

            return $next($request);

        } elseif (auth()->user()->role == 1) {
            return to_route('admin.dashboard');
        } elseif (auth()->user()->role == 3) {
            return to_route('employee.dashboard');
        }
    }
}
