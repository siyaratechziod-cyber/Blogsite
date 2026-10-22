@extends('layouts.app')

@section('title', 'BlogHub — High-Signal Engineering & Architecture Publication')
@section('meta_description', 'Discover cutting-edge articles on software architecture, Laravel, distributed systems, AI, and cybersecurity written by veteran engineers.')

@section('content')
<!-- Hero Section -->
<section class="bh-hero border-bottom border-secondary border-opacity-10">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill bg-danger bg-opacity-10 text-danger text-xs fw-bold mb-3 font-heading text-uppercase">
                    <span class="spinner-grow spinner-grow-sm text-danger" style="width: 8px; height: 8px;"></span>
                    Featured Editorial
                </div>
                <h1 class="display-5 fw-bold font-heading mb-3" style="letter-spacing: -0.03em;">
                    Where Engineering Leaders &amp; Architects Deepen Their Craft.
                </h1>
                <p class="lead text-secondary text-base mb-4">
                    In-depth teardowns, architectural benchmarks, and real-world post-mortems from practitioners shipping software at immense scale.
                </p>

                <!-- Primary CTAs -->
                <div class="d-flex flex-wrap gap-2 mb-4">
                    <a href="{{ route('blogs.index') }}" class="btn btn-bh-primary btn-lg">
                        <i class="bi bi-journal-text me-2"></i>Explore Blogs
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-bh-outline-coral btn-lg">
                        <i class="bi bi-pencil-square me-2"></i>Become an Author
                    </a>
                </div>

                <!-- Search form with live suggestions -->
                <form action="{{ route('search') }}" method="GET" class="mb-3" style="max-width: 520px;" data-search-suggest="{{ route('search.suggest') }}">
                    <div class="input-group">
                        <span class="input-group-text border-end-0 text-muted">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" name="q" class="bh-form-control border-start-0" placeholder="Search topics, architecture patterns, keywords..." autocomplete="off" aria-label="Search articles" required>
                        <button class="btn btn-bh-primary px-4" type="submit">
                            Search
                        </button>
                    </div>
                </form>

                @if ($categories->isNotEmpty())
                    <div class="d-flex flex-wrap align-items-center gap-2 text-sm">
                        <span class="text-muted text-xs text-uppercase fw-semibold">Popular:</span>
                        @foreach ($categories->take(4) as $category)
                            <a href="{{ route('categories.show', $category->slug) }}" class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3 py-2 text-xs fw-medium text-decoration-none">
                                {{ $category->name }}
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Hero Featured Post Card -->
            <div class="col-lg-6">
                @if ($featuredPost)
                    <article class="bh-card bh-spotlight-card shadow-lg bh-reveal">
                        <div class="bh-card-img-wrapper">
                            <a href="{{ route('blogs.show', $featuredPost->slug) }}" aria-label="{{ $featuredPost->title }}">
                                <img src="{{ $featuredPost->featured_image ?? 'https://images.unsplash.com/photo-1555066931-4365d14bab8c?auto=format&fit=crop&w=1200&q=80' }}" alt="{{ $featuredPost->title }}">
                            </a>
                            @if ($featuredPost->category)
                                <a href="{{ route('categories.show', $featuredPost->category->slug) }}" class="bh-badge-category position-absolute top-0 start-0 m-3 shadow-sm">
                                    <i class="bi {{ $featuredPost->category->icon ?? 'bi-star' }}"></i>
                                    {{ $featuredPost->category->name }}
                                </a>
                            @endif
                        </div>
                        <div class="bh-card-body d-flex flex-column">
                            <div class="d-flex align-items-center flex-wrap gap-3 text-xs text-muted mb-2">
                                <span><i class="bi bi-clock me-1"></i>{{ $featuredPost->estimated_reading_time }} min read</span>
                                <span>&bull;</span>
                                <span>{{ $featuredPost->published_at ? $featuredPost->published_at->format('F d, Y') : '' }}</span>
                                <span>&bull;</span>
                                <span><i class="bi bi-eye me-1"></i>{{ number_format($featuredPost->views) }} views</span>
                            </div>
                            <h2 class="h4 font-heading fw-bold mb-2">
                                <a href="{{ route('blogs.show', $featuredPost->slug) }}" class="hover-coral">
                                    {{ $featuredPost->title }}
                                </a>
                            </h2>
                            <p class="text-secondary text-sm mb-3 line-clamp-2">
                                {{ $featuredPost->excerpt }}
                            </p>
                            <div class="d-flex align-items-center justify-content-between pt-2 mt-auto">
                                <a href="{{ route('authors.show', $featuredPost->author) }}" class="d-flex align-items-center gap-2">
                                    <img src="{{ $featuredPost->author->avatar_url }}" alt="{{ $featuredPost->author->name }}" class="bh-avatar bh-avatar-sm">
                                    <div>
                                        <div class="fw-semibold text-xs text-primary">{{ $featuredPost->author->name }}</div>
                                        <div class="text-muted" style="font-size: 0.725rem;">{{ $featuredPost->author->specialty }}</div>
                                    </div>
                                </a>
                                <a href="{{ route('blogs.show', $featuredPost->slug) }}" class="btn btn-bh-outline-coral btn-sm">
                                    Read Article &rarr;
                                </a>
                            </div>
                        </div>
                    </article>
                @endif
            </div>
        </div>
    </div>
</section>

<!-- Featured Articles (asymmetric grid) -->
@if ($featuredGrid->isNotEmpty())
<section class="py-5">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <span class="text-coral fw-bold text-xs text-uppercase font-heading">Editor's Spotlight</span>
                <h2 class="h3 font-heading fw-bold mb-0">Featured Articles</h2>
            </div>
            <a href="{{ route('blogs.index', ['sort' => 'popular']) }}" class="text-sm fw-semibold text-primary">
                View All &rarr;
            </a>
        </div>

        <div class="row g-4">
            @php $spotlight = $featuredGrid->first(); @endphp
            <div class="col-lg-7">
                <article class="bh-card bh-spotlight-card h-100 d-flex flex-column bh-reveal">
                    <div class="bh-card-img-wrapper">
                        <a href="{{ route('blogs.show', $spotlight->slug) }}" aria-label="{{ $spotlight->title }}">
                            <img src="{{ $spotlight->featured_image ?? 'https://images.unsplash.com/photo-1555066931-4365d14bab8c?auto=format&fit=crop&w=1200&q=80' }}" alt="{{ $spotlight->title }}">
                        </a>
                        @if ($spotlight->category)
                            <a href="{{ route('categories.show', $spotlight->category->slug) }}" class="bh-badge-category position-absolute top-0 start-0 m-3 shadow-sm">
                                <i class="bi {{ $spotlight->category->icon ?? 'bi-tag' }}"></i>
                                {{ $spotlight->category->name }}
                            </a>
                        @endif
                    </div>
                    <div class="bh-card-body d-flex flex-column flex-grow-1">
                        <div class="d-flex align-items-center flex-wrap gap-3 text-xs text-muted mb-2">
                            <span><i class="bi bi-clock me-1"></i>{{ $spotlight->estimated_reading_time }} min read</span>
                            <span>&bull;</span>
                            <span>{{ $spotlight->published_at ? $spotlight->published_at->format('M d, Y') : '' }}</span>
                            <span>&bull;</span>
                            <span><i class="bi bi-chat me-1"></i>{{ number_format($spotlight->comments_count) }} comments</span>
                        </div>
                        <h3 class="h2 font-heading fw-bold mb-3">
                            <a href="{{ route('blogs.show', $spotlight->slug) }}" class="hover-coral">
                                {{ $spotlight->title }}
                            </a>
                        </h3>
                        <p class="text-secondary text-sm mb-4 line-clamp-2">
                            {{ $spotlight->excerpt }}
                        </p>
                        <div class="d-flex align-items-center justify-content-between pt-3 border-top border-secondary border-opacity-10 mt-auto">
                            <a href="{{ route('authors.show', $spotlight->author) }}" class="d-flex align-items-center gap-2">
                                <img src="{{ $spotlight->author->avatar_url }}" alt="{{ $spotlight->author->name }}" class="bh-avatar bh-avatar-sm">
                                <div>
                                    <div class="fw-semibold text-xs text-primary">{{ $spotlight->author->name }}</div>
                                    <div class="text-muted" style="font-size: 0.725rem;">{{ $spotlight->author->specialty }}</div>
                                </div>
                            </a>
                            <a href="{{ route('blogs.show', $spotlight->slug) }}" class="btn btn-bh-secondary btn-sm">
                                Read More &rarr;
                            </a>
                        </div>
                    </div>
                </article>
            </div>

            <div class="col-lg-5">
                <div class="d-flex flex-column gap-4 h-100">
                    @foreach ($featuredGrid->skip(1) as $mini)
                        <a href="{{ route('blogs.show', $mini->slug) }}" class="bh-card bh-mini-card flex-grow-1 text-decoration-none bh-reveal">
                            <div class="bh-mini-img">
                                <img src="{{ $mini->featured_image ?? 'https://images.unsplash.com/photo-1555066931-4365d14bab8c?auto=format&fit=crop&w=800&q=80' }}" alt="{{ $mini->title }}" loading="lazy">
                            </div>
                            <div class="bh-mini-body">
                                @if ($mini->category)
                                    <div class="text-xs text-coral fw-bold text-uppercase mb-1">{{ $mini->category->name }}</div>
                                @endif
                                <h3 class="h6 font-heading fw-bold line-clamp-2 mb-2">
                                    <span class="hover-coral">{{ $mini->title }}</span>
                                </h3>
                                <div class="d-flex align-items-center gap-3 text-xs text-muted">
                                    <span><i class="bi bi-clock me-1"></i>{{ $mini->estimated_reading_time }} min</span>
                                    <span><i class="bi bi-eye me-1"></i>{{ number_format($mini->views) }}</span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
@endif

<!-- Trending Articles Section -->
@if ($trendingPosts->count() > 0)
<section class="py-5 bg-secondary bg-opacity-5">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <span class="text-coral fw-bold text-xs text-uppercase letter-spacing-1 font-heading">Must-Read Insights</span>
                <h2 class="h3 font-heading fw-bold mb-0">Trending This Week</h2>
            </div>
            <a href="{{ route('blogs.index', ['sort' => 'popular']) }}" class="text-sm fw-semibold text-primary">
                View All &rarr;
            </a>
        </div>

        <div class="row g-4">
            @foreach ($trendingPosts as $post)
                <div class="col-lg-3 col-md-6">
                    <div class="position-relative h-100">
                        <span class="bh-rank-badge" style="left: auto; right: 1rem;" aria-label="Rank {{ $loop->iteration }}">#{{ $loop->iteration }}</span>
                        <x-blog-card :post="$post" />
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Explore Categories Section -->
@if ($categories->isNotEmpty())
<section class="py-5">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <span class="text-coral fw-bold text-xs text-uppercase font-heading">Curated Domains</span>
                <h2 class="h3 font-heading fw-bold mb-0">Explore by Category</h2>
            </div>
            <a href="{{ route('categories.index') }}" class="text-sm fw-semibold text-primary">
                Browse All ({{ $stats['categories_count'] }}) &rarr;
            </a>
        </div>

        <div class="row g-3 g-lg-4">
            @foreach ($categories as $category)
                <div class="col-xl-2 col-lg-3 col-md-4 col-6">
                    <x-category-card :category="$category" />
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Latest Articles Section -->
@if ($latestPosts->isNotEmpty())
<section class="py-5 bg-secondary bg-opacity-5">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <span class="text-coral fw-bold text-xs text-uppercase font-heading">Fresh From The Press</span>
                <h2 class="h3 font-heading fw-bold mb-0">Latest Publications</h2>
            </div>
            <a href="{{ route('blogs.index') }}" class="btn btn-bh-secondary btn-sm">
                Explore Full Archive &rarr;
            </a>
        </div>

        <div class="row g-4">
            @foreach ($latestPosts as $post)
                <div class="col-lg-4 col-md-6">
                    <x-blog-card :post="$post" />
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Statistics Band -->
<section class="bh-stats-band py-5">
    <div class="container position-relative">
        <div class="text-center mb-5">
            <span class="text-coral fw-bold text-xs text-uppercase font-heading">By The Numbers</span>
            <h2 class="h3 font-heading fw-bold mb-2 text-white">A Growing Knowledge Base</h2>
            <p class="text-sm mx-auto mb-0" style="max-width: 540px; color: #94a3b8;">
                Every figure below is powered by real publishing activity on BlogHub — no vanity metrics.
            </p>
        </div>
        <div class="row g-4 text-center">
            <div class="col-6 col-lg-3">
                <div class="bh-stat-icon mb-3">
                    <i class="bi bi-journal-text"></i>
                </div>
                <div class="bh-stat-number" data-counter-target="{{ $stats['articles_count'] }}">0</div>
                <div class="bh-stat-label mt-1">Articles Published</div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="bh-stat-icon mb-3">
                    <i class="bi bi-people"></i>
                </div>
                <div class="bh-stat-number" data-counter-target="{{ $stats['authors_count'] }}">0</div>
                <div class="bh-stat-label mt-1">Expert Authors</div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="bh-stat-icon mb-3">
                    <i class="bi bi-eye"></i>
                </div>
                <div class="bh-stat-number" data-counter-target="{{ $stats['total_views'] }}">0</div>
                <div class="bh-stat-label mt-1">Reader Views</div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="bh-stat-icon mb-3">
                    <i class="bi bi-collection"></i>
                </div>
                <div class="bh-stat-number" data-counter-target="{{ $stats['categories_count'] }}">0</div>
                <div class="bh-stat-label mt-1">Topics Covered</div>
            </div>
        </div>
    </div>
</section>

<!-- Top Authors Section -->
@if ($featuredAuthors->isNotEmpty())
<section class="py-5">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <span class="text-coral fw-bold text-xs text-uppercase font-heading">Industry Practitioners</span>
                <h2 class="h3 font-heading fw-bold mb-0">Meet the Authors</h2>
            </div>
            <a href="{{ route('authors.index') }}" class="text-sm fw-semibold text-primary">
                All Authors &rarr;
            </a>
        </div>

        <div class="row g-4">
            @foreach ($featuredAuthors as $author)
                <div class="col-lg-4 col-md-6">
                    <x-author-card :author="$author" />
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Testimonials Carousel -->
@if ($testimonials->isNotEmpty())
<section class="py-5 bg-secondary bg-opacity-5">
    <div class="container">
        <div class="text-center mb-5">
            <span class="text-coral fw-bold text-xs text-uppercase font-heading">Trusted Worldwide</span>
            <h2 class="h3 font-heading fw-bold mb-2">What Engineering Leaders Say</h2>
            <p class="text-secondary text-sm mx-auto" style="max-width: 520px;">
                Engineers at high-growth startups and Fortune 500 enterprises rely on BlogHub for battle-tested insights.
            </p>
        </div>

        <div id="bhTestimonialCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="6500">
            <div class="carousel-inner">
                @foreach ($testimonials as $slide)
                    <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                        <div class="row g-4">
                            @foreach ($slide as $testimonial)
                                <div class="col-lg-4 {{ $loop->index === 1 ? 'd-none d-md-block' : '' }}{{ $loop->index === 2 ? 'd-none d-lg-block' : '' }}">
                                    <div class="bh-card bh-testimonial-card d-flex flex-column bh-reveal">
                                        <div class="d-flex align-items-start justify-content-between mb-3">
                                            <div class="bh-testimonial-stars">
                                                @for ($i = 0; $i < $testimonial->rating; $i++)
                                                    <i class="bi bi-star-fill"></i>
                                                @endfor
                                            </div>
                                            <i class="bi bi-quote bh-testimonial-quote-icon"></i>
                                        </div>
                                        <p class="text-secondary text-sm fst-italic mb-4 flex-grow-1">
                                            "{{ $testimonial->quote }}"
                                        </p>
                                        <div class="d-flex align-items-center gap-3 pt-3 border-top border-secondary border-opacity-10">
                                            @if ($testimonial->avatar)
                                                <img src="{{ $testimonial->avatar }}" alt="{{ $testimonial->name }}" class="bh-avatar bh-avatar-sm">
                                            @endif
                                            <div>
                                                <div class="fw-bold font-heading text-sm text-primary">{{ $testimonial->name }}</div>
                                                <div class="text-xs text-muted">{{ $testimonial->role_or_title }}{{ $testimonial->company ? ' • ' . $testimonial->company : '' }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="carousel-indicators">
                @foreach ($testimonials as $slide)
                    <button type="button" data-bs-target="#bhTestimonialCarousel" data-bs-slide-to="{{ $loop->index }}" class="{{ $loop->first ? 'active' : '' }}" aria-label="Testimonial slide {{ $loop->iteration }}"></button>
                @endforeach
            </div>

            <div class="d-flex justify-content-center gap-3 mt-2">
                <button type="button" class="bh-carousel-btn" data-bs-target="#bhTestimonialCarousel" data-bs-slide="prev" aria-label="Previous testimonials">
                    <i class="bi bi-arrow-left"></i>
                </button>
                <button type="button" class="bh-carousel-btn" data-bs-target="#bhTestimonialCarousel" data-bs-slide="next" aria-label="Next testimonials">
                    <i class="bi bi-arrow-right"></i>
                </button>
            </div>
        </div>
    </div>
</section>
@endif

<!-- Newsletter CTA Banner -->
<section class="py-5 my-4">
    <div class="container">
        <div class="bh-card p-5 text-center text-white" style="background: linear-gradient(135deg, var(--bh-navy-950), var(--bh-navy-800)); border-radius: var(--bh-radius-xl);">
            <div class="mx-auto" style="max-width: 620px;">
                <span class="bh-badge-category mb-3 text-white" style="background: rgba(255, 255, 255, 0.15);">
                    <i class="bi bi-envelope-paper"></i> Weekly Curated Digest
                </span>
                <h2 class="display-6 font-heading fw-bold mb-3 text-white">
                    Upgrade Your Architectural Thinking
                </h2>
                <p class="mb-4 text-sm" style="color: #cbd5e1;">
                    Every Thursday, we send a deep dive on distributed architecture, performance bottlenecks, and systems engineering. No spam. Ever.
                </p>
                <form action="{{ route('newsletter.subscribe') }}" method="POST" data-newsletter-form class="d-flex flex-column flex-sm-row gap-2 justify-content-center">
                    @csrf
                    <input type="email" name="email" class="bh-form-control" placeholder="Enter your work email address..." aria-label="Email address" style="max-width: 360px;" required>
                    <button class="btn btn-bh-primary px-4 py-2" type="submit">
                        <i class="bi bi-send me-2"></i>Subscribe Free
                    </button>
                </form>
                <p class="text-xs mt-3 mb-0" style="color: #94a3b8;">
                    Join a community of {{ number_format($stats['total_views']) }}+ reader sessions and counting. Unsubscribe anytime.
                </p>
            </div>
        </div>
    </div>
</section>
@endsection
