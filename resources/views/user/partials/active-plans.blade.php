{{--
    Active Plans Partial
    Displays user's active investment plans.
    Expects $plans (User_plans collection) and $settings from parent view.
--}}
<div class="bg-surface-raised border border-surface-border rounded-xl overflow-hidden">
    <div class="px-5 py-4 border-b border-surface-border flex items-center justify-between">
        <div class="flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-primary"></span>
            <h3 class="text-sm font-semibold text-content-primary">Active Plan(s)</h3>
        </div>
        <a href="{{ url('dashboard/mplans') }}" class="text-xs text-primary hover:text-primary-light font-medium transition-colors">
            View All &rarr;
        </a>
    </div>

    @if ($plans->count() > 0)
        <div class="divide-y divide-surface-border">
            @foreach ($plans->take(2) as $plan)
                <div class="px-5 py-3 flex items-center justify-between">
                    <div class="flex items-center gap-3 min-w-0">
                        <span class="w-8 h-8 rounded-lg bg-primary-subtle flex items-center justify-center flex-shrink-0">
                            @include('components.icons.chart-pie', ['class' => 'w-4 h-4 text-primary'])
                        </span>
                        <div class="min-w-0">
                            <p class="text-sm text-content-primary font-medium truncate">{{ $plan->plan ?? 'Plan' }}</p>
                            <p class="text-[10px] text-content-tertiary">{{ $plan->created_at ? $plan->created_at->format('M d, Y') : '' }}</p>
                        </div>
                    </div>
                    <div class="text-right flex-shrink-0">
                        <p class="text-sm font-semibold text-content-primary">{{ $settings->currency }}{{ number_format($plan->amount ?? 0, 2, '.', ',') }}</p>
                        <span class="inline-flex items-center text-[10px] font-medium px-1.5 py-0.5 rounded bg-gain/10 text-gain">
                            Active
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="px-5 py-10 text-center">
            @include('components.icons.chart-pie', ['class' => 'w-8 h-8 text-content-tertiary mx-auto mb-2'])
            <p class="text-xs text-content-tertiary mb-3">No Active Plans</p>
            @if(!empty($mod['trading']))
            <a href="{{ route('trade') }}" class="inline-flex items-center gap-1 text-xs font-medium bg-primary hover:bg-primary-dark text-content-inverse px-4 py-2 rounded-lg transition-colors">
                + Trade
            </a>
            @endif
        </div>
    @endif
</div>
