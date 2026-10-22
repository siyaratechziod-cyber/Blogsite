/* ============================================================
   BlogHub Admin Panel Scripts (Vanilla JS — no jQuery)
   Relies on bloghub.js for theme, toasts, back-to-top and
   character counters.
   ============================================================ */

(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', () => {
        initSlugGeneration();
        initImagePreviews();
        initConfirmForms();
    });

    /* Auto-fill slug from title; stops once the slug field is edited manually. */
    function initSlugGeneration() {
        const source = document.querySelector('[data-slug-source]');
        const target = document.querySelector('[data-slug-target]');

        if (!source || !target) return;

        let manuallyEdited = target.value.trim().length > 0;

        const slugify = (value) =>
            value
                .toString()
                .toLowerCase()
                .trim()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/[\s_]+/g, '-')
                .replace(/-+/g, '-')
                .replace(/^-|-$/g, '');

        target.addEventListener('input', () => {
            manuallyEdited = true;
            const clean = slugify(target.value);
            if (clean !== target.value) {
                const pos = target.selectionStart - (target.value.length - clean.length);
                target.value = clean;
                target.setSelectionRange(pos, pos);
            }
        });

        source.addEventListener('input', () => {
            if (!manuallyEdited) {
                target.value = slugify(source.value);
            }
        });
    }

    /* Live preview for file inputs marked with data-image-preview="#imgSelector". */
    function initImagePreviews() {
        document.querySelectorAll('[data-image-preview]').forEach((input) => {
            const preview = document.querySelector(input.getAttribute('data-image-preview'));
            if (!preview) return;

            input.addEventListener('change', () => {
                const file = input.files && input.files[0];
                if (file && file.type.startsWith('image/')) {
                    preview.src = URL.createObjectURL(file);
                    preview.classList.remove('d-none');
                }
            });
        });
    }

    /* Route destructive/irreversible form submissions through a shared modal. */
    function initConfirmForms() {
        const modalEl = document.getElementById('bhConfirmModal');
        const messageEl = document.getElementById('bhConfirmMessage');
        const okBtn = document.getElementById('bhConfirmOk');
        if (!modalEl || !okBtn) return;

        const modal = new bootstrap.Modal(modalEl);
        let pendingForm = null;

        document.addEventListener('submit', (event) => {
            const form = event.target;
            if (!(form instanceof HTMLFormElement) || !form.dataset.confirm) return;

            event.preventDefault();
            pendingForm = form;
            messageEl.textContent = form.dataset.confirm;
            modal.show();
        });

        okBtn.addEventListener('click', () => {
            if (pendingForm) {
                const form = pendingForm;
                pendingForm = null;
                modal.hide();
                HTMLFormElement.prototype.submit.call(form);
            }
        });
    }
})();
