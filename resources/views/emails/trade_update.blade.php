@extends('emails.layouts.master')

@section('preheader')Your trade for {{ $trade->asset_name }} has been updated@endsection

@section('greeting')Dear {{ $trade->user->name }},@endsection

@section('content')
<h2 style="margin: 0 0 16px; font-size: 20px; font-weight: 700; color: #111827;">Trade Update Notification</h2>

<p style="margin: 0 0 16px; font-size: 15px; line-height: 1.6; color: #374151;">
    Your trade for <strong>{{ $trade->asset_name }}</strong> has been updated.
</p>

@php
    $plColor = $trade->profit_loss >= 0 ? '#2E5C8A' : '#DC2626';
    $plSign = $trade->profit_loss >= 0 ? '+' : '-';
@endphp

@include('emails.partials.transaction-details', ['details' => [
    ['label' => 'Trade Result', 'value' => $trade->result],
    ['label' => 'Profit/Loss', 'value' => '<span style="color: ' . $plColor . '; font-weight: 700;">' . $plSign . \App\Helpers\CurrencyHelper::formatForUser(abs($trade->profit_loss), $trade->user ?? null) . '</span>'],
    ['label' => 'Status', 'value' => ucfirst($trade->status)],
]])
@endsection

@section('action')
@include('emails.partials.button', ['url' => route('user.trades.history'), 'label' => 'View Trade History'])
@endsection

@section('signoff')Thanks for trading with us@endsection
