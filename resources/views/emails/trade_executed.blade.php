@extends('emails.layouts.master')

@section('preheader')Your trade has been executed@endsection

@section('greeting')Hello {{ $user->username }},@endsection

@section('content')
<h2 style="margin: 0 0 16px; font-size: 20px; font-weight: 700; color: #111827;">Trade Executed Successfully</h2>

<p style="margin: 0 0 16px; font-size: 15px; line-height: 1.6; color: #374151;">
    Your trade has been successfully executed. Here are the details:
</p>

@include('emails.partials.transaction-details', ['details' => [
    ['label' => 'Asset', 'value' => $trade->asset_name],
    ['label' => 'Type', 'value' => ucfirst($trade->asset_type)],
    ['label' => 'Action', 'value' => ucfirst($trade->action)],
    ['label' => 'Leverage', 'value' => $trade->leverage . 'x'],
    ['label' => 'Take Profit', 'value' => \App\Helpers\CurrencyHelper::formatForUser($trade->take_profit, $user)],
    ['label' => 'Stop Loss', 'value' => \App\Helpers\CurrencyHelper::formatForUser($trade->stop_loss, $user)],
    ['label' => 'Amount', 'value' => \App\Helpers\CurrencyHelper::formatForUser($trade->amount, $user)],
]])

<p style="margin: 16px 0 0; font-size: 15px; line-height: 1.6; color: #374151;">
    You can view your trade history anytime in your account.
</p>
@endsection

@section('signoff')Thanks@endsection
