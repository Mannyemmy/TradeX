@extends('layouts.admin-dash')
@section('title', 'MT4 Subscription Settings')

@section('content')
    {{-- Page Header --}}
    <x-admin.page-header title="MT4 Subscription Settings" subtitle="Configure subscription fees for MT4 services." />

    {{-- Validation Errors --}}
    @if (count($errors) > 0)
        <x-admin.alert type="danger" :dismissible="true">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </x-admin.alert>
    @endif

    {{-- Subscription Form --}}
    <div class="max-w-2xl mx-auto mt-6">
        <x-admin.card>
            <form method="post" action="javascript:void(0)" id="subform">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <x-admin.form-group label="Monthly Subscription Fee" for="monthlyfee">
                        <input type="text" name="monthlyfee" id="monthlyfee" value="{{ $settings->monthlyfee }}"
                            class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                    </x-admin.form-group>

                    <x-admin.form-group label="Quarterly Subscription Fee" for="quaterlyfee">
                        <input type="text" name="quaterlyfee" id="quaterlyfee" value="{{ $settings->quarterlyfee }}"
                            class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                    </x-admin.form-group>

                    <x-admin.form-group label="Yearly Subscription Fee" for="yearlyfee">
                        <input type="text" name="yearlyfee" id="yearlyfee" value="{{ $settings->yearlyfee }}"
                            class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                    </x-admin.form-group>

                    <div>
                        <button type="submit"
                            class="bg-primary text-primary-foreground hover:bg-primary-hover rounded-lg px-4 py-2 text-sm font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-primary/30">
                            Save
                        </button>
                        <input type="hidden" name="id" value="1">
                    </div>
                </div>
            </form>
        </x-admin.card>
    </div>
@endsection

@push('scripts')
<script>
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

    $('#subform').on('submit', function() {
        $.ajax({
            url: "{{ route('updatesubfee') }}",
            type: 'POST',
            data: $('#subform').serialize(),
            success: function(response) {
                if (response.status === 200) {
                    showToast(response.success, 'success');
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
