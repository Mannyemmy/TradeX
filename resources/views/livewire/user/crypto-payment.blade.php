<div>
    <button type="button" wire:click="payViaBinance" wire:loading.attr="disabled"
        class="inline-flex items-center gap-2 bg-amber-500 hover:bg-amber-600 disabled:opacity-50 text-[#0F1115] font-semibold px-6 py-3 rounded-lg transition-colors">
        <svg class="w-5 h-5 animate-spin" wire:loading wire:target="payViaBinance" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <span>Pay Via Binance Pay</span>
    </button>
</div>
