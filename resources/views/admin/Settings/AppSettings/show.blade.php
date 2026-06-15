@extends('layouts.admin-dash')
@section('title', 'App Settings')

@section('content')
    {{-- Page Header --}}
    <x-admin.page-header title="App Settings" subtitle="Configure your website information, preferences, email, and integrations." />

    {{-- Validation Errors --}}
    @if (count($errors) > 0)
        <x-admin.alert type="danger" :dismissible="true">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </x-admin.alert>
    @endif

    {{-- Tabs --}}
    <div x-data="{ activeTab: 'info' }" class="mt-6">
        {{-- Tab Navigation --}}
        <div class="flex gap-1 border-b border-border overflow-x-auto">
            <button @click="activeTab = 'module'"
                :class="activeTab === 'module' ? 'border-primary text-primary' : 'border-transparent text-content-muted hover:text-content-secondary hover:border-border-strong'"
                class="px-4 py-2.5 text-sm font-medium border-b-2 transition-colors whitespace-nowrap -mb-px">
                Modules
            </button>
            <button @click="activeTab = 'info'"
                :class="activeTab === 'info' ? 'border-primary text-primary' : 'border-transparent text-content-muted hover:text-content-secondary hover:border-border-strong'"
                class="px-4 py-2.5 text-sm font-medium border-b-2 transition-colors whitespace-nowrap -mb-px">
                Website Information
            </button>
            <button @click="activeTab = 'pref'"
                :class="activeTab === 'pref' ? 'border-primary text-primary' : 'border-transparent text-content-muted hover:text-content-secondary hover:border-border-strong'"
                class="px-4 py-2.5 text-sm font-medium border-b-2 transition-colors whitespace-nowrap -mb-px">
                Preferences
            </button>
            <button @click="activeTab = 'email'"
                :class="activeTab === 'email' ? 'border-primary text-primary' : 'border-transparent text-content-muted hover:text-content-secondary hover:border-border-strong'"
                class="px-4 py-2.5 text-sm font-medium border-b-2 transition-colors whitespace-nowrap -mb-px">
                Email / Auth
            </button>
            <button @click="activeTab = 'display'"
                :class="activeTab === 'display' ? 'border-primary text-primary' : 'border-transparent text-content-muted hover:text-content-secondary hover:border-border-strong'"
                class="px-4 py-2.5 text-sm font-medium border-b-2 transition-colors whitespace-nowrap -mb-px">
                Theme / Display
            </button>
            <button @click="activeTab = 'apiconfig'"
                :class="activeTab === 'apiconfig' ? 'border-primary text-primary' : 'border-transparent text-content-muted hover:text-content-secondary hover:border-border-strong'"
                class="px-4 py-2.5 text-sm font-medium border-b-2 transition-colors whitespace-nowrap -mb-px">
                API Configuration
            </button>
        </div>

        {{-- Tab Panels --}}
        <div class="mt-6">
            {{-- Module Tab --}}
            <div x-show="activeTab === 'module'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                <livewire:admin.software-module />
            </div>

            {{-- Website Information Tab --}}
            <div x-show="activeTab === 'info'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                @include('admin.Settings.AppSettings.webinfo')
            </div>

            {{-- Preferences Tab --}}
            <div x-show="activeTab === 'pref'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                @include('admin.Settings.AppSettings.webpreference')
            </div>

            {{-- Email / Auth Tab --}}
            <div x-show="activeTab === 'email'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                @include('admin.Settings.AppSettings.email')
            </div>

            {{-- Theme / Display Tab --}}
            <div x-show="activeTab === 'display'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                @include('admin.Settings.AppSettings.theme')
            </div>

            {{-- API Config Tab --}}
            <div x-show="activeTab === 'apiconfig'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                @include('admin.Settings.AppSettings.apiconfig')
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Select2 initialization
    $(document).ready(function() {
        $('.select2').select2({
            width: '100%'
        });
    });

    // Theme form submit handler
    document.getElementById("themeForm")?.addEventListener('submit', function(){
        document.getElementById("themeBtn").disabled = true;
        var element = document.getElementById("loadingTheme");
        element.classList.remove("hidden");
    });

    // Currency select handler
    function changecurr() {
        var e = document.getElementById("select_c");
        var selected = e.options[e.selectedIndex].id;
        document.getElementById("s_c").value = selected;
    }

    // AJAX: Preferences form
    $('#updatepreference').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: "{{ route('updatepreference') }}",
            type: 'POST',
            data: $('#updatepreference').serialize(),
            success: function(response) {
                if (response.status === 200) {
                    Swal.fire({ icon: 'success', title: 'Success', text: response.success, timer: 3000, showConfirmButton: false });
                }
            },
            error: function(error) {
                Swal.fire({ icon: 'error', title: 'Error', text: 'Something went wrong.' });
            },
        });
    });

    // SMTP toggle logic
    let sendmail = document.querySelector('#sendmailserver');
    let smtp = document.querySelector('#smtpserver');
    let smtptext = document.querySelectorAll('.smtp-field');

    if (sendmail && smtp) {
        sendmail.addEventListener('click', sortform);
        smtp.addEventListener('click', sortform);

        if (smtp.checked) {
            smtptext.forEach(el => { el.classList.remove('hidden'); el.setAttribute('required', ''); });
        }

        function sortform() {
            if (sendmail.checked) {
                smtptext.forEach(el => { el.classList.add('hidden'); el.removeAttribute('required'); });
            }
            if (smtp.checked) {
                smtptext.forEach(el => { el.classList.remove('hidden'); el.setAttribute('required', ''); });
            }
        }
    }

    // AJAX: Email settings form
    $('#emailform').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: "{{ route('updateemailpreference') }}",
            type: 'POST',
            data: $('#emailform').serialize(),
            success: function(response) {
                if (response.status === 200) {
                    Swal.fire({ icon: 'success', title: 'Success', text: response.success, timer: 3000, showConfirmButton: false });
                }
            },
            error: function(error) {
                Swal.fire({ icon: 'error', title: 'Error', text: 'Something went wrong.' });
            },
        });
    });
</script>
@endpush
