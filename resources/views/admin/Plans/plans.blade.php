@extends('layouts.admin-dash')
@section('title', 'Investment Plans')
@section('content')
    <x-admin.page-header title="Investment Plans" subtitle="Manage system investment plans">
        <x-slot name="actions">
            <a href="{{ route('newplan') }}"
               class="inline-flex items-center gap-1.5 bg-primary text-primary-foreground hover:bg-primary-hover rounded-lg px-4 py-2 text-sm font-medium transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                New plan
            </a>
        </x-slot>
    </x-admin.page-header>

    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($plans as $plan)
            <x-admin.card :hover="true">
                <div class="space-y-4">
                    {{-- Plan Name & Tag --}}
                    <div>
                        <h2 class="text-xl font-semibold text-content">
                            {{ $plan->name }}
                            @if($plan->tag)
                                <x-admin.badge type="success">{{ $plan->tag }}</x-admin.badge>
                            @endif
                        </h2>
                    </div>

                    {{-- Price --}}
                    <div class="text-3xl font-bold text-primary">
                        {{ $settings->currency }}{{ number_format($plan->price) }}
                    </div>

                    {{-- Features --}}
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between text-content">
                            <span class="text-content-secondary">Min Deposit</span>
                            <span class="font-medium">{{ $settings->currency }}{{ number_format($plan->min_price) }}</span>
                        </div>
                        <div class="flex justify-between text-content">
                            <span class="text-content-secondary">Max Deposit</span>
                            <span class="font-medium">{{ $settings->currency }}{{ number_format($plan->max_price) }}</span>
                        </div>
                        <div class="flex justify-between text-content">
                            <span class="text-content-secondary">Min Return</span>
                            <span class="font-medium">{{ number_format($plan->minr) }}%</span>
                        </div>
                        <div class="flex justify-between text-content">
                            <span class="text-content-secondary">Max Return</span>
                            <span class="font-medium">{{ number_format($plan->maxr) }}%</span>
                        </div>
                        <div class="flex justify-between text-content">
                            <span class="text-content-secondary">Gift Bonus</span>
                            <span class="font-medium">{{ $settings->currency }}{{ $plan->gift }}</span>
                        </div>
                        <div class="flex justify-between text-content">
                            <span class="text-content-secondary">Duration</span>
                            <span class="font-medium">{{ $plan->expiration }}</span>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex items-center justify-center gap-3 pt-3 border-t border-border">
                        <a href="{{ route('editplan', $plan->id) }}"
                           class="inline-flex items-center gap-1.5 bg-primary text-primary-foreground hover:bg-primary-hover rounded-lg px-3 py-1.5 text-xs font-medium transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" /></svg>
                            Edit
                        </a>
                        <a href="{{ url('admin/dashboard/trashplan') }}/{{ $plan->id }}"
                           class="inline-flex items-center gap-1.5 bg-danger text-content-inverse hover:bg-danger/80 rounded-lg px-3 py-1.5 text-xs font-medium transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                            Delete
                        </a>
                    </div>
                </div>
            </x-admin.card>
        @empty
            <div class="col-span-full">
                <x-admin.card>
                    <p class="text-center text-content-secondary text-lg py-8">
                        No Investment Plan at the moment, click the button above to add a plan.
                    </p>
                </x-admin.card>
            </div>
        @endforelse
    </div>
@endsection
