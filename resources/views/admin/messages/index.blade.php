@extends('layouts.admin')

@section('title', 'Messages')
@section('page-title', 'Messages')

@php
    $currentStatus = request('status', '');
    $tabs = [
        '' => 'All',
        'unread' => 'Unread',
        'read' => 'Read',
    ];
@endphp

@section('content')
    <div class="bh-admin-card">
        <div class="bh-admin-card-header">
            <div>
                <h2 class="bh-admin-card-title">Contact Messages</h2>
                <p class="text-xs text-muted mb-0">Messages sent through the public contact form.</p>
            </div>
            @if ($unreadCount > 0)
                <span class="badge rounded-pill bg-primary-subtle text-primary-emphasis">{{ $unreadCount }} unread</span>
            @endif
        </div>

        <div class="bh-admin-card-body border-bottom">
            <div class="d-flex flex-wrap gap-2 mb-3">
                @foreach ($tabs as $value => $label)
                    <a href="{{ $value === '' ? route('admin.messages.index', request()->only('q')) : route('admin.messages.index', array_merge(request()->only('q'), ['status' => $value])) }}"
                       class="badge rounded-pill text-decoration-none px-3 py-2 {{ $currentStatus === $value ? 'bg-primary' : 'bg-secondary-subtle text-secondary-emphasis' }}"
                       @if ($currentStatus === $value) aria-current="page" @endif>
                        {{ $label }}
                        @if ($value === 'unread' && $unreadCount > 0)
                            <span class="opacity-75 ms-1">{{ $unreadCount }}</span>
                        @endif
                    </a>
                @endforeach
            </div>

            <form action="{{ route('admin.messages.index') }}" method="GET" class="bh-admin-filters" role="search">
                @if ($currentStatus !== '')
                    <input type="hidden" name="status" value="{{ $currentStatus }}">
                @endif
                <label for="messageSearch" class="visually-hidden">Search messages</label>
                <input type="text" id="messageSearch" name="q" class="bh-form-control py-2 text-sm" placeholder="Search name, email or subject..." value="{{ request('q') }}">
                <button type="submit" class="btn-bh-secondary py-2 px-3 text-sm">Search</button>
                @if (request()->hasAny(['q', 'status']))
                    <a href="{{ route('admin.messages.index') }}" class="text-xs text-muted hover-underline ms-1">Reset</a>
                @endif
            </form>
        </div>

        @if ($messages->isEmpty())
            <div class="bh-admin-empty">
                <i class="bi bi-envelope"></i>
                <p class="mb-1 fw-semibold">No messages found</p>
                <p class="text-sm mb-0">
                    @if (request()->hasAny(['q', 'status']))
                        Try adjusting your filters.
                    @else
                        Messages sent through the contact form will land here.
                    @endif
                </p>
            </div>
        @else
            <div class="table-responsive">
                <table class="bh-admin-table">
                    <thead>
                        <tr>
                            <th scope="col">From</th>
                            <th scope="col">Subject</th>
                            <th scope="col">Status</th>
                            <th scope="col">Received</th>
                            <th scope="col" class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($messages as $message)
                            <tr class="{{ $message->is_read ? '' : 'fw-semibold' }}">
                                <td>
                                    <div class="text-sm fw-semibold">{{ $message->name }}</div>
                                    <div class="text-xs text-muted">{{ $message->email }}</div>
                                </td>
                                <td>
                                    <a href="{{ route('admin.messages.show', $message) }}" class="bh-table-title text-truncate d-block" style="max-width: 320px;">{{ $message->subject }}</a>
                                    <div class="text-xs text-muted text-truncate" style="max-width: 320px;">{{ Str::limit($message->message, 80) }}</div>
                                </td>
                                <td>
                                    @if ($message->is_read)
                                        <span class="badge rounded-pill bg-secondary-subtle text-secondary-emphasis">Read</span>
                                    @else
                                        <span class="badge rounded-pill bg-warning-subtle text-warning-emphasis">Unread</span>
                                    @endif
                                </td>
                                <td class="text-nowrap text-muted text-xs">{{ $message->created_at->format('M j, Y') }}<br>{{ $message->created_at->format('g:i A') }}</td>
                                <td>
                                    <div class="d-flex justify-content-end gap-1">
                                        <a href="{{ route('admin.messages.show', $message) }}" class="bh-action-btn" title="View message" aria-label="View message from {{ $message->name }}">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <form action="{{ route('admin.messages.read', $message) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="bh-action-btn" title="{{ $message->is_read ? 'Mark as unread' : 'Mark as read' }}" aria-label="{{ $message->is_read ? 'Mark as unread' : 'Mark as read' }}">
                                                <i class="bi {{ $message->is_read ? 'bi-envelope' : 'bi-envelope-open' }}"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.messages.destroy', $message) }}" method="POST" data-confirm="Delete the message from {{ $message->name }} ({{ $message->email }})? This cannot be undone.">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bh-action-btn is-danger" title="Delete message" aria-label="Delete message from {{ $message->name }}">
                                                <i class="bi bi-trash3"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if ($messages->hasPages())
                <div class="bh-admin-pagination">
                    {{ $messages->links('pagination::bootstrap-5') }}
                </div>
            @endif
        @endif
    </div>
@endsection
