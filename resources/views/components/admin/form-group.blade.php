@props(['label' => '', 'for' => '', 'error' => '', 'helper' => '', 'required' => false])

<div {{ $attributes->merge(['class' => '']) }}>
    @if($label)
        <label @if($for) for="{{ $for }}" @endif class="block text-sm font-medium text-content mb-1.5">
            {{ $label }}
            @if($required)
                <span class="text-danger">*</span>
            @endif
        </label>
    @endif
    {{ $slot }}
    @if($helper && !$error)
        <p class="text-xs text-content-muted mt-1">{{ $helper }}</p>
    @endif
    @if($error)
        <p class="text-xs text-danger mt-1">{{ $error }}</p>
    @endif
</div>
