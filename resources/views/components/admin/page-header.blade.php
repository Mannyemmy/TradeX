@props(['title' => '', 'subtitle' => '', 'actions' => null])

<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-semibold text-content tracking-tight">{{ $title }}</h1>
        @if($subtitle)
            <p class="mt-1 text-sm text-content-secondary">{{ $subtitle }}</p>
        @endif
    </div>
    @if($actions)
        <div class="flex items-center gap-3">
            {{ $actions }}
        </div>
    @endif
</div>
