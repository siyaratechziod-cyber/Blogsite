@extends('layouts.app')

@section('title', 'Topics & Categories — BlogHub')
@section('meta_description', 'Explore articles categorized by architectural domains: Web Development, Cloud, AI, Security, Mobile, and more.')

@section('content')
<section class="py-5 border-bottom border-secondary border-opacity-10">
    <div class="container">
        <x-breadcrumbs :items="['Categories' => '']" />
        <span class="text-coral fw-bold text-xs text-uppercase font-heading">Taxonomy</span>
        <h1 class="display-6 font-heading fw-bold mb-2">Architectural Domains & Topics</h1>
        <p class="text-secondary text-sm mb-0" style="max-width: 600px;">
            Navigate curated publications organized by core software engineering specializations.
        </p>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="row g-4">
            @foreach ($categories as $category)
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <x-category-card :category="$category" />
                </div>
            @endforeach
        </div>
    </div>
</section>
@endsection
