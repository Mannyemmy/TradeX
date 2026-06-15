@extends('emails.layouts.master')

@section('preheader')Loan payment overdue@endsection

@section('greeting')Dear {{ $user->name }},@endsection

@section('content')
<h2 style="margin: 0 0 16px; font-size: 20px; font-weight: 700; color: #111827;">Loan Payment Overdue</h2>

<p style="margin: 0 0 16px; font-size: 15px; line-height: 1.6; color: #374151;">
    You have <span style="color: #DC2626; font-weight: 600;">{{ $overdueCount }} overdue installment(s)</span> on your loan. Please make a payment as soon as possible to avoid additional late fees.
</p>

@php $settings = \App\Models\Settings::find(1); @endphp
@include('emails.partials.transaction-details', ['details' => [
    ['label' => 'Loan Plan', 'value' => $loan->loanPlan->name ?? 'N/A'],
    ['label' => 'Overdue Installments', 'value' => '<span style="color: #DC2626; font-weight: 600;">' . $overdueCount . '</span>'],
    ['label' => 'Remaining Balance', 'value' => \App\Helpers\CurrencyHelper::formatForUser($loan->remaining_balance, $user ?? null)],
    ['label' => 'Total Repaid', 'value' => \App\Helpers\CurrencyHelper::formatForUser($loan->total_repaid, $user ?? null)],
    ['label' => 'Progress', 'value' => $loan->progress_percentage . '% complete'],
]])

<p style="margin: 16px 0 0; font-size: 15px; line-height: 1.6; color: #374151;">
    Late fees may be applied to overdue installments. Continued non-payment may result in your loan being marked as defaulted. Please log in to make your payment.
</p>
@endsection

@section('signoff')Please settle your overdue payments promptly@endsection
