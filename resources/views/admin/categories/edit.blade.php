@extends('layouts.admin')

@section('title', 'Edit Category')
@section('page-title', 'Edit Category')

@php($postsCount = $category->posts()->count())

@section('content')
    <a href="{{ route('admin.categories.index') }}" class="text-sm text-muted hover-underline d-inline-block mb-3">
        <i class="bi bi-arrow-left me-1"></i>Back to Categories
    </a>

    <div class="row">
        <div class="col-lg-7">
            <div class="bh-admin-card">
                <div class="bh-admin-card-header">
                    <h2 class="bh-admin-card-title">Category Details</h2>
                    <span class="text-xs text-muted">{{ $postsCount }} {{ Str::plural('post', $postsCount) }} in this category</span>
                </div>
                <div class="bh-admin-card-body">
                    <form action="{{ route('admin.categories.update', $category) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="categoryName" class="form-label text-xs fw-semibold text-muted">Name *</label>
                            <input type="text" id="categoryName" name="name" class="bh-form-control w-100 @error('name') is-invalid @enderror" value="{{ old('name', $category->name) }}" required data-slug-source>
                        </div>

                        <div class="mb-3">
                            <label for="categorySlug" class="form-label text-xs fw-semibold text-muted">Slug</label>
                            <input type="text" id="categorySlug" name="slug" class="bh-form-control w-100 @error('slug') is-invalid @enderror" value="{{ old('slug', $category->slug) }}" data-slug-target>
                            <p class="text-xs text-muted mb-0 mt-1">Changing the slug changes the public category URL.</p>
                        </div>

                        <div class="mb-3">
                            <label for="categoryIcon" class="form-label text-xs fw-semibold text-muted">Icon</label>
                            <div class="input-group">
                                <span class="input-group-text bg-transparent text-muted"><i class="bi {{ old('icon', $category->icon ?: 'bi-folder') }}"></i></span>
                                <input type="text" id="categoryIcon" name="icon" class="bh-form-control border-start-0 @error('icon') is-invalid @enderror" value="{{ old('icon', $category->icon) }}" placeholder="bi-code-slash">
                            </div>
                            <p class="text-xs text-muted mb-0 mt-1">Any <a href="https://icons.getbootstrap.com" target="_blank" rel="noopener" class="text-coral hover-underline">Bootstrap Icons</a> class.</p>
                        </div>

                        <div class="mb-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <label for="categoryDescription" class="form-label text-xs fw-semibold text-muted mb-0">Description</label>
                                <span class="text-xs text-muted" data-char-counter-output></span>
                            </div>
                            <textarea id="categoryDescription" name="description" rows="3" class="bh-form-control w-100 text-sm @error('description') is-invalid @enderror" placeholder="What kind of articles belong in this category?" data-char-counter="500">{{ old('description', $category->description) }}</textarea>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn-bh-primary py-2 px-4 text-sm">
                                <i class="bi bi-check-lg me-1"></i>Save Changes
                            </button>
                            <a href="{{ route('admin.categories.index') }}" class="btn-bh-secondary py-2 px-4 text-sm">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
