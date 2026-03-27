<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Ban Notice</title>
</head>
<body style="margin:0;padding:0;background:#f7f3f5;font-family:Arial,sans-serif;color:#4d3b42;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="padding:24px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="560" cellspacing="0" cellpadding="0" style="max-width:560px;background:#fff;border:1px solid #eadde2;border-radius:14px;overflow:hidden;">
                    <tr>
                        <td style="background:#c56a7f;color:#fff;padding:18px 22px;font-size:22px;font-weight:700;">
                            The Reset Trials
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:22px;">
                            <h1 style="margin:0 0 12px;font-size:24px;color:#9a4e61;">Temporary account ban</h1>
                            <p style="margin:0 0 14px;line-height:1.6;">Hi {{ $user->display_name ?: $user->name }}, your account has been temporarily banned for <strong>{{ $hours }} hour{{ $hours === 1 ? '' : 's' }}</strong>.</p>
                            <p style="margin:0 0 8px;font-weight:700;color:#6a4f58;">Reason:</p>
                            <p style="margin:0 0 14px;line-height:1.6;background:#f9eef2;border:1px solid #efd9e1;border-radius:10px;padding:12px;">{{ $reason }}</p>
                            <p style="margin:0 0 8px;"><strong>Ban ID:</strong> {{ $banId }}</p>
                            <p style="margin:14px 0;">
                                <a href="{{ $appealUrl }}" style="display:inline-block;background:#c56a7f;color:#fff;text-decoration:none;padding:10px 16px;border-radius:999px;font-weight:700;">Appeal this ban</a>
                            </p>
                            <p style="margin:0;line-height:1.6;">After the ban window ends, you can sign in again as normal.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
