@extends('emails.layouts.master')

@section('preheader')A new trade has been placed for you@endsection

@section('greeting')Dear {{ $trade->user->name }},@endsection

@section('content')
<h2 style="margin: 0 0 16px; font-size: 20px; font-weight: 700; color: #111827;">New Trade Placed for You</h2>

<p style="margin: 0 0 16px; font-size: 15px; line-height: 1.6; color: #374151;">
    A new trade has been placed for you by the system. Here are the details:
</p>

@include('emails.partials.transaction-details', ['details' => [
    ['label' => 'Asset', 'value' => $trade->asset_name],
    ['label' => 'Amount', 'value' => \App\Helpers\CurrencyHelper::formatForUser($trade->amount, $user ?? null)],
    ['label' => 'Leverage', 'value' => $trade->leverage . 'x'],
    ['label' => 'Trade Action', 'value' => ucfirst($trade->action)],
    ['label' => 'Take Profit', 'value' => $trade->take_profit ? \App\Helpers\CurrencyHelper::formatForUser($trade->take_profit, $user ?? null) : 'Not Set'],
    ['label' => 'Stop Loss', 'value' => $trade->stop_loss ? \App\Helpers\CurrencyHelper::formatForUser($trade->stop_loss, $user ?? null) : 'Not Set'],
    ['label' => 'Expires At', 'value' => \Carbon\Carbon::parse($trade->expires_at)->format('Y-m-d H:i')],
]])
@endsection

@section('action')
@include('emails.partials.button', ['url' => route('user.trades.history'), 'label' => 'View Trade History'])
@endsection

@section('signoff')Thanks for trading with us@endsection
