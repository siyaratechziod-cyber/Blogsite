@props(['items' => []])

<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb mb-0 text-sm">
        <li class="breadcrumb-item">
            <a href="{{ route('home') }}" class="text-secondary">
                <i class="bi bi-house-door me-1"></i> Home
            </a>
        </li>
        @foreach ($items as $label => $url)
            @if ($loop->last)
                <li class="breadcrumb-item active text-primary fw-medium" aria-current="page">{{ $label }}</li>
            @else
                <li class="breadcrumb-item">
                    <a href="{{ $url }}" class="text-secondary">{{ $label }}</a>
                </li>
            @endif
        @endforeach
    </ol>
</nav>
