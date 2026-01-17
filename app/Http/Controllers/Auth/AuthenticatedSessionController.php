<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view(\Illuminate\Auth\Events\Login::class);
    }

    /**
     * Handle an incoming authentication request.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();
        if (Auth::user()->role == 1) {

            $request->session()->regenerate();

            return redirect()->intended(RouteServiceProvider::ADMIN_DASHBOARD);

        } elseif (Auth::user()->role == 2) {

            $request->session()->regenerate();

            return redirect()->intended(RouteServiceProvider::MANAGER_DASHBOARD);

        } elseif (Auth::user()->role == 3) {

            $request->session()->regenerate();

            return redirect()->intended(RouteServiceProvider::EMPLOYEE_DASHBOARD);

        }

    }

    /**
     * Destroy an authenticated session.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
