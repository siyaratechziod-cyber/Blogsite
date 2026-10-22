@extends('layouts.app')

@section('title', 'Set New Password — BlogHub')

@section('content')
<section class="py-5">
    <div class="container" style="max-width: 480px;">
        <div class="bh-card p-4 p-md-5">
            <div class="text-center mb-4">
                <span class="bh-brand-badge mb-2" style="width: 44px; height: 44px; font-size: 1.35rem;">
                    <i class="bi bi-shield-check"></i>
                </span>
                <h1 class="h3 font-heading fw-bold mb-1">Set New Password</h1>
                <p class="text-secondary text-sm">Create a strong password for your account</p>
            </div>

            <form action="{{ route('password.update') }}" method="POST" data-validate>
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="mb-3">
                    <label for="resetEmail" class="form-label text-xs fw-semibold text-muted">Email Address *</label>
                    <input type="email" id="resetEmail" name="email" class="bh-form-control w-100" value="{{ $email ?? old('email') }}" autocomplete="email" required autofocus>
                </div>

                <div class="mb-3">
                    <label for="resetPassword" class="form-label text-xs fw-semibold text-muted">New Password *</label>
                    <div class="input-group">
                        <input type="password" id="resetPassword" name="password" class="bh-form-control border-end-0" placeholder="••••••••" autocomplete="new-password" minlength="8" required data-password-strength>
                        <button type="button" class="btn btn-outline-secondary border-start-0 text-muted" data-toggle-password="#resetPassword" aria-label="Toggle password visibility">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                    <div class="bh-strength" aria-hidden="true">
                        <div class="bh-strength-bar" data-strength-bar></div>
                    </div>
                    <div class="bh-strength-text text-xs text-muted" data-strength-text>Use 8+ characters with a mix of letters, numbers and symbols.</div>
                </div>

                <div class="mb-4">
                    <label for="resetPasswordConfirm" class="form-label text-xs fw-semibold text-muted">Confirm New Password *</label>
                    <div class="input-group">
                        <input type="password" id="resetPasswordConfirm" name="password_confirmation" class="bh-form-control border-end-0" placeholder="••••••••" autocomplete="new-password" minlength="8" required>
                        <button type="button" class="btn btn-outline-secondary border-start-0 text-muted" data-toggle-password="#resetPasswordConfirm" aria-label="Toggle password visibility">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn btn-bh-primary w-100 py-2">
                    <i class="bi bi-arrow-repeat me-1"></i> Update Password
                </button>
            </form>
        </div>
    </div>
</section>
@endsection
