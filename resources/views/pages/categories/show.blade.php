@extends('layouts.app')

@section('title', $category->name . ' Articles — BlogHub')
@section('meta_description', $category->description ?? 'Browse articles under ' . $category->name)

@section('content')
<section class="py-5 border-bottom border-secondary border-opacity-10">
    <div class="container">
        <x-breadcrumbs :items="[
            'Categories' => route('categories.index'),
            $category->name => '',
        ]" />

        <div class="d-flex align-items-center gap-3 mb-3">
            <div class="bh-cat-icon" style="width: 60px; height: 60px; font-size: 1.75rem;">
                <i class="bi {{ $category->icon ?? 'bi-folder' }}"></i>
            </div>
            <div>
                <span class="text-coral fw-bold text-xs text-uppercase font-heading">Category Archive</span>
                <h1 class="display-6 font-heading fw-bold mb-0">{{ $category->name }}</h1>
            </div>
        </div>

        @if ($category->description)
            <p class="text-secondary text-sm mb-0" style="max-width: 640px;">
                {{ $category->description }}
            </p>
        @endif
    </div>
</section>

<section class="py-5">
    <div class="container">
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
                title="No articles in this category yet"
                description="Articles in this domain are currently being drafted by our editorial team."
                actionText="Explore Other Topics"
                :actionUrl="route('categories.index')"
            />
        @endif
    </div>
</section>
@endsection
