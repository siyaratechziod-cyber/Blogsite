<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(): View
    {
        return view('admin.profile.edit', ['user' => Auth::user()]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'specialty' => ['nullable', 'string', 'max:100'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'profile_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:3072'],
            'cover_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:5120'],
            'remove_profile_image' => ['nullable', 'boolean'],
            'remove_cover_image' => ['nullable', 'boolean'],
            'twitter' => ['nullable', 'url', 'max:255'],
            'github' => ['nullable', 'url', 'max:255'],
            'linkedin' => ['nullable', 'url', 'max:255'],
        ]);

        if ($request->hasFile('profile_image')) {
            $this->deleteUpload($user->profile_image);
            $data['profile_image'] = Storage::disk('public')->url(
                $request->file('profile_image')->store('avatars', 'public')
            );
        } elseif ($request->boolean('remove_profile_image')) {
            $this->deleteUpload($user->profile_image);
            $data['profile_image'] = null;
        }

        if ($request->hasFile('cover_image')) {
            $this->deleteUpload($user->cover_image);
            $data['cover_image'] = Storage::disk('public')->url(
                $request->file('cover_image')->store('covers', 'public')
            );
        } elseif ($request->boolean('remove_cover_image')) {
            $this->deleteUpload($user->cover_image);
            $data['cover_image'] = null;
        }

        $data['social_links'] = array_filter([
            'twitter' => $data['twitter'] ?? null,
            'github' => $data['github'] ?? null,
            'linkedin' => $data['linkedin'] ?? null,
        ]);

        unset($data['twitter'], $data['github'], $data['linkedin'], $data['remove_profile_image'], $data['remove_cover_image']);

        $user->update($data);

        return back()->with('success', 'Profile updated successfully.');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        Auth::user()->update(['password' => $data['password']]);

        return back()->with('success', 'Password changed successfully.');
    }

    private function deleteUpload(?string $url): void
    {
        if (! $url || ! str_starts_with($url, '/storage/')) {
            return;
        }

        Storage::disk('public')->delete(Str::after($url, '/storage/'));
    }
}
