@props(['title' => '', 'actions' => null, 'tableId' => null])

<div class="bg-surface-card rounded-xl border border-border shadow-card">
    @if($title || $actions)
        <div class="flex items-center justify-between px-5 py-4 border-b border-border">
            @if($title)
                <h3 class="text-base font-medium text-content">{{ $title }}</h3>
            @endif
            @if($actions)
                <div class="flex items-center gap-3">
                    {{ $actions }}
                </div>
            @endif
        </div>
    @endif
    <div class="overflow-x-auto">
        {{ $slot }}
    </div>
</div>
