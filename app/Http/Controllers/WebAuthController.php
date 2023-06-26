<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WebAuthController extends Controller
{
    public function showLogin(): View
    {
        return view('login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('web')->attempt($credentials)) {
            // check if user has permission to view telescope

            if (! Auth::guard('web')->user()->hasPermissionTo('view telescope', 'web')) {
                return redirect('login')->withErrors([
                    'email' => 'You do not have permission to access.',
                ]);
            }

            $request->session()->regenerate();

            return redirect()->intended('telescope');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout(): RedirectResponse
    {
        Auth::guard('web')->logout();

        return redirect('login');
    }
}
