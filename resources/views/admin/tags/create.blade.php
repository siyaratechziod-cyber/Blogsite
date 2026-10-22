@extends('layouts.admin')

@section('title', 'New Tag')
@section('page-title', 'New Tag')

@section('content')
    <a href="{{ route('admin.tags.index') }}" class="text-sm text-muted hover-underline d-inline-block mb-3">
        <i class="bi bi-arrow-left me-1"></i>Back to Tags
    </a>

    <div class="row">
        <div class="col-lg-6">
            <div class="bh-admin-card">
                <div class="bh-admin-card-header">
                    <h2 class="bh-admin-card-title">Tag Details</h2>
                </div>
                <div class="bh-admin-card-body">
                    <form action="{{ route('admin.tags.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="tagName" class="form-label text-xs fw-semibold text-muted">Name *</label>
                            <input type="text" id="tagName" name="name" class="bh-form-control w-100 @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="e.g. Laravel" required data-slug-source>
                        </div>

                        <div class="mb-4">
                            <label for="tagSlug" class="form-label text-xs fw-semibold text-muted">Slug</label>
                            <input type="text" id="tagSlug" name="slug" class="bh-form-control w-100 @error('slug') is-invalid @enderror" value="{{ old('slug') }}" placeholder="auto-generated-from-name" data-slug-target>
                            <p class="text-xs text-muted mb-0 mt-1">Leave blank to generate automatically. Must be unique.</p>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn-bh-primary py-2 px-4 text-sm">
                                <i class="bi bi-check-lg me-1"></i>Create Tag
                            </button>
                            <a href="{{ route('admin.tags.index') }}" class="btn-bh-secondary py-2 px-4 text-sm">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
