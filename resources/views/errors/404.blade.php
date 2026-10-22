@extends('layouts.app')

@php
    $popularCategories = \App\Models\Category::withCount('publishedPosts')
        ->orderByDesc('published_posts_count')
        ->take(6)
        ->get();
@endphp

@section('title', '404 — Page Not Found — BlogHub')
@section('meta_description', 'The page you are looking for could not be found. Search the BlogHub archive or explore our most popular technical topics.')

@section('content')
<section class="py-5">
    <div class="container text-center" style="max-width: 720px;">
        <div class="display-1 font-heading fw-bold text-coral mb-0" style="letter-spacing: -0.05em; font-size: 7rem; line-height: 1;">404</div>
        <h1 class="h3 font-heading fw-bold mb-2">This Page Has Redirected to a Parallel Universe</h1>
        <p class="text-secondary text-sm mb-4">
            The URL you requested doesn't exist, was moved, or is still being drafted on a whiteboard somewhere.
            Use the search below or jump straight into our most-popular topics.
        </p>

        <form action="{{ route('search') }}" method="GET" class="mb-5" style="max-width: 480px; margin: 0 auto;" data-search-suggest="{{ route('search.suggest') }}" role="search">
            <div class="input-group">
                <label for="notFoundSearch" class="visually-hidden">Search articles</label>
                <input type="search" id="notFoundSearch" name="q" class="bh-form-control" placeholder="Search articles, authors, topics..." autocomplete="off" required>
                <button type="submit" class="btn btn-bh-primary px-4">
                    <i class="bi bi-search me-1"></i> Search
                </button>
            </div>
        </form>

        <div class="d-flex flex-wrap justify-content-center gap-3 mb-5">
            <a href="{{ route('home') }}" class="btn btn-bh-primary">
                <i class="bi bi-house-door me-2"></i>Back to Home
            </a>
            <a href="{{ route('blogs.index') }}" class="btn btn-bh-secondary">
                <i class="bi bi-journal-text me-2"></i>Browse All Articles
            </a>
        </div>

        <div class="bh-card p-4 text-start">
            <h2 class="h6 font-heading fw-bold mb-3 text-center">Popular Topics</h2>
            <div class="d-flex flex-wrap justify-content-center gap-2">
                @foreach ($popularCategories as $category)
                    <a href="{{ route('categories.show', $category->slug) }}" class="btn btn-sm btn-bh-secondary">
                        <i class="bi {{ $category->icon ?? 'bi-tag' }} me-1"></i>
                        {{ $category->name }}
                        <span class="text-xs opacity-75 ms-1">({{ $category->published_posts_count }})</span>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endsection
