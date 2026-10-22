<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="auth-login-url" content="{{ route('login') }}">

    <title>@yield('title', 'BlogHub — High-Signal Engineering & Tech Architecture Publication')</title>
    <meta name="description" content="@yield('meta_description', 'BlogHub is the premier technical publication for modern software engineers, distributed systems architects, and technology leaders.')">
    <meta name="author" content="@yield('meta_author', 'BlogHub Editorial Team')">

    <!-- Canonical & Social Sharing -->
    <link rel="canonical" href="{{ url()->current() }}">
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:site_name" content="BlogHub">
    <meta property="og:title" content="@yield('title', 'BlogHub — High-Signal Engineering & Tech Architecture Publication')">
    <meta property="og:description" content="@yield('meta_description', 'BlogHub is the premier technical publication for modern software engineers, distributed systems architects, and technology leaders.')">
    <meta property="og:url" content="{{ url()->current() }}">
    @hasSection('og_image')
        <meta property="og:image" content="@yield('og_image')">
        <meta name="twitter:card" content="summary_large_image">
    @else
        <meta name="twitter:card" content="summary">
    @endif
    <meta name="twitter:title" content="@yield('title', 'BlogHub — High-Signal Engineering & Tech Architecture Publication')">
    <meta name="twitter:description" content="@yield('meta_description', 'BlogHub is the premier technical publication for modern software engineers, distributed systems architects, and technology leaders.')">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500&family=Outfit:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- BlogHub Custom Design System CSS -->
    <link rel="stylesheet" href="{{ asset('css/bloghub.css') }}">

    @stack('styles')
</head>
<body class="d-flex flex-column min-vh-100">
    <!-- Scroll Reading Progress Indicator -->
    <div id="bh-scroll-progress"></div>

    <!-- Navigation Header -->
    <x-navbar />

    <!-- Main Content Container -->
    <main class="flex-grow-1">
        <div class="container pt-3">
            <x-alerts />
        </div>
        @yield('content')
    </main>

    <!-- Footer -->
    <x-footer />

    <!-- Back to Top Floating Button -->
    <button type="button" id="bh-back-to-top" title="Scroll to top" aria-label="Scroll to top">
        <i class="bi bi-chevron-up"></i>
    </button>

    <!-- Toast Notification Container -->
    <x-toast />

    <!-- Bootstrap 5.3 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <!-- BlogHub Global Vanilla JS -->
    <script src="{{ asset('js/bloghub.js') }}"></script>

    <script>
        // Trigger toast on server redirect flash messages
        @if (session('success'))
            document.addEventListener('DOMContentLoaded', () => {
                if (window.showToast) window.showToast(@json(session('success')), 'success');
            });
        @endif
        @if (session('error'))
            document.addEventListener('DOMContentLoaded', () => {
                if (window.showToast) window.showToast(@json(session('error')), 'error');
            });
        @endif
        @if (session('info'))
            document.addEventListener('DOMContentLoaded', () => {
                if (window.showToast) window.showToast(@json(session('info')), 'info');
            });
        @endif
    </script>

    @stack('scripts')
</body>
</html>
