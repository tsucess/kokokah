@props([
    'name',
    'label' => null,
    'options' => [],
    'selected' => null,
    'required' => false,
    'error' => null,
    'hint' => null,
    'placeholder' => 'Select an option',
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

    <select 
        id="{{ $name }}"
        name="{{ $name }}"
        class="form-select @if($hasError) is-invalid @endif"
        @required($required)
        {{ $attributes }}
    >
        @if($placeholder)
            <option value="">{{ $placeholder }}</option>
        @endif

        @foreach($options as $value => $label)
            <option 
                value="{{ $value }}"
                @selected($selected == $value || old($name) == $value)
            >
                {{ $label }}
            </option>
        @endforeach
    </select>

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

