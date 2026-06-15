@extends('emails.layouts.master')

@section('preheader')Congratulations! Your loan has been fully repaid@endsection

@section('greeting')Dear {{ $user->name }},@endsection

@section('content')
<h2 style="margin: 0 0 16px; font-size: 20px; font-weight: 700; color: #111827;">Loan Fully Repaid</h2>

<p style="margin: 0 0 16px; font-size: 15px; line-height: 1.6; color: #374151;">
    Congratulations! Your loan has been <span style="color: #059669; font-weight: 600;">fully repaid</span>. Your account is now clear of this obligation.
</p>

@php $settings = \App\Models\Settings::find(1); @endphp
@include('emails.partials.transaction-details', ['details' => [
    ['label' => 'Loan Plan', 'value' => $loan->loanPlan->name ?? 'N/A'],
    ['label' => 'Original Amount', 'value' => \App\Helpers\CurrencyHelper::formatForUser($loan->approved_amount ?? $loan->amount, $user ?? null)],
    ['label' => 'Total Repaid', 'value' => \App\Helpers\CurrencyHelper::formatForUser($loan->total_repaid, $user ?? null)],
    ['label' => 'Status', 'value' => '<span style="color: #059669; font-weight: 600;">Completed</span>'],
]])

<p style="margin: 16px 0 0; font-size: 15px; line-height: 1.6; color: #374151;">
    You are now eligible to apply for a new loan. Thank you for your timely payments!
</p>
@endsection

@section('signoff')Congratulations on completing your loan@endsection
