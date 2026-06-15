@extends('emails.layouts.master')

@section('preheader')Update on your loan application@endsection

@section('greeting')Dear {{ $loan->user->name }},@endsection

@section('content')
<h2 style="margin: 0 0 16px; font-size: 20px; font-weight: 700; color: #111827;">Loan Application Rejected</h2>

<p style="margin: 0 0 16px; font-size: 15px; line-height: 1.6; color: #374151;">
    We regret to inform you that your loan application has been <span style="color: #DC2626; font-weight: 600;">rejected</span>.
</p>

<p style="margin: 0; font-size: 15px; line-height: 1.6; color: #374151;">
    If you have any questions, please feel free to contact us.
</p>
@endsection

@section('signoff')Thank you for using our services@endsection
