@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
    @php($isAdmin = Auth::user()->isAdmin())

    {{-- Primary stat cards --}}
    <div class="row g-3 mb-4">
        @if ($isAdmin)
            <div class="col-6 col-md-4 col-xl-3">
                <div class="bh-stat-card">
                    <span class="bh-stat-icon is-navy"><i class="bi bi-people"></i></span>
                    <div>
                        <div class="bh-stat-value">{{ number_format($stats['total_users']) }}</div>
                        <div class="bh-stat-label">Total Users</div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-xl-3">
                <div class="bh-stat-card">
                    <span class="bh-stat-icon is-navy"><i class="bi bi-person-badge"></i></span>
                    <div>
                        <div class="bh-stat-value">{{ number_format($stats['authors_count']) }}</div>
                        <div class="bh-stat-label">Authors</div>
                    </div>
                </div>
            </div>
        @endif

        <div class="col-6 col-md-4 col-xl-3">
            <div class="bh-stat-card">
                <span class="bh-stat-icon"><i class="bi bi-file-earmark-text"></i></span>
                <div>
                    <div class="bh-stat-value">{{ number_format($stats['total_posts']) }}</div>
                    <div class="bh-stat-label">{{ $isAdmin ? 'Total Posts' : 'My Posts' }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl-3">
            <div class="bh-stat-card">
                <span class="bh-stat-icon is-moss"><i class="bi bi-check-circle"></i></span>
                <div>
                    <div class="bh-stat-value">{{ number_format($stats['published_posts']) }}</div>
                    <div class="bh-stat-label">Published</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl-3">
            <div class="bh-stat-card">
                <span class="bh-stat-icon is-muted"><i class="bi bi-pencil"></i></span>
                <div>
                    <div class="bh-stat-value">{{ number_format($stats['draft_posts']) }}</div>
                    <div class="bh-stat-label">Drafts</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl-3">
            <div class="bh-stat-card">
                <span class="bh-stat-icon"><i class="bi bi-chat-left-text"></i></span>
                <div>
                    <div class="bh-stat-value">{{ number_format($stats['total_comments']) }}</div>
                    <div class="bh-stat-label">Comments</div>
                </div>
            </div>
        </div>

        @if ($isAdmin)
            <div class="col-6 col-md-4 col-xl-3">
                <div class="bh-stat-card">
                    <span class="bh-stat-icon is-moss"><i class="bi bi-diagram-3"></i></span>
                    <div>
                        <div class="bh-stat-value">{{ number_format($stats['categories_count']) }}</div>
                        <div class="bh-stat-label">Categories</div>
                    </div>
                </div>
            </div>
        @else
            <div class="col-6 col-md-4 col-xl-3">
                <div class="bh-stat-card">
                    <span class="bh-stat-icon is-moss"><i class="bi bi-heart"></i></span>
                    <div>
                        <div class="bh-stat-value">{{ number_format($stats['total_likes']) }}</div>
                        <div class="bh-stat-label">Likes Received</div>
                    </div>
                </div>
            </div>
        @endif

        <div class="col-6 col-md-4 col-xl-3">
            <div class="bh-stat-card">
                <span class="bh-stat-icon is-navy"><i class="bi bi-eye"></i></span>
                <div>
                    <div class="bh-stat-value">{{ number_format($stats['total_views']) }}</div>
                    <div class="bh-stat-label">Total Views</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Admin quick links --}}
    @if ($isAdmin)
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <a href="{{ route('admin.comments.index', ['status' => 'pending']) }}" class="bh-admin-card d-flex align-items-center gap-3 p-3 text-decoration-none h-100">
                    <span class="bh-stat-icon"><i class="bi bi-hourglass-split"></i></span>
                    <div>
                        <div class="bh-stat-value">{{ number_format($stats['pending_comments']) }}</div>
                        <div class="bh-stat-label">Comments Awaiting Review</div>
                    </div>
                    <i class="bi bi-arrow-right ms-auto text-muted"></i>
                </a>
            </div>
            <div class="col-md-4">
                <a href="{{ route('admin.messages.index', ['status' => 'unread']) }}" class="bh-admin-card d-flex align-items-center gap-3 p-3 text-decoration-none h-100">
                    <span class="bh-stat-icon is-navy"><i class="bi bi-envelope"></i></span>
                    <div>
                        <div class="bh-stat-value">{{ number_format($stats['unread_messages']) }}</div>
                        <div class="bh-stat-label">Unread Messages</div>
                    </div>
                    <i class="bi bi-arrow-right ms-auto text-muted"></i>
                </a>
            </div>
            <div class="col-md-4">
                <a href="{{ route('admin.subscribers.index') }}" class="bh-admin-card d-flex align-items-center gap-3 p-3 text-decoration-none h-100">
                    <span class="bh-stat-icon is-moss"><i class="bi bi-send"></i></span>
                    <div>
                        <div class="bh-stat-value">{{ number_format($stats['subscribers_count']) }}</div>
                        <div class="bh-stat-label">Newsletter Subscribers</div>
                    </div>
                    <i class="bi bi-arrow-right ms-auto text-muted"></i>
                </a>
            </div>
        </div>
    @endif

    <div class="row g-3">
        {{-- Recent posts --}}
        <div class="col-lg-8">
            <div class="bh-admin-card h-100">
                <div class="bh-admin-card-header">
                    <h2 class="bh-admin-card-title"><i class="bi bi-clock-history me-1"></i> Recent Posts</h2>
                    <a href="{{ route('admin.posts.index') }}" class="text-sm fw-semibold text-decoration-none" style="color: var(--bh-coral-500);">View all</a>
                </div>

                @if ($recentPosts->isEmpty())
                    <div class="bh-admin-empty">
                        <i class="bi bi-file-earmark-plus"></i>
                        <p class="mb-2">No posts yet.</p>
                        <a href="{{ route('admin.posts.create') }}" class="btn-bh-primary py-2 px-3 text-sm">Write your first post</a>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="bh-admin-table">
                            <thead>
                                <tr>
                                    <th scope="col">Post</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Comments</th>
                                    <th scope="col">Views</th>
                                    <th scope="col" class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($recentPosts as $post)
                                    <tr>
                                        <td>
                                            <a href="{{ route('admin.posts.show', $post) }}" class="bh-table-title line-clamp-1">{{ $post->title }}</a>
                                            <span class="text-xs text-muted">
                                                {{ $isAdmin ? $post->author->name : $post->category?->name }}
                                                &middot; {{ $post->published_at ? $post->published_at->format('M j, Y') : 'Not published' }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge rounded-pill {{ $post->status === 'published' ? 'bg-success-subtle text-success-emphasis' : 'bg-secondary-subtle text-secondary-emphasis' }}">
                                                {{ ucfirst($post->status) }}
                                            </span>
                                        </td>
                                        <td>{{ number_format($post->comments_count) }}</td>
                                        <td>{{ number_format($post->views) }}</td>
                                        <td class="text-end">
                                            <a href="{{ route('admin.posts.edit', $post) }}" class="bh-action-btn" title="Edit post" aria-label="Edit {{ $post->title }}">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        {{-- Recent comments --}}
        <div class="col-lg-4">
            <div class="bh-admin-card h-100">
                <div class="bh-admin-card-header">
                    <h2 class="bh-admin-card-title"><i class="bi bi-chat-left-text me-1"></i> Recent Comments</h2>
                    @if ($isAdmin)
                        <a href="{{ route('admin.comments.index') }}" class="text-sm fw-semibold text-decoration-none" style="color: var(--bh-coral-500);">Moderate</a>
                    @endif
                </div>

                @if ($recentComments->isEmpty())
                    <div class="bh-admin-empty">
                        <i class="bi bi-chat"></i>
                        <p class="mb-0">No comments yet.</p>
                    </div>
                @else
                    <ul class="list-unstyled mb-0">
                        @foreach ($recentComments as $comment)
                            <li class="px-3 py-3 {{ ! $loop->last ? 'border-bottom' : '' }}" style="border-color: var(--bh-border-subtle) !important;">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <img src="{{ $comment->author_avatar }}" alt="{{ $comment->author_name }}" class="bh-avatar bh-avatar-sm">
                                    <span class="fw-semibold text-sm">{{ $comment->author_name }}</span>
                                    <span class="badge rounded-pill ms-auto {{ $comment->status === 'approved' ? 'bg-success-subtle text-success-emphasis' : ($comment->status === 'pending' ? 'bg-warning-subtle text-warning-emphasis' : 'bg-danger-subtle text-danger-emphasis') }}">
                                        {{ ucfirst($comment->status) }}
                                    </span>
                                </div>
                                <p class="text-sm text-muted mb-1 line-clamp-2">{{ $comment->content }}</p>
                                @if ($comment->post)
                                    <a href="{{ route('admin.posts.show', $comment->post) }}" class="text-xs fw-semibold text-decoration-none line-clamp-1 d-block" style="color: var(--bh-coral-500);">
                                        on "{{ $comment->post->title }}"
                                    </a>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>

    {{-- Recent messages (admin only) --}}
    @if ($isAdmin && $recentMessages->isNotEmpty())
        <div class="row g-3 mt-0">
            <div class="col-12">
                <div class="bh-admin-card">
                    <div class="bh-admin-card-header">
                        <h2 class="bh-admin-card-title"><i class="bi bi-envelope me-1"></i> Recent Contact Messages</h2>
                        <a href="{{ route('admin.messages.index') }}" class="text-sm fw-semibold text-decoration-none" style="color: var(--bh-coral-500);">View all</a>
                    </div>
                    <div class="table-responsive">
                        <table class="bh-admin-table">
                            <thead>
                                <tr>
                                    <th scope="col">From</th>
                                    <th scope="col">Subject</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Received</th>
                                    <th scope="col" class="text-end">Open</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($recentMessages as $message)
                                    <tr>
                                        <td>
                                            <span class="fw-semibold">{{ $message->name }}</span>
                                            <span class="d-block text-xs text-muted">{{ $message->email }}</span>
                                        </td>
                                        <td class="line-clamp-1">{{ $message->subject }}</td>
                                        <td>
                                            <span class="badge rounded-pill {{ $message->is_read ? 'bg-secondary-subtle text-secondary-emphasis' : 'bg-danger-subtle text-danger-emphasis' }}">
                                                {{ $message->is_read ? 'Read' : 'New' }}
                                            </span>
                                        </td>
                                        <td class="text-muted">{{ $message->created_at->diffForHumans() }}</td>
                                        <td class="text-end">
                                            <a href="{{ route('admin.messages.show', $message) }}" class="bh-action-btn" title="Open message" aria-label="Open message from {{ $message->name }}">
                                                <i class="bi bi-envelope-open"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
