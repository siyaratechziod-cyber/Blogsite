@extends('layouts.app')

@section('title', 'Verified Authors & Contributors — BlogHub')
@section('meta_description', 'Meet the veteran software engineers, principal architects, and engineering leaders writing on BlogHub.')

@section('content')
@php
    $hasFilters = request()->filled('q') || request()->filled('specialty');
@endphp

<section class="bh-page-hero">
    <div class="container">
        <x-breadcrumbs :items="['Authors' => '']" />

        <div class="row align-items-end justify-content-between g-4">
            <div class="col-lg-6">
                <span class="text-coral fw-bold text-xs text-uppercase font-heading">Contributors</span>
                <h1 class="display-6 font-heading fw-bold mb-2">Our Engineering Voices</h1>
                <p class="text-secondary text-sm mb-0" style="max-width: 600px;">
                    Meet the experienced engineers, researchers, and systems thinkers sharing practical wisdom on BlogHub.
                </p>
            </div>

            <!-- Author Search & Specialty Filter -->
            <div class="col-lg-5">
                <form action="{{ route('authors.index') }}" method="GET" class="row g-2">
                    <div class="col-12">
                        <div class="input-group">
                            <span class="input-group-text border-end-0 text-muted">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" name="q" value="{{ request('q') }}" class="bh-form-control border-start-0" placeholder="Search authors by name or expertise..." aria-label="Search authors" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-8">
                        <select name="specialty" class="bh-form-control" aria-label="Filter by specialty">
                            <option value="">All Specialties</option>
                            @foreach ($specialties as $item)
                                <option value="{{ $item }}" {{ request('specialty') === $item ? 'selected' : '' }}>{{ $item }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-4 d-grid">
                        <button class="btn btn-bh-primary" type="submit">
                            <i class="bi bi-funnel me-1"></i>Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
            <div class="text-sm text-muted">
                @if ($authors->total() > 0)
                    Showing <span class="fw-semibold text-primary">{{ $authors->firstItem() }}&ndash;{{ $authors->lastItem() }}</span>
                    of <span class="fw-semibold text-primary">{{ $authors->total() }}</span> authors
                @else
                    No authors found
                @endif
            </div>
            @if ($hasFilters)
                <a href="{{ route('authors.index') }}" class="text-sm fw-semibold text-coral text-decoration-none">
                    <i class="bi bi-x-circle me-1"></i>Clear Filters
                </a>
            @endif
        </div>

        @if ($authors->count() > 0)
            <div class="row g-4 mb-5">
                @foreach ($authors as $author)
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <x-author-card :author="$author" />
                    </div>
                @endforeach
            </div>

            <div class="d-flex justify-content-center">
                {{ $authors->links('pagination::bootstrap-5') }}
            </div>
        @else
            <x-empty-state
                icon="bi-people"
                title="No authors match your criteria"
                description="Try a different name, expertise keyword, or clear the specialty filter to browse all contributors."
                actionText="Reset Filters"
                :actionUrl="route('authors.index')"
            />
        @endif
    </div>
</section>
@endsection
