@extends('layouts.admin-dash')
@section('title', 'Active Investments')
@section('content')
    <x-admin.page-header title="Active Investments" subtitle="Monitor currently active investment plans" />

    <div class="mt-6">
        <x-admin.table-card>
            <table id="ShipTable" class="display w-full">
                <thead>
                    <tr>
                        <th>Client name</th>
                        <th>Investment Plan</th>
                        <th>Amount Invested</th>
                        <th>Duration</th>
                        <th>ROI</th>
                        <th>Start Date</th>
                        <th>Expiration Date</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($plans as $plan)
                        <tr>
                            <td>{{ $plan->duser->name }}</td>
                            <td>{{ $plan->dplan->name }}</td>
                            <td>{{ $settings->currency }}{{ number_format($plan->amount) }}</td>
                            <td>{{ $plan->inv_duration }}</td>
                            <td>{{ $settings->currency }}{{ $plan->profit_earned ? $plan->profit_earned : '0' }}</td>
                            <td>{{ $plan->created_at->toDayDateTimeString() }}</td>
                            <td>{{ \Carbon\Carbon::parse($plan->expire_date)->toDayDateTimeString() }}</td>
                            <td>
                                <x-admin.dropdown>
                                    <x-slot name="trigger">
                                        <button type="button" class="inline-flex items-center gap-1.5 bg-surface-alt text-content hover:bg-surface-card rounded-lg px-3 py-1.5 text-xs font-medium transition-colors border border-border">
                                            Action
                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" /></svg>
                                        </button>
                                    </x-slot>
                                    <a class="block px-4 py-2 text-sm text-danger hover:bg-surface-alt transition-colors" href="{{ route('deleteplan', $plan->id) }}">Delete</a>
                                    <a class="block px-4 py-2 text-sm text-content hover:bg-surface-alt transition-colors" href="{{ route('user.plans', $plan->duser->id) }}">More actions</a>
                                </x-admin.dropdown>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </x-admin.table-card>
    </div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#ShipTable').DataTable({
        responsive: true,
        pageLength: 25,
        order: [[5, 'desc']],
        language: { search: '', searchPlaceholder: 'Search...' }
    });
});
</script>
@endpush
