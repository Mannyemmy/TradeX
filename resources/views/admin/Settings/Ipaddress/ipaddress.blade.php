@extends('layouts.admin-dash')
@section('title', 'IP Address Blacklist')

@section('content')
    {{-- Page Header --}}
    <x-admin.page-header title="IP Address Blacklist" subtitle="Block specific IP addresses from accessing your website." />

    {{-- Add IP Form --}}
    <x-admin.card class="mb-6">
        <div class="max-w-xl mx-auto">
            <form method="post" action="javascript:void(0)" id="ipform">
                @csrf
                <x-admin.form-group label="IP Address" for="ipaddress" helper="This IP Address won't be able to access your website.">
                    <input type="text" name="ipaddress" id="ipaddress"
                        class="w-full bg-surface-card border border-border rounded-lg px-3.5 py-2.5 text-sm text-content placeholder:text-content-muted focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary transition-colors">
                </x-admin.form-group>
                <div class="mt-4">
                    <button type="submit"
                        class="bg-primary text-primary-foreground hover:bg-primary-hover rounded-lg px-5 py-2.5 text-sm font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-primary/30">
                        Blacklist
                    </button>
                </div>
            </form>
        </div>
    </x-admin.card>

    {{-- Blacklisted IPs Table --}}
    <x-admin.card padding="p-0">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr>
                        <th class="bg-surface-alt px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left border-b border-border">IP Address</th>
                        <th class="bg-surface-alt px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left border-b border-border">Date Blacklisted</th>
                        <th class="bg-surface-alt px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left border-b border-border">Actions</th>
                    </tr>
                </thead>
                <tbody id="showipaddress">
                </tbody>
            </table>
        </div>
    </x-admin.card>
@endsection

@push('scripts')
<script>
    let textinput = document.getElementById('ipaddress');
    getallips();

    function showToast(message, type = 'success') {
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: type,
            title: message,
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            background: type === 'success' ? 'rgb(var(--success-light))' : 'rgb(var(--danger-light))',
            color: type === 'success' ? 'rgb(var(--success))' : 'rgb(var(--danger))',
        });
    }

    function getallips() {
        fetch("{{ route('allipaddress') }}")
            .then(res => res.json())
            .then(response => {
                if (response.status === 200) {
                    document.getElementById('showipaddress').innerHTML = response.data;
                }
            })
            .catch(err => console.log(err));
    }

    function deleteip(id) {
        fetch("{{ url('admin/dashboard/delete-ip') }}" + '/' + id)
            .then(res => res.json())
            .then(response => {
                if (response.status === 200) {
                    showToast(response.success, 'success');
                    getallips();
                }
            })
            .catch(err => console.log(err));
    }

    $('#ipform').on('submit', function() {
        $.ajax({
            url: "{{ route('addipaddress') }}",
            type: 'POST',
            data: $('#ipform').serialize(),
            success: function(response) {
                if (response.status === 200) {
                    textinput.value = "";
                    showToast(response.success, 'success');
                    getallips();
                }
            },
            error: function(error) {
                showToast('An error occurred. Please try again.', 'error');
                console.log(error);
            },
        });
    });
</script>
@endpush
