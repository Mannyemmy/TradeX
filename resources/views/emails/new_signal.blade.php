@extends('emails.layouts.master')

@section('preheader')New trading signal alert@endsection

@section('content')
<h2 style="margin: 0 0 16px; font-size: 20px; font-weight: 700; color: #111827;">New Trading Signal Alert</h2>

@include('emails.partials.transaction-details', ['details' => [
    ['label' => 'Signal Name', 'value' => $signal->name],
    ['label' => 'Entry Price', 'value' => $signal->entry_price],
    ['label' => 'Take Profit', 'value' => $signal->take_profit],
    ['label' => 'Stop Loss', 'value' => $signal->stop_loss],
    ['label' => 'Leverage', 'value' => $signal->leverage . 'x'],
]])
@endsection

@section('action')
@include('emails.partials.button', ['url' => url('/dashboard/singalssubscriptions'), 'label' => 'View Signals'])
@endsection

@section('signoff')Thanks@endsection
