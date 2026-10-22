@php
    $post = $post ?? null;
    $isAdmin = Auth::user()->isAdmin();
    $selectedTags = array_map('intval', old('tags', $post ? $post->tags->pluck('id')->all() : []));
@endphp

<form action="{{ $action }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    <div class="row g-4">
        <!-- Main column -->
        <div class="col-lg-8">
            <div class="bh-admin-card mb-4">
                <div class="bh-admin-card-header">
                    <h2 class="bh-admin-card-title">Content</h2>
                </div>
                <div class="bh-admin-card-body">
                    <div class="mb-3">
                        <label for="postTitle" class="form-label text-xs fw-semibold text-muted">Title *</label>
                        <input type="text" id="postTitle" name="title" class="bh-form-control w-100 @error('title') is-invalid @enderror" value="{{ old('title', $post->title ?? '') }}" placeholder="A clear, descriptive headline" required data-slug-source>
                    </div>

                    <div class="mb-3">
                        <label for="postSlug" class="form-label text-xs fw-semibold text-muted">Slug</label>
                        <input type="text" id="postSlug" name="slug" class="bh-form-control w-100 @error('slug') is-invalid @enderror" value="{{ old('slug', $post->slug ?? '') }}" placeholder="auto-generated-from-title" data-slug-target>
                        <p class="text-xs text-muted mb-0 mt-1">Leave blank to generate automatically. Must be unique.</p>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <label for="postExcerpt" class="form-label text-xs fw-semibold text-muted mb-0">Excerpt</label>
                            <span class="text-xs text-muted" data-char-counter-output></span>
                        </div>
                        <textarea id="postExcerpt" name="excerpt" rows="3" class="bh-form-control w-100 text-sm @error('excerpt') is-invalid @enderror" placeholder="Short summary shown on cards and previews" data-char-counter="500">{{ old('excerpt', $post->excerpt ?? '') }}</textarea>
                    </div>

                    <div class="mb-1">
                        <label for="postContent" class="form-label text-xs fw-semibold text-muted">Content *</label>
                        <textarea id="postContent" name="content" rows="16" class="bh-form-control w-100 text-sm @error('content') is-invalid @enderror" placeholder="Write your article..." required style="font-family: var(--font-mono, monospace); min-height: 320px;">{{ old('content', $post->content ?? '') }}</textarea>
                        <p class="text-xs text-muted mb-0 mt-1">
                            <i class="bi bi-markdown me-1"></i>Markdown is supported (headings, lists, links, code blocks, quotes). Raw HTML is not rendered.
                        </p>
                    </div>
                </div>
            </div>

            <div class="bh-admin-card">
                <div class="bh-admin-card-header">
                    <h2 class="bh-admin-card-title">Featured Image</h2>
                </div>
                <div class="bh-admin-card-body">
                    <img src="{{ $post?->featured_image ?? '' }}" alt="Featured image preview" id="featuredImagePreview" class="bh-img-preview mb-3 {{ $post?->featured_image ? '' : 'd-none' }}">

                    <label for="postFeaturedImage" class="form-label text-xs fw-semibold text-muted">Upload image</label>
                    <input type="file" id="postFeaturedImage" name="featured_image" class="bh-form-control w-100 @error('featured_image') is-invalid @enderror" accept="image/jpeg,image/png,image/webp,image/gif" data-image-preview="#featuredImagePreview">
                    <p class="text-xs text-muted mb-0 mt-1">JPG, PNG, WebP or GIF — max 5MB. Recommended 1600×900px.</p>

                    @if ($post?->featured_image)
                        <div class="form-check mt-3">
                            <input class="form-check-input" type="checkbox" name="remove_featured_image" id="removeFeaturedImage" value="1" @checked(old('remove_featured_image'))>
                            <label class="form-check-label text-xs text-secondary" for="removeFeaturedImage">
                                Remove current featured image
                            </label>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar column -->
        <div class="col-lg-4">
            <div class="bh-admin-card mb-4">
                <div class="bh-admin-card-header">
                    <h2 class="bh-admin-card-title">Publish</h2>
                </div>
                <div class="bh-admin-card-body">
                    <div class="mb-3">
                        <label for="postStatus" class="form-label text-xs fw-semibold text-muted">Status *</label>
                        <select id="postStatus" name="status" class="bh-form-control w-100 @error('status') is-invalid @enderror" required>
                            <option value="draft" @selected(old('status', $post->status ?? 'draft') === 'draft')>Draft — visible only to you</option>
                            <option value="published" @selected(old('status', $post->status ?? 'draft') === 'published')>Published — live on the site</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="postPublishedAt" class="form-label text-xs fw-semibold text-muted">Publish date</label>
                        <input type="datetime-local" id="postPublishedAt" name="published_at" class="bh-form-control w-100 @error('published_at') is-invalid @enderror" value="{{ old('published_at', optional($post?->published_at)->format('Y-m-d\TH:i')) }}">
                        <p class="text-xs text-muted mb-0 mt-1">Leave blank to publish immediately when the status is Published.</p>
                    </div>

                    <div class="mb-0">
                        <label for="postReadingTime" class="form-label text-xs fw-semibold text-muted">Reading time (minutes)</label>
                        <input type="number" id="postReadingTime" name="reading_time" class="bh-form-control w-100 @error('reading_time') is-invalid @enderror" value="{{ old('reading_time', $post->reading_time ?? '') }}" min="1" max="120" placeholder="Auto-calculated">
                    </div>
                </div>
            </div>

            <div class="bh-admin-card mb-4">
                <div class="bh-admin-card-header">
                    <h2 class="bh-admin-card-title">Organization</h2>
                </div>
                <div class="bh-admin-card-body">
                    <div class="mb-3">
                        <label for="postCategory" class="form-label text-xs fw-semibold text-muted">Category *</label>
                        <select id="postCategory" name="category_id" class="bh-form-control w-100 @error('category_id') is-invalid @enderror" required>
                            <option value="">Choose a category...</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @selected((string) old('category_id', $post->category_id ?? '') === (string) $category->id)>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    @if ($isAdmin && $authors->isNotEmpty())
                        <div class="mb-3">
                            <label for="postAuthor" class="form-label text-xs fw-semibold text-muted">Author</label>
                            <select id="postAuthor" name="user_id" class="bh-form-control w-100 @error('user_id') is-invalid @enderror">
                                @foreach ($authors as $author)
                                    <option value="{{ $author->id }}" @selected((string) old('user_id', $post->user_id ?? Auth::id()) === (string) $author->id)>{{ $author->name }} ({{ ucfirst($author->role) }})</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div class="mb-0">
                        <span class="form-label text-xs fw-semibold text-muted d-block">Tags</span>
                        @forelse ($tags as $tag)
                            <label class="bh-tag-checkbox">
                                <input type="checkbox" name="tags[]" value="{{ $tag->id }}" @checked(in_array($tag->id, $selectedTags, true))>
                                {{ $tag->name }}
                            </label>
                        @empty
                            <p class="text-xs text-muted mb-2">No tags exist yet — add some below.</p>
                        @endforelse

                        <label for="postNewTags" class="visually-hidden">New tags</label>
                        <input type="text" id="postNewTags" name="new_tags" class="bh-form-control w-100 text-sm mt-2 @error('new_tags') is-invalid @enderror" value="{{ old('new_tags') }}" placeholder="New tags, comma-separated">
                    </div>
                </div>
            </div>

            <div class="bh-admin-card">
                <div class="bh-admin-card-body d-flex flex-column flex-sm-row gap-2">
                    <button type="submit" class="btn-bh-primary py-2 px-4 text-sm flex-grow-1">
                        <i class="bi bi-check-lg me-1"></i>{{ $submitLabel }}
                    </button>
                    <a href="{{ route('admin.posts.index') }}" class="btn-bh-secondary py-2 px-4 text-sm text-center">Cancel</a>
                </div>
            </div>
        </div>
    </div>
</form>
