@props([
    'type' => 'info',
    'dismissible' => false,
    'title' => null,
])

@php
    $typeClasses = match($type) {
        'success' => 'alert-success',
        'danger' => 'alert-danger',
        'warning' => 'alert-warning',
        'info' => 'alert-info',
        'primary' => 'alert-primary',
        'secondary' => 'alert-secondary',
        'light' => 'alert-light',
        'dark' => 'alert-dark',
        default => 'alert-info',
    };

    $alertClasses = "alert {$typeClasses}";
    if ($dismissible) {
        $alertClasses .= " alert-dismissible fade show";
    }
@endphp

<div class="{{ $alertClasses }}" role="alert" {{ $attributes }}>
    @if($title)
        <h4 class="alert-heading">{{ $title }}</h4>
    @endif

    {{ $slot }}

    @if($dismissible)
        <button 
            type="button" 
            class="btn-close" 
            data-bs-dismiss="alert" 
            aria-label="Close"
        ></button>
    @endif
</div>

