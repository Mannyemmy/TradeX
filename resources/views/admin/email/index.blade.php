@extends('layouts.admin-dash')
@section('title', 'Send Email')

@section('content')
    {{-- Page Header --}}
    <x-admin.page-header title="Send Email to Users" subtitle="Compose and send bulk or targeted emails to users." />

    {{-- Flash Messages --}}
    @if (session('success'))
        <x-admin.alert type="success" :dismissible="true" class="mt-4">
            {{ session('success') }}
        </x-admin.alert>
    @endif

    @if (session('error'))
        <x-admin.alert type="danger" :dismissible="true" class="mt-4">
            {{ session('error') }}
        </x-admin.alert>
    @endif

    @if (count($errors) > 0)
        <x-admin.alert type="danger" :dismissible="true" class="mt-4">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </x-admin.alert>
    @endif

    {{-- Email Form --}}
    <div class="mt-6 max-w-3xl mx-auto" x-data="emailForm()">
        <x-admin.card>
            <form method="post" action="{{ route('sendmailtoall') }}">
                @csrf

                <div class="space-y-5">
                    {{-- Category --}}
                    <x-admin.form-group label="Category" for="category" :required="true">
                        <select id="category" name="category" x-model="category" @change="onCategoryChange()"
                            class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                            <option value="All">All Users</option>
                            <option value="No active plans">Users without active investment plan</option>
                            <option value="No deposit">Users without any Deposit (likely to be new users)</option>
                            <option value="Select Users">Choose Users</option>
                        </select>
                    </x-admin.form-group>

                    {{-- Select Users (shown conditionally) --}}
                    <div x-show="category === 'Select Users'" x-transition x-cloak>
                        <x-admin.form-group label="" for="showusers">
                            <template x-if="category === 'Select Users'">
                                <div>
                                    <label class="block text-sm font-medium text-content mb-1.5">
                                        Select Users (<span class="text-primary font-semibold" x-text="selectedCount">0</span>)
                                    </label>
                                    <select @change="updateCount($event)" name="users[]" multiple
                                        class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary select2"
                                        style="width: 100%" id="showusers"></select>
                                </div>
                            </template>
                        </x-admin.form-group>
                    </div>

                    {{-- Greeting / Title --}}
                    <x-admin.form-group label="Greeting / Title">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <input type="text" aria-label="Hello" value="Hello" name="greet"
                                class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary"
                                placeholder="Greeting (e.g. Hello)">
                            <input type="text" aria-label="Investor" value="Investor" name="title"
                                class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary"
                                placeholder="Title (e.g. Investor)">
                        </div>
                    </x-admin.form-group>

                    {{-- Subject --}}
                    <x-admin.form-group label="Subject" for="subject" :required="true">
                        <input id="subject" type="text" name="subject" placeholder="Email subject line" required
                            class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                    </x-admin.form-group>

                    {{-- Message --}}
                    <x-admin.form-group label="Message" for="message" :required="true">
                        <textarea id="message" name="message" rows="8" placeholder="Type your message here" required
                            class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary ckeditor"></textarea>
                    </x-admin.form-group>

                    {{-- Submit --}}
                    <div>
                        <button type="submit"
                            class="bg-primary text-primary-foreground hover:bg-primary-hover rounded-lg px-5 py-2.5 text-sm font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-primary/30 inline-flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" /></svg>
                            Send Email
                        </button>
                    </div>
                </div>
            </form>
        </x-admin.card>
    </div>
@endsection

@section('scripts')
    <script src="//cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
    <script>
        function emailForm() {
            return {
                category: 'All',
                selectedCount: 0,
                usersFetched: false,

                onCategoryChange() {
                    if (this.category === 'Select Users' && !this.usersFetched) {
                        this.fetchUsers();
                    }
                },

                updateCount(event) {
                    let count = 0;
                    const options = event.target.options;
                    for (let i = 0; i < options.length; i++) {
                        if (options[i].selected) count++;
                    }
                    this.selectedCount = count;
                },

                fetchUsers() {
                    const usersSelect = document.querySelector('#showusers');
                    fetch("{{ route('fetchusers') }}")
                        .then(response => response.json())
                        .then(data => {
                            data.data.forEach(element => {
                                const opt = document.createElement('option');
                                opt.value = element.id;
                                opt.textContent = element.name;
                                usersSelect.appendChild(opt);
                            });
                            this.usersFetched = true;
                            if (typeof $ !== 'undefined') {
                                $('.select2').select2();
                            }
                        });
                }
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            if (typeof $ !== 'undefined' && $.fn.select2) {
                $('.select2').select2();
            }
            if (typeof CKEDITOR !== 'undefined') {
                CKEDITOR.replace('message');
            }
        });
    </script>
@endsection
