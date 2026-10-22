@extends('layouts.admin')

@section('title', 'Posts')
@section('page-title', 'Posts')

@php
    $isAdmin = Auth::user()->isAdmin();
    $statusBadges = [
        'published' => 'bg-success-subtle text-success-emphasis',
        'draft' => 'bg-warning-subtle text-warning-emphasis',
        'archived' => 'bg-secondary-subtle text-secondary-emphasis',
    ];
@endphp

@section('content')
    <div class="bh-admin-card">
        <div class="bh-admin-card-header">
            <div>
                <h2 class="bh-admin-card-title">{{ $isAdmin ? 'All Posts' : 'My Posts' }}</h2>
                <p class="text-xs text-muted mb-0">{{ $posts->total() }} {{ Str::plural('post', $posts->total()) }} found</p>
            </div>
            <a href="{{ route('admin.posts.create') }}" class="btn-bh-primary py-2 px-3 text-sm d-inline-flex align-items-center gap-1">
                <i class="bi bi-plus-lg"></i> Write a Post
            </a>
        </div>

        <div class="bh-admin-card-body border-bottom">
            <form action="{{ route('admin.posts.index') }}" method="GET" class="bh-admin-filters" role="search">
                <label for="postSearch" class="visually-hidden">Search posts</label>
                <input type="text" id="postSearch" name="q" class="bh-form-control py-2 text-sm" placeholder="Search title or slug..." value="{{ request('q') }}">

                <label for="postStatus" class="visually-hidden">Filter by status</label>
                <select id="postStatus" name="status" class="bh-form-control py-2 text-sm">
                    <option value="">All statuses</option>
                    @foreach (['published' => 'Published', 'draft' => 'Draft', 'archived' => 'Archived'] as $value => $label)
                        <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
                    @endforeach
                </select>

                <label for="postCategory" class="visually-hidden">Filter by category</label>
                <select id="postCategory" name="category" class="bh-form-control py-2 text-sm">
                    <option value="">All categories</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected((string) request('category') === (string) $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>

                @if ($isAdmin && $authors->isNotEmpty())
                    <label for="postAuthor" class="visually-hidden">Filter by author</label>
                    <select id="postAuthor" name="author" class="bh-form-control py-2 text-sm">
                        <option value="">All authors</option>
                        @foreach ($authors as $author)
                            <option value="{{ $author->id }}" @selected((string) request('author') === (string) $author->id)>{{ $author->name }}</option>
                        @endforeach
                    </select>
                @endif

                <button type="submit" class="btn-bh-secondary py-2 px-3 text-sm">Filter</button>
                @if (request()->hasAny(['q', 'status', 'category', 'author']))
                    <a href="{{ route('admin.posts.index') }}" class="text-xs text-muted hover-underline ms-1">Reset</a>
                @endif
            </form>
        </div>

        @if ($posts->isEmpty())
            <div class="bh-admin-empty">
                <i class="bi bi-file-earmark-text"></i>
                <p class="mb-1 fw-semibold">No posts found</p>
                <p class="text-sm mb-3">
                    @if (request()->hasAny(['q', 'status', 'category', 'author']))
                        Try adjusting your filters, or start writing something new.
                    @else
                        Nothing here yet — your first article is one click away.
                    @endif
                </p>
                <a href="{{ route('admin.posts.create') }}" class="btn-bh-primary py-2 px-3 text-sm">Write a Post</a>
            </div>
        @else
            <div class="table-responsive">
                <table class="bh-admin-table">
                    <thead>
                        <tr>
                            <th scope="col">Post</th>
                            <th scope="col">Category</th>
                            @if ($isAdmin)
                                <th scope="col">Author</th>
                            @endif
                            <th scope="col">Status</th>
                            <th scope="col">Stats</th>
                            <th scope="col">Date</th>
                            <th scope="col" class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($posts as $post)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        @if ($post->featured_image)
                                            <img src="{{ $post->featured_image }}" alt="" class="bh-table-thumb">
                                        @else
                                            <span class="bh-table-thumb d-inline-flex align-items-center justify-content-center text-muted"><i class="bi bi-image"></i></span>
                                        @endif
                                        <div class="min-w-0">
                                            <a href="{{ route('admin.posts.show', $post) }}" class="bh-table-title text-truncate">{{ $post->title }}</a>
                                            <span class="text-xs text-muted text-truncate d-block">/{{ $post->slug }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $post->category?->name ?? '—' }}</td>
                                @if ($isAdmin)
                                    <td>{{ $post->author?->name ?? '—' }}</td>
                                @endif
                                <td>
                                    <span class="badge rounded-pill {{ $statusBadges[$post->status] ?? 'bg-secondary-subtle text-secondary-emphasis' }}">{{ ucfirst($post->status) }}</span>
                                </td>
                                <td class="text-nowrap text-muted text-xs">
                                    <i class="bi bi-eye me-1"></i>{{ number_format($post->views) }}
                                    <span class="mx-1">·</span>
                                    <i class="bi bi-chat-left-text me-1"></i>{{ $post->comments_count }}
                                </td>
                                <td class="text-nowrap text-muted text-xs">
                                    {{ $post->published_at ? $post->published_at->format('M j, Y') : 'Not published' }}
                                </td>
                                <td>
                                    <div class="d-flex justify-content-end gap-1">
                                        @if ($post->status === 'published' && $post->published_at?->isPast())
                                            <a href="{{ route('blogs.show', $post->slug) }}" target="_blank" rel="noopener" class="bh-action-btn" title="View public page" aria-label="View public page for {{ $post->title }}">
                                                <i class="bi bi-box-arrow-up-right"></i>
                                            </a>
                                        @endif
                                        <a href="{{ route('admin.posts.edit', $post) }}" class="bh-action-btn" title="Edit post" aria-label="Edit {{ $post->title }}">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.posts.destroy', $post) }}" method="POST" data-confirm="Delete “{{ $post->title }}”? Comments, likes and bookmarks on it will be removed too. This cannot be undone.">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bh-action-btn is-danger" title="Delete post" aria-label="Delete {{ $post->title }}">
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

            @if ($posts->hasPages())
                <div class="bh-admin-pagination">
                    {{ $posts->links('pagination::bootstrap-5') }}
                </div>
            @endif
        @endif
    </div>
@endsection
