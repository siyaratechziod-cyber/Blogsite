@extends('layouts.admin')

@section('title', 'My Profile')
@section('page-title', 'My Profile')

@section('content')
    <p class="text-sm text-muted mb-4">
        Manage how you appear to readers across BlogHub — your name, photos, bio and social links are shown on your
        <a href="{{ route('authors.show', $user) }}" target="_blank" rel="noopener" class="text-coral hover-underline">public profile</a>.
    </p>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="bh-admin-card">
                <div class="bh-admin-card-header">
                    <h2 class="bh-admin-card-title">Profile Information</h2>
                </div>
                <div class="bh-admin-card-body">
                    <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Cover image -->
                        <div class="mb-4">
                            <label for="profileCover" class="form-label text-xs fw-semibold text-muted">Cover Image</label>
                            <img src="{{ $user->cover_image ?? '' }}" alt="Cover image preview" id="coverImagePreview" class="bh-img-preview mb-2 {{ $user->cover_image ? '' : 'd-none' }}">
                            <input type="file" id="profileCover" name="cover_image" class="bh-form-control w-100 @error('cover_image') is-invalid @enderror" accept="image/jpeg,image/png,image/webp,image/gif" data-image-preview="#coverImagePreview">
                            <p class="text-xs text-muted mb-0 mt-1">JPG, PNG, WebP or GIF — max 5MB. Shown as a banner on your profile page.</p>

                            @if ($user->cover_image)
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" name="remove_cover_image" id="removeCoverImage" value="1" @checked(old('remove_cover_image'))>
                                    <label class="form-check-label text-xs text-secondary" for="removeCoverImage">Remove current cover image</label>
                                </div>
                            @endif
                        </div>

                        <!-- Profile photo -->
                        <div class="mb-4 d-flex align-items-center gap-3">
                            <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" id="profileImagePreview" class="bh-avatar bh-avatar-xl">
                            <div class="flex-grow-1">
                                <label for="profilePhoto" class="form-label text-xs fw-semibold text-muted">Profile Photo</label>
                                <input type="file" id="profilePhoto" name="profile_image" class="bh-form-control w-100 @error('profile_image') is-invalid @enderror" accept="image/jpeg,image/png,image/webp,image/gif" data-image-preview="#profileImagePreview">
                                <p class="text-xs text-muted mb-0 mt-1">JPG, PNG, WebP or GIF — max 3MB. Square images look best.</p>

                                @if ($user->profile_image)
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox" name="remove_profile_image" id="removeProfileImage" value="1" @checked(old('remove_profile_image'))>
                                        <label class="form-check-label text-xs text-secondary" for="removeProfileImage">Remove current profile photo</label>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="mb-3">
                            <label for="profileName" class="form-label text-xs fw-semibold text-muted">Full Name *</label>
                            <input type="text" id="profileName" name="name" class="bh-form-control w-100 @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="profileSpecialty" class="form-label text-xs fw-semibold text-muted">Specialty</label>
                            <input type="text" id="profileSpecialty" name="specialty" class="bh-form-control w-100 @error('specialty') is-invalid @enderror" value="{{ old('specialty', $user->specialty) }}" maxlength="100" placeholder="e.g. Full-Stack Developer & Technical Writer">
                            <p class="text-xs text-muted mb-0 mt-1">Shown under your name on posts and your profile page.</p>
                        </div>

                        <div class="mb-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <label for="profileBio" class="form-label text-xs fw-semibold text-muted mb-0">Bio</label>
                                <span class="text-xs text-muted" data-char-counter-output></span>
                            </div>
                            <textarea id="profileBio" name="bio" rows="5" class="bh-form-control w-100 text-sm @error('bio') is-invalid @enderror" placeholder="Tell readers a little about yourself" data-char-counter="1000">{{ old('bio', $user->bio) }}</textarea>
                        </div>

                        <h3 class="text-xs fw-bold text-uppercase text-muted mb-3" style="letter-spacing: 0.08em;">Social Links</h3>

                        <div class="mb-3">
                            <label for="profileTwitter" class="form-label text-xs fw-semibold text-muted">Twitter / X</label>
                            <div class="input-group">
                                <span class="input-group-text bg-transparent text-muted"><i class="bi bi-twitter-x"></i></span>
                                <input type="url" id="profileTwitter" name="twitter" class="bh-form-control border-start-0 @error('twitter') is-invalid @enderror" value="{{ old('twitter', $user->social_links['twitter'] ?? '') }}" placeholder="https://x.com/username">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="profileGithub" class="form-label text-xs fw-semibold text-muted">GitHub</label>
                            <div class="input-group">
                                <span class="input-group-text bg-transparent text-muted"><i class="bi bi-github"></i></span>
                                <input type="url" id="profileGithub" name="github" class="bh-form-control border-start-0 @error('github') is-invalid @enderror" value="{{ old('github', $user->social_links['github'] ?? '') }}" placeholder="https://github.com/username">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="profileLinkedin" class="form-label text-xs fw-semibold text-muted">LinkedIn</label>
                            <div class="input-group">
                                <span class="input-group-text bg-transparent text-muted"><i class="bi bi-linkedin"></i></span>
                                <input type="url" id="profileLinkedin" name="linkedin" class="bh-form-control border-start-0 @error('linkedin') is-invalid @enderror" value="{{ old('linkedin', $user->social_links['linkedin'] ?? '') }}" placeholder="https://linkedin.com/in/username">
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn-bh-primary py-2 px-4 text-sm">
                                <i class="bi bi-check-lg me-1"></i>Save Profile
                            </button>
                            <a href="{{ route('authors.show', $user) }}" target="_blank" rel="noopener" class="btn-bh-secondary py-2 px-4 text-sm">
                                <i class="bi bi-box-arrow-up-right me-1"></i>View Public Profile
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="bh-admin-card">
                <div class="bh-admin-card-header">
                    <h2 class="bh-admin-card-title">Change Password</h2>
                </div>
                <div class="bh-admin-card-body">
                    <form action="{{ route('admin.profile.password') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="currentPassword" class="form-label text-xs fw-semibold text-muted">Current Password *</label>
                            <div class="input-group">
                                <input type="password" id="currentPassword" name="current_password" class="bh-form-control border-end-0 @error('current_password') is-invalid @enderror" placeholder="••••••••" autocomplete="current-password" required>
                                <button type="button" class="btn btn-outline-secondary border-start-0 text-muted" data-toggle-password="#currentPassword" aria-label="Toggle password visibility">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="newPassword" class="form-label text-xs fw-semibold text-muted">New Password *</label>
                            <div class="input-group">
                                <input type="password" id="newPassword" name="password" class="bh-form-control border-end-0 @error('password') is-invalid @enderror" placeholder="••••••••" autocomplete="new-password" minlength="8" data-password-strength required>
                                <button type="button" class="btn btn-outline-secondary border-start-0 text-muted" data-toggle-password="#newPassword" aria-label="Toggle password visibility">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            <div class="bh-strength" aria-hidden="true">
                                <div class="bh-strength-bar" data-strength-bar></div>
                            </div>
                            <div class="bh-strength-text text-xs text-muted" data-strength-text>Use 8+ characters with a mix of letters, numbers and symbols.</div>
                        </div>

                        <div class="mb-4">
                            <label for="confirmNewPassword" class="form-label text-xs fw-semibold text-muted">Confirm New Password *</label>
                            <div class="input-group">
                                <input type="password" id="confirmNewPassword" name="password_confirmation" class="bh-form-control border-end-0" placeholder="••••••••" autocomplete="new-password" minlength="8" required>
                                <button type="button" class="btn btn-outline-secondary border-start-0 text-muted" data-toggle-password="#confirmNewPassword" aria-label="Toggle password visibility">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </div>

                        <button type="submit" class="btn-bh-primary w-100 py-2 text-sm">
                            <i class="bi bi-shield-lock me-1"></i>Update Password
                        </button>

                        <p class="text-xs text-muted mb-0 mt-3">
                            <i class="bi bi-info-circle me-1"></i>You will stay signed in on this device after changing your password.
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
