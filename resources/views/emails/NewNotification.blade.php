@extends('emails.layouts.master')

@section('greeting'){{ $salutaion ? $salutaion : 'Hello' }} {{ $recipient }},@endsection

@section('content')
@if ($attachment != null)
<p style="margin: 0 0 16px;">
    <img src="{{ $message->embed(asset('storage/'. $attachment)) }}" alt="Attachment" style="max-width: 100%; height: auto; border-radius: 6px;" />
</p>
@endif

<div style="font-size: 15px; line-height: 1.6; color: #374151;">
    {!! nl2br(e($body)) !!}
</div>
@endsection

@section('signoff')Thanks@endsection
