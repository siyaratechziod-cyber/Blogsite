@extends('layouts.admin')

@section('title', 'Tags')
@section('page-title', 'Tags')

@section('content')
    <div class="bh-admin-card">
        <div class="bh-admin-card-header">
            <div>
                <h2 class="bh-admin-card-title">All Tags</h2>
                <p class="text-xs text-muted mb-0">{{ $tags->total() }} {{ Str::plural('tag', $tags->total()) }} · deleting a tag only removes it from posts, articles stay intact</p>
            </div>
            <a href="{{ route('admin.tags.create') }}" class="btn-bh-primary py-2 px-3 text-sm d-inline-flex align-items-center gap-1">
                <i class="bi bi-plus-lg"></i> New Tag
            </a>
        </div>

        @if ($tags->isEmpty())
            <div class="bh-admin-empty">
                <i class="bi bi-tags"></i>
                <p class="mb-1 fw-semibold">No tags yet</p>
                <p class="text-sm mb-3">Tags help readers discover related articles.</p>
                <a href="{{ route('admin.tags.create') }}" class="btn-bh-primary py-2 px-3 text-sm">Create a Tag</a>
            </div>
        @else
            <div class="table-responsive">
                <table class="bh-admin-table">
                    <thead>
                        <tr>
                            <th scope="col">Name</th>
                            <th scope="col">Slug</th>
                            <th scope="col">Posts</th>
                            <th scope="col" class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tags as $tag)
                            <tr>
                                <td>
                                    <span class="badge rounded-pill bg-primary-subtle text-primary-emphasis">{{ $tag->name }}</span>
                                </td>
                                <td class="text-muted text-xs">/{{ $tag->slug }}</td>
                                <td class="text-muted text-xs">{{ $tag->posts_count }} {{ Str::plural('post', $tag->posts_count) }}</td>
                                <td>
                                    <div class="d-flex justify-content-end gap-1">
                                        <a href="{{ route('admin.tags.edit', $tag) }}" class="bh-action-btn" title="Edit tag" aria-label="Edit {{ $tag->name }}">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.tags.destroy', $tag) }}" method="POST" data-confirm="Delete the “{{ $tag->name }}” tag? It will be removed from all {{ $tag->posts_count }} post(s) that use it. This cannot be undone.">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bh-action-btn is-danger" title="Delete tag" aria-label="Delete {{ $tag->name }}">
                                                <i class="bi bi-trash3"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if ($tags->hasPages())
                <div class="bh-admin-pagination">
                    {{ $tags->links('pagination::bootstrap-5') }}
                </div>
            @endif
        @endif
    </div>
@endsection
