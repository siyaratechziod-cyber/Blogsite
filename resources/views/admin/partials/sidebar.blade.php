@php
    $user = Auth::user();
    $isAdmin = $user->isAdmin();

    $pendingCommentsCount = $isAdmin ? \App\Models\Comment::where('status', 'pending')->count() : 0;
    $unreadMessagesCount = $isAdmin ? \App\Models\ContactMessage::where('is_read', false)->count() : 0;
@endphp

<!-- Brand -->
<a class="bh-admin-sidebar-brand" href="{{ route('admin.dashboard') }}">
    <span class="bh-brand-badge">
        <i class="bi bi-journal-code"></i>
    </span>
    <span class="fw-bold">Blog<span style="color: var(--bh-coral-500);">Hub</span></span>
    <span class="bh-admin-badge ms-auto">CMS</span>
</a>

<!-- Logged-in user -->
<div class="bh-admin-sidebar-role">
    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="bh-avatar bh-avatar-sm">
    <div class="min-w-0">
        <div class="fw-semibold text-sm text-truncate">{{ $user->name }}</div>
        <span class="bh-badge-role bh-badge-{{ $user->role }}">{{ ucfirst($user->role) }}</span>
    </div>
</div>

<!-- Navigation -->
<nav class="bh-admin-nav" aria-label="Admin navigation">
    <span class="bh-admin-nav-heading">Overview</span>
    <a class="bh-admin-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
        <i class="bi bi-speedometer2"></i><span>Dashboard</span>
    </a>

    <span class="bh-admin-nav-heading mt-2">Content</span>
    <a class="bh-admin-nav-link {{ request()->routeIs('admin.posts.index', 'admin.posts.show', 'admin.posts.edit') ? 'active' : '' }}" href="{{ route('admin.posts.index') }}">
        <i class="bi bi-file-earmark-text"></i><span>All Posts</span>
    </a>
    <a class="bh-admin-nav-link {{ request()->routeIs('admin.posts.create') ? 'active' : '' }}" href="{{ route('admin.posts.create') }}">
        <i class="bi bi-pencil-square"></i><span>Write a Post</span>
    </a>

    @if ($isAdmin)
        <a class="bh-admin-nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}">
            <i class="bi bi-diagram-3"></i><span>Categories</span>
        </a>
        <a class="bh-admin-nav-link {{ request()->routeIs('admin.tags.*') ? 'active' : '' }}" href="{{ route('admin.tags.index') }}">
            <i class="bi bi-tags"></i><span>Tags</span>
        </a>

        <span class="bh-admin-nav-heading mt-2">Community</span>
        <a class="bh-admin-nav-link {{ request()->routeIs('admin.comments.*') ? 'active' : '' }}" href="{{ route('admin.comments.index') }}">
            <i class="bi bi-chat-left-text"></i><span>Comments</span>
            @if ($pendingCommentsCount > 0)
                <span class="bh-admin-nav-badge">{{ $pendingCommentsCount }}</span>
            @endif
        </a>
        <a class="bh-admin-nav-link {{ request()->routeIs('admin.messages.*') ? 'active' : '' }}" href="{{ route('admin.messages.index') }}">
            <i class="bi bi-envelope"></i><span>Messages</span>
            @if ($unreadMessagesCount > 0)
                <span class="bh-admin-nav-badge">{{ $unreadMessagesCount }}</span>
            @endif
        </a>
        <a class="bh-admin-nav-link {{ request()->routeIs('admin.subscribers.*') ? 'active' : '' }}" href="{{ route('admin.subscribers.index') }}">
            <i class="bi bi-send"></i><span>Subscribers</span>
        </a>

        <span class="bh-admin-nav-heading mt-2">Administration</span>
        <a class="bh-admin-nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
            <i class="bi bi-people"></i><span>Users</span>
        </a>
    @endif

    <span class="bh-admin-nav-heading mt-2">Account</span>
    <a class="bh-admin-nav-link {{ request()->routeIs('admin.profile.*') ? 'active' : '' }}" href="{{ route('admin.profile.edit') }}">
        <i class="bi bi-person-gear"></i><span>My Profile</span>
    </a>
    <a class="bh-admin-nav-link" href="{{ route('home') }}">
        <i class="bi bi-globe2"></i><span>View Site</span>
    </a>
    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit" class="bh-admin-nav-link w-100 border-0 bg-transparent text-start text-danger">
            <i class="bi bi-box-arrow-right"></i><span>Sign Out</span>
        </button>
    </form>
</nav>
