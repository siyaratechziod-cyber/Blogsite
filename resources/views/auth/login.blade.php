@extends('layouts.app')

@section('title', 'Sign In — BlogHub')
@section('meta_description', 'Sign in to your BlogHub account to interact with technical articles, follow authors, and participate in discussions.')

@section('content')
<section class="py-5">
    <div class="container" style="max-width: 480px;">
        <div class="bh-card p-4 p-md-5">
            <div class="text-center mb-4">
                <span class="bh-brand-badge mb-2" style="width: 44px; height: 44px; font-size: 1.35rem;">
                    <i class="bi bi-shield-lock"></i>
                </span>
                <h1 class="h3 font-heading fw-bold mb-1">Welcome Back</h1>
                <p class="text-secondary text-sm">Sign in to your BlogHub account</p>
            </div>

            <form action="{{ route('login.submit') }}" method="POST" data-validate>
                @csrf

                <!-- Email Address -->
                <div class="mb-3">
                    <label for="loginEmail" class="form-label text-xs fw-semibold text-muted">Email Address *</label>
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-end-0 text-muted">
                            <i class="bi bi-envelope"></i>
                        </span>
                        <input type="email" id="loginEmail" name="email" class="bh-form-control border-start-0 w-100" value="{{ old('email') }}" placeholder="name@company.com" autocomplete="email" required autofocus>
                    </div>
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <div class="d-flex align-items-center justify-content-between mb-1">
                        <label for="loginPassword" class="form-label text-xs fw-semibold text-muted mb-0">Password *</label>
                        <a href="{{ route('password.request') }}" class="text-xs text-coral hover-underline">
                            Forgot password?
                        </a>
                    </div>
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-end-0 text-muted">
                            <i class="bi bi-key"></i>
                        </span>
                        <input type="password" id="loginPassword" name="password" class="bh-form-control border-start-0 border-end-0" placeholder="••••••••" autocomplete="current-password" required>
                        <button type="button" class="btn btn-outline-secondary border-start-0 text-muted" data-toggle-password="#loginPassword" aria-label="Toggle password visibility">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" name="remember" id="rememberMe">
                    <label class="form-check-label text-xs text-secondary" for="rememberMe">
                        Keep me signed in on this device
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-bh-primary w-100 py-2 mb-3">
                    <i class="bi bi-box-arrow-in-right me-1"></i> Sign In
                </button>
            </form>

            <div class="text-center pt-3 border-top border-secondary border-opacity-10 text-xs text-secondary">
                Don't have an account yet?
                <a href="{{ route('register') }}" class="text-coral fw-bold ms-1">
                    Create account
                </a>
            </div>
        </div>

        <!-- Quick Demo Accounts Helper -->
        <div class="bh-card p-3 mt-3 bg-secondary bg-opacity-5 text-xs text-secondary">
            <div class="fw-bold mb-1 text-primary"><i class="bi bi-info-circle me-1"></i> Quick Test Credentials:</div>
            <div><strong>Admin:</strong> <code>admin@bloghub.test</code> | Password: <code>password</code></div>
            <div><strong>Author:</strong> <code>sophia.chen@bloghub.test</code> | Password: <code>password</code></div>
            <div><strong>Reader:</strong> <code>lucas.wright@reader.test</code> | Password: <code>password</code></div>
        </div>
    </div>
</section>
@endsection
