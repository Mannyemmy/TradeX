@extends('layouts.admin-dash')
@section('title', $user->name . ' Investment Plans')
@section('content')
    <x-admin.page-header title="{{ $user->name }} Investment Plans">
        <x-slot name="actions">
            <a class="inline-flex items-center gap-1.5 bg-primary text-primary-foreground hover:bg-primary-hover rounded-lg px-3 py-1.5 text-xs font-medium transition-colors" href="{{ route('viewuser', $user->id) }}">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
                back
            </a>
        </x-slot>
    </x-admin.page-header>

    <div class="mt-6">
        <x-admin.table-card>
            <table id="ShipTable" class="display w-full">
                <thead>
                    <tr>
                        <th>Plan</th>
                        <th>Amount</th>
                        <th>Active</th>
                        <th>Duration</th>
                        <th>Created on</th>
                        <th>Expire At</th>
                        <th>Option</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($plans as $plan)
                        <tr>
                            <td>{{ $plan->dplan->name }}</td>
                            <td>{{ $settings->currency }}{{ number_format($plan->amount) }}</td>
                            <td>
                                @if ($plan->active == 'yes')
                                    <x-admin.badge type="success">{{ $plan->active }}</x-admin.badge>
                                @else
                                    <x-admin.badge type="danger">{{ $plan->active }}</x-admin.badge>
                                @endif
                            </td>
                            <td>{{ $plan->inv_duration }}</td>
                            <td>{{ \Carbon\Carbon::parse($plan->created_at)->toDayDateTimeString() }}</td>
                            <td>{{ \Carbon\Carbon::parse($plan->expire_date)->toDayDateTimeString() }}</td>
                            <td>
                                <div class="flex items-center gap-1">
                                    <a href="{{ route('deleteplan', $plan->id) }}"
                                       class="inline-flex items-center gap-1.5 bg-info text-content-inverse hover:bg-info/80 rounded-lg px-3 py-1.5 text-xs font-medium transition-colors">
                                        Delete Plan
                                    </a>
                                    <a href="{{ route('admin.investments.edit', $plan->id) }}"
                                       class="inline-flex items-center gap-1.5 bg-warning text-content-inverse hover:bg-warning/80 rounded-lg px-3 py-1.5 text-xs font-medium transition-colors"
                                       title="Edit / Backdate">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125" /></svg>
                                        Edit
                                    </a>
                                    @if ($plan->active == 'yes')
                                        <a href="{{ route('markas', ['id' => $plan->id, 'status' => 'expired']) }}"
                                           class="inline-flex items-center gap-1.5 bg-danger text-content-inverse hover:bg-danger/80 rounded-lg px-3 py-1.5 text-xs font-medium transition-colors">
                                            Mark as expired
                                        </a>
                                    @else
                                        <a href="{{ route('markas', ['id' => $plan->id, 'status' => 'yes']) }}"
                                           class="inline-flex items-center gap-1.5 bg-success text-content-inverse hover:bg-success/80 rounded-lg px-3 py-1.5 text-xs font-medium transition-colors">
                                            Mark as active
                                        </a>
                                    @endif
                                </div>
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
        order: [[0, 'desc']],
        language: { search: '', searchPlaceholder: 'Search...' }
    });
});
</script>
@endpush
