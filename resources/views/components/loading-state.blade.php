@props(['message' => 'Loading content...'])

<div class="d-flex flex-column align-items-center justify-content-center p-5 text-center my-3">
    <div class="spinner-border text-coral mb-3" style="width: 2.75rem; height: 2.75rem; color: var(--bh-coral-500);" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
    <div class="text-secondary text-sm font-medium">{{ $message }}</div>
</div>
