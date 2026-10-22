<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminUserController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::withCount('posts')->latest();

        if ($search = $request->input('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%");
            });
        }

        if ($role = $request->input('role')) {
            if (in_array($role, ['admin', 'author', 'reader'], true)) {
                $query->where('role', $role);
            }
        }

        if ($status = $request->input('status')) {
            if ($status === 'active') {
                $query->where('is_active', true);
            } elseif ($status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $users = $query->paginate(10)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function edit(User $user): View
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'role' => ['required', Rule::in(['admin', 'author', 'reader'])],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if ($user->id === Auth::id() && $data['role'] !== $user->role) {
            return back()->with('error', 'You cannot change your own role. Ask another admin to do it.')->withInput();
        }

        if ($user->isAdmin() && $data['role'] !== 'admin' && $this->isLastAdmin($user)) {
            return back()->with('error', 'This is the last remaining admin account, so its role cannot be changed.')->withInput();
        }

        $isActive = $request->boolean('is_active');

        if ($user->isActive() && ! $isActive) {
            if ($user->id === Auth::id()) {
                return back()->with('error', 'You cannot deactivate your own account.')->withInput();
            }

            if ($user->isAdmin() && $this->isLastActiveAdmin($user)) {
                return back()->with('error', 'This is the last active admin account, so it cannot be deactivated.')->withInput();
            }
        }

        $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => $data['role'],
            'is_active' => $isActive,
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    public function toggleStatus(User $user): RedirectResponse
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot deactivate your own account.');
        }

        if ($user->isAdmin() && $user->isActive() && $this->isLastActiveAdmin($user)) {
            return back()->with('error', 'This is the last active admin account, so it cannot be deactivated.');
        }

        $user->update(['is_active' => ! $user->isActive()]);

        return back()->with(
            'success',
            $user->isActive() ? 'User account activated.' : 'User account deactivated.'
        );
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        if ($user->posts()->exists()) {
            return back()->with('error', 'This user has published content. Deactivate the account instead of deleting it to avoid losing their posts.');
        }

        if ($user->isAdmin() && $this->isLastAdmin($user)) {
            return back()->with('error', 'This is the last remaining admin account, so it cannot be deleted.');
        }

        $this->deleteUpload($user->profile_image);
        $this->deleteUpload($user->cover_image);
        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }

    private function isLastAdmin(User $user): bool
    {
        return User::where('role', 'admin')
            ->whereKeyNot($user->id)
            ->doesntExist();
    }

    private function isLastActiveAdmin(User $user): bool
    {
        return User::where('role', 'admin')
            ->where('is_active', true)
            ->whereKeyNot($user->id)
            ->doesntExist();
    }

    private function deleteUpload(?string $url): void
    {
        if (! $url || ! str_starts_with($url, '/storage/')) {
            return;
        }

        Storage::disk('public')->delete(Str::after($url, '/storage/'));
    }
}
