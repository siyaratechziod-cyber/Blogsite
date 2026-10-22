@props(['author'])

<div class="bh-card d-flex flex-column h-100 p-4 text-center bh-reveal">
    <div class="position-relative mx-auto mb-3">
        <a href="{{ route('authors.show', $author) }}">
            <img src="{{ $author->avatar_url }}" alt="{{ $author->name }}" class="bh-avatar bh-avatar-xl shadow-sm">
        </a>
        <span class="bh-badge-role bh-badge-{{ $author->role }} position-absolute bottom-0 end-0">
            {{ ucfirst($author->role) }}
        </span>
    </div>

    <h4 class="h5 mb-1 font-heading">
        <a href="{{ route('authors.show', $author) }}" class="hover-coral">
            {{ $author->name }}
        </a>
    </h4>

    <div class="text-xs text-coral fw-semibold mb-2">
        {{ $author->specialty ?? 'Technical Contributor' }}
    </div>

    <p class="text-secondary text-sm mb-4 line-clamp-2">
        {{ $author->bio ?? 'Writing about software architecture and modern web technologies on BlogHub.' }}
    </p>

    <!-- Metrics -->
    <div class="row g-2 py-3 border-top border-bottom border-secondary border-opacity-10 mb-4 mt-auto">
        <div class="col-6">
            <div class="fw-bold font-heading fs-6">{{ $author->posts_count ?? $author->posts()->count() }}</div>
            <div class="text-xs text-muted">Articles</div>
        </div>
        <div class="col-6 border-start border-secondary border-opacity-10">
            <div class="fw-bold font-heading fs-6" data-follower-count="{{ $author->id }}">{{ $author->followers_count ?? $author->followers()->count() }}</div>
            <div class="text-xs text-muted">Followers</div>
        </div>
    </div>

    <!-- Actions -->
    <div class="d-flex gap-2">
        <a href="{{ route('authors.show', $author) }}" class="btn btn-bh-secondary btn-sm flex-grow-1">
            Profile
        </a>
        <button type="button" class="btn {{ Auth::check() && Auth::user()->isFollowing($author) ? 'btn-bh-secondary' : 'btn-bh-outline-coral' }} btn-sm flex-grow-1" data-action="follow-author" data-author-id="{{ $author->id }}">
            <i class="bi {{ Auth::check() && Auth::user()->isFollowing($author) ? 'bi-check2' : 'bi-plus-lg' }}"></i>
            {{ Auth::check() && Auth::user()->isFollowing($author) ? 'Following' : 'Follow' }}
        </button>
    </div>
</div>
