@extends('layouts.admin')

@section('title', 'Write a Post')
@section('page-title', 'Write a Post')

@section('content')
    <a href="{{ route('admin.posts.index') }}" class="text-sm text-muted hover-underline d-inline-block mb-3">
        <i class="bi bi-arrow-left me-1"></i>Back to Posts
    </a>

    @include('admin.posts._form', [
        'action' => route('admin.posts.store'),
        'method' => 'POST',
        'submitLabel' => 'Create Post',
    ])
@endsection
