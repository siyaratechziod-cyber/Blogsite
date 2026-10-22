@extends('layouts.app')

@section('title', 'Reset Password — BlogHub')

@section('content')
<section class="py-5">
    <div class="container" style="max-width: 480px;">
        <div class="bh-card p-4 p-md-5">
            <div class="text-center mb-4">
                <span class="bh-brand-badge mb-2" style="width: 44px; height: 44px; font-size: 1.35rem;">
                    <i class="bi bi-key"></i>
                </span>
                <h1 class="h3 font-heading fw-bold mb-1">Reset Password</h1>
                <p class="text-secondary text-sm">
                    Enter your email and we'll send you instructions to reset your account password.
                </p>
            </div>

            <form action="{{ route('password.email') }}" method="POST" data-validate>
                @csrf

                <div class="mb-4">
                    <label for="forgotEmail" class="form-label text-xs fw-semibold text-muted">Email Address *</label>
                    <input type="email" id="forgotEmail" name="email" class="bh-form-control w-100" value="{{ old('email') }}" placeholder="name@company.com" autocomplete="email" required autofocus>
                </div>

                <button type="submit" class="btn btn-bh-primary w-100 py-2 mb-3">
                    <i class="bi bi-envelope me-1"></i> Send Password Reset Link
                </button>
            </form>

            <div class="text-center pt-3 border-top border-secondary border-opacity-10 text-xs text-secondary">
                Remember your credentials?
                <a href="{{ route('login') }}" class="text-coral fw-bold ms-1">
                    Return to Sign In
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
