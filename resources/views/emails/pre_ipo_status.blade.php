@extends('emails.layouts.master')

@section('preheader'){{ $company->name }} status update: {{ ucfirst($newStatus) }}@endsection

@section('greeting')Hello {{ $user->username }},@endsection

@section('content')
<h2 style="margin: 0 0 16px; font-size: 20px; font-weight: 700; color: #111827;">
    {{ $company->name }} — Status Update
</h2>

@if($newStatus === 'public')
<p style="margin: 0 0 16px; font-size: 15px; line-height: 1.6; color: #374151;">
    Great news! <strong>{{ $company->name }}</strong> ({{ $company->symbol }}) has officially gone public. Your Pre-IPO shares have been converted and are now trading at live market prices.
</p>

<p style="margin: 0 0 16px; font-size: 15px; line-height: 1.6; color: #374151;">
    You can now <strong>sell your shares</strong> at the current market price from your holdings page.
</p>
@elseif($newStatus === 'open')
<p style="margin: 0 0 16px; font-size: 15px; line-height: 1.6; color: #374151;">
    <strong>{{ $company->name }}</strong> ({{ $company->symbol }}) is now open for share purchases. Don't miss this opportunity to invest before the IPO.
</p>
@elseif($newStatus === 'closed')
<p style="margin: 0 0 16px; font-size: 15px; line-height: 1.6; color: #374151;">
    The Pre-IPO round for <strong>{{ $company->name }}</strong> ({{ $company->symbol }}) has been closed. No further purchases are available. Your existing holdings remain in your portfolio.
</p>
@elseif($newStatus === 'ipo')
<p style="margin: 0 0 16px; font-size: 15px; line-height: 1.6; color: #374151;">
    <strong>{{ $company->name }}</strong> ({{ $company->symbol }}) is now in the IPO transition period. Stay tuned — once the company goes public, you'll be able to trade your shares at live market prices.
</p>
@endif

@include('emails.partials.transaction-details', ['details' => [
    ['label' => 'Company', 'value' => $company->name . ' (' . $company->symbol . ')'],
    ['label' => 'Sector', 'value' => $company->sector ?? 'N/A'],
    ['label' => 'New Status', 'value' => '<strong>' . ucfirst($newStatus) . '</strong>'],
    ['label' => 'Share Price', 'value' => \App\Helpers\CurrencyHelper::formatForUser($company->share_price, $user)],
]])

<p style="margin: 16px 0 0; font-size: 15px; line-height: 1.6; color: #374151;">
    Visit your dashboard to view your Pre-IPO holdings and the latest updates.
</p>
@endsection

@section('signoff')Best regards@endsection
