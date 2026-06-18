@extends('emails.layouts.master')

@section('preheader'){{ $foramin ? 'New withdrawal request' : 'Withdrawal ' . ($withdrawal->status == 'Processed' ? 'processed' : 'submitted') }}@endsection

@section('greeting')Hello {{ $foramin ? 'Admin' : $user->username }},@endsection

@section('content')
@if ($foramin)
    {{-- Admin notification --}}
    <h2 style="margin: 0 0 16px; font-size: 20px; font-weight: 700; color: #111827;">New Withdrawal Request</h2>

    <p style="margin: 0 0 16px; font-size: 15px; line-height: 1.6; color: #374151;">
        <strong>{{ $user->name }}</strong> has made a withdrawal request of <strong>{{ \App\Helpers\CurrencyHelper::formatForUser($withdrawal->amount, $user) }}</strong>. Please login to your account to review and take necessary action.
    </p>

    @include('emails.partials.transaction-details', ['details' => [
        ['label' => 'User', 'value' => $user->name],
        ['label' => 'Amount', 'value' => \App\Helpers\CurrencyHelper::formatForUser($withdrawal->amount, $user)],
        ['label' => 'Status', 'value' => '<span style="color: #D97706; font-weight: 600;">Pending Review</span>'],
    ]])
@else
    @if ($withdrawal->status == 'Processed')
        {{-- User: processed withdrawal --}}
        <h2 style="margin: 0 0 16px; font-size: 20px; font-weight: 700; color: #111827;">Withdrawal Processed</h2>

        <p style="margin: 0 0 16px; font-size: 15px; line-height: 1.6; color: #374151;">
            Your withdrawal request has been <span style="color: #2E5C8A; font-weight: 600;">approved and processed</span> successfully.
        </p>

        @include('emails.partials.transaction-details', ['details' => [
            ['label' => 'Amount', 'value' => \App\Helpers\CurrencyHelper::formatForUser($withdrawal->amount, $user)],
            ['label' => 'Payment Method', 'value' => $withdrawal->payment_mode],
            ['label' => 'Status', 'value' => '<span style="color: #2E5C8A; font-weight: 600;">Processed</span>'],
        ]])

        <p style="margin: 16px 0 0; font-size: 15px; line-height: 1.6; color: #374151;">
            You should receive your funds shortly. If you have any questions, feel free to contact our support team.
        </p>
    @else
        {{-- User: pending withdrawal --}}
        <h2 style="margin: 0 0 16px; font-size: 20px; font-weight: 700; color: #111827;">Withdrawal Submitted</h2>

        <p style="margin: 0 0 16px; font-size: 15px; line-height: 1.6; color: #374151;">
            Your withdrawal request has been submitted successfully. We will process your request shortly.
        </p>

        @include('emails.partials.transaction-details', ['details' => [
            ['label' => 'Amount', 'value' => \App\Helpers\CurrencyHelper::formatForUser($withdrawal->amount, $user)],
            ['label' => 'Payment Method', 'value' => $withdrawal->payment_mode],
            ['label' => 'Status', 'value' => '<span style="color: #D97706; font-weight: 600;">' . $withdrawal->status . '</span>'],
        ]])
    @endif
@endif
@endsection

@section('signoff')Thanks@endsection

