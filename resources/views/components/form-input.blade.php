@props([
    'name',
    'label' => null,
    'type' => 'text',
    'required' => false,
    'error' => null,
    'hint' => null,
    'placeholder' => null,
])

@php
    $hasError = $error || $errors->has($name);
    $errorMessage = $error ?? $errors->first($name);
@endphp

<div class="mb-3">
    @if($label)
        <label for="{{ $name }}" class="form-label">
            {{ $label }}
            @if($required)
                <span class="text-danger">*</span>
            @endif
        </label>
    @endif

    <input 
        type="{{ $type }}"
        id="{{ $name }}"
        name="{{ $name }}"
        class="form-control @if($hasError) is-invalid @endif"
        placeholder="{{ $placeholder }}"
        @required($required)
        {{ $attributes }}
    >

    @if($hint)
        <small class="form-text text-muted d-block mt-1">
            {{ $hint }}
        </small>
    @endif

    @if($hasError)
        <div class="invalid-feedback d-block">
            {{ $errorMessage }}
        </div>
    @endif
</div>

