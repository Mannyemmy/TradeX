@extends('layouts.admin-dash')
@section('title', $title)

@section('content')

    <x-admin.page-header :title="$title">
        <x-slot name="actions">
            <button type="button" onclick="$('#addAssetModal').removeClass('hidden').addClass('flex')"
                    class="inline-flex items-center gap-2 bg-primary text-primary-foreground rounded-lg px-4 py-2 text-sm font-medium hover:bg-primary-hover transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                Add Custom Asset
            </button>
            <a href="{{ route('admin.assets.refresh') }}"
               class="inline-flex items-center gap-2 bg-info text-content-inverse rounded-lg px-4 py-2 text-sm font-medium hover:opacity-90 transition-opacity"
               onclick="this.innerHTML='<svg class=\'w-4 h-4 animate-spin\' fill=\'none\' viewBox=\'0 0 24 24\'><circle class=\'opacity-25\' cx=\'12\' cy=\'12\' r=\'10\' stroke=\'currentColor\' stroke-width=\'4\'></circle><path class=\'opacity-75\' fill=\'currentColor\' d=\'M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z\'></path></svg> Refreshing...'; this.disabled=true; return true;">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" /></svg>
                Refresh Prices
            </a>
        </x-slot>
    </x-admin.page-header>

    {{-- Asset Class Tabs --}}
    <div x-data="{ activeTab: '{{ $assetClasses[0] ?? '' }}' }" class="mt-6">
        <div class="flex gap-1 border-b border-border">
            @foreach($assetClasses as $i => $class)
                <button @click="activeTab = '{{ $class }}'"
                        :class="activeTab === '{{ $class }}'
                            ? 'border-primary text-primary'
                            : 'border-transparent text-content-muted hover:text-content-secondary hover:border-border-strong'"
                        class="px-4 py-2.5 text-sm font-medium border-b-2 transition-colors -mb-px flex items-center gap-2">
                    {{ ucfirst($class) }}
                    <span class="bg-surface-alt text-content-muted text-xs font-medium px-2 py-0.5 rounded-full">{{ isset($grouped[$class]) ? $grouped[$class]->count() : 0 }}</span>
                </button>
            @endforeach
        </div>

        {{-- Tab Panels --}}
        @foreach($assetClasses as $i => $class)
            <div x-show="activeTab === '{{ $class }}'" x-transition class="mt-5">
                @if(isset($grouped[$class]) && $grouped[$class]->count())
                    <x-admin.table-card>
                        <table class="w-full asset-table">
                            <thead>
                                <tr>
                                    <th class="bg-surface-alt px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left border-b border-border w-10"></th>
                                    <th class="bg-surface-alt px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left border-b border-border">Name</th>
                                    <th class="bg-surface-alt px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left border-b border-border">Symbol</th>
                                    <th class="bg-surface-alt px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left border-b border-border">Price</th>
                                    <th class="bg-surface-alt px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left border-b border-border">24h Change</th>
                                    <th class="bg-surface-alt px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left border-b border-border">Source</th>
                                    <th class="bg-surface-alt px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left border-b border-border">Active</th>
                                    <th class="bg-surface-alt px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left border-b border-border">Last Updated</th>
                                    <th class="bg-surface-alt px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-right border-b border-border">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($grouped[$class] as $asset)
                                    <tr class="hover:bg-surface-alt/50 transition-colors">
                                        <td class="px-4 py-3.5 border-b border-border">
                                            @if($asset->logo_url)
                                                <img src="{{ $asset->logo_url }}" alt="{{ $asset->symbol }}"
                                                     class="w-6 h-6 rounded-full object-cover">
                                            @else
                                                <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-surface-alt text-xs font-medium text-content-muted">{{ substr($asset->symbol, 0, 2) }}</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3.5 text-sm text-content border-b border-border">{{ $asset->name }}</td>
                                        <td class="px-4 py-3.5 text-sm font-semibold text-content border-b border-border">{{ $asset->symbol }}</td>
                                        <td class="px-4 py-3.5 text-sm text-content border-b border-border">{{ $asset->formatted_price }}</td>
                                        <td class="px-4 py-3.5 text-sm border-b border-border">
                                            @if($asset->price_change_pct_24h !== null)
                                                <span class="{{ $asset->price_change_pct_24h >= 0 ? 'text-success' : 'text-danger' }} font-medium">
                                                    {{ $asset->formatted_change }}
                                                </span>
                                            @else
                                                <span class="text-content-muted">—</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3.5 text-sm border-b border-border">
                                            <x-admin.badge :type="$asset->data_source === 'manual' ? 'warning' : 'info'">{{ $asset->data_source }}</x-admin.badge>
                                        </td>
                                        <td class="px-4 py-3.5 border-b border-border">
                                            <label class="relative inline-flex items-center cursor-pointer">
                                                <input type="checkbox" class="sr-only peer toggle-active" data-id="{{ $asset->id }}" {{ $asset->is_active ? 'checked' : '' }}>
                                                <div class="w-9 h-5 bg-surface-alt peer-focus:ring-2 peer-focus:ring-primary/30 rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-content-inverse after:border-border after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-primary"></div>
                                            </label>
                                        </td>
                                        <td class="px-4 py-3.5 text-xs text-content-muted border-b border-border">
                                            {{ $asset->updated_at ? $asset->updated_at->diffForHumans() : '—' }}
                                        </td>
                                        <td class="px-4 py-3.5 text-right border-b border-border">
                                            <div class="flex items-center justify-end gap-2">
                                                <a href="{{ route('admin.assets.edit', $asset->id) }}"
                                                   class="inline-flex items-center gap-1 text-xs font-medium text-primary hover:text-primary-hover transition-colors">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" /></svg>
                                                    Edit
                                                </a>
                                                <form action="{{ route('admin.assets.destroy', $asset->id) }}" method="POST" class="delete-asset-form inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="delete-asset-btn inline-flex items-center gap-1 text-xs font-medium text-danger hover:text-red-400 transition-colors">
                                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" /></svg>
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </x-admin.table-card>
                @else
                    <x-admin.card class="text-center py-12">
                        <svg class="w-10 h-10 mx-auto mb-3 text-content-muted/50" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" /></svg>
                        <p class="text-sm text-content-muted">No {{ $class }} assets found. Run price refresh or add assets manually.</p>
                    </x-admin.card>
                @endif
            </div>
        @endforeach
    </div>

    {{-- Add Custom Asset Modal --}}
    <div id="addAssetModal" class="fixed inset-0 z-[60] hidden items-center justify-center p-4">
        <div class="absolute inset-0 bg-surface-overlay/60" onclick="$('#addAssetModal').removeClass('flex').addClass('hidden')"></div>
        <div class="relative w-full max-w-lg bg-surface-card rounded-2xl shadow-xl overflow-hidden">
            <div class="px-6 py-4 border-b border-border flex items-center justify-between">
                <h3 class="text-lg font-semibold text-content">Add Custom Asset</h3>
                <button type="button" onclick="$('#addAssetModal').removeClass('flex').addClass('hidden')" class="text-content-muted hover:text-content transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
            <div class="px-6 py-5">
                <form action="{{ route('admin.assets.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <x-admin.form-group label="Name" for="name" required>
                        <input type="text" name="name" id="name" required placeholder="e.g. Custom Token"
                               class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                    </x-admin.form-group>
                    <x-admin.form-group label="Symbol" for="symbol" required>
                        <input type="text" name="symbol" id="symbol" required placeholder="e.g. CTKN"
                               class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                    </x-admin.form-group>
                    <x-admin.form-group label="Asset Class" for="asset_class" required>
                        <select name="asset_class" id="asset_class" required
                                class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                            <option value="crypto">Crypto</option>
                            <option value="forex">Forex</option>
                            <option value="stock">Stock</option>
                            <option value="etf">ETF</option>
                            <option value="index">Index</option>
                        </select>
                    </x-admin.form-group>
                    <x-admin.form-group label="Initial Price" for="price">
                        <input type="number" name="price" id="price" step="0.00000001" min="0" placeholder="0.00"
                               class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                    </x-admin.form-group>
                    <x-admin.form-group label="Logo URL" for="logo_url" helper="Optional — paste a direct image URL">
                        <input type="url" name="logo_url" id="logo_url" placeholder="https://..."
                               class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                    </x-admin.form-group>
                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" onclick="$('#addAssetModal').removeClass('flex').addClass('hidden')"
                                class="bg-secondary-light text-content-secondary rounded-lg px-4 py-2 text-sm font-medium hover:bg-surface-alt transition-colors">
                            Cancel
                        </button>
                        <button type="submit"
                                class="bg-primary text-primary-foreground rounded-lg px-4 py-2 text-sm font-medium hover:bg-primary-hover transition-colors">
                            Add Asset
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('.asset-table').DataTable({
        paging: false,
        info: false,
        order: [[1, 'asc']]
    });

    // Toggle active status
    $('.toggle-active').on('change', function() {
        var assetId = $(this).data('id');
        var checkbox = $(this);

        $.ajax({
            url: '/admin/assets/' + assetId + '/toggle',
            type: 'POST',
            data: { _token: '{{ csrf_token() }}' },
            success: function(response) {
                if (!response.success) {
                    checkbox.prop('checked', !checkbox.prop('checked'));
                }
            },
            error: function() {
                checkbox.prop('checked', !checkbox.prop('checked'));
            }
        });
    });

    // Delete asset with confirmation
    $('.delete-asset-btn').on('click', function() {
        var form = $(this).closest('form');
        Swal.fire({
            title: 'Delete this asset?',
            text: 'This action cannot be undone. Assets with open trades cannot be deleted.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#EF4444',
            cancelButtonColor: '#64748B',
            confirmButtonText: 'Yes, delete it',
            background: '#1E293B',
            color: '#E2E8F0'
        }).then(function(result) {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});
</script>
@endpush
