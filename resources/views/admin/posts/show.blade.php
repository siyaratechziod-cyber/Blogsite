@extends('layouts.admin')

@section('title', 'Post Details')
@section('page-title', 'Post Details')

@php
    $statusBadges = [
        'published' => 'bg-success-subtle text-success-emphasis',
        'draft' => 'bg-warning-subtle text-warning-emphasis',
        'archived' => 'bg-secondary-subtle text-secondary-emphasis',
    ];
    $isPublic = $post->status === 'published' && $post->published_at?->isPast();
@endphp

@section('content')
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
        <a href="{{ route('admin.posts.index') }}" class="text-sm text-muted hover-underline">
            <i class="bi bi-arrow-left me-1"></i>Back to Posts
        </a>
        <div class="d-flex gap-2">
            @if ($isPublic)
                <a href="{{ route('blogs.show', $post->slug) }}" target="_blank" rel="noopener" class="btn-bh-secondary py-2 px-3 text-sm">
                    <i class="bi bi-box-arrow-up-right me-1"></i>View Public Page
                </a>
            @endif
            <a href="{{ route('admin.posts.edit', $post) }}" class="btn-bh-primary py-2 px-3 text-sm">
                <i class="bi bi-pencil me-1"></i>Edit Post
            </a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="bh-admin-card">
                @if ($post->featured_image)
                    <img src="{{ $post->featured_image }}" alt="" style="width: 100%; max-height: 360px; object-fit: cover;">
                @endif

                <div class="bh-admin-card-body">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="badge rounded-pill {{ $statusBadges[$post->status] ?? 'bg-secondary-subtle text-secondary-emphasis' }}">{{ ucfirst($post->status) }}</span>
                        <span class="text-xs text-muted">
                            {{ $post->published_at ? $post->published_at->format('F j, Y \a\t g:i A') : 'Not published yet' }}
                        </span>
                    </div>

                    <h1 class="h3 font-heading fw-bold mb-3">{{ $post->title }}</h1>

                    @if ($post->excerpt)
                        <p class="text-secondary mb-4">{{ $post->excerpt }}</p>
                    @endif

                    <hr>

                    @if (trim((string) $post->content) !== '')
                        <div class="bh-article-content">
                            {!! Str::markdown($post->content, ['html_input' => 'strip', 'allow_unsafe_links' => false]) !!}
                        </div>
                    @else
                        <div class="bh-admin-empty">
                            <i class="bi bi-file-earmark"></i>
                            <p class="text-sm mb-0">This post has no content yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="bh-admin-card mb-4">
                <div class="bh-admin-card-header">
                    <h2 class="bh-admin-card-title">Details</h2>
                </div>
                <div class="bh-admin-card-body">
                    <dl class="bh-detail-list mb-0">
                        <dt>Slug</dt>
                        <dd class="text-break">/{{ $post->slug }}</dd>

                        <dt>Category</dt>
                        <dd>{{ $post->category?->name ?? '—' }}</dd>

                        <dt>Author</dt>
                        <dd>
                            @if ($post->author)
                                <a href="{{ route('authors.show', $post->author) }}" class="text-coral hover-underline">{{ $post->author->name }}</a>
                            @else
                                —
                            @endif
                        </dd>

                        <dt>Reading time</dt>
                        <dd>{{ $post->estimated_reading_time }} min</dd>

                        <dt>Engagement</dt>
                        <dd class="d-flex gap-3">
                            <span><i class="bi bi-eye me-1"></i>{{ number_format($post->views) }} views</span>
                            <span><i class="bi bi-heart me-1"></i>{{ $post->likes_count }}</span>
                            <span><i class="bi bi-chat-left-text me-1"></i>{{ $post->comments_count }}</span>
                        </dd>

                        <dt>Created</dt>
                        <dd>{{ $post->created_at?->format('M j, Y g:i A') }}</dd>

                        <dt class="mb-0">Last updated</dt>
                        <dd class="mb-0">{{ $post->updated_at?->diffForHumans() }}</dd>
                    </dl>
                </div>
            </div>

            <div class="bh-admin-card mb-4">
                <div class="bh-admin-card-header">
                    <h2 class="bh-admin-card-title">Tags</h2>
                </div>
                <div class="bh-admin-card-body">
                    @forelse ($post->tags as $tag)
                        <span class="bh-tag-checkbox" style="cursor: default;">{{ $tag->name }}</span>
                    @empty
                        <p class="text-xs text-muted mb-0">No tags assigned.</p>
                    @endforelse
                </div>
            </div>

            <div class="bh-admin-card">
                <div class="bh-admin-card-header">
                    <h2 class="bh-admin-card-title text-danger">Danger Zone</h2>
                </div>
                <div class="bh-admin-card-body">
                    <p class="text-xs text-muted mb-3">Deleting this post also removes its comments, likes and bookmarks. This cannot be undone.</p>
                    <form action="{{ route('admin.posts.destroy', $post) }}" method="POST" data-confirm="Delete “{{ $post->title }}”? Comments, likes and bookmarks on it will be removed too. This cannot be undone.">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger py-2 px-3 text-sm w-100">
                            <i class="bi bi-trash3 me-1"></i>Delete Post
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
