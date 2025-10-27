@props([
    'title' => null,
    'subtitle' => null,
    'footer' => null,
    'class' => '',
    'headerClass' => '',
    'bodyClass' => '',
    'footerClass' => '',
])

<div class="card {{ $class }}" {{ $attributes }}>
    @if($title || $subtitle)
        <div class="card-header {{ $headerClass }}">
            @if($title)
                <h5 class="card-title mb-0">{{ $title }}</h5>
            @endif
            @if($subtitle)
                <p class="card-subtitle text-muted mb-0">{{ $subtitle }}</p>
            @endif
        </div>
    @endif

    <div class="card-body {{ $bodyClass }}">
        {{ $slot }}
    </div>

    @if($footer)
        <div class="card-footer {{ $footerClass }}">
            {{ $footer }}
        </div>
    @endif
</div>

