@extends('emails.layouts.master')

@section('preheader')Welcome to {{ $settings->site_name ?? config('app.name') }}!@endsection

@section('content')
<h2 style="margin: 0 0 16px; font-size: 20px; font-weight: 700; color: #111827;">Welcome, {{ $user->name }}!</h2>

<p style="margin: 0 0 20px; font-size: 15px; line-height: 1.6; color: #374151;">
    We are thrilled to have you join the <strong>{{ $settings->site_name ?? config('app.name') }}</strong> community!
    This is just the beginning of an exciting financial journey. Our platform offers powerful tools to help you grow and manage your investments seamlessly.
</p>

<h3 style="margin: 0 0 12px; font-size: 16px; font-weight: 700; color: #111827;">Unlock the Full Potential of Our System:</h3>

<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="margin: 0 0 24px;">
    <tr>
        <td style="padding: 8px 0; font-size: 14px; line-height: 1.5; color: #374151; border-bottom: 1px solid #F3F4F6;">
            <span style="color: #2E5C8A; font-weight: 700;">&#9679;</span>&nbsp; <strong>Trading System</strong> &mdash; Buy, sell, and manage assets effortlessly.
        </td>
    </tr>
    <tr>
        <td style="padding: 8px 0; font-size: 14px; line-height: 1.5; color: #374151; border-bottom: 1px solid #F3F4F6;">
            <span style="color: #2E5C8A; font-weight: 700;">&#9679;</span>&nbsp; <strong>Copy Trading</strong> &mdash; Mirror expert traders' strategies for passive gains.
        </td>
    </tr>
    <tr>
        <td style="padding: 8px 0; font-size: 14px; line-height: 1.5; color: #374151; border-bottom: 1px solid #F3F4F6;">
            <span style="color: #2E5C8A; font-weight: 700;">&#9679;</span>&nbsp; <strong>NFT Marketplace</strong> &mdash; Trade exclusive digital assets securely.
        </td>
    </tr>
    <tr>
        <td style="padding: 8px 0; font-size: 14px; line-height: 1.5; color: #374151; border-bottom: 1px solid #F3F4F6;">
            <span style="color: #2E5C8A; font-weight: 700;">&#9679;</span>&nbsp; <strong>Signal Subscription</strong> &mdash; Stay ahead with premium market insights.
        </td>
    </tr>
    <tr>
        <td style="padding: 8px 0; font-size: 14px; line-height: 1.5; color: #374151; border-bottom: 1px solid #F3F4F6;">
            <span style="color: #2E5C8A; font-weight: 700;">&#9679;</span>&nbsp; <strong>Flexible Investment Management</strong> &mdash; Choose from tailored plans to suit your goals.
        </td>
    </tr>
    <tr>
        <td style="padding: 8px 0; font-size: 14px; line-height: 1.5; color: #374151;">
            <span style="color: #2E5C8A; font-weight: 700;">&#9679;</span>&nbsp; <strong>Loan System</strong> &mdash; Access financial support when you need it.
        </td>
    </tr>
</table>

<h3 style="margin: 0 0 12px; font-size: 16px; font-weight: 700; color: #111827;">Start Earning in 3 Simple Steps:</h3>

<table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="margin: 0 0 24px;">
    <tr>
        <td style="padding: 10px 16px; background-color: #F9FAFB; border-radius: 6px; margin-bottom: 8px;">
            <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%">
                <tr>
                    <td width="36" valign="top" style="padding-right: 12px;">
                        <span style="display: inline-block; width: 28px; height: 28px; line-height: 28px; text-align: center; background-color: #2E5C8A; color: #fff; border-radius: 50%; font-size: 14px; font-weight: 700;">1</span>
                    </td>
                    <td style="font-size: 14px; line-height: 1.5; color: #374151;">
                        <strong>Make a Deposit</strong> &mdash; Fund your account securely.
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr><td style="height: 8px;"></td></tr>
    <tr>
        <td style="padding: 10px 16px; background-color: #F9FAFB; border-radius: 6px;">
            <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%">
                <tr>
                    <td width="36" valign="top" style="padding-right: 12px;">
                        <span style="display: inline-block; width: 28px; height: 28px; line-height: 28px; text-align: center; background-color: #2E5C8A; color: #fff; border-radius: 50%; font-size: 14px; font-weight: 700;">2</span>
                    </td>
                    <td style="font-size: 14px; line-height: 1.5; color: #374151;">
                        <strong>Select an Investment Plan</strong> &mdash; Choose a strategy that fits your goals.
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr><td style="height: 8px;"></td></tr>
    <tr>
        <td style="padding: 10px 16px; background-color: #F9FAFB; border-radius: 6px;">
            <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%">
                <tr>
                    <td width="36" valign="top" style="padding-right: 12px;">
                        <span style="display: inline-block; width: 28px; height: 28px; line-height: 28px; text-align: center; background-color: #2E5C8A; color: #fff; border-radius: 50%; font-size: 14px; font-weight: 700;">3</span>
                    </td>
                    <td style="font-size: 14px; line-height: 1.5; color: #374151;">
                        <strong>Sit Back &amp; Earn</strong> &mdash; Watch your money work for you!
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<p style="margin: 0; font-size: 15px; line-height: 1.6; color: #374151;">
    At <strong>{{ $settings->site_name ?? config('app.name') }}</strong>, we prioritize <strong>simplicity, security, and profitability</strong>. No hassle, no stress &mdash; just seamless financial growth.
</p>
@endsection

@section('signoff')Welcome aboard@endsection
