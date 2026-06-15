@extends('emails.layouts.master')

@section('preheader')P2P Order Notification@endsection

@section('greeting')Hello {{ $name }},@endsection

@section('content')
<h2 style="margin: 0 0 16px; font-size: 20px; font-weight: 700; color: #111827;">P2P Order Notification</h2>

<div style="font-size: 15px; line-height: 1.6; color: #374151;">
    {!! $message !!}
</div>
@endsection

@section('signoff')Thanks@endsection
