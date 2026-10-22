@extends('layouts.app')

@section('title', $q !== '' ? 'Search: "' . $q . '" — BlogHub' : 'Search — BlogHub')
@section('meta_description', $q !== ''
    ? 'Search results for "' . $q . '" across articles, topics, and authors on BlogHub.'
    : 'Search BlogHub articles, architecture patterns, topics, and authors.')

@section('content')
@php
    $articlesTotal = $posts?->total() ?? 0;
    $noResults = $q !== '' && $articlesTotal === 0 && $categories->isEmpty() && $authors->isEmpty();
@endphp

<section class="bh-page-hero">
    <div class="container">
        <x-breadcrumbs :items="['Search' => '']" />

        <div class="row align-items-center g-4">
            <div class="col-lg-7">
                <span class="text-coral fw-bold text-xs text-uppercase font-heading">Global Query</span>
                @if ($q !== '')
                    <h1 class="h3 font-heading fw-bold mb-2">Search Results for &ldquo;{{ $q }}&rdquo;</h1>
                    <p class="text-secondary text-sm mb-0">
                        Found <span class="fw-semibold text-primary">{{ $articlesTotal }}</span> {{ Str::plural('article', $articlesTotal) }},
                        <span class="fw-semibold text-primary">{{ $categories->count() }}</span> {{ Str::plural('topic', $categories->count()) }},
                        and <span class="fw-semibold text-primary">{{ $authors->count() }}</span> {{ Str::plural('author', $authors->count()) }}.
                    </p>
                @else
                    <h1 class="h3 font-heading fw-bold mb-2">Search BlogHub</h1>
                    <p class="text-secondary text-sm mb-0">
                        Type in a topic, keyword, architecture pattern, or author name to find matching content.
                    </p>
                @endif
            </div>
            <div class="col-lg-5">
                <form action="{{ route('search') }}" method="GET" data-search-suggest="{{ route('search.suggest') }}">
                    <div class="input-group">
                        <span class="input-group-text border-end-0 text-muted">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" name="q" value="{{ $q }}" class="bh-form-control border-start-0" placeholder="Search again..." aria-label="Search" autocomplete="off" required>
                        <button type="submit" class="btn btn-bh-primary px-3" aria-label="Search">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        @if ($q === '')
            <x-empty-state
                icon="bi-search"
                title="Enter a search term"
                description="Type in a topic, keyword, or author name to find matching content. Or start exploring a popular topic below."
            />

            @if ($suggestedCategories->isNotEmpty())
                <div class="mt-5">
                    <h2 class="h5 font-heading fw-bold mb-3">Popular Topics to Explore</h2>
                    <div class="row g-3">
                        @foreach ($suggestedCategories as $cat)
                            <div class="col-lg-3 col-md-4 col-6">
                                <x-category-card :category="$cat" />
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @elseif ($noResults)
            <x-empty-state
                icon="bi-emoji-frown"
                title="No results found for '{{ $q }}'"
                description="Try using different keywords, checking spelling, or exploring one of the popular topics below."
                actionText="Browse Categories"
                :actionUrl="route('categories.index')"
            />

            @if ($suggestedCategories->isNotEmpty())
                <div class="mt-5">
                    <h2 class="h5 font-heading fw-bold mb-3">Suggested Topics</h2>
                    <div class="row g-3">
                        @foreach ($suggestedCategories as $cat)
                            <div class="col-lg-3 col-md-4 col-6">
                                <x-category-card :category="$cat" />
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @else
            <!-- Matching Categories -->
            @if ($categories->count() > 0)
                <div class="mb-5">
                    <h2 class="h5 font-heading fw-bold mb-3">Matching Topics ({{ $categories->count() }})</h2>
                    <div class="row g-3">
                        @foreach ($categories as $cat)
                            <div class="col-lg-3 col-md-4 col-6">
                                <x-category-card :category="$cat" />
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Matching Authors -->
            @if ($authors->count() > 0)
                <div class="mb-5">
                    <h2 class="h5 font-heading fw-bold mb-3">Matching Authors ({{ $authors->count() }})</h2>
                    <div class="row g-4">
                        @foreach ($authors as $author)
                            <div class="col-lg-3 col-md-4 col-6">
                                <x-author-card :author="$author" />
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Matching Articles -->
            @if ($articlesTotal > 0)
                <div>
                    <h2 class="h5 font-heading fw-bold mb-3">Articles ({{ $articlesTotal }})</h2>
                    <div class="row g-4 mb-5">
                        @foreach ($posts as $post)
                            <div class="col-lg-4 col-md-6">
                                <x-blog-card :post="$post" />
                            </div>
                        @endforeach
                    </div>

                    <div class="d-flex justify-content-center">
                        {{ $posts->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            @endif
        @endif
    </div>
</section>
@endsection
