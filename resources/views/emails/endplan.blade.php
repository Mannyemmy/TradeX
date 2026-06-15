@extends('emails.layouts.master')

@section('preheader')Your investment plan has expired@endsection

@section('greeting')Hello {{ $demo->receiver_name }},@endsection

@section('content')
<h2 style="margin: 0 0 16px; font-size: 20px; font-weight: 700; color: #111827;">Investment Plan Expired</h2>

<p style="margin: 0 0 16px; font-size: 15px; line-height: 1.6; color: #374151;">
    This is to notify you that your investment plan (<strong>{{ $demo->receiver_plan }}</strong> plan) has expired and your capital for this plan has been added to your account for withdrawal.
</p>

@include('emails.partials.transaction-details', ['details' => [
    ['label' => 'Plan', 'value' => $demo->receiver_plan],
    ['label' => 'Amount', 'value' => $demo->received_amount],
    ['label' => 'Date', 'value' => $demo->date],
]])
@endsection

@section('signoff')Kind regards@endsection
