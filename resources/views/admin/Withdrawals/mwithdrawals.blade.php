@extends('layouts.admin-dash')
@section('title', 'Manage Withdrawals')
@section('content')
    <x-admin.page-header title="Manage Withdrawals" subtitle="Review and process client withdrawal requests" />

    <div class="mt-6">
        <x-admin.table-card>
            <table id="ShipTable" class="display w-full">
                <thead>
                    <tr>
                        <th>Client name</th>
                        <th>Amount requested</th>
                        <th>Amount + charges</th>
                        <th>Payment Method</th>
                        <th>Receiver's email</th>
                        <th>Status</th>
                        <th>Date created</th>
                        <th>Option</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($withdrawals as $deposit)
                        <tr>
                            <td>{{ optional($deposit->duser)->name ?? 'N/A' }}</td>
                            <td>{{ $settings->currency }}{{ number_format($deposit->amount ?? 0, 2) }}</td>
                            <td>{{ $settings->currency }}{{ number_format($deposit->to_deduct ?? 0, 2) }}</td>
                            <td>{{ $deposit->payment_mode ?? 'N/A' }}</td>
                            <td>{{ optional($deposit->duser)->email ?? 'N/A' }}</td>
                            <td>
                                @if ($deposit->status == 'Processed')
                                    <x-admin.badge type="success">{{ $deposit->status }}</x-admin.badge>
                                @else
                                    <x-admin.badge type="danger">{{ $deposit->status }}</x-admin.badge>
                                @endif
                            </td>
                            <td>{{ $deposit->created_at ? \Carbon\Carbon::parse($deposit->created_at)->toDayDateTimeString() : 'N/A' }}</td>
                            <td>
                                <a href="{{ route('processwithdraw', $deposit->id) }}"
                                   class="inline-flex items-center gap-1.5 bg-info text-content-inverse hover:bg-info/80 rounded-lg px-3 py-1.5 text-xs font-medium transition-colors">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                    View
                                </a>
                                <a href="{{ route('admin.withdrawals.edit', $deposit->id) }}"
                                   class="inline-flex items-center gap-1.5 bg-warning text-content-inverse hover:bg-warning/80 rounded-lg px-3 py-1.5 text-xs font-medium transition-colors"
                                   title="Edit / Backdate">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125" /></svg>
                                    Edit
                                </a>
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
        order: [[6, 'desc']],
        language: { search: '', searchPlaceholder: 'Search...' }
    });
});
</script>
@endpush
