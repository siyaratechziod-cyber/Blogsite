@props([
    'icon' => 'bi-search',
    'title' => 'No items found',
    'description' => 'We couldn\'t find any records matching your criteria.',
    'actionText' => null,
    'actionUrl' => null,
])

<div class="bh-card p-5 text-center my-4">
    <div class="bh-cat-icon mx-auto mb-3" style="width: 64px; height: 64px; font-size: 1.75rem;">
        <i class="bi {{ $icon }}"></i>
    </div>
    <h4 class="h5 font-heading fw-bold mb-2">{{ $title }}</h4>
    <p class="text-secondary text-sm mb-4 mx-auto" style="max-width: 440px;">
        {{ $description }}
    </p>
    @if ($actionText && $actionUrl)
        <div>
            <a href="{{ $actionUrl }}" class="btn btn-bh-primary">
                {{ $actionText }}
            </a>
        </div>
    @endif
</div>
