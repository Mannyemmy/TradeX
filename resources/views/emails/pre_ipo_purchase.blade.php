@extends('emails.layouts.master')

@section('preheader')Your Pre-IPO share purchase has been confirmed@endsection

@section('greeting')Hello {{ $user->username }},@endsection

@section('content')
<h2 style="margin: 0 0 16px; font-size: 20px; font-weight: 700; color: #111827;">Pre-IPO Share Purchase Confirmed</h2>

<p style="margin: 0 0 16px; font-size: 15px; line-height: 1.6; color: #374151;">
    Your purchase of <strong>{{ $company->name }}</strong> ({{ $company->symbol }}) shares has been successfully processed. Here are the details:
</p>

@include('emails.partials.transaction-details', ['details' => [
    ['label' => 'Company', 'value' => $company->name . ' (' . $company->symbol . ')'],
    ['label' => 'Sector', 'value' => $company->sector ?? 'N/A'],
    ['label' => 'Shares Purchased', 'value' => number_format($quantity)],
    ['label' => 'Price Per Share', 'value' => \App\Helpers\CurrencyHelper::formatForUser($company->share_price, $user)],
    ['label' => 'Total Cost', 'value' => \App\Helpers\CurrencyHelper::formatForUser($totalCost, $user)],
]])

<p style="margin: 16px 0 0; font-size: 15px; line-height: 1.6; color: #374151;">
    Your shares are now held in your Pre-IPO portfolio. You can view your holdings anytime from your dashboard.
</p>
@endsection

@section('signoff')Happy Investing@endsection
