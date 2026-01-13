<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $remember = (bool) $request->input('remember', false);

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            $user = Auth::user();

            if ($user && method_exists($user, 'isAdmin') && $user->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }

            if ($user && method_exists($user, 'isTeacher') && $user->isTeacher()) {
                return redirect()->intended(route('teacher.dashboard', [], false));
            }

            if ($user && method_exists($user, 'isStudent') && $user->isStudent()) {
                return redirect()->intended(route('student.dashboard', [], false));
            }

            return redirect()->intended(route('dashboard', [], false));
        }

        return back()->withErrors(['email' => trans('auth.failed')])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
