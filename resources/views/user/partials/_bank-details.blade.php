{{-- Bank Transfer Details Partial --}}
@php
    $pm = $payment_mode;
@endphp

<div class="space-y-4">
    @if (!empty($pm->bankname))
        <div>
            <label class="block text-xs font-medium text-content-tertiary uppercase tracking-wide mb-1">Bank Name</label>
            <div class="flex items-stretch gap-2">
                <input type="text" value="{{ $pm->bankname }}" readonly
                       class="flex-1 bg-surface-overlay border border-surface-border-light rounded-lg px-4 py-3 text-sm text-content-primary focus:outline-none">
                <button onclick="navigator.clipboard.writeText('{{ $pm->bankname }}')"
                        class="px-3 rounded-lg border border-surface-border-light bg-surface-overlay text-content-secondary hover:text-primary hover:border-primary/30 transition">
                    @include('components.icons.copy', ['class' => 'w-4 h-4'])
                </button>
            </div>
        </div>
    @endif

    @if (!empty($pm->account_name))
        <div>
            <label class="block text-xs font-medium text-content-tertiary uppercase tracking-wide mb-1">Account Name</label>
            <div class="flex items-stretch gap-2">
                <input type="text" value="{{ $pm->account_name }}" readonly
                       class="flex-1 bg-surface-overlay border border-surface-border-light rounded-lg px-4 py-3 text-sm text-content-primary focus:outline-none">
                <button onclick="navigator.clipboard.writeText('{{ $pm->account_name }}')"
                        class="px-3 rounded-lg border border-surface-border-light bg-surface-overlay text-content-secondary hover:text-primary hover:border-primary/30 transition">
                    @include('components.icons.copy', ['class' => 'w-4 h-4'])
                </button>
            </div>
        </div>
    @endif

    @if (!empty($pm->account_number))
        <div>
            <label class="block text-xs font-medium text-content-tertiary uppercase tracking-wide mb-1">Account Number</label>
            <div class="flex items-stretch gap-2">
                <input type="text" value="{{ $pm->account_number }}" readonly
                       class="flex-1 bg-surface-overlay border border-surface-border-light rounded-lg px-4 py-3 text-sm text-content-primary font-mono focus:outline-none">
                <button onclick="navigator.clipboard.writeText('{{ $pm->account_number }}')"
                        class="px-3 rounded-lg border border-surface-border-light bg-surface-overlay text-content-secondary hover:text-primary hover:border-primary/30 transition">
                    @include('components.icons.copy', ['class' => 'w-4 h-4'])
                </button>
            </div>
        </div>
    @endif

    @if (!empty($pm->swift_code))
        <div>
            <label class="block text-xs font-medium text-content-tertiary uppercase tracking-wide mb-1">Swift Code</label>
            <div class="flex items-stretch gap-2">
                <input type="text" value="{{ $pm->swift_code }}" readonly
                       class="flex-1 bg-surface-overlay border border-surface-border-light rounded-lg px-4 py-3 text-sm text-content-primary font-mono focus:outline-none">
                <button onclick="navigator.clipboard.writeText('{{ $pm->swift_code }}')"
                        class="px-3 rounded-lg border border-surface-border-light bg-surface-overlay text-content-secondary hover:text-primary hover:border-primary/30 transition">
                    @include('components.icons.copy', ['class' => 'w-4 h-4'])
                </button>
            </div>
        </div>
    @endif
</div>
