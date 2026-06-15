<div>
    @if(Auth::user()->taxtype=='on')
        <div class="flex items-start gap-3 p-4 rounded-lg bg-warning/10 border border-warning/20 mb-4" role="alert">
            @include('components.icons.exclamation-triangle', ['class' => 'w-5 h-5 text-warning mt-0.5 shrink-0'])
            <div class="text-sm text-warning">
                <p>You are required to pay Tax fee of @money(Auth::user()->taxamount).</p>
                <p class="mt-1">Contact support at {{ $settings->contact_email }}</p>
            </div>
        </div>
    @endif
</div>