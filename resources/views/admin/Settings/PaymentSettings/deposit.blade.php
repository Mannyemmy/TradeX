{{-- Payment Methods Tab --}}
<div x-data="{ showAddModal: false, methodType: 'currency' }">
    {{-- Header --}}
    <div class="flex items-center justify-between mb-5">
        <h3 class="text-lg font-semibold text-content">Payment Methods</h3>
        <button @click="showAddModal = true"
            class="bg-primary text-primary-foreground hover:bg-primary-hover rounded-lg px-4 py-2 text-sm font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-primary/30 inline-flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
            Add New
        </button>
    </div>

    {{-- Add Payment Method Modal --}}
    <div x-show="showAddModal" x-cloak class="fixed inset-0 z-[60] flex items-center justify-center p-4">
        <div x-show="showAddModal" x-transition.opacity class="absolute inset-0 bg-surface-overlay/60" @click="showAddModal = false"></div>
        <div x-show="showAddModal" x-transition class="relative w-full max-w-2xl bg-surface-card rounded-2xl shadow-xl overflow-hidden max-h-[90vh] flex flex-col">
            {{-- Modal Header --}}
            <div class="px-6 py-4 border-b border-border flex items-center justify-between shrink-0">
                <h3 class="text-lg font-semibold text-content">Add New Payment Method</h3>
                <button @click="showAddModal = false" class="text-content-muted hover:text-content transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
            {{-- Modal Body --}}
            <div class="px-6 py-5 overflow-y-auto">
                <form method="POST" action="{{ route('addpaymethod') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <x-admin.form-group label="Name" for="name" class="md:col-span-2" :required="true">
                            <input type="text" name="name" id="name" placeholder="Payment method name" required
                                class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                        </x-admin.form-group>

                        <x-admin.form-group label="Minimum Amount" for="minamount" helper="Required but only applies to withdrawal" :required="true">
                            <input type="number" name="minimum" id="minamount" required
                                class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                        </x-admin.form-group>

                        <x-admin.form-group label="Maximum Amount" for="maxamount" helper="Required but only applies to withdrawal" :required="true">
                            <input type="number" name="maximum" id="maxamount" required
                                class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                        </x-admin.form-group>

                        <x-admin.form-group label="Charges" for="charges" helper="Required but only applies to withdrawal" :required="true">
                            <input type="number" name="charges" id="charges" required
                                class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                        </x-admin.form-group>

                        <x-admin.form-group label="Charges Type" for="chargetype" helper="Required but only applies to withdrawal">
                            <select name="chargetype"
                                class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                                <option value="percentage">Percentage(%)</option>
                                <option value="fixed">Fixed({{ $settings->currency }})</option>
                            </select>
                        </x-admin.form-group>

                        <x-admin.form-group label="Type" for="methodtype" :required="true">
                            <select name="methodtype" id="methodtype" required
                                x-model="methodType"
                                class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                                <option value="currency">Currency</option>
                                <option value="crypto">Crypto</option>
                            </select>
                        </x-admin.form-group>

                        <x-admin.form-group label="Image URL (Logo)" for="url">
                            <input type="text" name="url" id="url"
                                class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                        </x-admin.form-group>

                        {{-- Currency inputs --}}
                        <template x-if="methodType === 'currency'">
                            <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                                <x-admin.form-group label="Bank Name" for="bank">
                                    <input type="text" name="bank" id="bank" required
                                        class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary currinput">
                                </x-admin.form-group>
                                <x-admin.form-group label="Account Name" for="acnt_name">
                                    <input type="text" name="account_name" id="acnt_name" required
                                        class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary currinput">
                                </x-admin.form-group>
                                <x-admin.form-group label="Account Number" for="acnt_number">
                                    <input type="number" name="account_number" id="acnt_number" required
                                        class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary currinput">
                                </x-admin.form-group>
                                <x-admin.form-group label="Swift/Other Code" for="swift">
                                    <input type="text" name="swift" id="swift"
                                        class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary currinput">
                                </x-admin.form-group>
                            </div>
                        </template>

                        {{-- Cryptocurrency inputs --}}
                        <template x-if="methodType === 'crypto'">
                            <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                                <x-admin.form-group label="Wallet Address" for="walletaddress">
                                    <input type="text" name="walletaddress" id="walletaddress" required
                                        class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary cryptoinput">
                                </x-admin.form-group>
                                <x-admin.form-group label="Barcode Image (Optional)" helper="Recommended Size: 575px both width and height">
                                    <input type="file" name="barcode"
                                        class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary cryptoinput">
                                </x-admin.form-group>
                                <x-admin.form-group label="Wallet Address Network Type" for="wallettype">
                                    <input type="text" name="wallettype" id="wallettype" placeholder="eg ERC" required
                                        class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary cryptoinput">
                                </x-admin.form-group>
                            </div>
                        </template>

                        <x-admin.form-group label="Status" for="status" :required="true">
                            <select name="status" id="status" required
                                class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                                <option value="enabled">Enable</option>
                                <option value="disabled">Disable</option>
                            </select>
                        </x-admin.form-group>

                        <x-admin.form-group label="Type for" :required="true">
                            <select name="typefor" required
                                class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                                <option value="withdrawal">Withdrawal</option>
                                <option value="deposit">Deposit</option>
                                <option value="both">Both</option>
                            </select>
                        </x-admin.form-group>

                        <x-admin.form-group label="Optional Note" class="md:col-span-2">
                            <input type="text" name="note" placeholder="Payment may take up to 24 hours"
                                class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                        </x-admin.form-group>

                        <div class="md:col-span-2">
                            <button type="submit"
                                class="bg-primary text-primary-foreground hover:bg-primary-hover rounded-lg px-4 py-2 text-sm font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-primary/30">
                                Save Method
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Payment Methods Table --}}
    <x-admin.card padding="p-0">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr>
                        <th class="bg-surface-alt px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left border-b border-border">Method Name</th>
                        <th class="bg-surface-alt px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left border-b border-border">Type</th>
                        <th class="bg-surface-alt px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left border-b border-border">Used for</th>
                        <th class="bg-surface-alt px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left border-b border-border">Status</th>
                        <th class="bg-surface-alt px-4 py-3 text-xs font-medium text-content-muted uppercase tracking-wide text-left border-b border-border">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($methods as $method)
                        <tr class="border-b border-border last:border-0 hover:bg-surface-alt/50 transition-colors">
                            <td class="px-4 py-3.5 text-sm font-medium text-content">{{ $method->name }}</td>
                            <td class="px-4 py-3.5 text-sm text-content-secondary">{{ $method->methodtype }}</td>
                            <td class="px-4 py-3.5 text-sm text-content-secondary">{{ $method->type }}</td>
                            <td class="px-4 py-3.5">
                                @if ($method->status == 'enabled')
                                    <x-admin.badge type="success">{{ $method->status }}</x-admin.badge>
                                @else
                                    <x-admin.badge type="danger">{{ $method->status }}</x-admin.badge>
                                @endif
                            </td>
                            <td class="px-4 py-3.5">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('editpaymethod', $method->id) }}"
                                        class="bg-primary-light text-primary hover:bg-primary hover:text-white rounded-lg px-3 py-1.5 text-xs font-medium transition-colors inline-flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" /></svg>
                                        Edit
                                    </a>
                                    @if ($method->defaultpay == 'yes')
                                        <span class="bg-danger-light text-danger/50 rounded-lg px-3 py-1.5 text-xs font-medium cursor-not-allowed inline-flex items-center gap-1" title="Cannot delete default method">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                                            Delete
                                        </span>
                                    @else
                                        <a href="{{ route('deletepaymethod', $method->id) }}"
                                            class="bg-danger-light text-danger hover:bg-danger hover:text-white rounded-lg px-3 py-1.5 text-xs font-medium transition-colors inline-flex items-center gap-1"
                                            onclick="return confirm('Are you sure you want to delete this payment method?')">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                                            Delete
                                        </a>
                                    @endif
                                    @if ($method->status == 'enabled')
                                        <a href="{{ route('togglestatus', $method->id) }}"
                                            class="bg-warning-light text-warning hover:bg-warning hover:text-white rounded-lg px-3 py-1.5 text-xs font-medium transition-colors">
                                            Disable
                                        </a>
                                    @else
                                        <a href="{{ route('togglestatus', $method->id) }}"
                                            class="bg-success-light text-success hover:bg-success hover:text-white rounded-lg px-3 py-1.5 text-xs font-medium transition-colors">
                                            Enable
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if(count($methods) === 0)
            <div class="py-12 text-center text-content-muted">
                <svg class="w-12 h-12 mx-auto mb-3 text-content-muted/50" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" /></svg>
                <p class="text-sm">No payment methods configured yet.</p>
            </div>
        @endif
    </x-admin.card>
</div>
