@extends('layouts.dash1')
@section('title', $title)
@section('content')

    <x-danger-alert />
    <x-success-alert />

    {{-- Ticker Tape --}}
    @include('user.partials.ticker-tape')

    {{-- Quick Nav --}}
    @include('user.partials.quick-nav')

    {{-- Back Link --}}
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('user.courses') }}" class="p-2 rounded-lg bg-surface-overlay hover:bg-surface-border text-content-secondary transition-colors">
            <x-icon name="arrow-left" class="w-5 h-5" />
        </a>
        <h2 class="text-xl font-bold text-content-primary">{{ $course->title }}</h2>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Course Meta --}}
            <div class="rounded-xl bg-surface-raised border border-surface-border p-6">
                <div class="grid grid-cols-3 gap-4 text-sm mb-6">
                    <div>
                        <p class="text-xs text-content-tertiary mb-0.5">CREATED BY</p>
                        <p class="font-medium text-content-primary">{{ $settings->site_name }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-content-tertiary mb-0.5">CATEGORY</p>
                        <p class="font-medium text-content-primary">{{ $course->category->name ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-content-tertiary mb-0.5">LAST UPDATED</p>
                        <p class="font-medium text-content-primary">{{ \Carbon\Carbon::parse($course->updated_at)->toDayDateTimeString() }}</p>
                    </div>
                </div>

                <h3 class="text-sm font-semibold text-content-primary mb-2">About Course</h3>
                <p class="text-sm text-content-secondary">{{ $course->description }}</p>
            </div>

            {{-- Course Lessons --}}
            <div class="rounded-xl bg-surface-raised border border-surface-border overflow-hidden">
                <div class="px-5 py-3 border-b border-surface-border">
                    <h3 class="text-sm font-semibold text-content-primary">Course Lessons</h3>
                </div>
                <div class="divide-y divide-surface-border">
                    @forelse ($lessons as $lesson)
                        <div class="flex items-center justify-between px-5 py-3">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-loss/10 flex items-center justify-center flex-shrink-0">
                                    <x-icon name="play" class="w-4 h-4 text-loss" />
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-content-primary">{{ $lesson->title }}</p>
                                    <p class="text-xs text-content-tertiary">{{ $lesson->description }} &middot; {{ $lesson->length }}</p>
                                </div>
                            </div>
                            <div>
                                @if ($lesson->is_preview)
                                    <button data-toggle="modal" data-target="#preview{{ $lesson->id }}" class="px-3 py-1 rounded-lg bg-info/10 text-info text-xs font-medium hover:bg-info/20 transition-colors">Preview</button>
                                @else
                                    <x-icon name="lock-closed" class="w-4 h-4 text-content-tertiary" />
                                @endif
                            </div>
                        </div>

                        @if ($loop->iteration == 5 && $loop->remaining > 0)
                            <div class="px-5 py-3 text-sm text-content-tertiary">
                                {{ $loop->remaining }} More Lesson{{ $loop->remaining > 1 ? 's' : '' }}
                            </div>
                        @endif
                        @break($loop->iteration == 5)

                        {{-- Preview Modal --}}
                        @if ($lesson->is_preview)
                            <div class="modal fade" tabindex="-1" id="preview{{ $lesson->id }}">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content" style="background: #FFFFFF; border: 1px solid #DCE3EC;">
                                        <div class="embed-responsive embed-responsive-16by9">
                                            <iframe class="embed-responsive-item" src="{{ $lesson->video_link }}" allowfullscreen></iframe>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @empty
                        <div class="px-5 py-8 text-center text-content-tertiary text-sm">No lessons available.</div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Sidebar: Purchase Card --}}
        <div>
            <div class="rounded-xl bg-surface-raised border border-surface-border overflow-hidden sticky top-24">
                <img src="{{ $course->image && str_starts_with($course->image, 'http') ? $course->image : asset('storage/' . $course->image) }}"
                     alt="course image" class="w-full h-48 object-cover">
                <div class="p-5">
                    <p class="text-2xl font-bold text-content-primary mb-4">
                        {{ !$course->amount ? 'Free' : \App\Helpers\CurrencyHelper::formatForUser($course->amount) }}
                    </p>
                    <button type="button" onclick="confirmBuy()" class="w-full py-2.5 rounded-lg bg-primary hover:bg-primary-dark text-content-inverse text-sm font-semibold transition-colors">
                        Buy Now
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Buy Modal (hidden form) --}}
    <form id="buyForm" action="{{ route('user.buycourse') }}" method="POST" class="hidden">
        @csrf
        <input type="hidden" name="course_id" value="{{ $course->id }}">
    </form>

@endsection

@section('scripts')
@parent
<script>
    function confirmBuy() {
        Swal.fire({
            title: 'Purchase Course?',
            text: '{{ !$course->amount ? \App\Helpers\CurrencyHelper::formatForUser(0) : \App\Helpers\CurrencyHelper::formatForUser($course->amount) }} will be deducted from your account balance.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#2E5C8A',
            cancelButtonColor: '#64748B',
            confirmButtonText: 'Confirm Purchase',
            cancelButtonText: 'Cancel',
            background: '#FFFFFF',
            color: '#0F1B2D'
        }).then((result) => {
            if (result.isConfirmed) document.getElementById('buyForm').submit();
        });
    }
</script>
@endsection
