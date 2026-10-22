@extends('layouts.app')

@section('title', 'Create Account — BlogHub')
@section('meta_description', 'Join the BlogHub engineering community to follow verified authors, save articles, and participate in architecture discussions.')

@section('content')
<section class="py-5">
    <div class="container" style="max-width: 520px;">
        <div class="bh-card p-4 p-md-5">
            <div class="text-center mb-4">
                <span class="bh-brand-badge mb-2" style="width: 44px; height: 44px; font-size: 1.35rem;">
                    <i class="bi bi-person-plus"></i>
                </span>
                <h1 class="h3 font-heading fw-bold mb-1">Join BlogHub</h1>
                <p class="text-secondary text-sm">Create your free developer account</p>
            </div>

            <form action="{{ route('register.submit') }}" method="POST" data-validate>
                @csrf

                <!-- Full Name -->
                <div class="mb-3">
                    <label for="registerName" class="form-label text-xs fw-semibold text-muted">Full Name *</label>
                    <input type="text" id="registerName" name="name" class="bh-form-control w-100" value="{{ old('name') }}" placeholder="e.g. Maya Patel" autocomplete="name" maxlength="255" required autofocus>
                </div>

                <!-- Username -->
                <div class="mb-3">
                    <label for="registerUsername" class="form-label text-xs fw-semibold text-muted">Username (optional)</label>
                    <div class="input-group">
                        <span class="input-group-text bg-transparent text-muted">@</span>
                        <input type="text" id="registerUsername" name="username" class="bh-form-control border-start-0" value="{{ old('username') }}" placeholder="mayapatel" autocomplete="username" maxlength="50">
                    </div>
                </div>

                <!-- Email Address -->
                <div class="mb-3">
                    <label for="registerEmail" class="form-label text-xs fw-semibold text-muted">Email Address *</label>
                    <input type="email" id="registerEmail" name="email" class="bh-form-control w-100" value="{{ old('email') }}" placeholder="name@company.com" autocomplete="email" maxlength="255" required>
                </div>

                <!-- Role Selection -->
                <div class="mb-3">
                    <label for="registerRole" class="form-label text-xs fw-semibold text-muted">Account Intent</label>
                    <select id="registerRole" name="role" class="bh-form-control w-100">
                        <option value="reader" {{ old('role') === 'reader' ? 'selected' : '' }}>Community Reader (Read, bookmark, comment, follow)</option>
                        <option value="author" {{ old('role') === 'author' ? 'selected' : '' }}>Contributing Author (Write and publish engineering articles)</option>
                    </select>
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <label for="registerPassword" class="form-label text-xs fw-semibold text-muted">Password (min 8 chars) *</label>
                    <div class="input-group">
                        <input type="password" id="registerPassword" name="password" class="bh-form-control border-end-0" placeholder="••••••••" autocomplete="new-password" minlength="8" required data-password-strength>
                        <button type="button" class="btn btn-outline-secondary border-start-0 text-muted" data-toggle-password="#registerPassword" aria-label="Toggle password visibility">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                    <div class="bh-strength" aria-hidden="true">
                        <div class="bh-strength-bar" data-strength-bar></div>
                    </div>
                    <div class="bh-strength-text text-xs text-muted" data-strength-text>Use 8+ characters with a mix of letters, numbers and symbols.</div>
                </div>

                <!-- Confirm Password -->
                <div class="mb-3">
                    <label for="registerPasswordConfirm" class="form-label text-xs fw-semibold text-muted">Confirm Password *</label>
                    <div class="input-group">
                        <input type="password" id="registerPasswordConfirm" name="password_confirmation" class="bh-form-control border-end-0" placeholder="••••••••" autocomplete="new-password" minlength="8" required>
                        <button type="button" class="btn btn-outline-secondary border-start-0 text-muted" data-toggle-password="#registerPasswordConfirm" aria-label="Toggle password visibility">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>

                <!-- Terms Agreement -->
                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" name="terms" value="1" id="registerTerms" required {{ old('terms') ? 'checked' : '' }}>
                    <label class="form-check-label text-xs text-secondary" for="registerTerms">
                        I agree to the <a href="{{ route('terms') }}" class="text-coral fw-semibold">Terms of Service</a> and <a href="{{ route('privacy') }}" class="text-coral fw-semibold">Privacy Policy</a>.
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-bh-primary w-100 py-2 mb-3">
                    <i class="bi bi-check-circle me-1"></i> Create My Account
                </button>
            </form>

            <div class="text-center pt-3 border-top border-secondary border-opacity-10 text-xs text-secondary">
                Already have an account?
                <a href="{{ route('login') }}" class="text-coral fw-bold ms-1">
                    Sign in
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
