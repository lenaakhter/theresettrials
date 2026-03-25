<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset your password — The Reset Trials</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=Quicksand:wght@400;500;600&display=swap');

        body {
            margin: 0;
            padding: 0;
            background-color: #F7F3F4;
            font-family: 'Quicksand', Arial, sans-serif;
            color: #3a2e31;
        }

        .wrapper {
            width: 100%;
            padding: 40px 16px;
            background-color: #F7F3F4;
        }

        .card {
            max-width: 540px;
            margin: 0 auto;
            background: #EFE8EB;
            border: 1px solid #E2D9DD;
            border-radius: 16px;
            overflow: hidden;
        }

        .header {
            background: #c56a7f;
            padding: 32px 40px;
            text-align: center;
        }

        .header__wordmark {
            font-family: 'Playfair Display', Georgia, serif;
            font-size: 22px;
            font-weight: 600;
            color: #fff;
            letter-spacing: 0.03em;
            margin: 0;
        }

        .body {
            padding: 36px 40px 28px;
        }

        .body__title {
            font-family: 'Playfair Display', Georgia, serif;
            font-size: 22px;
            color: #c56a7f;
            margin: 0 0 12px;
        }

        .body__text {
            font-size: 15px;
            line-height: 1.7;
            color: #5A5A5A;
            margin: 0 0 28px;
        }

        .btn-wrap {
            text-align: center;
            margin-bottom: 28px;
        }

        .btn {
            display: inline-block;
            background: #c56a7f;
            color: #fff !important;
            text-decoration: none;
            font-family: 'Quicksand', Arial, sans-serif;
            font-weight: 600;
            font-size: 15px;
            padding: 14px 36px;
            border-radius: 10px;
            letter-spacing: 0.02em;
        }

        .fallback {
            font-size: 13px;
            color: #8C7B7F;
            line-height: 1.6;
            margin: 0 0 8px;
        }

        .fallback a {
            color: #c56a7f;
            word-break: break-all;
        }

        .expiry {
            font-size: 13px;
            color: #8C7B7F;
            margin: 16px 0 0;
        }

        .footer {
            border-top: 1px solid #E2D9DD;
            padding: 20px 40px;
            text-align: center;
            font-size: 12px;
            color: #8C7B7F;
            line-height: 1.6;
        }

        .footer a {
            color: #c56a7f;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="card">
            <div class="header">
                <p class="header__wordmark">The Reset Trials</p>
            </div>
            <div class="body">
                <h1 class="body__title">Reset your password</h1>
                <p class="body__text">
                    We received a request to reset the password for your account.
                    Click the button below to choose a new one. If you didn't request this, you can safely ignore this email — your password won't change.
                </p>
                <div class="btn-wrap">
                    <a href="{{ $url }}" class="btn">Reset my password</a>
                </div>
                <p class="fallback">
                    Button not working? Copy and paste this link into your browser:<br>
                    <a href="{{ $url }}">{{ $url }}</a>
                </p>
                <p class="expiry">This link will expire in 60 minutes.</p>
            </div>
            <div class="footer">
                &copy; {{ date('Y') }} The Reset Trials &mdash; You're receiving this because a password reset was requested for your account.
            </div>
        </div>
    </div>
</body>
</html>
