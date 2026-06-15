{{-- Top Up / Credit-Debit Modal --}}
<x-admin.modal id="topupmodal" title="Credit/Debit {{ $user->name }} account.">
    <form method="post" action="{{ route('topup') }}">
        @csrf
        <x-admin.form-group label="Amount" class="mb-4" required>
            <input class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary amount-input" placeholder="Enter amount" type="number" name="amount" step="any" min="0" required>
        </x-admin.form-group>

        <x-admin.form-group label="Select where to Credit/Debit" class="mb-4" required>
            <select class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary" name="type" required>
                <option value="" selected disabled>Select Column</option>
                <option value="Bonus">Bonus</option>
                <option value="Profit">Profit</option>
                <option value="Ref_Bonus">Ref_Bonus</option>
                <option value="balance">Account Balance</option>
                <option value="Deposit">Deposit</option>
            </select>
        </x-admin.form-group>

        <x-admin.form-group label="Select credit to add, debit to subtract." class="mb-4" helper="NOTE: You cannot debit deposit" required>
            <select class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary" name="t_type" required>
                <option value="">Select type</option>
                <option value="Credit">Credit</option>
                <option value="Debit">Debit</option>
            </select>
        </x-admin.form-group>

        <input type="hidden" name="user_id" value="{{ $user->id }}">
        <button type="submit" class="inline-flex items-center gap-1.5 bg-primary text-primary-foreground hover:bg-primary-hover rounded-lg px-4 py-2 text-sm font-medium transition-colors">
            Submit
        </button>
    </form>
</x-admin.modal>

{{-- Win Rate Modal --}}
<x-admin.modal id="winrate" title="Set {{ $user->name }} {{ $user->l_name }} Win Rate">
    <form role="form" method="post" action="{{ route('winRate') }}">
        @csrf
        <x-admin.form-group label="Win Rate" class="mb-4">
            <input type="number" name="winrate" class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary" value="{{ $user->win_rate }}">
        </x-admin.form-group>

        <input type="hidden" name="user_id" value="{{ $user->id }}">
        <button type="submit" class="inline-flex items-center gap-1.5 bg-primary text-primary-foreground hover:bg-primary-hover rounded-lg px-4 py-2 text-sm font-medium transition-colors">
            Set Win Rate
        </button>
    </form>
</x-admin.modal>

{{-- Signal Strength Modal --}}
<x-admin.modal id="signalstrength" title="Set {{ $user->name }} {{ $user->l_name }} Signal Strength">
    <form role="form" method="post" action="{{ route('signalStrength') }}">
        @csrf
        <x-admin.form-group label="Enable Signal Strength" class="mb-4">
            <label class="inline-flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="signal_strength_enabled" value="1" {{ $user->signal_strength_enabled ? 'checked' : '' }}
                       class="w-4 h-4 rounded border-border text-primary focus:ring-primary/30">
                <span class="text-sm text-content">Show signal strength on user dashboard</span>
            </label>
        </x-admin.form-group>

        <x-admin.form-group label="Signal Strength Score (0–100)" class="mb-4">
            <input type="number" name="signal_strength_score" min="0" max="100"
                   class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary"
                   value="{{ $user->signal_strength_score ?? 0 }}">
            <p class="text-xs text-content-secondary mt-1">0–24 Weak | 25–49 Moderate | 50–100 Strong</p>
        </x-admin.form-group>

        <input type="hidden" name="user_id" value="{{ $user->id }}">
        <button type="submit" class="inline-flex items-center gap-1.5 bg-primary text-primary-foreground hover:bg-primary-hover rounded-lg px-4 py-2 text-sm font-medium transition-colors">
            Set Signal Strength
        </button>
    </form>
</x-admin.modal>

{{-- Withdrawal Code Modal --}}
<x-admin.modal id="withdrawalcode" title="Set {{ $user->name }} {{ $user->l_name }} Withdrawal Codes">
    @php
        $modalDefaultLabels = [
            1 => 'Broker Commission Fee Code',
            2 => 'Anti-Theft Security Code',
            3 => 'IMF Code',
            4 => 'Cost of Transfer Code',
            5 => 'Taxation Code',
        ];
    @endphp
    <form role="form" method="post" action="{{ route('withdrawalcode') }}" x-data="{
        @for ($i = 1; $i <= 5; $i++)
            m_code{{ $i }}_enabled: {{ $user->{"code{$i}_enabled"} ? 'true' : 'false' }}{{ $i < 5 ? ',' : '' }}
        @endfor
    }">
        @csrf
        <div class="space-y-3">
            @for ($i = 1; $i <= 5; $i++)
                <div class="p-3 rounded-lg border transition-colors"
                     :class="m_code{{ $i }}_enabled ? 'border-primary/30 bg-primary/5' : 'border-border'">
                    <div class="flex items-center gap-3 mb-2">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="code{{ $i }}_enabled" value="1"
                                   x-model="m_code{{ $i }}_enabled" class="sr-only peer">
                            <div class="w-8 h-4.5 bg-surface-alt peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border after:rounded-full after:h-3.5 after:w-3.5 after:transition-all peer-checked:bg-primary"></div>
                        </label>
                        <span class="text-xs font-semibold text-content">Step {{ $i }}</span>
                    </div>
                    <div class="space-y-2" x-show="m_code{{ $i }}_enabled" x-cloak>
                        <input type="text" name="code{{ $i }}_label"
                               value="{{ $user->{"code{$i}_label"} ?: $modalDefaultLabels[$i] }}"
                               placeholder="Label"
                               class="w-full bg-surface-card border border-border rounded-lg px-3 py-1.5 text-xs text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                        <input type="text" name="code{{ $i }}"
                               value="{{ $user->{"code{$i}"} }}"
                               placeholder="Code value"
                               class="w-full bg-surface-card border border-border rounded-lg px-3 py-1.5 text-xs text-content font-mono focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                    </div>
                </div>
            @endfor
        </div>

        <input type="hidden" name="user_id" value="{{ $user->id }}">
        <div class="mt-4">
            <button type="submit" class="inline-flex items-center gap-1.5 bg-primary text-primary-foreground hover:bg-primary-hover rounded-lg px-4 py-2 text-sm font-medium transition-colors">
                Save Withdrawal Codes
            </button>
        </div>
    </form>
</x-admin.modal>

{{-- Notify User Modal --}}
<x-admin.modal id="notify" title="Dashboard Banner for {{ $user->name }} {{ $user->l_name }}">
    <form role="form" method="post" action="{{ route('notify') }}">
        @csrf
        <x-admin.form-group label="Banner Message" class="mb-4">
            <textarea name="notify" class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary" rows="5" placeholder="Enter banner message for this user's dashboard...">{{ $user->dashboard_banner_message }}</textarea>
            <p class="text-xs text-content-secondary mt-1">This will appear as a banner at the top of the user's dashboard.</p>
        </x-admin.form-group>

        <x-admin.form-group label="Message Type" class="mb-4">
            <select name="banner_type" class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary">
                <option value="warning" {{ ($user->dashboard_banner_type ?? 'warning') == 'warning' ? 'selected' : '' }}> Warning</option>
                <option value="success" {{ $user->dashboard_banner_type == 'success' ? 'selected' : '' }}> Success</option>
                <option value="danger" {{ $user->dashboard_banner_type == 'danger' ? 'selected' : '' }}> Danger</option>
            </select>
        </x-admin.form-group>

        <div class="mb-4 flex items-center gap-3">
            <label class="relative inline-flex items-center cursor-pointer">
                <input type="checkbox" name="banner_enabled" value="1" class="sr-only peer" {{ $user->dashboard_banner_enabled ? 'checked' : '' }}>
                <div class="w-9 h-5 bg-surface-alt peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-primary/30 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-border after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-primary"></div>
            </label>
            <span class="text-sm text-content">Enable banner on dashboard</span>
        </div>

        <input type="hidden" name="user_id" value="{{ $user->id }}">
        <button type="submit" class="inline-flex items-center gap-1.5 bg-primary text-primary-foreground hover:bg-primary-hover rounded-lg px-4 py-2 text-sm font-medium transition-colors">
            Save Banner
        </button>
    </form>
</x-admin.modal>

{{-- Send Email Modal --}}
<x-admin.modal id="sendmailtooneusermodal" title="Send Email">
    <p class="text-sm text-content-secondary mb-4">This message will be sent to {{ $user->name }}</p>
    <form method="post" action="{{ route('sendmailtooneuser') }}">
        @csrf
        <x-admin.form-group label="Subject" class="mb-4" required>
            <input type="text" name="subject" class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary" placeholder="Subject" required>
        </x-admin.form-group>

        <x-admin.form-group label="Message" class="mb-4" required>
            <textarea placeholder="Type your message here" class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary" name="message" rows="8" required></textarea>
        </x-admin.form-group>

        <input type="hidden" name="user_id" value="{{ $user->id }}">
        <button type="submit" class="inline-flex items-center gap-1.5 bg-primary text-primary-foreground hover:bg-primary-hover rounded-lg px-4 py-2 text-sm font-medium transition-colors">
            Send
        </button>
    </form>
</x-admin.modal>

{{-- Trading History Modal --}}
<x-admin.modal id="tradingmodal" title="Add Trading History for {{ $user->name }} {{ $user->l_name }}">
    <form role="form" method="post" action="{{ route('addhistory') }}">
        @csrf
        <x-admin.form-group label="Select Investment Plan" class="mb-4">
            <select class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary" name="plan">
                <option value="" selected disabled>Select Plan</option>
                @foreach ($pl as $plns)
                    <option value="{{ $plns->name }}">{{ $plns->name }}</option>
                @endforeach
            </select>
        </x-admin.form-group>

        <x-admin.form-group label="Amount" class="mb-4">
            <input type="number" name="amount" step="any" min="0" class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary amount-input">
        </x-admin.form-group>

        <x-admin.form-group label="Type" class="mb-4">
            <select class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary" name="type">
                <option value="" selected disabled>Select type</option>
                <option value="Bonus">Bonus</option>
                <option value="ROI">ROI</option>
            </select>
        </x-admin.form-group>

        <input type="hidden" name="user_id" value="{{ $user->id }}">
        <button type="submit" class="inline-flex items-center gap-1.5 bg-primary text-primary-foreground hover:bg-primary-hover rounded-lg px-4 py-2 text-sm font-medium transition-colors">
            Add History
        </button>
    </form>
</x-admin.modal>

{{-- Edit User Modal --}}
<x-admin.modal id="edituser" title="Edit {{ $user->name }} details." maxWidth="max-w-xl">
    <form role="form" method="post" action="{{ route('edituser') }}">
        <x-admin.form-group label="Username" class="mb-4" helper="Note: same username should be use in the referral link." required>
            <input class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary" id="editUsernameInput" value="{{ $user->username }}" type="text" name="username" required>
        </x-admin.form-group>

        <x-admin.form-group label="Fullname" class="mb-4" required>
            <input class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary" value="{{ $user->name }}" type="text" name="name" required>
        </x-admin.form-group>

        <x-admin.form-group label="Email" class="mb-4" required>
            <input class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary" value="{{ $user->email }}" type="text" name="email" required>
        </x-admin.form-group>

        <x-admin.form-group label="Phone Number" class="mb-4" required>
            <input class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary" value="{{ $user->phone }}" type="text" name="phone" required>
        </x-admin.form-group>

        <x-admin.form-group label="Country" class="mb-4">
            <input class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary" value="{{ $user->country }}" type="text" name="country">
        </x-admin.form-group>

        <x-admin.form-group label="Win Rate (%)" class="mb-4" for="win_rate">
            <input type="number" name="win_rate" id="win_rate" class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary" value="{{ old('win_rate', $user->win_rate) }}" min="0" max="100">
        </x-admin.form-group>

        <x-admin.form-group label="Referral link" class="mb-4" required>
            <input class="w-full bg-surface-card border border-border rounded-lg px-3 py-2 text-sm text-content focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary" value="{{ $user->ref_link }}" type="text" name="ref_link" required>
        </x-admin.form-group>

        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="user_id" value="{{ $user->id }}">
        <button type="submit" class="inline-flex items-center gap-1.5 bg-primary text-primary-foreground hover:bg-primary-hover rounded-lg px-4 py-2 text-sm font-medium transition-colors">
            Update
        </button>
    </form>
</x-admin.modal>

{{-- Reset Password Modal --}}
<x-admin.modal id="resetpswdmodal" title="Reset Password">
    <p class="text-sm text-content-secondary mb-4">Are you sure you want to reset password for {{ $user->name }} to <span class="text-primary font-semibold">user01236</span></p>
    <a class="inline-flex items-center gap-1.5 bg-primary text-primary-foreground hover:bg-primary-hover rounded-lg px-4 py-2 text-sm font-medium transition-colors" href="{{ url('admin/dashboard/resetpswd') }}/{{ $user->id }}">Reset Now</a>
</x-admin.modal>

{{-- Switch User Modal --}}
<x-admin.modal id="switchusermodal" title="You are about to login as {{ $user->name }}.">
    <a class="inline-flex items-center gap-1.5 bg-success text-content-inverse hover:bg-success/80 rounded-lg px-4 py-2 text-sm font-medium transition-colors" href="{{ url('admin/dashboard/switchuser') }}/{{ $user->id }}">Proceed</a>
</x-admin.modal>

{{-- Clear Account Modal --}}
<x-admin.modal id="clearacctmodal" title="Clear Account">
    <p class="text-sm text-content-secondary mb-4">You are clearing account for {{ $user->name }} to {{ $settings->currency }}0.00</p>
    <a class="inline-flex items-center gap-1.5 bg-primary text-primary-foreground hover:bg-primary-hover rounded-lg px-4 py-2 text-sm font-medium transition-colors" href="{{ url('admin/dashboard/clearacct') }}/{{ $user->id }}">Proceed</a>
</x-admin.modal>

{{-- Delete User Modal --}}
<x-admin.modal id="deletemodal" title="Delete User">
    <p class="text-sm text-content-secondary mb-4">Are you sure you want to delete {{ $user->name }} Account? Everything associated with this account will be loss.</p>
    <a class="inline-flex items-center gap-1.5 bg-danger text-content-inverse hover:bg-danger/80 rounded-lg px-4 py-2 text-sm font-medium transition-colors" href="{{ url('admin/dashboard/delsystemuser') }}/{{ $user->id }}">Yes i'm sure</a>
</x-admin.modal>

@push('scripts')
<script>
document.getElementById('editUsernameInput')?.addEventListener('keypress', function(e) {
    if (e.key === ' ') e.preventDefault();
});

// Prevent non-numeric input on amount fields (blocks e, E, +, -, etc.)
document.querySelectorAll('.amount-input').forEach(function(input) {
    input.addEventListener('keydown', function(e) {
        if (['e', 'E', '+', '-'].includes(e.key)) {
            e.preventDefault();
        }
    });
    input.addEventListener('paste', function(e) {
        var pasted = (e.clipboardData || window.clipboardData).getData('text');
        if (!/^\d*\.?\d*$/.test(pasted)) {
            e.preventDefault();
        }
    });
});
</script>
@endpush