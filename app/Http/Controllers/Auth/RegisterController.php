<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function showRegistrationForm(): View
    {
        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['nullable', 'string', 'max:50', 'alpha_dash', 'unique:users,username'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
            'role' => ['nullable', 'in:reader,author'],
            'terms' => ['accepted'],
        ]);

        $username = $validated['username'] ?? User::generateUniqueUsername($validated['name']);
        $role = $validated['role'] ?? 'reader';

        $user = User::create([
            'name' => $validated['name'],
            'username' => $username,
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $role,
            'bio' => $role === 'author' ? 'Passionate author on BlogHub.' : 'Tech enthusiast and avid reader.',
        ]);

        Auth::login($user);

        return redirect()->route('home')->with('success', 'Your account has been created! Welcome to BlogHub.');
    }
}
