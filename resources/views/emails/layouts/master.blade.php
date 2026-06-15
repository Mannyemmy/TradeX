<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="color-scheme" content="light" />
    <meta name="supported-color-schemes" content="light" />
    <title>@yield('title', $settings->site_name ?? config('app.name'))</title>
    <style>
        /* Reset */
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { -ms-interpolation-mode: bicubic; border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; }
        body { margin: 0 !important; padding: 0 !important; width: 100% !important; }

        /* Responsive */
        @media only screen and (max-width: 620px) {
            .email-container { width: 100% !important; max-width: 100% !important; }
            .email-body-inner { padding: 24px 20px !important; }
            .email-header-inner { padding: 16px 20px !important; }
            .email-footer-inner { padding: 20px !important; }
            .detail-table { width: 100% !important; }
            .btn-td { padding: 12px 24px !important; }
        }
    </style>
    <!--[if mso]>
    <style type="text/css">
        body, table, td { font-family: Arial, Helvetica, sans-serif !important; }
    </style>
    <![endif]-->
</head>
<body style="margin: 0; padding: 0; background-color: #F5F7F9; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">

    {{-- Preheader text (hidden preview in inbox) --}}
    @hasSection('preheader')
    <div style="display: none; max-height: 0; overflow: hidden; mso-hide: all;">
        @yield('preheader')
    </div>
    @endif

    {{-- Outer wrapper --}}
    <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color: #F5F7F9;">
        <tr>
            <td align="center" style="padding: 24px 0;">

                {{-- Email container (600px max) --}}
                <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="600" class="email-container" style="max-width: 600px; width: 100%; border-radius: 8px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.08);">

                    {{-- ========== HEADER ========== --}}
                    <tr>
                        <td align="center" class="email-header-inner" style="background-color: #059669; padding: 24px 32px;">
                            @if(!empty($settings->logo))
                                <a href="{{ config('app.url') }}" target="_blank" style="text-decoration: none;">
                                    <img src="{{ asset('storage/app/public/' . $settings->logo) }}"
                                         alt="{{ $settings->site_name ?? config('app.name') }}"
                                         height="48"
                                         style="display: block; max-height: 48px; width: auto; border: 0;" />
                                </a>
                            @else
                                <a href="{{ config('app.url') }}" target="_blank" style="text-decoration: none;">
                                    <span style="color: #FFFFFF; font-size: 22px; font-weight: 700; letter-spacing: -0.5px;">
                                        {{ $settings->site_name ?? config('app.name') }}
                                    </span>
                                </a>
                            @endif
                        </td>
                    </tr>

                    {{-- ========== BODY ========== --}}
                    <tr>
                        <td class="email-body-inner" style="background-color: #FFFFFF; padding: 32px 40px;">

                            {{-- Greeting --}}
                            @hasSection('greeting')
                                <p style="margin: 0 0 20px; font-size: 16px; line-height: 1.5; color: #374151;">
                                    @yield('greeting')
                                </p>
                            @endif

                            {{-- Main content --}}
                            @yield('content')

                            {{-- CTA Button --}}
                            @hasSection('action')
                                <table role="presentation" cellpadding="0" cellspacing="0" border="0" style="margin: 28px 0;">
                                    <tr>
                                        <td>
                                            @yield('action')
                                        </td>
                                    </tr>
                                </table>
                            @endif

                            {{-- Sign-off --}}
                            <p style="margin: 28px 0 0; font-size: 15px; line-height: 1.6; color: #374151;">
                                @yield('signoff', 'Best regards'),<br />
                                <strong>{{ $settings->site_name ?? config('app.name') }} Team</strong>
                            </p>

                        </td>
                    </tr>

                    {{-- ========== FOOTER ========== --}}
                    <tr>
                        <td class="email-footer-inner" style="background-color: #0F1115; padding: 28px 32px; text-align: center;">

                            @if(!empty($settings->site_address))
                                <p style="margin: 0 0 8px; font-size: 13px; line-height: 1.5; color: #9CA3AF;">
                                    {{ $settings->site_address }}
                                </p>
                            @endif

                            @if(!empty($settings->contact_email))
                                <p style="margin: 0 0 16px; font-size: 13px; line-height: 1.5; color: #9CA3AF;">
                                    <a href="mailto:{{ $settings->contact_email }}" style="color: #059669; text-decoration: none;">
                                        {{ $settings->contact_email }}
                                    </a>
                                </p>
                            @endif

                            <p style="margin: 0; font-size: 12px; line-height: 1.5; color: #6B7280;">
                                &copy; {{ date('Y') }} {{ $settings->site_name ?? config('app.name') }}. All rights reserved.
                            </p>

                        </td>
                    </tr>

                </table>
                {{-- /Email container --}}

            </td>
        </tr>
    </table>
    {{-- /Outer wrapper --}}

</body>
</html>
