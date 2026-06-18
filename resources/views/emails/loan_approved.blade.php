@extends('emails.layouts.master')

@section('preheader')Your loan application has been approved@endsection

@section('greeting')Dear {{ $user->name }},@endsection

@section('content')
<h2 style="margin: 0 0 16px; font-size: 20px; font-weight: 700; color: #111827;">Loan Application Approved</h2>

<p style="margin: 0 0 16px; font-size: 15px; line-height: 1.6; color: #374151;">
    We are pleased to inform you that your loan application has been <span style="color: #2E5C8A; font-weight: 600;">approved</span> and the funds have been credited to your account.
</p>

@php $settings = \App\Models\Settings::find(1); @endphp
@include('emails.partials.transaction-details', ['details' => [
    ['label' => 'Loan Plan', 'value' => $loan->loanPlan->name ?? 'N/A'],
    ['label' => 'Approved Amount', 'value' => \App\Helpers\CurrencyHelper::formatForUser($loan->approved_amount ?? $loan->amount, $user)],
    ['label' => 'Total Repayable', 'value' => \App\Helpers\CurrencyHelper::formatForUser($loan->total_repayable, $user)],
    ['label' => 'Duration', 'value' => $loan->duration . ' months'],
    ['label' => 'Interest Rate', 'value' => $loan->interest_rate . '% ' . ucfirst($loan->interest_type ?? '')],
    ['label' => 'First Payment', 'value' => $loan->first_payment_date ? $loan->first_payment_date->format('M d, Y') : 'N/A'],
    ['label' => 'Status', 'value' => '<span style="color: #2E5C8A; font-weight: 600;">Approved &amp; Disbursed</span>'],
]])

<p style="margin: 16px 0 0; font-size: 15px; line-height: 1.6; color: #374151;">
    You can view your repayment schedule and make payments from your dashboard. If you have any questions, please contact us.
</p>
@endsection

@section('signoff')Thank you for using our services@endsection
