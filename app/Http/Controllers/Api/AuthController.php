<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['nullable', 'string', 'max:50', 'alpha_dash', 'unique:users,username'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', PasswordRule::min(8)],
            'role' => ['nullable', 'in:reader,author'],
            'terms' => ['accepted'],
            'device_name' => ['nullable', 'string', 'max:100'],
        ]);

        $role = $validated['role'] ?? 'reader';

        $user = User::create([
            'name' => $validated['name'],
            'username' => $validated['username'] ?? User::generateUniqueUsername($validated['name']),
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $role,
            'bio' => $role === 'author' ? 'Passionate author on BlogHub.' : 'Tech enthusiast and avid reader.',
        ]);

        return $this->tokenResponse($user, $validated['device_name'] ?? null, 'Your account has been created! Welcome to BlogHub.', 201);
    }

    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'device_name' => ['nullable', 'string', 'max:100'],
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => 'The provided credentials do not match our records.',
            ]);
        }

        if (! $user->isActive()) {
            return response()->json([
                'message' => 'This account has been deactivated. Please contact the site administrator.',
            ], 403);
        }

        return $this->tokenResponse($user, $credentials['device_name'] ?? null, 'Welcome back, ' . $user->name . '!');
    }

    public function me(Request $request): JsonResponse
    {
        $user = $request->user()->loadCount(['posts', 'followers', 'following']);

        return response()->json([
            'user' => (new UserResource($user))->withPrivate(),
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'You have been logged out successfully.']);
    }

    public function logoutAll(Request $request): JsonResponse
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'You have been logged out from all devices.']);
    }

    public function forgotPassword(Request $request): JsonResponse
    {
        $request->validate(['email' => ['required', 'email']]);

        $status = Password::sendResetLink($request->only('email'));

        if ($status !== Password::RESET_LINK_SENT) {
            throw ValidationException::withMessages(['email' => __($status)]);
        }

        return response()->json(['message' => __($status)]);
    }

    public function resetPassword(Request $request): JsonResponse
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', PasswordRule::min(8)],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                // Sign out every device that used the old password.
                $user->tokens()->delete();

                event(new PasswordReset($user));
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            throw ValidationException::withMessages(['email' => __($status)]);
        }

        return response()->json(['message' => __($status)]);
    }

    private function tokenResponse(User $user, ?string $deviceName, string $message, int $status = 200): JsonResponse
    {
        $token = $user->createToken($deviceName ?: 'flutter-app')->plainTextToken;

        return response()->json([
            'message' => $message,
            'token' => $token,
            'token_type' => 'Bearer',
            'user' => (new UserResource($user))->withPrivate(),
        ], $status);
    }
}
