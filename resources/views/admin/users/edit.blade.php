@extends('layouts.admin')

@section('title', 'Edit User')
@section('page-title', 'Edit User')

@php($isSelf = $user->id === Auth::id())

@section('content')
    <a href="{{ route('admin.users.index') }}" class="text-sm text-muted hover-underline d-inline-block mb-3">
        <i class="bi bi-arrow-left me-1"></i>Back to Users
    </a>

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="bh-admin-card mb-4">
                <div class="bh-admin-card-header">
                    <h2 class="bh-admin-card-title">Account Settings</h2>
                    @if ($isSelf)
                        <span class="badge rounded-pill bg-primary-subtle text-primary-emphasis">This is you</span>
                    @endif
                </div>
                <div class="bh-admin-card-body">
                    <form action="{{ route('admin.users.update', $user) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="userName" class="form-label text-xs fw-semibold text-muted">Full Name *</label>
                            <input type="text" id="userName" name="name" class="bh-form-control w-100 @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="userEmail" class="form-label text-xs fw-semibold text-muted">Email Address *</label>
                            <input type="email" id="userEmail" name="email" class="bh-form-control w-100 @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="userRole" class="form-label text-xs fw-semibold text-muted">Role *</label>
                            @if ($isSelf)
                                <select id="userRole" class="bh-form-control w-100" disabled>
                                    <option>{{ ucfirst($user->role) }}</option>
                                </select>
                                <input type="hidden" name="role" value="{{ $user->role }}">
                                <p class="text-xs text-muted mb-0 mt-1">You cannot change your own role. Ask another admin to do it.</p>
                            @else
                                <select id="userRole" name="role" class="bh-form-control w-100 @error('role') is-invalid @enderror" required>
                                    <option value="reader" @selected(old('role', $user->role) === 'reader')>Reader — can comment, like, bookmark and follow</option>
                                    <option value="author" @selected(old('role', $user->role) === 'author')>Author — can write and manage their own posts</option>
                                    <option value="admin" @selected(old('role', $user->role) === 'admin')>Admin — full access to the CMS</option>
                                </select>
                                <p class="text-xs text-muted mb-0 mt-1">Changing the role changes what this user can access immediately.</p>
                            @endif
                        </div>

                        <div class="form-check form-switch mb-4">
                            @if ($isSelf)
                                <input class="form-check-input" type="checkbox" role="switch" id="userActive" checked disabled>
                                <label class="form-check-label text-sm" for="userActive">Account active</label>
                                <input type="hidden" name="is_active" value="1">
                                <p class="text-xs text-muted mb-0 mt-1">You cannot deactivate your own account.</p>
                            @else
                                <input class="form-check-input" type="checkbox" role="switch" id="userActive" name="is_active" value="1" @checked(old('is_active', $user->isActive()))>
                                <label class="form-check-label text-sm" for="userActive">Account active — deactivated users cannot sign in</label>
                            @endif
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn-bh-primary py-2 px-4 text-sm">
                                <i class="bi bi-check-lg me-1"></i>Save Changes
                            </button>
                            <a href="{{ route('admin.users.index') }}" class="btn-bh-secondary py-2 px-4 text-sm">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>

            @if (! $isSelf)
                <div class="bh-admin-card">
                    <div class="bh-admin-card-header">
                        <h2 class="bh-admin-card-title text-danger">Danger Zone</h2>
                    </div>
                    <div class="bh-admin-card-body">
                        @if ($user->posts()->exists())
                            <p class="text-xs text-muted mb-0">
                                <i class="bi bi-info-circle me-1"></i>This user has published content, so the account cannot be deleted — deactivate it instead to preserve their posts.
                            </p>
                        @else
                            <p class="text-xs text-muted mb-3">The account will be removed permanently. Uploaded avatars and covers are deleted too.</p>
                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" data-confirm="Permanently delete {{ $user->name }} ({{ $user->email }})? This cannot be undone.">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger py-2 px-3 text-sm">
                                    <i class="bi bi-trash3 me-1"></i>Delete Account
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <div class="col-lg-5">
            <div class="bh-admin-card">
                <div class="bh-admin-card-header">
                    <h2 class="bh-admin-card-title">Overview</h2>
                </div>
                <div class="bh-admin-card-body">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="bh-avatar bh-avatar-lg">
                        <div class="flex-grow-1">
                            <div class="fw-bold">{{ $user->name }}</div>
                            @if ($user->username)
                                <div class="text-xs text-muted">&#64;{{ $user->username }}</div>
                            @endif
                            <span class="bh-badge-role bh-badge-{{ $user->role }} mt-1 d-inline-block">{{ ucfirst($user->role) }}</span>
                        </div>
                    </div>

                    <dl class="bh-detail-list mb-0">
                        <dt>Specialty</dt>
                        <dd>{{ $user->specialty ?: '—' }}</dd>

                        <dt>Posts Written</dt>
                        <dd>{{ $user->posts()->count() }}</dd>

                        <dt>Joined</dt>
                        <dd>{{ $user->created_at?->format('F j, Y') }}</dd>

                        <dt class="mb-0">Public Profile</dt>
                        <dd class="mb-0">
                            <a href="{{ route('authors.show', $user) }}" target="_blank" rel="noopener" class="text-coral hover-underline">
                                View profile page <i class="bi bi-box-arrow-up-right text-xs"></i>
                            </a>
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
@endsection
