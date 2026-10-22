@props(['post'])

@php
    $liked = $post->liked_by_me !== null
        ? (bool) $post->liked_by_me
        : (Auth::check() && $post->isLikedBy(Auth::user()));
    $bookmarked = $post->bookmarked_by_me !== null
        ? (bool) $post->bookmarked_by_me
        : (Auth::check() && $post->isBookmarkedBy(Auth::user()));
    $commentsCount = $post->comments_count ?? $post->comments()->count();
@endphp

<article class="bh-card d-flex flex-column h-100 bh-reveal">
    <div class="bh-card-img-wrapper">
        <a href="{{ route('blogs.show', $post->slug) }}" aria-label="{{ $post->title }}">
            <img src="{{ $post->featured_image ?? 'https://images.unsplash.com/photo-1555066931-4365d14bab8c?auto=format&fit=crop&w=800&q=80' }}" alt="{{ $post->title }}" loading="lazy">
        </a>
        @if ($post->category)
            <div class="position-absolute top-0 start-0 m-3">
                <a href="{{ route('categories.show', $post->category->slug) }}" class="bh-badge-category shadow-sm">
                    <i class="bi {{ $post->category->icon ?? 'bi-tag' }}"></i>
                    {{ $post->category->name }}
                </a>
            </div>
        @endif
    </div>

    <div class="bh-card-body d-flex flex-column flex-grow-1">
        <div class="d-flex align-items-center gap-3 text-xs text-muted mb-2">
            <span><i class="bi bi-clock me-1"></i>{{ $post->estimated_reading_time }} min read</span>
            <span>&bull;</span>
            <span>{{ $post->published_at ? $post->published_at->format('M d, Y') : 'Recently' }}</span>
        </div>

        <h3 class="h5 mb-2 fw-bold font-heading line-clamp-2">
            <a href="{{ route('blogs.show', $post->slug) }}" class="hover-coral">
                {{ $post->title }}
            </a>
        </h3>

        <p class="text-secondary text-sm mb-4 line-clamp-3 flex-grow-1">
            {{ $post->excerpt }}
        </p>

        <!-- Card Footer with Author and Actions -->
        <div class="d-flex align-items-center justify-content-between pt-3 border-top border-secondary border-opacity-10 mt-auto">
            <a href="{{ route('authors.show', $post->author) }}" class="d-flex align-items-center gap-2">
                <img src="{{ $post->author->avatar_url }}" alt="{{ $post->author->name }}" class="bh-avatar bh-avatar-sm">
                <div>
                    <div class="fw-semibold text-xs text-primary">{{ $post->author->name }}</div>
                    <div class="text-muted" style="font-size: 0.725rem;">{{ Str::limit($post->author->specialty ?? 'Author', 24) }}</div>
                </div>
            </a>

            <div class="d-flex align-items-center gap-2">
                <!-- Views -->
                <span class="text-muted d-flex align-items-center gap-1 text-xs" title="Views">
                    <i class="bi bi-eye"></i>{{ number_format($post->views) }}
                </span>

                <!-- Comments -->
                <a href="{{ route('blogs.show', $post->slug) }}#comments" class="text-muted d-flex align-items-center gap-1 text-xs text-decoration-none" title="Comments">
                    <i class="bi bi-chat"></i>{{ number_format($commentsCount) }}
                </a>

                <!-- Like Button -->
                <button type="button" class="btn p-0 border-0 text-muted d-flex align-items-center gap-1 text-xs" data-action="like-post" data-post-id="{{ $post->id }}" title="Like this article">
                    <i class="bi {{ $liked ? 'bi-heart-fill text-danger' : 'bi-heart' }}"></i>
                    <span class="like-count">{{ $post->likes_count ?? $post->likes()->count() }}</span>
                </button>

                <!-- Bookmark Button -->
                <button type="button" class="btn p-0 border-0 text-muted text-xs" data-action="bookmark-post" data-post-id="{{ $post->id }}" title="Bookmark article">
                    <i class="bi {{ $bookmarked ? 'bi-bookmark-fill text-primary' : 'bi-bookmark' }}"></i>
                </button>
            </div>
        </div>
    </div>
</article>
