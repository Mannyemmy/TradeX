@props(['active' => ''])

<div x-data="{ activeTab: '{{ $active }}' }" {{ $attributes->merge(['class' => '']) }}>
    {{-- Tab Headers --}}
    <div class="flex gap-1 border-b border-border">
        {{ $tabs }}
    </div>
    {{-- Tab Panels --}}
    <div class="mt-5">
        {{ $slot }}
    </div>
</div>
