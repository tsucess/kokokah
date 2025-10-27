@props([
    'variant' => 'primary',
    'size' => 'md',
    'disabled' => false,
    'type' => 'button',
    'href' => null,
])

@php
    // Define button classes based on variant
    $variantClasses = match($variant) {
        'primary' => 'primaryButton',
        'secondary' => 'secondaryButton',
        'tertiary' => 'tertiaryButton',
        'danger' => 'btn-danger',
        'success' => 'btn-success',
        'warning' => 'btn-warning',
        'info' => 'btn-info',
        'light' => 'btn-light',
        'dark' => 'btn-dark',
        default => 'primaryButton',
    };

    // Define size classes
    $sizeClasses = match($size) {
        'sm' => 'btn-sm',
        'md' => '',
        'lg' => 'btn-lg',
        default => '',
    };

    // Combine all classes
    $classes = "btn {$variantClasses} {$sizeClasses}";
    $classes = trim(preg_replace('/\s+/', ' ', $classes));
@endphp

@if($href)
    <a
        href="{{ $href }}"
        class="{{ $classes }}"
        @disabled($disabled)
        {{ $attributes }}
    >
        {{ $slot }}
    </a>
@else
    <button
        type="{{ $type }}"
        class="{{ $classes }}"
        @disabled($disabled)
        {{ $attributes }}
    >
        {{ $slot }}
    </button>
@endif
