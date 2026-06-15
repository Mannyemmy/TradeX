@extends('emails.layouts.master')

@section('preheader')Loan payment reminder@endsection

@section('greeting')Hello {{ $user->name }},@endsection

@section('content')
<h2 style="margin: 0 0 16px; font-size: 20px; font-weight: 700; color: #111827;">Payment Reminder</h2>

<p style="margin: 0 0 16px; font-size: 15px; line-height: 1.6; color: #374151;">
    This is a friendly reminder that your next loan payment is due on <strong>{{ $schedule->due_date->format('M d, Y') }}</strong>.
</p>

@php $settings = \App\Models\Settings::find(1); @endphp
@include('emails.partials.transaction-details', ['details' => [
    ['label' => 'Loan Plan', 'value' => $loan->loanPlan->name ?? 'N/A'],
    ['label' => 'Installment', 'value' => '#' . $schedule->installment_number],
    ['label' => 'Amount Due', 'value' => \App\Helpers\CurrencyHelper::formatForUser($schedule->total_amount, $user ?? null)],
    ['label' => 'Due Date', 'value' => $schedule->due_date->format('M d, Y')],
]])

<p style="margin: 16px 0 0; font-size: 15px; line-height: 1.6; color: #374151;">
    Please ensure your account has sufficient balance. You can make your payment from the Loans section of your dashboard.
</p>
@endsection

@section('signoff')Thank you for your timely payments@endsection
