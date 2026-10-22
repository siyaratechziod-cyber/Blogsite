@extends('layouts.admin')

@section('title', 'Message from ' . $message->name)
@section('page-title', 'Message Details')

@section('content')
    <a href="{{ route('admin.messages.index') }}" class="text-sm text-muted hover-underline d-inline-block mb-3">
        <i class="bi bi-arrow-left me-1"></i>Back to Messages
    </a>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="bh-admin-card">
                <div class="bh-admin-card-header">
                    <div>
                        <h2 class="bh-admin-card-title">{{ $message->subject }}</h2>
                        <p class="text-xs text-muted mb-0">Received {{ $message->created_at->format('F j, Y \a\t g:i A') }}</p>
                    </div>
                    @if ($message->is_read)
                        <span class="badge rounded-pill bg-secondary-subtle text-secondary-emphasis">Read</span>
                    @else
                        <span class="badge rounded-pill bg-warning-subtle text-warning-emphasis">Unread</span>
                    @endif
                </div>
                <div class="bh-admin-card-body">
                    <div class="d-flex align-items-center gap-3 mb-3 pb-3 border-bottom">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($message->name) }}&background=e2e8f0&color=334155&size=80" alt="" class="bh-avatar bh-avatar-md">
                        <div class="min-w-0">
                            <div class="fw-bold">{{ $message->name }}</div>
                            <a href="mailto:{{ $message->email }}" class="text-sm text-coral hover-underline">{{ $message->email }}</a>
                        </div>
                    </div>

                    <p class="text-sm mb-0" style="white-space: pre-wrap;">{{ $message->message }}</p>

                    <div class="d-flex flex-wrap gap-2 mt-4 pt-3 border-top">
                        <a href="mailto:{{ $message->email }}?subject={{ urlencode('Re: ' . $message->subject) }}" class="btn-bh-primary py-2 px-4 text-sm">
                            <i class="bi bi-reply me-1"></i>Reply via Email
                        </a>
                        <form action="{{ route('admin.messages.read', $message) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn-bh-secondary py-2 px-4 text-sm">
                                <i class="bi {{ $message->is_read ? 'bi-envelope' : 'bi-envelope-open' }} me-1"></i>{{ $message->is_read ? 'Mark as Unread' : 'Mark as Read' }}
                            </button>
                        </form>
                        <form action="{{ route('admin.messages.destroy', $message) }}" method="POST" data-confirm="Delete the message from {{ $message->name }} ({{ $message->email }})? This cannot be undone.">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger py-2 px-4 text-sm">
                                <i class="bi bi-trash3 me-1"></i>Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="bh-admin-card">
                <div class="bh-admin-card-header">
                    <h2 class="bh-admin-card-title">Sender</h2>
                </div>
                <div class="bh-admin-card-body">
                    <dl class="bh-detail-list mb-0">
                        <dt>Name</dt>
                        <dd>{{ $message->name }}</dd>

                        <dt>Email</dt>
                        <dd><a href="mailto:{{ $message->email }}" class="text-coral hover-underline">{{ $message->email }}</a></dd>

                        <dt>Subject</dt>
                        <dd>{{ $message->subject }}</dd>

                        <dt class="mb-0">Received</dt>
                        <dd class="mb-0">{{ $message->created_at->format('M j, Y g:i A') }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
@endsection
