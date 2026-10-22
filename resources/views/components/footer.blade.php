@php
    $footerCategories = \App\Models\Category::withCount('publishedPosts')
        ->orderByDesc('published_posts_count')
        ->take(5)
        ->get();
@endphp

<footer class="bh-footer">
    <div class="container">
        <div class="row g-4 g-lg-5 mb-5">
            <!-- Brand Column -->
            <div class="col-lg-4 col-md-6">
                <a class="bh-brand text-white mb-3 d-inline-block" href="{{ route('home') }}">
                    <span class="bh-brand-badge">
                        <i class="bi bi-journal-code"></i>
                    </span>
                    <span>Blog<span style="color: var(--bh-coral-500);">Hub</span></span>
                </a>
                <p class="text-secondary mb-4 pe-lg-4 text-sm">
                    The premier technical publication for modern software engineers, distributed systems architects, and technology leaders. High-signal engineering discourse without the noise.
                </p>
                <div class="d-flex align-items-center gap-2">
                    <a href="https://twitter.com" target="_blank" rel="noopener" class="btn-icon" aria-label="Follow BlogHub on X (Twitter)">
                        <i class="bi bi-twitter-x"></i>
                    </a>
                    <a href="https://github.com" target="_blank" rel="noopener" class="btn-icon" aria-label="BlogHub on GitHub">
                        <i class="bi bi-github"></i>
                    </a>
                    <a href="https://linkedin.com" target="_blank" rel="noopener" class="btn-icon" aria-label="BlogHub on LinkedIn">
                        <i class="bi bi-linkedin"></i>
                    </a>
                    <a href="{{ route('blogs.index') }}" class="btn-icon" aria-label="RSS Feed">
                        <i class="bi bi-rss-fill"></i>
                    </a>
                </div>
            </div>

            <!-- Categories Column -->
            <div class="col-lg-2 col-md-6 col-6">
                <h2 class="h6">Topics</h2>
                <ul class="list-unstyled d-flex flex-column gap-2 text-sm">
                    @foreach ($footerCategories as $footerCategory)
                        <li>
                            <a href="{{ route('categories.show', $footerCategory->slug) }}">
                                {{ $footerCategory->name }}
                            </a>
                        </li>
                    @endforeach
                    <li><a href="{{ route('categories.index') }}" class="text-coral fw-semibold">View All &rarr;</a></li>
                </ul>
            </div>

            <!-- Platform Quick Links -->
            <div class="col-lg-2 col-md-6 col-6">
                <h2 class="h6">Platform</h2>
                <ul class="list-unstyled d-flex flex-column gap-2 text-sm">
                    <li><a href="{{ route('blogs.index') }}">Latest Articles</a></li>
                    <li><a href="{{ route('authors.index') }}">Verified Authors</a></li>
                    <li><a href="{{ route('about') }}">Editorial Standards</a></li>
                    <li><a href="{{ route('contact') }}">Contact & Support</a></li>
                    <li><a href="{{ route('privacy') }}">Privacy Policy</a></li>
                    <li><a href="{{ route('terms') }}">Terms of Service</a></li>
                </ul>
            </div>

            <!-- Newsletter Column -->
            <div class="col-lg-4 col-md-6">
                <h2 class="h6">Stay in the Loop</h2>
                <p class="text-secondary text-sm mb-3">
                    Join our weekly curated digest of high-impact technical articles, architecture teardowns, and production war stories.
                </p>
                <form action="{{ route('newsletter.subscribe') }}" method="POST" class="d-flex flex-column gap-2" data-newsletter-form>
                    @csrf
                    <div class="input-group">
                        <label for="bhFooterEmail" class="visually-hidden">Email address</label>
                        <input type="email" id="bhFooterEmail" name="email" class="bh-form-control" placeholder="Enter your work email..." required>
                        <button class="btn btn-bh-primary px-3" type="submit">
                            Subscribe
                        </button>
                    </div>
                    <span class="text-xs text-muted">Zero spam. Unsubscribe at any time with one click.</span>
                </form>
            </div>
        </div>

        <!-- Bottom Copyright -->
        <div class="pt-4 border-top border-secondary border-opacity-10 d-flex flex-column flex-md-row align-items-center justify-content-between gap-3 text-xs text-secondary">
            <div>
                &copy; <span data-current-year>{{ date('Y') }}</span> BlogHub Media Inc. All rights reserved. Crafted with Laravel & Bootstrap 5.
            </div>
            <div class="d-flex gap-4">
                <a href="{{ route('privacy') }}">Privacy</a>
                <a href="{{ route('terms') }}">Terms</a>
                <a href="{{ route('contact') }}">Support</a>
            </div>
        </div>
    </div>
</footer>
