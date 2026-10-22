@extends('layouts.app')

@section('title', 'About BlogHub — Architectural Rigor, Zero Clickbait')
@section('meta_description', 'Learn about BlogHub mission, our editorial standards, and our team of industry practitioners.')

@section('content')
<section class="py-5 border-bottom border-secondary border-opacity-10">
    <div class="container text-center" style="max-width: 760px;">
        <x-breadcrumbs :items="['About Us' => '']" />
        <span class="text-coral fw-bold text-xs text-uppercase font-heading">Our Mission</span>
        <h1 class="display-5 font-heading fw-bold mb-3">Architectural Rigor. Deep Insight. Zero Clickbait.</h1>
        <p class="lead text-secondary text-base mb-0">
            BlogHub was founded with a singular purpose: to provide software architects, principal engineers, and technical leaders with high-signal, production-grade technical publications.
        </p>
    </div>
</section>

<section class="py-5">
    <div class="container" style="max-width: 960px;">
        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="bh-card p-4 h-100">
                    <div class="bh-cat-icon mb-3">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <h2 class="h5 font-heading fw-bold mb-2">Verified Practitioners</h2>
                    <p class="text-secondary text-sm mb-0">
                        Every article is authored by engineers with battle-tested experience in production environments.
                    </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="bh-card p-4 h-100">
                    <div class="bh-cat-icon mb-3">
                        <i class="bi bi-cpu"></i>
                    </div>
                    <h2 class="h5 font-heading fw-bold mb-2">Reproducible Code</h2>
                    <p class="text-secondary text-sm mb-0">
                        We prioritize real architectural snippets, configuration files, and benchmarked metrics over hand-waving abstractions.
                    </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="bh-card p-4 h-100">
                    <div class="bh-cat-icon mb-3">
                        <i class="bi bi-journal-richtext"></i>
                    </div>
                    <h2 class="h5 font-heading fw-bold mb-2">Editorial Peer Review</h2>
                    <p class="text-secondary text-sm mb-0">
                        Submissions undergo rigorous peer review to guarantee factual accuracy, security posture, and clarity.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Platform Statistics -->
<section class="bh-stats-band position-relative overflow-hidden mb-5">
    <div class="container position-relative">
        <div class="row g-4 text-center">
            <div class="col-lg-3 col-sm-6">
                <div class="bh-stat-number" data-counter-target="{{ $stats['articles_count'] }}">0</div>
                <div class="bh-stat-label">Articles Published</div>
            </div>
            <div class="col-lg-3 col-sm-6">
                <div class="bh-stat-number" data-counter-target="{{ $stats['authors_count'] }}">0</div>
                <div class="bh-stat-label">Expert Authors</div>
            </div>
            <div class="col-lg-3 col-sm-6">
                <div class="bh-stat-number" data-counter-target="{{ $stats['total_views'] }}">0</div>
                <div class="bh-stat-label">Reader Views</div>
            </div>
            <div class="col-lg-3 col-sm-6">
                <div class="bh-stat-number" data-counter-target="{{ $stats['categories_count'] }}">0</div>
                <div class="bh-stat-label">Topics Covered</div>
            </div>
        </div>
    </div>
</section>

<section class="pb-5">
    <div class="container" style="max-width: 960px;">
        <div class="row g-4 mb-5">
            <div class="col-lg-6">
                <div class="bh-card h-100 p-4 p-md-5">
                    <div class="bh-cat-icon mb-3">
                        <i class="bi bi-flag"></i>
                    </div>
                    <h2 class="h4 font-heading fw-bold mb-3">Our Story</h2>
                    <p class="text-secondary text-sm mb-3">
                        BlogHub started as an internal knowledge base where a small group of platform engineers documented outages, migrations, and architectural trade-offs. The write-ups proved so useful to colleagues that we decided to open the vault.
                    </p>
                    <p class="text-secondary text-sm mb-0">
                        Today we serve a global audience of architects and engineering leaders who want depth over breadth — and we remain editorially independent, reader-funded through a members-first mindset.
                    </p>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="bh-card h-100 p-4 p-md-5">
                    <div class="bh-cat-icon mb-3">
                        <i class="bi bi-binoculars"></i>
                    </div>
                    <h2 class="h4 font-heading fw-bold mb-3">Our Vision</h2>
                    <p class="text-secondary text-sm mb-3">
                        We envision a web where technical reading compounds in value — where every published piece still teaches something useful a decade later. No trends-chasing, no churn, no hollow listicles.
                    </p>
                    <p class="text-secondary text-sm mb-0">
                        Long-term, BlogHub aims to be the reference shelf for production engineering: reproducible, reviewed, and ruthlessly practical.
                    </p>
                </div>
            </div>
        </div>

        <div class="bh-card p-5 mb-5">
            <h2 class="h4 font-heading fw-bold mb-3">Our Publishing Philosophy</h2>
            <p class="text-secondary text-base mb-3">
                In an era dominated by superficial AI summaries and churn-and-burn blog farms, BlogHub stands apart as a refuge for deliberate craft. We believe that building enduring software requires an appreciation for memory layout, network topology, protocol nuances, and team ergonomics.
            </p>
            <p class="text-secondary text-base mb-0">
                Whether diagnosing query locks in PostgreSQL, building state-based CRDTs for mobile offline persistence, or tuning Swoole worker pools in Laravel Octane, our writers share actionable lessons learned through real outages and successful migrations.
            </p>
        </div>

        <!-- Editorial Team -->
        <h2 class="h4 font-heading fw-bold mb-4 text-center">Editorial Leadership</h2>
        <div class="row g-4 mb-5">
            @foreach ($team as $member)
                <div class="col-md-6">
                    <x-author-card :author="$member" />
                </div>
            @endforeach
        </div>

        <!-- Achievements -->
        <h2 class="h4 font-heading fw-bold mb-4 text-center">Achievements</h2>
        <div class="row g-4 mb-5">
            <div class="col-md-6 col-lg-3">
                <div class="bh-card h-100 p-4 text-center">
                    <div class="bh-cat-icon mx-auto mb-3">
                        <i class="bi bi-trophy"></i>
                    </div>
                    <h3 class="h6 font-heading fw-bold mb-2">Peer-Reviewed Library</h3>
                    <p class="text-secondary text-xs mb-0">A growing archive of independently reviewed production case studies.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="bh-card h-100 p-4 text-center">
                    <div class="bh-cat-icon mx-auto mb-3">
                        <i class="bi bi-patch-check"></i>
                    </div>
                    <h3 class="h6 font-heading fw-bold mb-2">Verified Authorship</h3>
                    <p class="text-secondary text-xs mb-0">Every byline is a real practitioner, verified by the editorial board.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="bh-card h-100 p-4 text-center">
                    <div class="bh-cat-icon mx-auto mb-3">
                        <i class="bi bi-graph-up-arrow"></i>
                    </div>
                    <h3 class="h6 font-heading fw-bold mb-2">Measured Impact</h3>
                    <p class="text-secondary text-xs mb-0">Articles cite benchmarks and metrics — not marketing claims.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="bh-card h-100 p-4 text-center">
                    <div class="bh-cat-icon mx-auto mb-3">
                        <i class="bi bi-people"></i>
                    </div>
                    <h3 class="h6 font-heading fw-bold mb-2">Active Community</h3>
                    <p class="text-secondary text-xs mb-0">Thousands of engineers follow, discuss, and refine our publications.</p>
                </div>
            </div>
        </div>

        <!-- CTA -->
        <div class="bh-card p-5 text-center">
            <h2 class="h4 font-heading fw-bold mb-2">Ready to go deeper?</h2>
            <p class="text-secondary text-sm mb-4">
                Browse the full archive, or join the editorial team and share what your production experience taught you.
            </p>
            <div class="d-flex flex-wrap justify-content-center gap-3">
                <a href="{{ route('blogs.index') }}" class="btn btn-bh-primary btn-lg">
                    <i class="bi bi-journal-text me-2"></i>Explore Blogs
                </a>
                <a href="{{ route('register') }}" class="btn btn-bh-outline-coral btn-lg">
                    <i class="bi bi-pencil-square me-2"></i>Become an Author
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
