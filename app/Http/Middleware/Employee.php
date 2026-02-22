<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Employee
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->user()->role == 3) {
            if ($redirect = PanelTimeRestriction::check($request)) {
                return $redirect;
            }

            return $next($request);

        } elseif (auth()->user()->role == 1) {
            return to_route('admin.dashboard');
        } elseif (auth()->user()->role == 2) {
            return to_route('manager.dashboard');
        }
    }
}
