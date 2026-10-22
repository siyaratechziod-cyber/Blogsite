@extends('layouts.admin')

@section('title', 'Edit Post')
@section('page-title', 'Edit Post')

@section('content')
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
        <a href="{{ route('admin.posts.index') }}" class="text-sm text-muted hover-underline">
            <i class="bi bi-arrow-left me-1"></i>Back to Posts
        </a>
        <span class="text-xs text-muted">
            Editing: <strong>{{ $post->title }}</strong>
            @if ($post->status === 'published' && $post->published_at?->isPast())
                — <a href="{{ route('blogs.show', $post->slug) }}" target="_blank" rel="noopener" class="text-coral hover-underline">view public page <i class="bi bi-box-arrow-up-right"></i></a>
            @endif
        </span>
    </div>

    @include('admin.posts._form', [
        'action' => route('admin.posts.update', $post),
        'method' => 'PUT',
        'submitLabel' => 'Save Changes',
    ])
@endsection
