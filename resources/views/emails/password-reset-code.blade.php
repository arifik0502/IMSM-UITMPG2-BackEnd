<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Password Reset Code</title>
</head>
<body style="margin:0; padding:0; background-color:#f8fafc; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="padding: 32px 16px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" style="max-width: 480px; background-color:#ffffff; border-radius:16px; padding: 32px; border: 1px solid #e2e8f0;">
                    <tr>
                        <td style="text-align:center; padding-bottom: 16px;">
                            <span style="font-size:18px; font-weight:700; color:#1e293b;">{{ config('app.name') }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <p style="color:#334155; font-size:15px; line-height:1.5; margin: 0 0 8px;">Hi {{ $user->name }},</p>
                            <p style="color:#334155; font-size:15px; line-height:1.5; margin: 0 0 24px;">
                                Use the verification code below to reset your password. This code will expire in {{ config('auth.passwords.users.expire') }} minutes.
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:center; padding: 8px 0 24px;">
                            <span style="display:inline-block; font-size:32px; font-weight:700; letter-spacing: 8px; color:#4f46e5; background:#eef2ff; padding: 14px 24px; border-radius: 12px;">
                                {{ $code }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <p style="color:#64748b; font-size:13px; line-height:1.5; margin:0;">
                                If you didn't request a password reset, you can safely ignore this email &mdash; your password won't be changed.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>