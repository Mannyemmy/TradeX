@extends('emails.layouts.master')

@section('preheader'){{ $foramin ? 'New deposit notification' : 'Deposit ' . ($deposit->status == 'Processed' ? 'confirmed' : 'received') }}@endsection

@section('greeting')Hello {{ $foramin ? 'Admin' : $user->username }},@endsection

@section('content')
@if ($foramin)
    {{-- Admin notification --}}
    <h2 style="margin: 0 0 16px; font-size: 20px; font-weight: 700; color: #111827;">New Deposit Notification</h2>

    <p style="margin: 0 0 16px; font-size: 15px; line-height: 1.6; color: #374151;">
        A deposit of <strong>{{ \App\Helpers\CurrencyHelper::formatForUser($deposit->amount, $user) }}</strong> has been made by <strong>{{ $user->name }}</strong>.{{ $deposit->status == 'Processed' ? '' : ' Please login to process it.' }}
    </p>

    @include('emails.partials.transaction-details', ['details' => [
        ['label' => 'User', 'value' => $user->name],
        ['label' => 'Amount', 'value' => \App\Helpers\CurrencyHelper::formatForUser($deposit->amount, $user)],
        ['label' => 'Status', 'value' => $deposit->status],
    ]])
@else
    @if ($deposit->status == 'Processed')
        {{-- User: confirmed deposit --}}
        <h2 style="margin: 0 0 16px; font-size: 20px; font-weight: 700; color: #111827;">Deposit Confirmed</h2>

        <p style="margin: 0 0 16px; font-size: 15px; line-height: 1.6; color: #374151;">
            Your deposit of <strong>{{ \App\Helpers\CurrencyHelper::formatForUser($deposit->amount, $user) }}</strong> has been successfully
            <span style="color: #059669; font-weight: 600;">processed and confirmed</span>.
        </p>

        @include('emails.partials.transaction-details', ['details' => [
            ['label' => 'Amount', 'value' => \App\Helpers\CurrencyHelper::formatForUser($deposit->amount, $user)],
            ['label' => 'Payment Method', 'value' => $deposit->payment_mode],
            ['label' => 'Status', 'value' => '<span style="color: #059669; font-weight: 600;">&#10003; Confirmed</span>'],
            ['label' => 'Account Balance', 'value' => \App\Helpers\CurrencyHelper::formatForUser($user->account_bal, $user)],
        ]])

        <p style="margin: 16px 0 0; font-size: 15px; line-height: 1.6; color: #374151;">
            You can now use these funds for transactions.
        </p>
    @else
        {{-- User: pending deposit --}}
        <h2 style="margin: 0 0 16px; font-size: 20px; font-weight: 700; color: #111827;">Deposit Received</h2>

        <p style="margin: 0 0 16px; font-size: 15px; line-height: 1.6; color: #374151;">
            Your deposit request has been received successfully. We will process it shortly.
        </p>

        @include('emails.partials.transaction-details', ['details' => [
            ['label' => 'Amount', 'value' => \App\Helpers\CurrencyHelper::formatForUser($deposit->amount, $user)],
            ['label' => 'Payment Method', 'value' => $deposit->payment_mode],
            ['label' => 'Status', 'value' => '<span style="color: #D97706; font-weight: 600;">Pending</span>'],
        ]])

        <p style="margin: 16px 0 0; font-size: 15px; line-height: 1.6; color: #374151;">
            You will receive a confirmation once it's approved.
        </p>
    @endif
@endif
@endsection

@section('signoff')Thank you for using {{ $settings->site_name ?? config('app.name') }}@endsection
