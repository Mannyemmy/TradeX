{{-- Top Copy Trading Experts --}}
@if(isset($topExperts) && $topExperts->count() > 0)
<div class="bg-surface-raised border border-surface-border rounded-xl p-5">
    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center gap-2">
            <x-icon name="user-group" class="w-5 h-5 text-primary" />
            <h3 class="text-sm font-semibold text-content-primary">Top Expert Traders</h3>
        </div>
        <a href="{{ route('copyTrading') }}" class="text-xs text-primary hover:text-primary-dark transition-colors">View All</a>
    </div>

    <div class="space-y-3">
        @foreach($topExperts as $expert)
            <a href="{{ route('copyTrading.expert', $expert->id) }}" class="group flex items-center gap-3 p-3 rounded-lg bg-surface-overlay/50 border border-transparent hover:border-primary/30 hover:bg-surface-overlay transition-all duration-200">
                {{-- Avatar --}}
                <div class="relative flex-shrink-0">
                    @if($expert->profile_picture)
                        <img src="{{ asset('storage/app/public/' . $expert->profile_picture) }}" alt="{{ $expert->name }}" class="w-10 h-10 rounded-full object-cover ring-2 ring-surface-border group-hover:ring-primary/40 transition-all">
                    @else
                        <div class="w-10 h-10 rounded-full bg-surface-overlay flex items-center justify-center ring-2 ring-surface-border">
                            <span class="text-xs font-bold text-content-tertiary">{{ strtoupper(substr($expert->name, 0, 2)) }}</span>
                        </div>
                    @endif
                    <span class="absolute -bottom-0.5 -right-0.5 w-2.5 h-2.5 bg-gain rounded-full border-2 border-surface-raised"></span>
                </div>

                {{-- Info --}}
                <div class="min-w-0 flex-1">
                    <h4 class="text-sm font-semibold text-content-primary truncate group-hover:text-primary transition-colors">{{ $expert->name }}</h4>
                    <div class="flex items-center gap-2 mt-0.5">
                        <span class="bg-primary/10 text-primary text-[10px] font-medium px-1.5 py-0.5 rounded">{{ $expert->area_of_expertise }}</span>
                        <span class="text-[10px] text-content-tertiary">{{ number_format($expert->win_rate) }}% win</span>
                    </div>
                </div>

                {{-- ROI --}}
                <div class="text-right flex-shrink-0">
                    <p class="text-sm font-bold text-gain">{{ number_format($expert->daily_roi, 2) }}%</p>
                    <p class="text-[10px] text-content-tertiary">daily ROI</p>
                </div>
            </a>
        @endforeach
    </div>

    <a href="{{ route('copyTrading') }}" class="mt-4 flex items-center justify-center gap-1.5 py-2.5 rounded-lg bg-primary/10 text-primary text-xs font-semibold hover:bg-primary hover:text-content-inverse transition-all duration-200">
        Explore All Experts
        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" /></svg>
    </a>
</div>
@endif
