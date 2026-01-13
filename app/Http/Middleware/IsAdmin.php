<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (! $user || ! method_exists($user, 'isAdmin') || ! $user->isAdmin()) {
            // Redirect users to their role-specific dashboards instead of aborting
            if ($user && method_exists($user, 'isTeacher') && $user->isTeacher()) {
                return redirect()->route('teacher.dashboard');
            }

            if ($user && method_exists($user, 'isStudent') && $user->isStudent()) {
                return redirect()->route('student.dashboard');
            }

            // Fallback: send to generic dashboard
            return redirect()->route('dashboard');
        }

        return $next($request);
    }
}
