@props(['href', 'icon' => '', 'active' => false, 'badge' => null])

<a href="{{ $href }}"
   class="mx-3 flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition-colors cursor-pointer
          {{ $active
              ? 'bg-sidebar-active text-content-inverse relative before:absolute before:left-0 before:top-1/2 before:-translate-y-1/2 before:h-5 before:w-0.5 before:rounded-full before:bg-primary'
              : 'text-sidebar-text hover:bg-sidebar-hover' }}">
    @if($icon)
        {!! $icon !!}
    @endif
    <span class="flex-1">{{ $slot }}</span>
    @if($badge !== null)
        <span class="{{ $badge === 'OFF' ? 'bg-danger/20 text-danger' : 'bg-primary text-primary-foreground' }} text-[0.65rem] font-semibold px-1.5 py-0.5 rounded-full min-w-[1.25rem] text-center">{{ $badge }}</span>
    @endif
</a>
