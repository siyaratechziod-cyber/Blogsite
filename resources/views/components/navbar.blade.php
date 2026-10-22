<nav class="navbar navbar-expand-lg bh-navbar">
    <div class="container">
        <!-- Brand -->
        <a class="bh-brand" href="{{ route('home') }}">
            <span class="bh-brand-badge">
                <i class="bi bi-journal-code"></i>
            </span>
            <span>Blog<span style="color: var(--bh-coral-500);">Hub</span></span>
        </a>

        <!-- Mobile Toggler -->
        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#bhNavbarNav" aria-controls="bhNavbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <i class="bi bi-list fs-2 text-primary"></i>
        </button>

        <!-- Navbar Links -->
        <div class="collapse navbar-collapse" id="bhNavbarNav">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0 gap-lg-1">
                <li class="nav-item">
                    <a class="nav-link bh-nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                        Home
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link bh-nav-link {{ request()->routeIs('blogs.*') ? 'active' : '' }}" href="{{ route('blogs.index') }}">
                        Articles
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link bh-nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}" href="{{ route('categories.index') }}">
                        Categories
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link bh-nav-link {{ request()->routeIs('authors.*') ? 'active' : '' }}" href="{{ route('authors.index') }}">
                        Authors
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link bh-nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">
                        About
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link bh-nav-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">
                        Contact
                    </a>
                </li>
            </ul>

            <!-- Right Actions: Search, Theme Toggle, Auth -->
            <div class="d-flex align-items-center gap-2 mt-3 mt-lg-0">
                <!-- Search Button / Form -->
                <form action="{{ route('search') }}" method="GET" class="d-none d-xl-flex align-items-center me-1 position-relative" data-search-suggest="{{ route('search.suggest') }}" role="search">
                    <label for="bhNavSearch" class="visually-hidden">Search articles</label>
                    <input type="text" id="bhNavSearch" name="q" class="bh-form-control py-1 px-3 pe-4 text-sm" placeholder="Search articles..." value="{{ request('q') }}" autocomplete="off" style="width: 170px; height: 36px;">
                    <button type="submit" class="btn p-0 position-absolute end-0 me-2 text-muted border-0" aria-label="Search">
                        <i class="bi bi-search text-xs"></i>
                    </button>
                </form>

                <a href="{{ route('search') }}" class="btn-icon d-xl-none" title="Search" aria-label="Search">
                    <i class="bi bi-search"></i>
                </a>

                <!-- Theme Toggle Button -->
                <button type="button" class="btn-icon" id="bh-theme-toggle" title="Toggle Light/Dark Theme" aria-label="Toggle light and dark theme">
                    <i class="bi bi-moon-stars-fill" id="bh-theme-icon"></i>
                </button>

                <!-- Auth Buttons -->
                @auth
                    <div class="dropdown ms-1">
                        <button class="btn btn-sm d-flex align-items-center gap-2 p-1 border-0 rounded-pill" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="{{ Auth::user()->avatar_url }}" alt="{{ Auth::user()->name }}" class="bh-avatar bh-avatar-sm">
                            <span class="d-none d-md-inline fw-semibold text-sm pe-1">{{ Str::limit(Auth::user()->name, 14) }}</span>
                            <i class="bi bi-chevron-down text-muted text-xs"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-md border-0 py-2" style="background-color: var(--bh-surface-elevated); min-width: 220px; border-radius: var(--bh-radius-md);">
                            <li class="px-3 py-2 border-bottom mb-2">
                                <div class="fw-bold text-sm">{{ Auth::user()->name }}</div>
                                <div class="text-xs text-muted text-truncate">{{ Auth::user()->email }}</div>
                                <span class="bh-badge-role bh-badge-{{ Auth::user()->role }} mt-1 d-inline-block">{{ ucfirst(Auth::user()->role) }}</span>
                            </li>
                            @if (Auth::user()->isAuthor())
                                <li>
                                    <a class="dropdown-item d-flex align-items-center gap-2 py-2 text-sm text-primary fw-semibold" href="{{ route('admin.dashboard') }}">
                                        <i class="bi bi-speedometer2"></i> {{ Auth::user()->isAdmin() ? 'Admin Dashboard' : 'Author Dashboard' }}
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                            @endif
                            <li>
                                <a class="dropdown-item d-flex align-items-center gap-2 py-2 text-sm" href="{{ route('authors.show', Auth::user()) }}">
                                    <i class="bi bi-person"></i> My Profile
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center gap-2 py-2 text-sm" href="{{ route('blogs.index') }}">
                                    <i class="bi bi-bookmarks"></i> Explore Articles
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
                @else
                    <a href="{{ route('login') }}" class="btn-bh-secondary py-2 px-3 text-sm">
                        Sign In
                    </a>
                    <a href="{{ route('register') }}" class="btn-bh-primary py-2 px-3 text-sm">
                        Get Started
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>
