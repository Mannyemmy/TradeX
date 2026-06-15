@php
    $assets = [
        ['name' => 'Bitcoin',      'symbol' => 'BTC',  'key' => 'btc'],
        ['name' => 'Ethereum',     'symbol' => 'ETH',  'key' => 'eth'],
        ['name' => 'Litecoin',     'symbol' => 'LTC',  'key' => 'ltc'],
        ['name' => 'Chainlink',    'symbol' => 'LINK', 'key' => 'link'],
        ['name' => 'Binance Coin', 'symbol' => 'BNB',  'key' => 'bnb'],
        ['name' => 'Aave',         'symbol' => 'AAVE', 'key' => 'aave'],
        ['name' => 'Tether',       'symbol' => 'USDT', 'key' => 'usdt'],
        ['name' => 'Bitcoin Cash', 'symbol' => 'BCH',  'key' => 'bch'],
        ['name' => 'Ripple',       'symbol' => 'XRP',  'key' => 'xrp'],
        ['name' => 'Stellar',      'symbol' => 'XLM',  'key' => 'xlm'],
        ['name' => 'Ada',          'symbol' => 'ADA',  'key' => 'ada'],
    ];
@endphp

@foreach ($assets as $asset)
    <tr class="border-b border-border last:border-0 hover:bg-surface-alt/50 transition-colors">
        <td class="px-4 py-3.5 text-sm font-medium text-content">{{ $asset['name'] }}</td>
        <td class="px-4 py-3.5 text-sm text-content-secondary">{{ $asset['symbol'] }}</td>
        <td class="px-4 py-3.5 text-sm">
            @if ($moresettings->{$asset['key']} == 'enabled')
                <x-admin.badge type="success">enabled</x-admin.badge>
            @else
                <x-admin.badge type="danger">disabled</x-admin.badge>
            @endif
        </td>
        <td class="px-4 py-3.5">
            @if ($moresettings->{$asset['key']} == 'enabled')
                <a href="{{ route('setassetstatus', ['asset' => $asset['key'], 'status' => 'disabled']) }}"
                    class="bg-danger text-white hover:bg-danger/90 rounded-lg px-3 py-1.5 text-xs font-medium transition-colors">Disable</a>
            @else
                <a href="{{ route('setassetstatus', ['asset' => $asset['key'], 'status' => 'enabled']) }}"
                    class="bg-success text-white hover:bg-success/90 rounded-lg px-3 py-1.5 text-xs font-medium transition-colors">Enable</a>
            @endif
        </td>
    </tr>
@endforeach