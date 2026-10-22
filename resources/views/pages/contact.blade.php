@extends('layouts.app')

@section('title', 'Contact & Editorial Inquiries — BlogHub')
@section('meta_description', 'Get in touch with the BlogHub editorial board for article submissions, corporate inquiries, or technical feedback.')

@section('content')
<section class="py-5 border-bottom border-secondary border-opacity-10">
    <div class="container text-center" style="max-width: 680px;">
        <x-breadcrumbs :items="['Contact' => '']" />
        <span class="text-coral fw-bold text-xs text-uppercase font-heading">Get in Touch</span>
        <h1 class="display-6 font-heading fw-bold mb-2">Connect with Our Team</h1>
        <p class="text-secondary text-sm mb-0">
            Have a submission proposal, technical correction, or enterprise inquiry? We respond within 24 business hours.
        </p>
    </div>
</section>

<section class="py-5">
    <div class="container" style="max-width: 960px;">
        <div class="row g-5">
            <!-- Contact Details -->
            <div class="col-lg-5">
                <div class="bh-card p-4 h-100">
                    <h2 class="h5 font-heading fw-bold mb-4">Direct Channels</h2>

                    <div class="d-flex align-items-start gap-3 mb-4">
                        <div class="bh-cat-icon" style="width: 44px; height: 44px; font-size: 1.25rem;">
                            <i class="bi bi-envelope"></i>
                        </div>
                        <div>
                            <div class="fw-bold font-heading text-sm text-primary">Editorial Submissions</div>
                            <a href="mailto:editorial@bloghub.test" class="text-sm text-secondary text-decoration-none d-block">editorial@bloghub.test</a>
                            <a href="mailto:hello@bloghub.test" class="text-sm text-secondary text-decoration-none d-block">hello@bloghub.test</a>
                        </div>
                    </div>

                    <div class="d-flex align-items-start gap-3 mb-4">
                        <div class="bh-cat-icon" style="width: 44px; height: 44px; font-size: 1.25rem;">
                            <i class="bi bi-shield-lock"></i>
                        </div>
                        <div>
                            <div class="fw-bold font-heading text-sm text-primary">Security Disclosures</div>
                            <a href="mailto:security@bloghub.test" class="text-sm text-secondary text-decoration-none d-block">security@bloghub.test</a>
                        </div>
                    </div>

                    <div class="d-flex align-items-start gap-3 mb-4">
                        <div class="bh-cat-icon" style="width: 44px; height: 44px; font-size: 1.25rem;">
                            <i class="bi bi-telephone"></i>
                        </div>
                        <div>
                            <div class="fw-bold font-heading text-sm text-primary">Phone</div>
                            <a href="tel:+14155550134" class="text-sm text-secondary text-decoration-none d-block">+1 (415) 555-0134</a>
                            <span class="text-xs text-muted">Mon – Fri, 09:00 – 18:00 PST</span>
                        </div>
                    </div>

                    <div class="d-flex align-items-start gap-3 mb-4">
                        <div class="bh-cat-icon" style="width: 44px; height: 44px; font-size: 1.25rem;">
                            <i class="bi bi-buildings"></i>
                        </div>
                        <div>
                            <div class="fw-bold font-heading text-sm text-primary">Headquarters</div>
                            <div class="text-sm text-secondary">548 Market St, Suite 34000<br>San Francisco, CA 94104</div>
                        </div>
                    </div>

                    <hr class="border-secondary border-opacity-10 my-4">

                    <h3 class="h6 font-heading fw-bold mb-2">Office Hours</h3>
                    <p class="text-secondary text-xs mb-0">
                        Monday – Friday: 09:00 – 18:00 PST<br>
                        Weekend urgent response for verified security vulnerabilities.
                    </p>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="col-lg-7">
                <div class="bh-card p-4 p-md-5">
                    <h2 class="h4 font-heading fw-bold mb-4">Send Us a Message</h2>

                    <form action="{{ route('contact.submit') }}" method="POST" data-validate>
                        @csrf
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="contactName" class="form-label text-xs fw-semibold text-muted">Full Name *</label>
                                <input type="text" id="contactName" name="name" class="bh-form-control w-100" value="{{ old('name') }}" placeholder="e.g. Rachel Green" maxlength="100" required>
                            </div>
                            <div class="col-md-6">
                                <label for="contactEmail" class="form-label text-xs fw-semibold text-muted">Work Email *</label>
                                <input type="email" id="contactEmail" name="email" class="bh-form-control w-100" value="{{ old('email') }}" placeholder="e.g. rachel@company.com" maxlength="150" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="contactSubject" class="form-label text-xs fw-semibold text-muted">Subject *</label>
                            <input type="text" id="contactSubject" name="subject" class="bh-form-control w-100" value="{{ old('subject') }}" placeholder="e.g. Article Submission: Event-Driven Sagas" maxlength="200" required>
                        </div>

                        <div class="mb-4">
                            <label for="contactMessage" class="form-label text-xs fw-semibold text-muted">Message *</label>
                            <textarea id="contactMessage" name="message" rows="5" class="bh-form-control w-100" placeholder="Provide detail regarding your inquiry or proposed topic outline..." required minlength="10" maxlength="5000" data-char-counter="5000">{{ old('message') }}</textarea>
                            <div class="text-end text-xs text-muted mt-1">
                                <span data-char-counter-output>0</span> / 5000 characters
                            </div>
                        </div>

                        <button type="submit" class="btn btn-bh-primary w-100 py-2">
                            <i class="bi bi-send me-2"></i> Submit Inquiry
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Map Placeholder -->
        <div class="mt-5">
            <h2 class="h5 font-heading fw-bold mb-3">Visit Our Office</h2>
            <div class="bh-map-placeholder">
                <i class="bi bi-geo-alt display-5"></i>
                <div class="fw-bold font-heading">BlogHub Headquarters</div>
                <div class="text-sm">548 Market St, Suite 34000 — San Francisco, CA 94104</div>
                <div class="text-xs">Interactive map integration placeholder</div>
            </div>
        </div>

        <!-- FAQ -->
        <div class="mt-5" style="max-width: 820px;">
            <div class="text-center mb-4">
                <span class="text-coral fw-bold text-xs text-uppercase font-heading">Common Questions</span>
                <h2 class="h4 font-heading fw-bold mb-0">Frequently Asked Questions</h2>
            </div>

            <div class="accordion bh-faq" id="bhContactFaq">
                <div class="accordion-item">
                    <h3 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#bhFaqOne" aria-expanded="true" aria-controls="bhFaqOne">
                            How do I submit an article proposal?
                        </button>
                    </h3>
                    <div id="bhFaqOne" class="accordion-collapse collapse show" data-bs-parent="#bhContactFaq">
                        <div class="accordion-body">
                            Send your topic outline and a short author bio to editorial@bloghub.test, or use the form above with the subject line "Article Submission". We review proposals within five business days and will respond with editorial guidelines and a proposed publishing window.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h3 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#bhFaqTwo" aria-expanded="false" aria-controls="bhFaqTwo">
                            Is there a minimum experience requirement for authors?
                        </button>
                    </h3>
                    <div id="bhFaqTwo" class="accordion-collapse collapse" data-bs-parent="#bhContactFaq">
                        <div class="accordion-body">
                            We accept writers who can demonstrate hands-on production experience with the technology they cover. There is no formal certification requirement, but every submission is peer-reviewed by at least two practicing engineers before publication.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h3 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#bhFaqThree" aria-expanded="false" aria-controls="bhFaqThree">
                            Do you accept sponsored or guest posts?
                        </button>
                    </h3>
                    <div id="bhFaqThree" class="accordion-collapse collapse" data-bs-parent="#bhContactFaq">
                        <div class="accordion-body">
                            We do not publish sponsored content, paid backlinks, or undisclosed commercial posts. Corporate inquiries about partnerships or recruiting are welcome, but they are clearly separated from editorial content and never influence coverage.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h3 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#bhFaqFour" aria-expanded="false" aria-controls="bhFaqFour">
                            I found a technical error in a published article. What should I do?
                        </button>
                    </h3>
                    <div id="bhFaqFour" class="accordion-collapse collapse" data-bs-parent="#bhContactFaq">
                        <div class="accordion-body">
                            Please use the form above with the subject "Technical Correction" and include the article URL plus a reference supporting the correction. Verified corrections are applied quickly and the article is annotated with a revision note.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h3 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#bhFaqFive" aria-expanded="false" aria-controls="bhFaqFive">
                            How do I report a security vulnerability?
                        </button>
                    </h3>
                    <div id="bhFaqFive" class="accordion-collapse collapse" data-bs-parent="#bhContactFaq">
                        <div class="accordion-body">
                            Email security@bloghub.test directly. Include reproduction steps and affected URLs. Verified vulnerabilities receive urgent response even on weekends, and we credit responsible reporters in our release notes unless anonymity is requested.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
