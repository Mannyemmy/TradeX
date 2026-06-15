@extends('emails.layouts.master')

@section('preheader')Your loan request has been received@endsection

@section('greeting')Hello {{ $user->name }},@endsection

@section('content')
<h2 style="margin: 0 0 16px; font-size: 20px; font-weight: 700; color: #111827;">Loan Request Received</h2>

<p style="margin: 0 0 16px; font-size: 15px; line-height: 1.6; color: #374151;">
    Your loan request has been received and is currently under review.
</p>

@php $settings = \App\Models\Settings::find(1); @endphp
@include('emails.partials.transaction-details', ['details' => [
    ['label' => 'Loan Plan', 'value' => $loan->loanPlan->name ?? 'N/A'],
    ['label' => 'Amount Requested', 'value' => \App\Helpers\CurrencyHelper::formatForUser($loan->amount, $user ?? null)],
    ['label' => 'Duration', 'value' => $loan->duration . ' months'],
    ['label' => 'Purpose', 'value' => $loan->purpose],
    ['label' => 'Status', 'value' => '<span style="color: #D97706; font-weight: 600;">Under Review</span>'],
]])

<p style="margin: 16px 0 0; font-size: 15px; line-height: 1.6; color: #374151;">
    We will notify you once a decision has been made.
</p>
@endsection

@section('signoff')Thank you for choosing us@endsection
