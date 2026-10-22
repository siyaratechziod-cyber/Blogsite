@extends('layouts.app')

@section('title', $post->title . ' — BlogHub')
@section('meta_description', $post->excerpt)
@section('meta_author', $post->author->name)
@section('og_type', 'article')
@if ($post->featured_image)
    @section('og_image', $post->featured_image)
@endif

@section('content')
@php
    $liked = $post->liked_by_me !== null ? (bool) $post->liked_by_me : (Auth::check() && $post->isLikedBy(Auth::user()));
    $bookmarked = $post->bookmarked_by_me !== null ? (bool) $post->bookmarked_by_me : (Auth::check() && $post->isBookmarkedBy(Auth::user()));
    $shareUrl = url()->current();
    $isFollowingAuthor = Auth::check() && Auth::user()->isFollowing($post->author);
@endphp

<article class="py-5">
    <div class="container" style="max-width: 880px;">
        <x-breadcrumbs :items="[
            'Articles' => route('blogs.index'),
            $post->category->name => route('categories.show', $post->category->slug),
            Str::limit($post->title, 24) => '',
        ]" />

        <!-- Article Header -->
        <header class="mb-4">
            <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                <a href="{{ route('categories.show', $post->category->slug) }}" class="bh-badge-category">
                    <i class="bi {{ $post->category->icon ?? 'bi-tag' }}"></i>
                    {{ $post->category->name }}
                </a>
                <span class="text-xs text-muted">&bull;</span>
                <span class="text-xs text-muted"><i class="bi bi-clock me-1"></i>{{ $post->estimated_reading_time }} min read</span>
                <span class="text-xs text-muted">&bull;</span>
                <span class="text-xs text-muted"><i class="bi bi-eye me-1"></i>{{ number_format($post->views) }} views</span>
                <span class="text-xs text-muted">&bull;</span>
                <span class="text-xs text-muted"><i class="bi bi-chat me-1"></i>{{ number_format($post->approvedComments->count()) }} comments</span>
            </div>

            <h1 class="display-5 font-heading fw-bold mb-4" style="letter-spacing: -0.03em; line-height: 1.2;">
                {{ $post->title }}
            </h1>

            <p class="lead text-secondary mb-4" style="font-size: 1.2rem; line-height: 1.6;">
                {{ $post->excerpt }}
            </p>

            <!-- Author Banner & Interactive Bar -->
            <div class="d-flex flex-wrap align-items-center justify-content-between p-3 rounded-4 border border-secondary border-opacity-10 mb-4" style="background-color: var(--bh-surface-card);">
                <div class="d-flex align-items-center gap-3">
                    <a href="{{ route('authors.show', $post->author) }}">
                        <img src="{{ $post->author->avatar_url }}" alt="{{ $post->author->name }}" class="bh-avatar bh-avatar-md">
                    </a>
                    <div>
                        <div class="d-flex align-items-center gap-2">
                            <a href="{{ route('authors.show', $post->author) }}" class="fw-bold text-sm text-primary hover-coral">
                                {{ $post->author->name }}
                            </a>
                            <span class="bh-badge-role bh-badge-{{ $post->author->role }}">{{ ucfirst($post->author->role) }}</span>
                        </div>
                        <div class="text-xs text-muted">
                            {{ $post->author->specialty ?? 'Technical Contributor' }} &bull;
                            Published {{ $post->published_at ? $post->published_at->format('M d, Y') : 'Recently' }}
                        </div>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-2 mt-3 mt-sm-0">
                    <a href="{{ route('authors.show', $post->author) }}" class="btn btn-bh-secondary btn-sm">
                        <i class="bi bi-person me-1"></i>Profile
                    </a>
                    <button type="button" class="btn {{ $isFollowingAuthor ? 'btn-bh-secondary' : 'btn-bh-outline-coral' }} btn-sm" data-action="follow-author" data-author-id="{{ $post->author->id }}" data-follow-url="{{ route('authors.follow', $post->author) }}">
                        <i class="bi {{ $isFollowingAuthor ? 'bi-check2' : 'bi-plus-lg' }}"></i>
                        {{ $isFollowingAuthor ? 'Following' : 'Follow' }}
                    </button>
                </div>
            </div>
        </header>

        <!-- Featured Image -->
        @if ($post->featured_image)
            <figure class="mb-5 overflow-hidden rounded-4 shadow-sm" style="aspect-ratio: 16/9;">
                <img src="{{ $post->featured_image }}" alt="{{ $post->title }}" class="w-100 h-100 object-fit-cover">
            </figure>
        @endif

        <!-- Article Body Content (Markdown rendered) -->
        <div class="bh-article-content mb-5">
            {!! Str::markdown($post->content ?? '', ['html_input' => 'strip', 'allow_unsafe_links' => false]) !!}
        </div>

        <!-- Tags List -->
        @if ($post->tags->count() > 0)
            <div class="d-flex flex-wrap align-items-center gap-2 py-4 border-top border-bottom border-secondary border-opacity-10 mb-5">
                <span class="text-xs fw-bold text-muted text-uppercase me-2">Tags:</span>
                @foreach ($post->tags as $tag)
                    <a href="{{ route('blogs.index', ['tag' => $tag->slug]) }}" class="bh-badge-tag">
                        #{{ $tag->name }}
                    </a>
                @endforeach
            </div>
        @endif

        <!-- Sticky-Style Action Bar -->
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 p-3 rounded-4 shadow-sm border border-secondary border-opacity-10 mb-5" style="background-color: var(--bh-surface-elevated);">
            <div class="d-flex align-items-center gap-3">
                <button type="button" class="btn btn-sm d-flex align-items-center gap-2" data-action="like-post" data-post-id="{{ $post->id }}" title="Like this article">
                    <i class="bi {{ $liked ? 'bi-heart-fill text-danger' : 'bi-heart text-secondary' }} fs-5"></i>
                    <span class="fw-bold text-sm like-count">{{ number_format($post->likes_count ?? $post->likes()->count()) }}</span>
                    <span class="text-xs text-muted d-none d-sm-inline">Likes</span>
                </button>

                <button type="button" class="btn btn-sm d-flex align-items-center gap-2" data-action="bookmark-post" data-post-id="{{ $post->id }}" title="Bookmark for later">
                    <i class="bi {{ $bookmarked ? 'bi-bookmark-fill text-primary' : 'bi-bookmark text-secondary' }} fs-5"></i>
                    <span class="text-xs text-muted d-none d-sm-inline">{{ $bookmarked ? 'Saved' : 'Save' }}</span>
                </button>
            </div>

            <div class="d-flex align-items-center gap-2">
                <button type="button" class="btn btn-sm btn-bh-secondary" data-copy-url="{{ $shareUrl }}">
                    <i class="bi bi-link-45deg me-1"></i>Copy Link
                </button>
                <div class="dropdown">
                    <button class="btn btn-sm btn-bh-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-share me-1"></i>Share
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end bh-share-menu shadow">
                        <li>
                            <a class="dropdown-item" href="https://twitter.com/intent/tweet?url={{ urlencode($shareUrl) }}&text={{ urlencode($post->title) }}" target="_blank" rel="noopener">
                                <i class="bi bi-twitter-x me-2"></i>X (Twitter)
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($shareUrl) }}" target="_blank" rel="noopener">
                                <i class="bi bi-facebook me-2"></i>Facebook
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode($shareUrl) }}" target="_blank" rel="noopener">
                                <i class="bi bi-linkedin me-2"></i>LinkedIn
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="https://wa.me/?text={{ urlencode($post->title . ' — ' . $shareUrl) }}" target="_blank" rel="noopener">
                                <i class="bi bi-whatsapp me-2"></i>WhatsApp
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="mailto:?subject={{ urlencode($post->title) }}&body={{ urlencode('Worth reading on BlogHub: ' . $shareUrl) }}">
                                <i class="bi bi-envelope me-2"></i>Email
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <button class="dropdown-item" type="button" data-copy-url="{{ $shareUrl }}">
                                <i class="bi bi-link-45deg me-2"></i>Copy Link
                            </button>
                        </li>
                        <li>
                            <button class="dropdown-item" type="button" data-native-share data-share-url="{{ $shareUrl }}" data-share-title="{{ $post->title }}">
                                <i class="bi bi-phone me-2"></i>Share via device
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Comments Section -->
        <section id="comments" class="mb-5 pt-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h2 class="h4 font-heading fw-bold mb-0">
                    Discussion ({{ $post->approvedComments->count() }})
                </h2>
            </div>

            <!-- Comment Form -->
            <div class="bh-card p-4 mb-4">
                <h3 class="h6 font-heading fw-bold mb-3">Join the Conversation</h3>

                @guest
                    <p class="text-xs text-muted mb-3">
                        <i class="bi bi-info-circle me-1"></i>
                        Posting as a guest. <a href="{{ route('login') }}" class="fw-semibold">Log in</a> to comment with your BlogHub profile.
                    </p>
                @endguest

                <form action="{{ route('comments.store', $post) }}" method="POST" data-validate>
                    @csrf
                    @guest
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="comment-name" class="form-label text-xs fw-semibold text-muted">Your Name *</label>
                                <input type="text" id="comment-name" name="name" class="bh-form-control w-100" placeholder="e.g. Alex Miller" value="{{ old('name') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="comment-email" class="form-label text-xs fw-semibold text-muted">Your Email *</label>
                                <input type="email" id="comment-email" name="email" class="bh-form-control w-100" placeholder="e.g. alex@example.com" value="{{ old('email') }}" required>
                            </div>
                        </div>
                    @endguest

                    <div class="mb-3">
                        <label for="comment-content" class="form-label text-xs fw-semibold text-muted">Your Comment *</label>
                        <textarea name="content" id="comment-content" rows="4" class="bh-form-control w-100" placeholder="Share your perspective or ask a technical question..." required minlength="3" maxlength="1500" data-char-counter="1500">{{ old('content') }}</textarea>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-bh-primary btn-sm">
                            <i class="bi bi-chat-left-text me-1"></i> Post Comment
                        </button>
                    </div>
                </form>
            </div>

            <!-- Comments List -->
            @if ($post->approvedComments->count() > 0)
                <div class="d-flex flex-column gap-3">
                    @foreach ($post->approvedComments as $comment)
                        <div class="bh-card p-4">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div class="d-flex align-items-center gap-2">
                                    <img src="{{ $comment->author_avatar }}" alt="{{ $comment->author_name }}" class="bh-avatar bh-avatar-sm">
                                    <div>
                                        <div class="fw-semibold text-sm text-primary">{{ $comment->author_name }}</div>
                                        <div class="text-xs text-muted">{{ $comment->created_at->diffForHumans() }}</div>
                                    </div>
                                </div>
                                @if ($comment->user && $comment->user->id === $post->author->id)
                                    <span class="bh-badge-role bh-badge-author">Author</span>
                                @endif
                            </div>
                            <p class="text-secondary text-sm mb-0 ps-sm-5">
                                {{ $comment->content }}
                            </p>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-secondary text-sm fst-italic">Be the first to leave a comment on this article.</p>
            @endif
        </section>

        <!-- Related Articles -->
        @if ($relatedPosts->count() > 0)
            <section class="pt-5 border-top border-secondary border-opacity-10">
                <h2 class="h4 font-heading fw-bold mb-4">Related Publications</h2>
                <div class="row g-4">
                    @foreach ($relatedPosts as $rPost)
                        <div class="col-md-4">
                            <x-blog-card :post="$rPost" />
                        </div>
                    @endforeach
                </div>
            </section>
        @endif
    </div>
</article>
@endsection
