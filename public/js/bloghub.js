/**
 * BlogHub — Global Vanilla JavaScript Foundation (ES6+)
 * No jQuery, pure standard web APIs.
 */

document.addEventListener('DOMContentLoaded', () => {
    initTheme();
    initStickyNavbar();
    initScrollProgress();
    initBackToTop();
    initScrollReveal();
    initAnimatedCounters();
    initPasswordToggles();
    initCopyButtons();
    initFormValidation();
    initInteractionButtons();
    initSearchSuggestions();
    initCharacterCounters();
    initViewToggle();
    initLightbox();
    initNativeShare();
    initNewsletterForms();
    initTocScrollSpy();
    initPasswordStrength();
    initFooterYear();
});

/* ==========================================================================
   1. Theme Management (Light / Dark Mode with localStorage)
   ========================================================================== */
function initTheme() {
    const themeToggleBtn = document.getElementById('bh-theme-toggle');
    const storedTheme = localStorage.getItem('bh-theme');
    const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    
    const activeTheme = storedTheme || (systemPrefersDark ? 'dark' : 'light');
    setTheme(activeTheme);

    if (themeToggleBtn) {
        themeToggleBtn.addEventListener('click', () => {
            const currentTheme = document.documentElement.getAttribute('data-bs-theme') || 'light';
            const nextTheme = currentTheme === 'dark' ? 'light' : 'dark';
            setTheme(nextTheme);
            localStorage.setItem('bh-theme', nextTheme);
        });
    }
}

function setTheme(theme) {
    document.documentElement.setAttribute('data-bs-theme', theme);
    const themeIcon = document.getElementById('bh-theme-icon');
    if (themeIcon) {
        if (theme === 'dark') {
            themeIcon.className = 'bi bi-sun-fill text-warning';
        } else {
            themeIcon.className = 'bi bi-moon-stars-fill text-secondary';
        }
    }
}

/* ==========================================================================
   2. Sticky Navbar & Active Navigation
   ========================================================================== */
function initStickyNavbar() {
    const navbar = document.querySelector('.bh-navbar');
    if (!navbar) return;

    window.addEventListener('scroll', () => {
        if (window.scrollY > 20) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    }, { passive: true });
}

/* ==========================================================================
   3. Scroll Reading Progress Bar
   ========================================================================== */
function initScrollProgress() {
    const progressBar = document.getElementById('bh-scroll-progress');
    if (!progressBar) return;

    window.addEventListener('scroll', () => {
        const scrollTop = window.scrollY;
        const docHeight = document.documentElement.scrollHeight - window.innerHeight;
        if (docHeight > 0) {
            const progress = (scrollTop / docHeight) * 100;
            progressBar.style.width = `${progress}%`;
        }
    }, { passive: true });
}

/* ==========================================================================
   4. Floating Back-to-Top Button
   ========================================================================== */
function initBackToTop() {
    const backToTopBtn = document.getElementById('bh-back-to-top');
    if (!backToTopBtn) return;

    window.addEventListener('scroll', () => {
        if (window.scrollY > 350) {
            backToTopBtn.classList.add('visible');
        } else {
            backToTopBtn.classList.remove('visible');
        }
    }, { passive: true });

    backToTopBtn.addEventListener('click', () => {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
}

/* ==========================================================================
   5. Toast Notification System
   ========================================================================== */
window.showToast = function(message, type = 'info', duration = 4000) {
    let container = document.querySelector('.bh-toast-container');
    if (!container) {
        container = document.createElement('div');
        container.className = 'bh-toast-container';
        document.body.appendChild(container);
    }

    const toast = document.createElement('div');
    toast.className = `bh-toast bh-toast-${type}`;

    let iconClass = 'bi-info-circle-fill text-primary';
    if (type === 'success') iconClass = 'bi-check-circle-fill text-success';
    if (type === 'error') iconClass = 'bi-exclamation-triangle-fill text-danger';

    toast.innerHTML = `
        <i class="bi ${iconClass} fs-5"></i>
        <div class="flex-grow-1 text-sm">${message}</div>
        <button type="button" class="btn-close btn-close-sm" aria-label="Close"></button>
    `;

    const closeBtn = toast.querySelector('.btn-close');
    closeBtn.addEventListener('click', () => toast.remove());

    container.appendChild(toast);

    setTimeout(() => {
        toast.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
        toast.style.opacity = '0';
        toast.style.transform = 'translateY(10px)';
        setTimeout(() => toast.remove(), 300);
    }, duration);
};

/* ==========================================================================
   6. Scroll Reveal using IntersectionObserver
   ========================================================================== */
function initScrollReveal() {
    const revealElements = document.querySelectorAll('.bh-reveal');
    if (!revealElements.length || !('IntersectionObserver' in window)) return;

    const observer = new IntersectionObserver((entries, obs) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('revealed');
                obs.unobserve(entry.target);
            }
        });
    }, { threshold: 0.12 });

    revealElements.forEach(el => observer.observe(el));
}

/* ==========================================================================
   7. Animated Counters
   ========================================================================== */
function initAnimatedCounters() {
    const counters = document.querySelectorAll('[data-counter-target]');
    if (!counters.length || !('IntersectionObserver' in window)) return;

    const observer = new IntersectionObserver((entries, obs) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const el = entry.target;
                const target = parseInt(el.getAttribute('data-counter-target'), 10);
                const duration = 1200;
                const startTime = performance.now();

                function updateCounter(currentTime) {
                    const elapsed = currentTime - startTime;
                    const progress = Math.min(elapsed / duration, 1);
                    // Ease out quadratic
                    const ease = progress * (2 - progress);
                    el.innerText = Math.floor(ease * target).toLocaleString();

                    if (progress < 1) {
                        requestAnimationFrame(updateCounter);
                    } else {
                        el.innerText = target.toLocaleString();
                    }
                }

                requestAnimationFrame(updateCounter);
                obs.unobserve(el);
            }
        });
    }, { threshold: 0.2 });

    counters.forEach(counter => observer.observe(counter));
}

/* ==========================================================================
   8. Password Show / Hide Toggle
   ========================================================================== */
function initPasswordToggles() {
    document.querySelectorAll('[data-toggle-password]').forEach(button => {
        button.addEventListener('click', () => {
            const targetSelector = button.getAttribute('data-toggle-password');
            const input = document.querySelector(targetSelector);
            if (!input) return;

            const icon = button.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                if (icon) icon.className = 'bi bi-eye-slash';
            } else {
                input.type = 'password';
                if (icon) icon.className = 'bi bi-eye';
            }
        });
    });
}

/* ==========================================================================
   9. Copy-Link Functionality
   ========================================================================== */
function initCopyButtons() {
    document.querySelectorAll('[data-copy-url]').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const url = btn.getAttribute('data-copy-url') || window.location.href;
            if (navigator.clipboard) {
                navigator.clipboard.writeText(url).then(() => {
                    window.showToast('Article link copied to clipboard!', 'success');
                }).catch(() => {
                    fallbackCopy(url);
                });
            } else {
                fallbackCopy(url);
            }
        });
    });
}

function fallbackCopy(text) {
    const input = document.createElement('input');
    input.value = text;
    document.body.appendChild(input);
    input.select();
    document.execCommand('copy');
    document.body.removeChild(input);
    window.showToast('Link copied to clipboard!', 'success');
}

/* ==========================================================================
   10. Form Validation Helpers & Loading Spinners
   ========================================================================== */
function initFormValidation() {
    document.querySelectorAll('form[data-validate]').forEach(form => {
        form.addEventListener('submit', (e) => {
            if (!form.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
                window.showToast('Please verify required fields before proceeding.', 'error');
            } else {
                const submitBtn = form.querySelector('[type="submit"]');
                if (submitBtn && !submitBtn.disabled) {
                    submitBtn.disabled = true;
                    const originalHtml = submitBtn.innerHTML;
                    submitBtn.setAttribute('data-original-html', originalHtml);
                    submitBtn.innerHTML = `<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Processing...`;
                }
            }
            form.classList.add('was-validated');
        });
    });
}

/* ==========================================================================
   11. Interactive Likes, Bookmarks & Follows (AJAX with CSRF)
   ========================================================================== */
function initInteractionButtons() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    // Likes
    document.querySelectorAll('[data-action="like-post"]').forEach(btn => {
        btn.addEventListener('click', async (e) => {
            e.preventDefault();
            const postId = btn.getAttribute('data-post-id');
            if (!postId) return;

            try {
                const res = await fetch(`/posts/${postId}/like`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken || '',
                        'Accept': 'application/json',
                    },
                });

                if (res.status === 401) {
                    promptLogin('Sign in to like articles and join the discussion.');
                    return;
                }

                const data = await res.json();
                if (data.success) {
                    const countEl = btn.querySelector('.like-count');
                    const iconEl = btn.querySelector('i');
                    if (countEl) countEl.innerText = data.likes_count;
                    if (data.liked) {
                        if (iconEl) iconEl.className = 'bi bi-heart-fill text-danger';
                        popElement(iconEl || btn);
                        floatUpCount(btn, '+1');
                        window.showToast('Added to your liked articles!', 'success');
                    } else {
                        if (iconEl) iconEl.className = 'bi bi-heart';
                        window.showToast('Removed from liked articles.', 'info');
                    }
                }
            } catch (err) {
                console.error(err);
            }
        });
    });

    // Bookmarks
    document.querySelectorAll('[data-action="bookmark-post"]').forEach(btn => {
        btn.addEventListener('click', async (e) => {
            e.preventDefault();
            const postId = btn.getAttribute('data-post-id');
            if (!postId) return;

            try {
                const res = await fetch(`/posts/${postId}/bookmark`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken || '',
                        'Accept': 'application/json',
                    },
                });

                if (res.status === 401) {
                    promptLogin('Sign in to bookmark articles for later reading.');
                    return;
                }

                const data = await res.json();
                if (data.success) {
                    const iconEl = btn.querySelector('i');
                    if (data.bookmarked) {
                        if (iconEl) iconEl.className = 'bi bi-bookmark-fill text-primary';
                        popElement(iconEl || btn);
                        window.showToast('Article saved to your bookmarks!', 'success');
                    } else {
                        if (iconEl) iconEl.className = 'bi bi-bookmark';
                        window.showToast('Article removed from bookmarks.', 'info');
                    }
                }
            } catch (err) {
                console.error(err);
            }
        });
    });

    // Follow Authors
    document.querySelectorAll('[data-action="follow-author"]').forEach(btn => {
        btn.addEventListener('click', async (e) => {
            e.preventDefault();
            const authorId = btn.getAttribute('data-author-id');
            const followUrl = btn.getAttribute('data-follow-url');
            if (!followUrl) return;

            try {
                const res = await fetch(followUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken || '',
                        'Accept': 'application/json',
                    },
                });

                if (res.status === 401) {
                    promptLogin('Sign in to follow your favourite authors.');
                    return;
                }

                const data = await res.json();
                if (data.success) {
                    if (data.following) {
                        btn.classList.remove('btn-bh-outline-coral');
                        btn.classList.add('btn-bh-secondary');
                        btn.innerHTML = `<i class="bi bi-check2"></i> Following`;
                        window.showToast('Now following this author!', 'success');
                    } else {
                        btn.classList.remove('btn-bh-secondary');
                        btn.classList.add('btn-bh-outline-coral');
                        btn.innerHTML = `<i class="bi bi-plus-lg"></i> Follow`;
                        window.showToast('Unfollowed author.', 'info');
                    }
                    const countEl = document.querySelector(`[data-follower-count="${authorId}"]`);
                    if (countEl && data.followers_count !== undefined) {
                        countEl.innerText = data.followers_count;
                    }
                }
            } catch (err) {
                console.error(err);
            }
        });
    });
}

/* ==========================================================================
   12. Shared Interaction Helpers
   ========================================================================== */
function authLoginUrl() {
    return document.querySelector('meta[name="auth-login-url"]')?.getAttribute('content') || '/login';
}

function promptLogin(message) {
    window.showToast(`${message} Redirecting you to sign in…`, 'info');
    setTimeout(() => {
        window.location.href = `${authLoginUrl()}?intended=${encodeURIComponent(window.location.pathname + window.location.search)}`;
    }, 1100);
}

function popElement(el) {
    if (!el) return;
    el.classList.remove('bh-pop');
    void el.offsetWidth;
    el.classList.add('bh-pop');
    el.addEventListener('animationend', () => el.classList.remove('bh-pop'), { once: true });
}

function floatUpCount(parent, text) {
    if (!parent) return;
    if (getComputedStyle(parent).position === 'static') {
        parent.style.position = 'relative';
    }
    const floater = document.createElement('span');
    floater.className = 'bh-float-up';
    floater.textContent = text;
    floater.style.left = '0.45rem';
    floater.style.top = '-0.25rem';
    parent.appendChild(floater);
    floater.addEventListener('animationend', () => floater.remove(), { once: true });
}

/* ==========================================================================
   13. Live Search Suggestions (debounced dropdown)
   ========================================================================== */
function initSearchSuggestions() {
    document.querySelectorAll('[data-search-suggest]').forEach(wrapper => {
        const input = wrapper.querySelector('input[name="q"], input[name="search"], input[type="search"]');
        if (!input) return;

        if (getComputedStyle(wrapper).position === 'static') {
            wrapper.style.position = 'relative';
        }

        let dropdown = wrapper.querySelector('.bh-suggest');
        if (!dropdown) {
            dropdown = document.createElement('div');
            dropdown.className = 'bh-suggest';
            dropdown.setAttribute('role', 'listbox');
            wrapper.appendChild(dropdown);
        }

        const suggestUrl = wrapper.getAttribute('data-search-suggest') || '/search/suggest';
        let debounceTimer = null;
        let activeIndex = -1;

        const close = () => {
            dropdown.classList.remove('open');
            dropdown.innerHTML = '';
            activeIndex = -1;
            input.setAttribute('aria-expanded', 'false');
        };

        const items = () => [...dropdown.querySelectorAll('.bh-suggest-item')];

        const setActive = (index) => {
            const list = items();
            if (!list.length) return;
            activeIndex = (index + list.length) % list.length;
            list.forEach((item, i) => item.classList.toggle('active', i === activeIndex));
            list[activeIndex].scrollIntoView({ block: 'nearest' });
        };

        const render = (data) => {
            const parts = [];
            if (data.posts && data.posts.length) {
                parts.push('<div class="bh-suggest-head">Articles</div>');
                data.posts.forEach(post => {
                    parts.push(`
                        <div class="bh-suggest-item" role="option" data-url="${post.url}">
                            <i class="bi bi-journal-text text-coral"></i>
                            <span class="text-truncate">${post.title}</span>
                            <span class="suggest-meta">${post.category || ''}</span>
                        </div>`);
                });
            }
            if (data.categories && data.categories.length) {
                parts.push('<div class="bh-suggest-head">Topics</div>');
                data.categories.forEach(category => {
                    parts.push(`
                        <div class="bh-suggest-item" role="option" data-url="${category.url}">
                            <i class="bi ${category.icon || 'bi-folder'}" style="color: var(--bh-moss-500);"></i>
                            <span>${category.name}</span>
                            <span class="suggest-meta">${category.posts_count} articles</span>
                        </div>`);
                });
            }
            if (!parts.length) {
                parts.push('<div class="bh-suggest-item" role="option" data-url="/search?q=' + encodeURIComponent(data.query || '') + '"><i class="bi bi-search text-muted"></i><span>No instant matches — press Enter to search everything</span></div>');
            }
            dropdown.innerHTML = parts.join('');
            dropdown.classList.add('open');
            input.setAttribute('aria-expanded', 'true');
            activeIndex = -1;

            dropdown.querySelectorAll('.bh-suggest-item').forEach(item => {
                item.addEventListener('mousedown', (e) => {
                    e.preventDefault();
                    const url = item.getAttribute('data-url');
                    if (url) window.location.href = url;
                });
            });
        };

        input.setAttribute('autocomplete', 'off');
        input.setAttribute('aria-expanded', 'false');

        input.addEventListener('input', () => {
            const query = input.value.trim();
            clearTimeout(debounceTimer);
            if (query.length < 2) {
                close();
                return;
            }
            debounceTimer = setTimeout(async () => {
                try {
                    const res = await fetch(`${suggestUrl}?q=${encodeURIComponent(query)}`, {
                        headers: { 'Accept': 'application/json' },
                    });
                    if (!res.ok) return;
                    render(await res.json());
                } catch (err) {
                    console.error(err);
                }
            }, 250);
        });

        input.addEventListener('keydown', (e) => {
            if (!dropdown.classList.contains('open')) return;
            const list = items();
            if (e.key === 'ArrowDown') {
                e.preventDefault();
                setActive(activeIndex + 1);
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                setActive(activeIndex - 1);
            } else if (e.key === 'Enter' && activeIndex > -1 && list[activeIndex]) {
                e.preventDefault();
                const url = list[activeIndex].getAttribute('data-url');
                if (url) window.location.href = url;
            } else if (e.key === 'Escape') {
                close();
            }
        });

        document.addEventListener('click', (e) => {
            if (!wrapper.contains(e.target)) close();
        });
    });
}

/* ==========================================================================
   14. Character Counters (comment & contact forms)
   ========================================================================== */
function initCharacterCounters() {
    document.querySelectorAll('[data-char-counter]').forEach(field => {
        const max = parseInt(field.getAttribute('data-char-counter') || field.getAttribute('maxlength') || '0', 10);
        let output = field.parentElement?.querySelector('[data-char-counter-output]');
        if (!output) {
            output = document.createElement('div');
            output.className = 'bh-char-count text-xs text-muted text-end mt-1';
            field.insertAdjacentElement('afterend', output);
        }
        const update = () => {
            const length = field.value.length;
            output.textContent = max ? `${length} / ${max}` : `${length}`;
        };
        field.addEventListener('input', update);
        update();
    });
}

/* ==========================================================================
   15. Grid / List View Toggle (persisted in localStorage)
   ========================================================================== */
function initViewToggle() {
    const toggle = document.querySelector('[data-view-toggle]');
    const container = document.getElementById('bhPostsContainer');
    if (!toggle || !container) return;

    const buttons = toggle.querySelectorAll('[data-view]');
    const apply = (view) => {
        container.classList.toggle('view-list', view === 'list');
        buttons.forEach(btn => btn.classList.toggle('active', btn.getAttribute('data-view') === view));
    };

    apply(localStorage.getItem('bh-view') || 'grid');

    buttons.forEach(btn => {
        btn.addEventListener('click', () => {
            const view = btn.getAttribute('data-view');
            localStorage.setItem('bh-view', view);
            apply(view);
        });
    });
}

/* ==========================================================================
   16. Image Lightbox (article images)
   ========================================================================== */
function initLightbox() {
    const images = document.querySelectorAll('.bh-article-content img, [data-lightbox]');
    if (!images.length) return;

    let overlay = null;

    const close = () => {
        if (!overlay) return;
        overlay.classList.remove('open');
        document.body.style.overflow = '';
    };

    const ensureOverlay = () => {
        if (overlay) return overlay;
        overlay = document.createElement('div');
        overlay.className = 'bh-lightbox';
        overlay.setAttribute('role', 'dialog');
        overlay.setAttribute('aria-label', 'Image preview');
        overlay.innerHTML = `
            <button type="button" class="bh-lightbox-close" aria-label="Close image preview"><i class="bi bi-x-lg"></i></button>
            <img src="" alt="">
        `;
        overlay.addEventListener('click', (e) => {
            if (e.target === overlay || e.target.closest('.bh-lightbox-close')) close();
        });
        document.body.appendChild(overlay);
        return overlay;
    };

    images.forEach(img => {
        if (img.closest('a') && !img.hasAttribute('data-lightbox')) return;
        img.classList.add('bh-zoomable');
        img.setAttribute('tabindex', '0');
        const open = () => {
            const box = ensureOverlay();
            const preview = box.querySelector('img');
            preview.src = img.currentSrc || img.src;
            preview.alt = img.alt || 'Article image';
            requestAnimationFrame(() => box.classList.add('open'));
            document.body.style.overflow = 'hidden';
        };
        img.addEventListener('click', open);
        img.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                open();
            }
        });
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') close();
    });
}

/* ==========================================================================
   17. Native Share (with clipboard fallback)
   ========================================================================== */
function initNativeShare() {
    document.querySelectorAll('[data-native-share]').forEach(btn => {
        btn.addEventListener('click', async (e) => {
            e.preventDefault();
            const url = btn.getAttribute('data-share-url') || window.location.href;
            const title = btn.getAttribute('data-share-title') || document.title;
            if (navigator.share) {
                try {
                    await navigator.share({ title, url });
                } catch (err) {
                    /* user cancelled share sheet */
                }
                return;
            }
            if (navigator.clipboard) {
                try {
                    await navigator.clipboard.writeText(url);
                    window.showToast('Share link copied to clipboard!', 'success');
                } catch (err) {
                    window.showToast('Could not copy the link.', 'error');
                }
            }
        });
    });
}

/* ==========================================================================
   18. Newsletter Forms — AJAX subscribe with loading + toast
   ========================================================================== */
function initNewsletterForms() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    document.querySelectorAll('form[data-newsletter-form]').forEach(form => {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const emailInput = form.querySelector('input[type="email"]');
            const submitBtn = form.querySelector('[type="submit"]');

            if (emailInput && !emailInput.checkValidity()) {
                window.showToast('Please enter a valid email address to subscribe.', 'error');
                emailInput.focus();
                return;
            }

            const originalHtml = submitBtn ? submitBtn.innerHTML : '';
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Subscribing…';
            }

            try {
                const res = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken || '',
                    },
                    body: new FormData(form),
                });

                const data = await res.json().catch(() => ({}));

                if (res.ok && data.success) {
                    window.showToast(data.message || 'You are subscribed. Engineering insights are on the way!', 'success');
                    form.reset();
                } else if (res.status === 422 && data.errors) {
                    window.showToast(Object.values(data.errors).flat()[0], 'error');
                } else {
                    window.showToast(data.message || 'Subscription failed. Please try again.', 'error');
                }
            } catch (err) {
                console.error(err);
                window.showToast('Network error — please try again.', 'error');
            } finally {
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalHtml;
                }
            }
        });
    });
}

/* ==========================================================================
   19. Sticky TOC Scroll Spy (privacy & terms pages)
   ========================================================================== */
function initTocScrollSpy() {
    const toc = document.querySelector('.bh-toc');
    if (!toc) return;

    const links = [...toc.querySelectorAll('a[href^="#"]')];
    const sections = links
        .map(link => document.getElementById(link.getAttribute('href').slice(1)))
        .filter(Boolean);

    if (!sections.length) return;

    const onScroll = () => {
        let active = sections[0];
        for (const section of sections) {
            if (section.getBoundingClientRect().top <= 140) active = section;
        }
        links.forEach(link => {
            link.classList.toggle('active', link.getAttribute('href') === `#${active.id}`);
        });
    };

    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
}

/* ==========================================================================
   20. Password Strength Meter
   ========================================================================== */
function initPasswordStrength() {
    const labels = ['', 'Weak password', 'Fair password', 'Good password', 'Strong password'];

    document.querySelectorAll('[data-password-strength]').forEach(input => {
        const scope = input.closest('form') || document;
        const bar = scope.querySelector('[data-strength-bar]');
        const text = scope.querySelector('[data-strength-text]');
        if (!bar) return;

        input.addEventListener('input', () => {
            const value = input.value;
            if (!value.length) {
                bar.className = 'bh-strength-bar';
                if (text) {
                    text.textContent = 'Use 8+ characters with a mix of letters, numbers and symbols.';
                    text.className = 'bh-strength-text text-xs text-muted';
                }
                return;
            }

            let score = 0;
            if (value.length >= 8) score++;
            if (value.length >= 12) score++;
            if (/[a-z]/.test(value) && /[A-Z]/.test(value)) score++;
            if (/\d/.test(value)) score++;
            if (/[^A-Za-z0-9]/.test(value)) score++;
            score = Math.min(Math.max(score, 1), 4);

            bar.className = `bh-strength-bar score-${score}`;
            if (text) {
                const colorClass = score <= 1 ? 'text-danger' : (score === 2 ? 'text-warning' : (score === 3 ? 'text-info' : 'text-success'));
                text.textContent = labels[score];
                text.className = `bh-strength-text text-xs ${colorClass}`;
            }
        });
    });
}

/* ==========================================================================
   21. Dynamic Footer Year
   ========================================================================== */
function initFooterYear() {
    const year = new Date().getFullYear();
    document.querySelectorAll('[data-current-year]').forEach(el => {
        el.textContent = year;
    });
}
