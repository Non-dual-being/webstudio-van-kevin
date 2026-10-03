<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="light dark">
    <meta name="supported-color-schemes" content="light dark">
    <title>@yield('title')</title>
    <style>
        @media screen and (max-width: 620px) {
            .outer { padding: 16px 12px !important; }
            .inset { padding: 24px !important; }
        }
        @media (prefers-color-scheme: dark) {
            .canvas { background-color: #111827 !important; }
            .card { background-color: #1e293b !important; border-color: #475569 !important; }
            .ink { color: #f1f5f9 !important; }
            .muted { color: #cbd5e1 !important; }
            .detail { background-color: #273449 !important; border-color: #475569 !important; }
            .rule { border-color: #475569 !important; }
            .accent { color: #fbbf24 !important; }
            .link { color: #e2e8f0 !important; }
        }
    </style>
</head>
<body class="canvas" style="margin: 0; padding: 0; background-color: #fafaf9; color: #0f172a; font-family: Arial, Helvetica, sans-serif; -webkit-text-size-adjust: 100%;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" class="canvas" bgcolor="#fafaf9" style="width: 100%; background-color: #fafaf9;">
        <tr>
            <td align="center" class="outer" style="padding: 40px 16px;">
                <!--[if mso]><table role="presentation" width="600" cellspacing="0" cellpadding="0" border="0"><tr><td><![endif]-->
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" class="card" bgcolor="#ffffff" style="width: 100%; max-width: 600px; table-layout: fixed; border: 1px solid #e7e5e4; border-radius: 8px; background-color: #ffffff;">
                    <tr>
                        <td class="inset rule" style="padding: 24px 32px; border-bottom: 1px solid #e7e5e4;">
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="width: 100%; table-layout: fixed;">
                                <tr>
                                    <td width="82" valign="middle" style="width: 82px;">
                                        <img src="{{ $message->embed(public_path('brand/kevin-webstudio-kds-mark.png')) }}" alt="" width="68" height="68" style="display: block; width: 68px; height: 68px; border: 0; border-radius: 4px; background-color: #ffffff;">
                                    </td>
                                    <td valign="middle" style="overflow-wrap: anywhere; word-wrap: break-word;">
                                        <p class="ink" style="margin: 0 0 5px; color: #0f172a; font-size: 20px; line-height: 25px; font-weight: bold;">Kevin Webstudio</p>
                                        <p class="muted" style="margin: 0; color: #475569; font-size: 12px; line-height: 18px;">Websites en webapplicaties op maat</p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td class="inset ink" style="padding: 32px; color: #0f172a; font-size: 16px; line-height: 26px; overflow-wrap: anywhere; word-wrap: break-word; word-break: break-word;">
                            @yield('content')
                        </td>
                    </tr>
                    <tr>
                        <td class="inset rule muted" style="padding: 20px 32px; border-top: 1px solid #e7e5e4; color: #475569; font-size: 13px; line-height: 21px; overflow-wrap: anywhere; word-wrap: break-word; word-break: break-word;">
                            Kevin Webstudio<br>
                            <a class="link" href="mailto:{{ rawurlencode($contactAddress) }}" style="color: #334155; text-decoration: underline;">{{ $contactAddress }}</a>
                        </td>
                    </tr>
                </table>
                <!--[if mso]></td></tr></table><![endif]-->
            </td>
        </tr>
    </table>
</body>
</html>
