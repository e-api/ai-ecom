<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to AI Powered E-commerce</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f4f4f4; font-family: Arial, Helvetica, sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f4f4f4; padding: 20px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">

                    <!-- Header -->
                    <tr>
                        <td style="background-color: #232f3e; padding: 24px 32px; text-align: center;">
                            <h1 style="margin: 0; color: #ffffff; font-size: 22px; font-weight: 700;">AI Powered E-commerce</h1>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding: 40px 32px;">
                            <h2 style="margin: 0 0 6px 0; color: #333333; font-size: 20px;">Welcome, {{ $user->name }}!</h2>
                            <p style="margin: 0 0 20px 0; color: #666666; font-size: 14px;">Thank you for choosing AI Powered E-commerce.</p>

                            <div style="background-color: #fafafa; border: 1px solid #e0e0e0; border-radius: 6px; padding: 20px; margin-bottom: 24px;">
                                <p style="margin: 0 0 4px 0; color: #555555; font-size: 13px; font-weight: 600;">Registered Email</p>
                                <p style="margin: 0 0 14px 0; color: #333333; font-size: 15px;">{{ $user->email }}</p>
                                <p style="margin: 0 0 4px 0; color: #555555; font-size: 13px; font-weight: 600;">Registration Date</p>
                                <p style="margin: 0; color: #333333; font-size: 15px;">{{ $user->created_at->format('F d, Y') }}</p>
                            </div>

                            <p style="margin: 0 0 16px 0; color: #333333; font-size: 14px; line-height: 1.6;">
                                Your account has been created successfully. You can now browse our catalog, track orders, and enjoy a personalized shopping experience.
                            </p>

                            <table cellpadding="0" cellspacing="0" style="margin: 0 auto 24px auto;">
                                <tr>
                                    <td style="background-color: #ff9900; border-radius: 24px; text-align: center; padding: 12px 36px;">
                                        <a href="{{ url('/') }}" style="color: #000000; text-decoration: none; font-size: 15px; font-weight: 700; display: inline-block;">Start Shopping</a>
                                    </td>
                                </tr>
                            </table>

                            <hr style="border: none; border-top: 1px solid #e0e0e0; margin: 0 0 16px 0;">

                            <p style="margin: 0; color: #888888; font-size: 12px; line-height: 1.5;">
                                If you did not create this account, please ignore this email or
                                <a href="mailto:info@site.1byte.pp.ua" style="color: #0066c0; text-decoration: none;">contact support</a>.
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #232f3e; padding: 20px 32px; text-align: center;">
                            <p style="margin: 0 0 6px 0; color: #aaaaaa; font-size: 12px;">&copy; {{ date('Y') }} AI Powered E-commerce. All rights reserved.</p>
                            <p style="margin: 0; color: #aaaaaa; font-size: 12px;">
                                <a href="#" style="color: #cccccc; text-decoration: none; margin: 0 8px;">Help</a> |
                                <a href="#" style="color: #cccccc; text-decoration: none; margin: 0 8px;">Privacy Policy</a> |
                                <a href="#" style="color: #cccccc; text-decoration: none; margin: 0 8px;">Terms of Service</a>
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>