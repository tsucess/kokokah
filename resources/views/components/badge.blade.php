@props([
    'variant' => 'primary',
    'pill' => false,
])

@php
    $variantClasses = match($variant) {
        'primary' => 'bg-primary',
        'secondary' => 'bg-secondary',
        'success' => 'bg-success',
        'danger' => 'bg-danger',
        'warning' => 'bg-warning text-dark',
        'info' => 'bg-info',
        'light' => 'bg-light text-dark',
        'dark' => 'bg-dark',
        default => 'bg-primary',
    };

    $badgeClasses = "badge {$variantClasses}";
    if ($pill) {
        $badgeClasses .= " rounded-pill";
    }
@endphp

<span class="{{ $badgeClasses }}" {{ $attributes }}>
    {{ $slot }}
</span>

