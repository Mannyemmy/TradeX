@extends('emails.layouts.master')

@section('preheader')Your NFT bid has been approved!@endsection

@section('greeting')Dear {{ $bid->user->name }},@endsection

@section('content')
<h2 style="margin: 0 0 16px; font-size: 20px; font-weight: 700; color: #111827;">Congratulations! Your Bid Was Approved</h2>

<p style="margin: 0 0 16px; font-size: 15px; line-height: 1.6; color: #374151;">
    Your bid of <strong>{{ number_format($bid->amount, 2) }} ETH</strong> for the NFT
    <strong>&ldquo;{{ $nft->name }}&rdquo;</strong> has been <span style="color: #2E5C8A; font-weight: 600;">approved</span>!
</p>

<p style="margin: 0; font-size: 15px; line-height: 1.6; color: #374151;">
    The NFT is now yours. You can view it in your collection.
</p>
@endsection

@section('action')
@include('emails.partials.button', ['url' => route('user.nfts.my'), 'label' => 'View My NFTs'])
@endsection

@section('signoff')Thanks for using our platform@endsection
