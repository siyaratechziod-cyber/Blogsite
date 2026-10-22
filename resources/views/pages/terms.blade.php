@extends('layouts.app')

@section('title', 'Terms of Service — BlogHub')
@section('meta_description', 'Review the BlogHub terms of service covering accounts, author submissions, acceptable use, community conduct, and limitation of liability.')

@section('content')
<section class="py-5 border-bottom border-secondary border-opacity-10">
    <div class="container">
        <x-breadcrumbs :items="['Terms of Service' => '']" />
        <h1 class="display-6 font-heading fw-bold mb-2">Terms of Service</h1>
        <div class="text-xs text-muted">
            <i class="bi bi-clock-history me-1"></i>Last updated: October 2, 2026 &bull; Effective immediately
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="row g-5">
            <!-- Sticky Table of Contents -->
            <aside class="col-lg-3 d-none d-lg-block">
                <nav class="bh-toc" aria-label="Table of contents">
                    <div class="toc-title">On This Page</div>
                    <div class="nav flex-column">
                        <a class="nav-link" href="#terms-acceptance">1. Acceptance of Terms</a>
                        <a class="nav-link" href="#terms-accounts">2. Accounts & Eligibility</a>
                        <a class="nav-link" href="#terms-ip">3. Intellectual Property</a>
                        <a class="nav-link" href="#terms-author">4. Author Submissions</a>
                        <a class="nav-link" href="#terms-use">5. Acceptable Use</a>
                        <a class="nav-link" href="#terms-conduct">6. Community Conduct</a>
                        <a class="nav-link" href="#terms-thirdparty">7. Third-Party Links</a>
                        <a class="nav-link" href="#terms-disclaimer">8. Disclaimers</a>
                        <a class="nav-link" href="#terms-liability">9. Limitation of Liability</a>
                        <a class="nav-link" href="#terms-termination">10. Termination</a>
                        <a class="nav-link" href="#terms-law">11. Governing Law</a>
                        <a class="nav-link" href="#terms-contact">12. Contact</a>
                    </div>
                </nav>
            </aside>

            <!-- Terms Content -->
            <div class="col-lg-9">
                <article class="bh-card p-4 p-md-5">
                    <p class="text-secondary mb-4">
                        These Terms of Service ("Terms") govern your access to and use of BlogHub. By creating an account, subscribing to the newsletter, or otherwise using the platform, you agree to be bound by these Terms.
                    </p>

                    <section class="bh-legal-section mb-4" id="terms-acceptance">
                        <h2 class="font-heading fw-bold">1. Acceptance of Terms</h2>
                        <p>By accessing BlogHub, you confirm that you have read, understood, and agree to these Terms and our <a href="{{ route('privacy') }}" class="text-coral">Privacy Policy</a>. If you do not agree, you must discontinue use of the platform. We may revise these Terms at any time; material revisions are announced on this page with an updated date.</p>
                    </section>

                    <section class="bh-legal-section mb-4" id="terms-accounts">
                        <h2 class="font-heading fw-bold">2. Accounts & Eligibility</h2>
                        <ul>
                            <li>You must be at least 16 years old to create an account.</li>
                            <li>You are responsible for maintaining the confidentiality of your credentials and for all activity under your account.</li>
                            <li>Registration information must be accurate; impersonation of another person or organization is prohibited.</li>
                            <li>One person may not operate multiple accounts for the purpose of manipulating engagement metrics.</li>
                        </ul>
                    </section>

                    <section class="bh-legal-section mb-4" id="terms-ip">
                        <h2 class="font-heading fw-bold">3. Intellectual Property</h2>
                        <p>The BlogHub name, design system, and editorial curation are protected by copyright and trademark law. Published articles remain the intellectual property of their respective authors. You may quote and share excerpts with attribution and a canonical link back to the original article; wholesale republication without written permission is prohibited.</p>
                    </section>

                    <section class="bh-legal-section mb-4" id="terms-author">
                        <h2 class="font-heading fw-bold">4. Author Submissions</h2>
                        <ul>
                            <li>By submitting a draft, you warrant that the work is original and that you hold the rights to publish it.</li>
                            <li>Authors grant BlogHub a non-exclusive, worldwide license to publish, archive, and promote the submitted work.</li>
                            <li>All submissions undergo peer review; the editorial board reserves the right to request revisions, defer, or decline publication.</li>
                            <li>Plagiarism, fabricated benchmarks, or undisclosed commercial content result in immediate removal and account suspension.</li>
                        </ul>
                    </section>

                    <section class="bh-legal-section mb-4" id="terms-use">
                        <h2 class="font-heading fw-bold">5. Acceptable Use</h2>
                        <p>You agree not to: scrape the platform at disruptive rates; attempt to breach authentication or access controls; upload malware; use automated means to inflate views, likes, or follows; or interfere with the operation and integrity of the service.</p>
                    </section>

                    <section class="bh-legal-section mb-4" id="terms-conduct">
                        <h2 class="font-heading fw-bold">6. Community Conduct</h2>
                        <p>Technical disagreement is welcome; harassment is not. Comments must remain on-topic and civil. We remove content that is abusive, hateful, spam, or legally impermissible, and we may suspend accounts that repeatedly violate this standard. Moderation decisions may be appealed via <a href="{{ route('contact') }}" class="text-coral">our contact page</a>.</p>
                    </section>

                    <section class="bh-legal-section mb-4" id="terms-thirdparty">
                        <h2 class="font-heading fw-bold">7. Third-Party Links</h2>
                        <p>Articles may reference external tools, repositories, and documentation. These links are provided for convenience; we do not control and are not responsible for the content, policies, or practices of third-party sites.</p>
                    </section>

                    <section class="bh-legal-section mb-4" id="terms-disclaimer">
                        <h2 class="font-heading fw-bold">8. Disclaimers</h2>
                        <p>Technical content is provided "as is" for educational purposes. Engineering decisions involve context that no article can fully capture; applying published techniques in production remains solely your responsibility. We disclaim all warranties, express or implied, including fitness for a particular purpose.</p>
                    </section>

                    <section class="bh-legal-section mb-4" id="terms-liability">
                        <h2 class="font-heading fw-bold">9. Limitation of Liability</h2>
                        <p>To the maximum extent permitted by law, BlogHub and its contributors shall not be liable for indirect, incidental, special, or consequential damages — including data loss, service interruption, or lost profits — arising from your use of the platform or reliance on its content.</p>
                    </section>

                    <section class="bh-legal-section mb-4" id="terms-termination">
                        <h2 class="font-heading fw-bold">10. Termination</h2>
                        <p>We may suspend or terminate accounts that violate these Terms, with or without prior notice depending on severity. You may close your account at any time by contacting support; upon termination, sections concerning intellectual property, liability, and governing law survive.</p>
                    </section>

                    <section class="bh-legal-section mb-4" id="terms-law">
                        <h2 class="font-heading fw-bold">11. Governing Law</h2>
                        <p>These Terms are governed by the laws of the State of California, without regard to conflict-of-law principles. Any dispute shall be brought in the state or federal courts located in San Francisco County, California.</p>
                    </section>

                    <section class="bh-legal-section" id="terms-contact">
                        <h2 class="font-heading fw-bold">12. Contact</h2>
                        <p>Questions regarding these Terms? Contact our legal team at <a href="mailto:legal@bloghub.test" class="text-coral">legal@bloghub.test</a> or through the <a href="{{ route('contact') }}" class="text-coral">contact page</a>.</p>
                    </section>
                </article>
            </div>
        </div>
    </div>
</section>
@endsection
