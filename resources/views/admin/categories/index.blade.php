@extends('layouts.admin')

@section('title', 'Categories')
@section('page-title', 'Categories')

@section('content')
    <div class="bh-admin-card">
        <div class="bh-admin-card-header">
            <div>
                <h2 class="bh-admin-card-title">All Categories</h2>
                <p class="text-xs text-muted mb-0">{{ $categories->total() }} {{ Str::plural('category', $categories->total()) }} · categories with posts cannot be deleted</p>
            </div>
            <a href="{{ route('admin.categories.create') }}" class="btn-bh-primary py-2 px-3 text-sm d-inline-flex align-items-center gap-1">
                <i class="bi bi-plus-lg"></i> New Category
            </a>
        </div>

        @if ($categories->isEmpty())
            <div class="bh-admin-empty">
                <i class="bi bi-diagram-3"></i>
                <p class="mb-1 fw-semibold">No categories yet</p>
                <p class="text-sm mb-3">Categories group your posts and power the public category pages.</p>
                <a href="{{ route('admin.categories.create') }}" class="btn-bh-primary py-2 px-3 text-sm">Create a Category</a>
            </div>
        @else
            <div class="table-responsive">
                <table class="bh-admin-table">
                    <thead>
                        <tr>
                            <th scope="col" style="width: 48px;">Icon</th>
                            <th scope="col">Name</th>
                            <th scope="col">Slug</th>
                            <th scope="col">Description</th>
                            <th scope="col">Posts</th>
                            <th scope="col" class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $category)
                            <tr>
                                <td>
                                    <span class="bh-stat-icon is-navy" style="width: 36px; height: 36px; font-size: 1rem;">
                                        <i class="bi {{ $category->icon ?: 'bi-folder' }}"></i>
                                    </span>
                                </td>
                                <td class="fw-semibold">{{ $category->name }}</td>
                                <td class="text-muted text-xs">/{{ $category->slug }}</td>
                                <td class="text-muted text-xs">{{ Str::limit($category->description, 60) ?: '—' }}</td>
                                <td>
                                    @if ($category->posts_count > 0)
                                        <a href="{{ route('admin.posts.index', ['category' => $category->id]) }}" class="badge rounded-pill bg-primary-subtle text-primary-emphasis text-decoration-none">{{ $category->posts_count }} {{ Str::plural('post', $category->posts_count) }}</a>
                                    @else
                                        <span class="badge rounded-pill bg-secondary-subtle text-secondary-emphasis">0 posts</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex justify-content-end gap-1">
                                        <a href="{{ route('admin.categories.edit', $category) }}" class="bh-action-btn" title="Edit category" aria-label="Edit {{ $category->name }}">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        @if ($category->posts_count > 0)
                                            <button type="button" class="bh-action-btn is-danger" disabled title="This category contains posts and cannot be deleted" aria-label="Delete {{ $category->name }} (blocked — contains posts)" style="opacity: 0.4;">
                                                <i class="bi bi-trash3"></i>
                                            </button>
                                        @else
                                            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" data-confirm="Delete the “{{ $category->name }}” category? This cannot be undone.">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bh-action-btn is-danger" title="Delete category" aria-label="Delete {{ $category->name }}">
                                                    <i class="bi bi-trash3"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if ($categories->hasPages())
                <div class="bh-admin-pagination">
                    {{ $categories->links('pagination::bootstrap-5') }}
                </div>
            @endif
        @endif
    </div>
@endsection
