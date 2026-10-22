<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex, nofollow">

    <title>@yield('title', 'Admin Panel') — BlogHub</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500&family=Outfit:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- BlogHub Design System + Admin Styles -->
    <link rel="stylesheet" href="{{ asset('css/bloghub.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">

    @stack('styles')
</head>
<body class="bh-admin-body">
    <!-- Mobile Sidebar (Offcanvas) -->
    <div class="offcanvas offcanvas-start bh-admin-offcanvas" tabindex="-1" id="bhAdminSidebar" aria-labelledby="bhAdminSidebarLabel">
        <div class="offcanvas-header border-bottom">
            <h2 class="offcanvas-title fs-6 fw-bold" id="bhAdminSidebarLabel">Admin Menu</h2>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close menu"></button>
        </div>
        <div class="offcanvas-body p-0 d-flex flex-column">
            @include('admin.partials.sidebar')
        </div>
    </div>

    <div class="bh-admin-shell">
        <!-- Desktop Sidebar -->
        <aside class="bh-admin-sidebar d-none d-lg-flex flex-column">
            @include('admin.partials.sidebar')
        </aside>

        <!-- Main Column -->
        <div class="bh-admin-main">
            <header class="bh-admin-topbar">
                <button class="btn-icon d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#bhAdminSidebar" aria-controls="bhAdminSidebar" aria-label="Open admin menu">
                    <i class="bi bi-list"></i>
                </button>
                <h1 class="bh-admin-page-title">@yield('page-title', 'Dashboard')</h1>

                <div class="ms-auto d-flex align-items-center gap-2">
                    <a href="{{ route('home') }}" class="btn-bh-secondary py-2 px-3 text-sm d-none d-sm-inline-flex align-items-center gap-1">
                        <i class="bi bi-box-arrow-up-right"></i><span class="d-none d-md-inline">View Site</span>
                    </a>

                    <button type="button" class="btn-icon" id="bh-theme-toggle" title="Toggle light/dark theme" aria-label="Toggle light and dark theme">
                        <i class="bi bi-moon-stars-fill" id="bh-theme-icon"></i>
                    </button>

                    <div class="dropdown">
                        <button class="btn btn-sm d-flex align-items-center gap-2 p-1 border-0 rounded-pill" type="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Account menu">
                            <img src="{{ Auth::user()->avatar_url }}" alt="{{ Auth::user()->name }}" class="bh-avatar bh-avatar-sm">
                            <i class="bi bi-chevron-down text-muted text-xs"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-md border-0 py-2" style="background-color: var(--bh-surface-elevated); min-width: 230px; border-radius: var(--bh-radius-md);">
                            <li class="px-3 py-2 border-bottom mb-2">
                                <div class="fw-bold text-sm">{{ Auth::user()->name }}</div>
                                <div class="text-xs text-muted text-truncate">{{ Auth::user()->email }}</div>
                                <span class="bh-badge-role bh-badge-{{ Auth::user()->role }} mt-1 d-inline-block">{{ ucfirst(Auth::user()->role) }}</span>
                            </li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center gap-2 py-2 text-sm" href="{{ route('admin.profile.edit') }}">
                                    <i class="bi bi-person-gear"></i> Edit Profile
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center gap-2 py-2 text-sm" href="{{ route('authors.show', Auth::user()) }}">
                                    <i class="bi bi-person-badge"></i> Public Profile
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item d-flex align-items-center gap-2 py-2 text-sm text-danger">
                                        <i class="bi bi-box-arrow-right"></i> Sign Out
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </header>

            <div class="bh-admin-content flex-grow-1">
                <x-alerts />
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Scroll Top -->
    <button type="button" id="bh-back-to-top" title="Scroll to top" aria-label="Scroll to top">
        <i class="bi bi-chevron-up"></i>
    </button>

    <!-- Shared Confirmation Modal -->
    <div class="modal fade" id="bhConfirmModal" tabindex="-1" aria-labelledby="bhConfirmTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content" style="background-color: var(--bh-surface-card); border: 1px solid var(--bh-border-subtle); border-radius: var(--bh-radius-lg);">
                <div class="modal-body text-center p-4">
                    <i class="bi bi-exclamation-triangle-fill fs-1 text-danger d-block mb-2"></i>
                    <h2 class="fs-6 fw-bold mb-2" id="bhConfirmTitle">Please confirm</h2>
                    <p class="mb-0 text-sm text-muted" id="bhConfirmMessage">Are you sure you want to continue?</p>
                </div>
                <div class="modal-footer justify-content-center border-0 pt-0 pb-4">
                    <button type="button" class="btn-bh-secondary px-4 py-2" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn-bh-primary px-4 py-2" id="bhConfirmOk">Yes, continue</button>
                </div>
            </div>
        </div>
    </div>

    <x-toast />

    <!-- Bootstrap 5.3 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <!-- BlogHub Global Vanilla JS + Admin JS -->
    <script src="{{ asset('js/bloghub.js') }}"></script>
    <script src="{{ asset('js/admin.js') }}"></script>

    <script>
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
