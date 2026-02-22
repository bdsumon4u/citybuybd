<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PanelTimeRestriction
{
    /**
     * Check if the authenticated user is within their allowed panel time.
     * Returns a redirect response if outside the window, or null if allowed.
     */
    public static function check(Request $request): ?\Illuminate\Http\RedirectResponse
    {
        $user = Auth::user();

        if (! $user) {
            return null;
        }

        $now = Carbon::now();
        $panelStart = Carbon::parse($user->panel_start);
        $panelEnd = Carbon::parse($user->panel_end);

        // Overnight shift (e.g. 22:00 - 06:00): allowed if now >= start OR now <= end
        $isOvernight = $panelEnd->lte($panelStart);
        $withinTime = $isOvernight
            ? ($now->gte($panelStart) || $now->lte($panelEnd))
            : ($now->gte($panelStart) && $now->lte($panelEnd));

        if (! $withinTime) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->with(
                'status',
                'Your panel access is restricted to '.$user->panel_start.' - '.$user->panel_end.'. Please try again during your allowed hours.'
            );
        }

        return null;
    }

    /**
     * Middleware handle — delegates to the static check.
     */
    public function handle(Request $request, Closure $next)
    {
        if ($redirect = static::check($request)) {
            return $redirect;
        }

        return $next($request);
    }
}
