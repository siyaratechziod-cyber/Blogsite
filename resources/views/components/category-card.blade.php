@props(['category'])

<a href="{{ route('categories.show', $category->slug) }}" class="bh-cat-card text-decoration-none bh-reveal">
    <div class="bh-cat-icon">
        <i class="bi {{ $category->icon ?? 'bi-folder2-open' }}"></i>
    </div>
    <div class="fw-bold font-heading text-primary fs-6">{{ $category->name }}</div>
    @if ($category->description)
        <div class="text-xs text-muted line-clamp-2 px-2">
            {{ $category->description }}
        </div>
    @endif
    <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3 py-1 text-xs mt-1">
        {{ $category->published_posts_count ?? $category->publishedPosts()->count() }} Articles
    </span>
</a>
