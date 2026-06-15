@extends('emails.layouts.master')

@section('preheader')Loan payment received@endsection

@section('greeting')Hello {{ $user->name }},@endsection

@section('content')
<h2 style="margin: 0 0 16px; font-size: 20px; font-weight: 700; color: #111827;">Loan Payment Received</h2>

<p style="margin: 0 0 16px; font-size: 15px; line-height: 1.6; color: #374151;">
    Your payment for <strong>Installment #{{ $schedule->installment_number }}</strong> has been successfully recorded.
</p>

@php $settings = \App\Models\Settings::find(1); @endphp
@include('emails.partials.transaction-details', ['details' => [
    ['label' => 'Loan Plan', 'value' => $loan->loanPlan->name ?? 'N/A'],
    ['label' => 'Installment', 'value' => '#' . $schedule->installment_number . ' of ' . ($loan->num_installments ?? $loan->duration)],
    ['label' => 'Amount Paid', 'value' => \App\Helpers\CurrencyHelper::formatForUser($schedule->paid_amount, $user ?? null)],
    ['label' => 'Remaining Balance', 'value' => \App\Helpers\CurrencyHelper::formatForUser($loan->remaining_balance, $user ?? null)],
    ['label' => 'Progress', 'value' => $loan->progress_percentage . '% complete'],
]])

<p style="margin: 16px 0 0; font-size: 15px; line-height: 1.6; color: #374151;">
    You can view your full repayment schedule from your dashboard.
</p>
@endsection

@section('signoff')Thank you for your payment@endsection
