<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Error' }}</title>
    <meta http-equiv="refresh" content="4;url={{ route('home') }}">
    <style>
        :root {
            color-scheme: light;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 1.25rem;
            font-family: "Quicksand", "Segoe UI", sans-serif;
            background: radial-gradient(circle at top, #f8edef 0%, #f4ecef 40%, #efe4e7 100%);
            color: #4d3b42;
        }

        .error-card {
            width: min(680px, 100%);
            background: #fff8fa;
            border: 1px solid #e6d6dc;
            border-radius: 18px;
            box-shadow: 0 20px 40px rgba(106, 79, 88, 0.14);
            padding: 1.25rem;
            text-align: center;
        }

        .error-image {
            width: min(240px, 52vw);
            height: auto;
            display: block;
            margin: 0 auto 0.75rem;
        }

        .error-code {
            margin: 0;
            color: #8C7B7F;
            font-weight: 700;
            letter-spacing: 0.04em;
            font-size: 0.8rem;
        }

        .error-title {
            margin: 0.35rem 0 0;
            color: #b05a6e;
            font-family: "Playfair Display", Georgia, serif;
            font-size: clamp(1.65rem, 3.5vw, 2.1rem);
        }

        .error-message {
            margin: 0.8rem 0 0;
            font-size: 1rem;
            line-height: 1.6;
        }

        .error-sub {
            margin: 0.2rem 0 0;
            color: #7e6a71;
            font-size: 0.95rem;
        }

        .error-link {
            display: inline-block;
            margin-top: 1rem;
            text-decoration: none;
            border: 2px solid #c56a7f;
            color: #c56a7f;
            padding: 0.5rem 1rem;
            border-radius: 999px;
            font-weight: 700;
        }

        .error-link:hover {
            background: #c56a7f;
            color: #fff;
        }
    </style>
</head>
<body>
    <main class="error-card" role="main" aria-live="polite">
        <img src="{{ asset($image ?? 'images/thinking.PNG') }}" alt="Error character" class="error-image">
        <p class="error-code">ERROR {{ $statusCode ?? 500 }}</p>
        <h1 class="error-title">{{ $title ?? 'Something went wrong' }}</h1>
        <p class="error-message">Sorry, you've hit an error. We'll send this to the team.</p>
        <p class="error-sub">Sending you back to the home page.</p>
        <a class="error-link" href="{{ route('home') }}">Go now</a>
    </main>

    <script>
        (() => {
            const homeUrl = "{{ route('home') }}";
            window.setTimeout(() => {
                window.location.replace(homeUrl + "?refresh=" + Date.now());
            }, 2500);
        })();
    </script>
</body>
</html>
