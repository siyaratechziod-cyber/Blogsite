@extends('layouts.admin')

@section('title', 'Comments')
@section('page-title', 'Comments')

@php
    $currentStatus = request('status', '');
    $statusBadges = [
        'pending' => 'bg-warning-subtle text-warning-emphasis',
        'approved' => 'bg-success-subtle text-success-emphasis',
        'rejected' => 'bg-danger-subtle text-danger-emphasis',
    ];
    $tabs = [
        '' => ['label' => 'All', 'count' => $counts['all']],
        'pending' => ['label' => 'Pending', 'count' => $counts['pending']],
        'approved' => ['label' => 'Approved', 'count' => $counts['approved']],
        'rejected' => ['label' => 'Rejected', 'count' => $counts['rejected']],
    ];
@endphp

@section('content')
    <div class="bh-admin-card">
        <div class="bh-admin-card-header">
            <div>
                <h2 class="bh-admin-card-title">Comment Moderation</h2>
                <p class="text-xs text-muted mb-0">Only approved comments are visible on the public site.</p>
            </div>
        </div>

        <div class="bh-admin-card-body border-bottom">
            <div class="d-flex flex-wrap gap-2 mb-3">
                @foreach ($tabs as $value => $tab)
                    <a href="{{ $value === '' ? route('admin.comments.index', request()->only('q')) : route('admin.comments.index', array_merge(request()->only('q'), ['status' => $value])) }}"
                       class="badge rounded-pill text-decoration-none px-3 py-2 {{ $currentStatus === $value ? 'bg-primary' : 'bg-secondary-subtle text-secondary-emphasis' }}"
                       @if ($currentStatus === $value) aria-current="page" @endif>
                        {{ $tab['label'] }}
                        <span class="opacity-75 ms-1">{{ $tab['count'] }}</span>
                    </a>
                @endforeach
            </div>

            <form action="{{ route('admin.comments.index') }}" method="GET" class="bh-admin-filters" role="search">
                @if ($currentStatus !== '')
                    <input type="hidden" name="status" value="{{ $currentStatus }}">
                @endif
                <label for="commentSearch" class="visually-hidden">Search comments</label>
                <input type="text" id="commentSearch" name="q" class="bh-form-control py-2 text-sm" placeholder="Search content, name or email..." value="{{ request('q') }}">
                <button type="submit" class="btn-bh-secondary py-2 px-3 text-sm">Search</button>
                @if (request()->hasAny(['q', 'status']))
                    <a href="{{ route('admin.comments.index') }}" class="text-xs text-muted hover-underline ms-1">Reset</a>
                @endif
            </form>
        </div>

        @if ($comments->isEmpty())
            <div class="bh-admin-empty">
                <i class="bi bi-chat-left-text"></i>
                <p class="mb-1 fw-semibold">No comments found</p>
                <p class="text-sm mb-0">
                    @if (request()->hasAny(['q', 'status']))
                        Try adjusting your filters.
                    @else
                        Comments left by readers will appear here for moderation.
                    @endif
                </p>
            </div>
        @else
            <div class="table-responsive">
                <table class="bh-admin-table">
                    <thead>
                        <tr>
                            <th scope="col">Comment</th>
                            <th scope="col">On Post</th>
                            <th scope="col">Status</th>
                            <th scope="col">Date</th>
                            <th scope="col" class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($comments as $comment)
                            <tr>
                                <td style="max-width: 420px;">
                                    <div class="d-flex align-items-start gap-3">
                                        <img src="{{ $comment->author_avatar }}" alt="" class="bh-avatar bh-avatar-sm mt-1">
                                        <div class="min-w-0">
                                            <div class="fw-semibold text-sm text-truncate">
                                                {{ $comment->author_name }}
                                                @if ($comment->user)
                                                    <span class="badge rounded-pill bg-primary-subtle text-primary-emphasis ms-1">Member</span>
                                                @endif
                                            </div>
                                            <div class="text-xs text-muted text-truncate">{{ $comment->email }}</div>
                                            <p class="text-sm mb-0 mt-1">{{ Str::limit($comment->content, 120) }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if ($comment->post)
                                        <a href="{{ route('admin.posts.show', $comment->post) }}" class="bh-table-title text-truncate d-block" style="max-width: 220px;">{{ $comment->post->title }}</a>
                                    @else
                                        <span class="text-muted">Deleted post</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge rounded-pill {{ $statusBadges[$comment->status] ?? 'bg-secondary-subtle text-secondary-emphasis' }}">{{ ucfirst($comment->status) }}</span>
                                </td>
                                <td class="text-nowrap text-muted text-xs">{{ $comment->created_at->format('M j, Y') }}<br>{{ $comment->created_at->format('g:i A') }}</td>
                                <td>
                                    <div class="d-flex justify-content-end gap-1">
                                        @if ($comment->status !== 'approved')
                                            <form action="{{ route('admin.comments.approve', $comment) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="bh-action-btn" title="Approve comment" aria-label="Approve comment by {{ $comment->author_name }}">
                                                    <i class="bi bi-check-lg"></i>
                                                </button>
                                            </form>
                                        @endif
                                        @if ($comment->status !== 'rejected')
                                            <form action="{{ route('admin.comments.reject', $comment) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="bh-action-btn" title="Reject comment" aria-label="Reject comment by {{ $comment->author_name }}">
                                                    <i class="bi bi-x-lg"></i>
                                                </button>
                                            </form>
                                        @endif
                                        <form action="{{ route('admin.comments.destroy', $comment) }}" method="POST" data-confirm="Delete the comment by {{ $comment->author_name }}? This cannot be undone.">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bh-action-btn is-danger" title="Delete comment" aria-label="Delete comment by {{ $comment->author_name }}">
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

            @if ($comments->hasPages())
                <div class="bh-admin-pagination">
                    {{ $comments->links('pagination::bootstrap-5') }}
                </div>
            @endif
        @endif
    </div>
@endsection
