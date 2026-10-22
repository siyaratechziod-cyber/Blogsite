@extends('layouts.app')

@section('title', $author->name . ' — Author Profile — BlogHub')
@section('meta_description', $author->bio ?? 'Read articles authored by ' . $author->name . ' on BlogHub.')

@section('content')
@php
    $isFollowing = Auth::check() && Auth::user()->isFollowing($author);
@endphp

<!-- Author Profile Header -->
<section class="py-5">
    <div class="container">
        <x-breadcrumbs :items="[
            'Authors' => route('authors.index'),
            $author->name => '',
        ]" />

        <div class="bh-card overflow-hidden mb-5">
            <!-- Cover Band -->
            <div class="bh-cover-band"></div>

            <div class="p-4 p-md-5">
                <div class="row g-4 align-items-start">
                    <div class="col-md-auto text-center">
                        <img src="{{ $author->avatar_url }}" alt="{{ $author->name }}" class="bh-profile-avatar mx-auto" style="margin-top: -92px;">
                    </div>

                    <div class="col-md">
                        <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                            <h1 class="h3 font-heading fw-bold mb-0">{{ $author->name }}</h1>
                            <span class="bh-badge-role bh-badge-{{ $author->role }}" style="position: static;">{{ ucfirst($author->role) }}</span>
                        </div>

                        @if ($author->username)
                            <div class="text-xs text-muted mb-2">&#64;{{ $author->username }}</div>
                        @endif

                        <div class="text-coral fw-semibold text-sm mb-3">
                            {{ $author->specialty ?? 'Technical Writer' }}
                        </div>

                        <p class="text-secondary text-sm mb-4" style="max-width: 640px;">
                            {{ $author->bio ?? 'Writing about software architecture, scaling patterns, and engineering culture.' }}
                        </p>

                        <div class="d-flex flex-wrap align-items-center gap-4">
                            <!-- Follow Button -->
                            <button type="button" class="btn {{ $isFollowing ? 'btn-bh-secondary' : 'btn-bh-outline-coral' }}" data-action="follow-author" data-author-id="{{ $author->id }}" data-follow-url="{{ route('authors.follow', $author) }}">
                                <i class="bi {{ $isFollowing ? 'bi-check2' : 'bi-plus-lg' }}"></i>
                                {{ $isFollowing ? 'Following' : 'Follow Author' }}
                            </button>

                            <!-- Counts -->
                            <div class="d-flex align-items-center gap-4 text-sm text-secondary">
                                <div>
                                    <span class="fw-bold font-heading text-primary fs-6">{{ number_format($author->posts_count) }}</span>
                                    <span class="text-muted text-xs ms-1">Articles</span>
                                </div>
                                <div>
                                    <span class="fw-bold font-heading text-primary fs-6" data-follower-count="{{ $author->id }}">{{ number_format($author->followers_count) }}</span>
                                    <span class="text-muted text-xs ms-1">Followers</span>
                                </div>
                                <div>
                                    <span class="fw-bold font-heading text-primary fs-6">{{ number_format($author->following_count) }}</span>
                                    <span class="text-muted text-xs ms-1">Following</span>
                                </div>
                            </div>

                            <!-- Social Links -->
                            @if ($author->social_links && is_array($author->social_links))
                                <div class="d-flex gap-2 ms-md-auto">
                                    @foreach ($author->social_links as $platform => $url)
                                        <a href="{{ $url }}" target="_blank" rel="noopener" class="btn-icon btn-sm" title="{{ ucfirst($platform) }}" aria-label="{{ ucfirst($platform) }} profile">
                                            @if ($platform === 'twitter') <i class="bi bi-twitter-x"></i>
                                            @elseif ($platform === 'github') <i class="bi bi-github"></i>
                                            @elseif ($platform === 'linkedin') <i class="bi bi-linkedin"></i>
                                            @else <i class="bi bi-globe"></i>
                                            @endif
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <div class="text-xs text-muted mt-3">
                            <i class="bi bi-calendar3 me-1"></i>Member since {{ $author->created_at ? $author->created_at->format('F Y') : 'Day One' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Topic Tags -->
        @if ($topTags->isNotEmpty())
            <div class="d-flex flex-wrap align-items-center gap-2 mb-5">
                <span class="text-xs fw-bold text-muted text-uppercase me-2">Writes about:</span>
                @foreach ($topTags as $tag)
                    <a href="{{ route('blogs.index', ['tag' => $tag->slug]) }}" class="bh-badge-tag">
                        #{{ $tag->name }}
                        <span class="opacity-75 ms-1">({{ $tag->posts_count }})</span>
                    </a>
                @endforeach
            </div>
        @endif

        <!-- Author Articles -->
        <h2 class="h4 font-heading fw-bold mb-4">Articles by {{ $author->name }}</h2>

        @if ($posts->count() > 0)
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
        @else
            <x-empty-state
                icon="bi-journal-x"
                title="No articles published yet"
                description="{{ $author->name }} has not published any articles yet."
            />
        @endif
    </div>
</section>
@endsection
