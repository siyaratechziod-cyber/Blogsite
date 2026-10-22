@extends('layouts.admin')

@section('title', 'Users')
@section('page-title', 'Users')

@php
    $roleBadges = [
        'admin' => 'bh-badge-admin',
        'author' => 'bh-badge-author',
        'reader' => 'bh-badge-reader',
    ];
@endphp

@section('content')
    <div class="bh-admin-card">
        <div class="bh-admin-card-header">
            <div>
                <h2 class="bh-admin-card-title">All Users</h2>
                <p class="text-xs text-muted mb-0">{{ $users->total() }} {{ Str::plural('account', $users->total()) }} · deactivated users cannot sign in</p>
            </div>
        </div>

        <div class="bh-admin-card-body border-bottom">
            <form action="{{ route('admin.users.index') }}" method="GET" class="bh-admin-filters" role="search">
                <label for="userSearch" class="visually-hidden">Search users</label>
                <input type="text" id="userSearch" name="q" class="bh-form-control py-2 text-sm" placeholder="Search name, email or username..." value="{{ request('q') }}">

                <label for="userRole" class="visually-hidden">Filter by role</label>
                <select id="userRole" name="role" class="bh-form-control py-2 text-sm">
                    <option value="">All roles</option>
                    @foreach (['admin' => 'Admins', 'author' => 'Authors', 'reader' => 'Readers'] as $value => $label)
                        <option value="{{ $value }}" @selected(request('role') === $value)>{{ $label }}</option>
                    @endforeach
                </select>

                <label for="userStatus" class="visually-hidden">Filter by status</label>
                <select id="userStatus" name="status" class="bh-form-control py-2 text-sm">
                    <option value="">All statuses</option>
                    <option value="active" @selected(request('status') === 'active')>Active</option>
                    <option value="inactive" @selected(request('status') === 'inactive')>Deactivated</option>
                </select>

                <button type="submit" class="btn-bh-secondary py-2 px-3 text-sm">Filter</button>
                @if (request()->hasAny(['q', 'role', 'status']))
                    <a href="{{ route('admin.users.index') }}" class="text-xs text-muted hover-underline ms-1">Reset</a>
                @endif
            </form>
        </div>

        @if ($users->isEmpty())
            <div class="bh-admin-empty">
                <i class="bi bi-people"></i>
                <p class="mb-1 fw-semibold">No users found</p>
                <p class="text-sm mb-0">Try adjusting your search or filters.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="bh-admin-table">
                    <thead>
                        <tr>
                            <th scope="col">User</th>
                            <th scope="col">Role</th>
                            <th scope="col">Status</th>
                            <th scope="col">Posts</th>
                            <th scope="col">Joined</th>
                            <th scope="col" class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="{{ $user->avatar_url }}" alt="" class="bh-avatar bh-avatar-sm" style="width: 38px; height: 38px;">
                                        <div class="min-w-0">
                                            <span class="fw-semibold">
                                                {{ $user->name }}
                                                @if ($user->id === Auth::id())
                                                    <span class="badge rounded-pill bg-primary-subtle text-primary-emphasis ms-1">You</span>
                                                @endif
                                            </span>
                                            <span class="d-block text-xs text-muted text-truncate">{{ $user->email }}</span>
                                            @if ($user->username)
                                                <span class="d-block text-xs text-muted">&#64;{{ $user->username }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="bh-badge-role {{ $roleBadges[$user->role] ?? '' }}">{{ ucfirst($user->role) }}</span>
                                </td>
                                <td>
                                    @if ($user->isActive())
                                        <span class="badge rounded-pill bg-success-subtle text-success-emphasis">Active</span>
                                    @else
                                        <span class="badge rounded-pill bg-danger-subtle text-danger-emphasis">Deactivated</span>
                                    @endif
                                </td>
                                <td class="text-muted text-xs">{{ $user->posts_count }}</td>
                                <td class="text-muted text-xs text-nowrap">{{ $user->created_at?->format('M j, Y') }}</td>
                                <td>
                                    <div class="d-flex justify-content-end gap-1">
                                        <a href="{{ route('admin.users.edit', $user) }}" class="bh-action-btn" title="Edit user" aria-label="Edit {{ $user->name }}">
                                            <i class="bi bi-pencil"></i>
                                        </a>

                                        @if ($user->id !== Auth::id())
                                            <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST" data-confirm="{{ $user->isActive() ? 'Deactivate ' . $user->name . '? They will no longer be able to sign in.' : 'Reactivate ' . $user->name . '’s account?' }}">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="bh-action-btn {{ $user->isActive() ? 'is-danger' : '' }}" title="{{ $user->isActive() ? 'Deactivate account' : 'Activate account' }}" aria-label="{{ $user->isActive() ? 'Deactivate' : 'Activate' }} {{ $user->name }}">
                                                    <i class="bi {{ $user->isActive() ? 'bi-person-slash' : 'bi-person-check' }}"></i>
                                                </button>
                                            </form>

                                            @if ($user->posts_count > 0)
                                                <button type="button" class="bh-action-btn is-danger" disabled title="This user has posts — deactivate the account instead" aria-label="Delete {{ $user->name }} (blocked — has posts)" style="opacity: 0.4;">
                                                    <i class="bi bi-trash3"></i>
                                                </button>
                                            @else
                                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" data-confirm="Permanently delete {{ $user->name }} ({{ $user->email }})? This cannot be undone.">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="bh-action-btn is-danger" title="Delete user" aria-label="Delete {{ $user->name }}">
                                                        <i class="bi bi-trash3"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if ($users->hasPages())
                <div class="bh-admin-pagination">
                    {{ $users->links('pagination::bootstrap-5') }}
                </div>
            @endif
        @endif
    </div>
@endsection
