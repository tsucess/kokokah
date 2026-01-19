@props(['badges' => [], 'maxDisplay' => 5, 'size' => 'md'])

<div class="badge-display-container" style="display: flex; flex-wrap: wrap; gap: 8px; align-items: center;">
    @if(count($badges) > 0)
        @foreach($badges->take($maxDisplay) as $badge)
            <div class="badge-item" 
                 title="{{ $badge->name }}: {{ $badge->description }}"
                 style="
                    font-size: {{ $size === 'sm' ? '16px' : ($size === 'lg' ? '28px' : '20px') }};
                    cursor: pointer;
                    transition: transform 0.2s ease;
                    padding: 4px;
                 "
                 onmouseover="this.style.transform='scale(1.2)'"
                 onmouseout="this.style.transform='scale(1)'">
                {{ $badge->icon ?? '🏆' }}
            </div>
        @endforeach

        @if(count($badges) > $maxDisplay)
            <div class="badge-more" 
                 title="And {{ count($badges) - $maxDisplay }} more badges"
                 style="
                    font-size: {{ $size === 'sm' ? '14px' : ($size === 'lg' ? '24px' : '18px') }};
                    color: #999;
                    font-weight: bold;
                    padding: 4px 8px;
                 ">
                +{{ count($badges) - $maxDisplay }}
            </div>
        @endif
    @else
        <span class="text-muted" style="font-size: 14px;">No badges earned yet</span>
    @endif
</div>

<style>
    .badge-display-container {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        align-items: center;
    }

    .badge-item {
        display: inline-block;
        transition: all 0.2s ease;
    }

    .badge-item:hover {
        filter: drop-shadow(0 0 4px rgba(0, 0, 0, 0.2));
    }

    .badge-more {
        display: inline-block;
        padding: 4px 8px;
        background-color: #f0f0f0;
        border-radius: 4px;
        font-size: 12px;
        color: #666;
    }
</style>

