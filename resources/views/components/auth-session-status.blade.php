@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'font-medium text-sm text-success text-center text-green-600']) }}>
        {{ $status }}
    </div>
@endif
