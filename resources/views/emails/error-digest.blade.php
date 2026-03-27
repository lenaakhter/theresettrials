<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error Digest</title>
</head>
<body style="margin:0;padding:0;background:#f7f3f5;font-family:Arial,sans-serif;color:#4d3b42;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="padding:24px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="700" cellspacing="0" cellpadding="0" style="max-width:700px;background:#fff;border:1px solid #eadde2;border-radius:14px;overflow:hidden;">
                    <tr>
                        <td style="background:#c56a7f;color:#fff;padding:18px 22px;font-size:22px;font-weight:700;">The Reset Trials</td>
                    </tr>
                    <tr>
                        <td style="padding:22px;">
                            <h1 style="margin:0 0 10px;font-size:22px;color:#9a4e61;">Daily error digest</h1>
                            <p style="margin:0 0 6px;"><strong>Date:</strong> {{ $digest['date'] ?? 'n/a' }}</p>
                            <p style="margin:0 0 6px;"><strong>Environment:</strong> {{ $digest['environment'] ?? 'n/a' }}</p>
                            <p style="margin:0 0 14px;"><strong>Total captured:</strong> {{ $digest['total'] ?? 0 }}</p>

                            @if (! empty($digest['items']))
                                @foreach ($digest['items'] as $item)
                                    <div style="margin-bottom:14px;padding:12px;border:1px solid #efd9e1;background:#f9eef2;border-radius:10px;">
                                        <p style="margin:0 0 6px;"><strong>{{ $item['status_code'] ?? 'n/a' }}</strong> • {{ $item['exception_class'] ?? 'n/a' }}</p>
                                        <p style="margin:0 0 6px;"><strong>Count:</strong> {{ $item['count'] ?? 0 }}</p>
                                        <p style="margin:0 0 6px;"><strong>Message:</strong> {{ $item['message'] ?? 'n/a' }}</p>
                                        <p style="margin:0 0 6px;"><strong>URL:</strong> {{ $item['url'] ?? 'n/a' }}</p>
                                        <p style="margin:0 0 6px;"><strong>Method:</strong> {{ $item['method'] ?? 'n/a' }}</p>
                                        <p style="margin:0 0 6px;"><strong>User ID:</strong> {{ $item['user_id'] ?? 'guest' }}</p>
                                        <p style="margin:0 0 6px;"><strong>IP:</strong> {{ $item['ip'] ?? 'n/a' }}</p>
                                        <p style="margin:0 0 6px;"><strong>First seen:</strong> {{ $item['first_seen'] ?? 'n/a' }}</p>
                                        <p style="margin:0;"><strong>Last seen:</strong> {{ $item['last_seen'] ?? 'n/a' }}</p>
                                    </div>
                                @endforeach
                            @else
                                <p style="margin:0;">No errors were captured.</p>
                            @endif
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
