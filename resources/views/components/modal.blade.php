@props([
    'id' => 'bhModal',
    'title' => 'Modal Title',
    'size' => '',
])

<div class="modal fade" id="{{ $id }}" tabindex="-1" aria-labelledby="{{ $id }}Label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered {{ $size }}">
        <div class="modal-content border-0 shadow-lg" style="background-color: var(--bh-surface-elevated); border-radius: var(--bh-radius-lg);">
            <div class="modal-header border-bottom border-secondary border-opacity-10 py-3 px-4">
                <h5 class="modal-title font-heading fw-bold" id="{{ $id }}Label">{{ $title }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                {{ $slot }}
            </div>
            @if (isset($footer))
                <div class="modal-footer border-top border-secondary border-opacity-10 py-3 px-4">
                    {{ $footer }}
                </div>
            @endif
        </div>
    </div>
</div>
