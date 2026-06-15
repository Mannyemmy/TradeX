{{-- Submit MT4 Subscription Modal --}}
<div id="submitmt4modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content" style="background: #161A1E; border: 1px solid #2A2F36; border-radius: 12px;">
            <div class="modal-header" style="border-bottom: 1px solid #2A2F36;">
                <h4 class="modal-title" style="color: #E8EAED; font-size: 16px; font-weight: 600;">Subscribe to Trading</h4>
                <button type="button" class="close" data-dismiss="modal" style="color: #9AA0AB;">&times;</button>
            </div>
            <div class="modal-body" style="padding: 24px;">
                <form role="form" method="post" action="{{ route('savemt4details') }}">
                    @csrf
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-content-secondary mb-1">Duration</label>
                            <select onchange="calcAmount(this)" name="duration" id="duratn"
                                    class="w-full px-3 py-2.5 rounded-lg bg-surface-overlay border border-surface-border text-content-primary text-sm focus:outline-none focus:ring-1 focus:ring-primary">
                                <option value="default">Select duration</option>
                                <option>Monthly</option>
                                <option>Quaterly</option>
                                <option>Yearly</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-content-secondary mb-1">Amount</label>
                            <input type="text" id="amount" disabled
                                   class="w-full px-3 py-2.5 rounded-lg bg-surface-overlay border border-surface-border text-content-primary text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-content-secondary mb-1">Login</label>
                            <input type="text" name="userid" required
                                   class="w-full px-3 py-2.5 rounded-lg bg-surface-overlay border border-surface-border text-content-primary text-sm focus:outline-none focus:ring-1 focus:ring-primary">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-content-secondary mb-1">Password</label>
                            <input type="text" name="pswrd" required
                                   class="w-full px-3 py-2.5 rounded-lg bg-surface-overlay border border-surface-border text-content-primary text-sm focus:outline-none focus:ring-1 focus:ring-primary">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-content-secondary mb-1">Account Name</label>
                            <input type="text" name="name" required
                                   class="w-full px-3 py-2.5 rounded-lg bg-surface-overlay border border-surface-border text-content-primary text-sm focus:outline-none focus:ring-1 focus:ring-primary">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-content-secondary mb-1">Account Type</label>
                            <input type="text" name="acntype" placeholder="E.g. Standard" required
                                   class="w-full px-3 py-2.5 rounded-lg bg-surface-overlay border border-surface-border text-content-primary text-sm placeholder-content-tertiary focus:outline-none focus:ring-1 focus:ring-primary">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-content-secondary mb-1">Currency</label>
                            <input type="text" name="currency" placeholder="E.g. USD" required
                                   class="w-full px-3 py-2.5 rounded-lg bg-surface-overlay border border-surface-border text-content-primary text-sm placeholder-content-tertiary focus:outline-none focus:ring-1 focus:ring-primary">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-content-secondary mb-1">Leverage</label>
                            <input type="text" name="leverage" placeholder="E.g. 1:500" required
                                   class="w-full px-3 py-2.5 rounded-lg bg-surface-overlay border border-surface-border text-content-primary text-sm placeholder-content-tertiary focus:outline-none focus:ring-1 focus:ring-primary">
                        </div>
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-content-secondary mb-1">Server</label>
                            <input type="text" name="server" placeholder="E.g. HantecGlobal-live" required
                                   class="w-full px-3 py-2.5 rounded-lg bg-surface-overlay border border-surface-border text-content-primary text-sm placeholder-content-tertiary focus:outline-none focus:ring-1 focus:ring-primary">
                        </div>
                    </div>
                    <p class="text-xs text-content-tertiary mt-3 mb-4">Amount will be deducted from your account balance.</p>
                    <input id="amountpay" type="hidden" name="amount">
                    <button type="submit" class="w-full py-2.5 rounded-lg bg-primary hover:bg-primary-dark text-content-inverse text-sm font-semibold transition-colors">
                        Subscribe Now
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function calcAmount(sub) {
        var amount = document.getElementById('amount');
        var amountpay = document.getElementById('amountpay');
        if (sub.value == "Quaterly") {
            amount.value = '<?php echo \App\Helpers\CurrencyHelper::getUserSymbol() . $settings->quarterlyfee; ?>';
            amountpay.value = '<?php echo $settings->quarterlyfee; ?>';
        }
        if (sub.value == "Yearly") {
            amount.value = '<?php echo \App\Helpers\CurrencyHelper::getUserSymbol() . $settings->yearlyfee; ?>';
            amountpay.value = '<?php echo $settings->yearlyfee; ?>';
        }
        if (sub.value == "Monthly") {
            amount.value = '<?php echo \App\Helpers\CurrencyHelper::getUserSymbol() . $settings->monthlyfee; ?>';
            amountpay.value = '<?php echo $settings->monthlyfee; ?>';
        }
    }
</script>
