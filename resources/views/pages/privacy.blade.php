@extends('layouts.app')

@section('title', 'Privacy Policy — BlogHub')
@section('meta_description', 'Read the BlogHub privacy policy to understand what data we collect, how it is used, and the rights you retain over your personal information.')

@section('content')
<section class="py-5 border-bottom border-secondary border-opacity-10">
    <div class="container">
        <x-breadcrumbs :items="['Privacy Policy' => '']" />
        <h1 class="display-6 font-heading fw-bold mb-2">Privacy Policy</h1>
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
                        <a class="nav-link" href="#privacy-collect">1. Information We Collect</a>
                        <a class="nav-link" href="#privacy-use">2. How We Use Information</a>
                        <a class="nav-link" href="#privacy-cookies">3. Cookies & Local Storage</a>
                        <a class="nav-link" href="#privacy-newsletter">4. Newsletter Communications</a>
                        <a class="nav-link" href="#privacy-sharing">5. Data Sharing</a>
                        <a class="nav-link" href="#privacy-retention">6. Data Retention</a>
                        <a class="nav-link" href="#privacy-security">7. Security Measures</a>
                        <a class="nav-link" href="#privacy-rights">8. Your Rights</a>
                        <a class="nav-link" href="#privacy-changes">9. Policy Changes</a>
                        <a class="nav-link" href="#privacy-contact">10. Contact Us</a>
                    </div>
                </nav>
            </aside>

            <!-- Policy Content -->
            <div class="col-lg-9">
                <article class="bh-card p-4 p-md-5">
                    <p class="text-secondary mb-4">
                        At BlogHub, we respect your privacy and are committed to protecting the personal information you share with us. This policy explains what we collect, why we collect it, and how you remain in control of your data.
                    </p>

                    <section class="bh-legal-section mb-4" id="privacy-collect">
                        <h2 class="font-heading fw-bold">1. Information We Collect</h2>
                        <p>We collect the following categories of information:</p>
                        <ul>
                            <li><strong>Account details</strong> — your name, username, and email address provided at registration.</li>
                            <li><strong>Engagement data</strong> — comments, article likes, bookmarks, and author follows that you explicitly trigger while browsing.</li>
                            <li><strong>Technical data</strong> — session identifiers and theme preferences stored locally in your browser to keep you signed in and preserve your light/dark mode choice.</li>
                        </ul>
                    </section>

                    <section class="bh-legal-section mb-4" id="privacy-use">
                        <h2 class="font-heading fw-bold">2. How We Use Information</h2>
                        <p>We use your information exclusively to deliver and improve our services:</p>
                        <ul>
                            <li>Authenticating sessions and protecting your account.</li>
                            <li>Rendering your personalized state — such as liked articles and followed authors.</li>
                            <li>Dispatching newsletter digests you explicitly opted into.</li>
                            <li>Detecting and preventing malicious automated abuse, spam, and content manipulation.</li>
                        </ul>
                        <p>We never use your data for behavioral advertising, and we do not build shadow profiles of readers.</p>
                    </section>

                    <section class="bh-legal-section mb-4" id="privacy-cookies">
                        <h2 class="font-heading fw-bold">3. Cookies & Local Storage</h2>
                        <p>Cookies and local storage are employed strictly for functional purposes: maintaining authenticated sessions, remembering your theme preference, and preserving list/grid view choices. We do not sell personal data to third-party ad brokers, and no advertising trackers are embedded on this site.</p>
                    </section>

                    <section class="bh-legal-section mb-4" id="privacy-newsletter">
                        <h2 class="font-heading fw-bold">4. Newsletter Communications</h2>
                        <p>If you subscribe to our weekly digest, your email address is stored solely for that purpose. Every dispatch includes a one-click unsubscribe link. We do not rent, sell, or share subscriber lists with commercial third parties under any circumstances.</p>
                    </section>

                    <section class="bh-legal-section mb-4" id="privacy-sharing">
                        <h2 class="font-heading fw-bold">5. Data Sharing</h2>
                        <p>We share personal data only when strictly necessary: with infrastructure providers that host and operate the platform under confidentiality obligations, or when required by valid legal process. Aggregate, anonymized statistics (such as total article views) may be published for editorial transparency.</p>
                    </section>

                    <section class="bh-legal-section mb-4" id="privacy-retention">
                        <h2 class="font-heading fw-bold">6. Data Retention</h2>
                        <p>Account data is retained for as long as your account remains active. If you delete your account, associated personal identifiers are removed or irreversibly anonymized within 30 days, except where retention is mandated by law.</p>
                    </section>

                    <section class="bh-legal-section mb-4" id="privacy-security">
                        <h2 class="font-heading fw-bold">7. Security Measures</h2>
                        <p>Passwords are stored using industry-standard one-way hashing. Sessions are protected with CSRF tokens and secure cookie attributes. While no system is impenetrable, we apply defense-in-depth practices and promptly disclose verified breaches to affected users.</p>
                    </section>

                    <section class="bh-legal-section mb-4" id="privacy-rights">
                        <h2 class="font-heading fw-bold">8. Your Rights</h2>
                        <p>You may request access to, correction of, export of, or deletion of your personal data at any time. You can also withdraw newsletter consent instantly via the unsubscribe link in any email. To exercise any right, contact our privacy compliance team at <a href="mailto:privacy@bloghub.test" class="text-coral">privacy@bloghub.test</a> — we respond within 30 days.</p>
                    </section>

                    <section class="bh-legal-section mb-4" id="privacy-changes">
                        <h2 class="font-heading fw-bold">9. Policy Changes</h2>
                        <p>We may update this policy as the platform evolves. Material changes are announced on this page with a revised "last updated" date, and continued use of the service constitutes acceptance of the revised policy.</p>
                    </section>

                    <section class="bh-legal-section" id="privacy-contact">
                        <h2 class="font-heading fw-bold">10. Contact Us</h2>
                        <p>Questions about this policy? Reach us at <a href="mailto:privacy@bloghub.test" class="text-coral">privacy@bloghub.test</a> or via our <a href="{{ route('contact') }}" class="text-coral">contact page</a>. We take privacy inquiries seriously and treat them as priority correspondence.</p>
                    </section>
                </article>
            </div>
        </div>
    </div>
</section>
@endsection
