@extends('layouts.app')

@section('title', 'Articles Archive — BlogHub')
@section('meta_description', 'Browse all technical articles, engineering deep-dives, and tutorials published on BlogHub. Filter by category, author and popularity.')

@section('content')
@php
    $activeCategory = $categories->firstWhere('slug', request('category'));
    $activeAuthor = $authors->firstWhere('id', (int) request('author'));
    $activeTag = $tags->firstWhere('slug', request('tag'));
    $hasFilters = request()->filled('q') || request()->filled('category') || request()->filled('author') || request()->filled('tag');
@endphp

<section class="bh-page-hero">
    <div class="container">
        <x-breadcrumbs :items="['Articles' => '']" />

        <div class="row align-items-end justify-content-between g-4">
            <div class="col-lg-6">
                <span class="text-coral fw-bold text-xs text-uppercase font-heading">The Archive</span>
                <h1 class="display-6 font-heading fw-bold mb-2">Explore All Articles</h1>
                <p class="text-secondary text-sm mb-0">
                    Comprehensive write-ups on frameworks, architecture, cloud infrastructure, and modern software design.
                </p>
            </div>

            <!-- Filter Toolbar -->
            <div class="col-lg-6">
                <form action="{{ route('blogs.index') }}" method="GET" class="row g-2">
                    <div class="col-md-12" data-search-suggest="{{ route('search.suggest') }}">
                        <div class="input-group">
                            <span class="input-group-text border-end-0 text-muted">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" name="q" value="{{ request('q') }}" class="bh-form-control border-start-0" placeholder="Search within articles..." aria-label="Search articles" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-6">
                        <select name="author" class="bh-form-control" aria-label="Filter by author" onchange="this.form.submit()">
                            <option value="">All Authors</option>
                            @foreach ($authors as $author)
                                <option value="{{ $author->id }}" {{ (string) request('author') === (string) $author->id ? 'selected' : '' }}>
                                    {{ $author->name }} ({{ $author->posts_count }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6">
                        <select name="sort" class="bh-form-control" aria-label="Sort articles" onchange="this.form.submit()">
                            <option value="latest" {{ $sort === 'latest' ? 'selected' : '' }}>Newest First</option>
                            <option value="oldest" {{ $sort === 'oldest' ? 'selected' : '' }}>Oldest First</option>
                            <option value="popular" {{ $sort === 'popular' ? 'selected' : '' }}>Most Viewed</option>
                        </select>
                    </div>
                    @if (request('category'))
                        <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif
                    @if (request('tag'))
                        <input type="hidden" name="tag" value="{{ request('tag') }}">
                    @endif
                    <div class="col-12 d-grid d-md-none">
                        <button class="btn btn-bh-primary" type="submit">
                            <i class="bi bi-funnel me-1"></i>Apply Filters
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Category Filter Pills -->
        <div class="d-flex flex-wrap gap-2 pt-4">
            <a href="{{ route('blogs.index', request()->except(['category', 'page'])) }}" class="btn btn-sm {{ ! request('category') ? 'btn-bh-primary' : 'btn-bh-secondary' }}">
                All Categories
            </a>
            @foreach ($categories as $cat)
                <a href="{{ route('blogs.index', array_merge(request()->except('page'), ['category' => $cat->slug])) }}" class="btn btn-sm {{ request('category') === $cat->slug ? 'btn-bh-primary' : 'btn-bh-secondary' }}">
                    <i class="bi {{ $cat->icon ?? 'bi-folder' }} me-1"></i>
                    {{ $cat->name }}
                    <span class="ms-1 opacity-75">({{ $cat->published_posts_count }})</span>
                </a>
            @endforeach
        </div>
    </div>
</section>

<!-- Articles Grid Section -->
<section class="py-5">
    <div class="container">
        <!-- Results count + view toggle -->
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
            <div class="text-sm text-muted">
                @if ($posts->total() > 0)
                    Showing <span class="fw-semibold text-primary">{{ $posts->firstItem() }}&ndash;{{ $posts->lastItem() }}</span>
                    of <span class="fw-semibold text-primary">{{ $posts->total() }}</span> articles
                @else
                    No articles found
                @endif
            </div>
            <div class="d-flex align-items-center gap-3">
                @if ($hasFilters)
                    <a href="{{ route('blogs.index') }}" class="text-sm fw-semibold text-coral text-decoration-none">
                        <i class="bi bi-x-circle me-1"></i>Clear All Filters
                    </a>
                @endif
                <div class="bh-view-toggle btn-group" role="group" aria-label="Toggle grid or list view" data-view-toggle>
                    <button type="button" class="btn active" data-view="grid" title="Grid view" aria-label="Grid view" aria-pressed="true">
                        <i class="bi bi-grid-3x3-gap-fill"></i>
                    </button>
                    <button type="button" class="btn" data-view="list" title="List view" aria-label="List view" aria-pressed="false">
                        <i class="bi bi-list-ul"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Active filter chips -->
        @if ($hasFilters)
            <div class="d-flex flex-wrap align-items-center gap-2 mb-4">
                <span class="text-xs text-muted text-uppercase fw-semibold">Active filters:</span>
                @if (request()->filled('q'))
                    <a href="{{ route('blogs.index', request()->except(['q', 'page'])) }}" class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3 py-2 text-xs fw-medium text-decoration-none">
                        Search: "{{ Str::limit(request('q'), 30) }}" <i class="bi bi-x ms-1"></i>
                    </a>
                @endif
                @if ($activeCategory)
                    <a href="{{ route('blogs.index', request()->except(['category', 'page'])) }}" class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3 py-2 text-xs fw-medium text-decoration-none">
                        <i class="bi {{ $activeCategory->icon ?? 'bi-folder' }} me-1"></i>{{ $activeCategory->name }} <i class="bi bi-x ms-1"></i>
                    </a>
                @endif
                @if ($activeAuthor)
                    <a href="{{ route('blogs.index', request()->except(['author', 'page'])) }}" class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3 py-2 text-xs fw-medium text-decoration-none">
                        <i class="bi bi-person me-1"></i>{{ $activeAuthor->name }} <i class="bi bi-x ms-1"></i>
                    </a>
                @endif
                @if ($activeTag)
                    <a href="{{ route('blogs.index', request()->except(['tag', 'page'])) }}" class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3 py-2 text-xs fw-medium text-decoration-none">
                        <i class="bi bi-tag me-1"></i>{{ $activeTag->name }} <i class="bi bi-x ms-1"></i>
                    </a>
                @endif
            </div>
        @endif

        <div id="bhPostsContainer">
            @if ($posts->count() > 0)
                <div class="row g-4 mb-5">
                    @foreach ($posts as $post)
                        <div class="col-lg-4 col-md-6">
                            <x-blog-card :post="$post" />
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center pt-3">
                    {{ $posts->links('pagination::bootstrap-5') }}
                </div>
            @else
                <x-empty-state
                    icon="bi-journal-x"
                    title="No articles match your criteria"
                    description="Try clearing your search query or choosing a different category to see available posts."
                    actionText="Reset Filters"
                    :actionUrl="route('blogs.index')"
                />
            @endif
        </div>
    </div>
</section>
@endsection
