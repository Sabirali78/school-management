<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'nullable|string',
        ]);

        return DB::transaction(function () use ($data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'role' => $data['role'] ?? 'Student',
            ]);

            Auth::login($user);

            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }

            if ($user->isTeacher()) {
                return redirect()->route('teacher.dashboard');
            }

            if ($user->isStudent()) {
                return redirect()->route('student.dashboard');
            }

            return redirect()->intended(route('dashboard', [], false));
        });
    }
}
