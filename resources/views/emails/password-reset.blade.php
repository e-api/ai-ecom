<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reset Your Password</title>
</head>

<body style="margin:0; padding:20px; background-color:#f3f4f6; font-family: Arial, Helvetica, sans-serif; line-height:1.6; color:#333333;">

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:600px; margin:0 auto;">
        <tr>
            <td style="background-color:#ffffff; border:1px solid #e5e7eb; border-radius:10px; padding:32px;">

                {{-- Header --}}
                <h2 style="margin:0 0 20px; color:#111827; font-size:22px; font-weight:bold;">
                    Password Reset Request
                </h2>

                {{-- Greeting --}}
                <p style="margin:0 0 14px;">
                    Hello {{ $user->name }},
                </p>

                <p style="margin:0 0 14px;">
                    We received a request to reset the password
                    for your account.
                </p>

                <p style="margin:0 0 14px;">
                    Click the button below to create a new password.
                </p>

                {{-- Button --}}
                <p style="margin:28px 0;">
                    <a href="{{ $resetUrl }}"
                        style="display:inline-block; background-color:#198754; color:#ffffff; padding:12px 24px; text-decoration:none; border-radius:6px; font-weight:bold;">
                        Reset Password
                    </a>
                </p>

                {{-- Expiry --}}
                <p style="margin:0 0 14px;">
                    This password reset link will expire after
                    {{ config('auth.passwords.users.expire') }} minutes.
                </p>

                {{-- Fallback URL --}}
                <p style="margin:0 0 14px; word-break:break-all;">
                    If the button does not work, copy and paste
                    this link into your browser:
                    <br>
                    <a href="{{ $resetUrl }}" style="color:#198754;">
                        {{ $resetUrl }}
                    </a>
                </p>

                {{-- Ignored request --}}
                <p style="margin:0 0 14px;">
                    If you did not request a password reset,
                    you can safely ignore this email.
                </p>

                {{-- Footer --}}
                <p style="margin:0;">
                    Regards,<br>
                    <strong>{{ config('app.name') }}</strong>
                </p>

                <hr style="border:none; border-top:1px solid #e5e7eb; margin:24px 0;">

                <p style="margin:0; font-size:12px; color:#777777;">
                    This is an automated email. Please do not reply
                    directly to this message.
                </p>

            </td>
        </tr>
    </table>

</body>

</html>