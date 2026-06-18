@extends('emails.layouts.master')

@section('preheader')New ROI received on your investment@endsection

@section('greeting')Hello {{ $user->name }},@endsection

@section('content')
<h2 style="margin: 0 0 16px; font-size: 20px; font-weight: 700; color: #111827;">New Return on Investment</h2>

<p style="margin: 0 0 16px; font-size: 15px; line-height: 1.6; color: #374151;">
    This is a notification of a new return on investment (ROI) on your investment account.
</p>

@include('emails.partials.transaction-details', ['details' => [
    ['label' => 'Plan', 'value' => $plan],
    ['label' => 'Amount', 'value' => '<span style="color: #2E5C8A; font-weight: 600;">' . \App\Helpers\CurrencyHelper::formatForUser($amount, $user ?? null) . '</span>'],
    ['label' => 'Date', 'value' => $plandate],
]])
@endsection

@section('signoff')Thanks@endsection
