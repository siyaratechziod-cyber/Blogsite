@extends('layouts.admin')

@section('title', 'New Category')
@section('page-title', 'New Category')

@section('content')
    <a href="{{ route('admin.categories.index') }}" class="text-sm text-muted hover-underline d-inline-block mb-3">
        <i class="bi bi-arrow-left me-1"></i>Back to Categories
    </a>

    <div class="row">
        <div class="col-lg-7">
            <div class="bh-admin-card">
                <div class="bh-admin-card-header">
                    <h2 class="bh-admin-card-title">Category Details</h2>
                </div>
                <div class="bh-admin-card-body">
                    <form action="{{ route('admin.categories.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="categoryName" class="form-label text-xs fw-semibold text-muted">Name *</label>
                            <input type="text" id="categoryName" name="name" class="bh-form-control w-100 @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="e.g. Web Development" required data-slug-source>
                        </div>

                        <div class="mb-3">
                            <label for="categorySlug" class="form-label text-xs fw-semibold text-muted">Slug</label>
                            <input type="text" id="categorySlug" name="slug" class="bh-form-control w-100 @error('slug') is-invalid @enderror" value="{{ old('slug') }}" placeholder="auto-generated-from-name" data-slug-target>
                            <p class="text-xs text-muted mb-0 mt-1">Leave blank to generate automatically. Must be unique.</p>
                        </div>

                        <div class="mb-3">
                            <label for="categoryIcon" class="form-label text-xs fw-semibold text-muted">Icon</label>
                            <div class="input-group">
                                <span class="input-group-text bg-transparent text-muted"><i class="bi {{ old('icon', 'bi-folder') }}" id="categoryIconPreview"></i></span>
                                <input type="text" id="categoryIcon" name="icon" class="bh-form-control border-start-0 @error('icon') is-invalid @enderror" value="{{ old('icon') }}" placeholder="bi-code-slash">
                            </div>
                            <p class="text-xs text-muted mb-0 mt-1">Any <a href="https://icons.getbootstrap.com" target="_blank" rel="noopener" class="text-coral hover-underline">Bootstrap Icons</a> class, e.g. <code>bi-code-slash</code>, <code>bi-cpu</code>.</p>
                        </div>

                        <div class="mb-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <label for="categoryDescription" class="form-label text-xs fw-semibold text-muted mb-0">Description</label>
                                <span class="text-xs text-muted" data-char-counter-output></span>
                            </div>
                            <textarea id="categoryDescription" name="description" rows="3" class="bh-form-control w-100 text-sm @error('description') is-invalid @enderror" placeholder="What kind of articles belong in this category?" data-char-counter="500">{{ old('description') }}</textarea>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn-bh-primary py-2 px-4 text-sm">
                                <i class="bi bi-check-lg me-1"></i>Create Category
                            </button>
                            <a href="{{ route('admin.categories.index') }}" class="btn-bh-secondary py-2 px-4 text-sm">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
