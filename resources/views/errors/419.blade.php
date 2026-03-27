<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Session Timed Out</title>
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

        .timeout-card {
            width: min(680px, 100%);
            background: #fff8fa;
            border: 1px solid #e6d6dc;
            border-radius: 18px;
            box-shadow: 0 20px 40px rgba(106, 79, 88, 0.14);
            padding: 1.25rem;
            text-align: center;
        }

        .timeout-image {
            width: min(240px, 52vw);
            height: auto;
            display: block;
            margin: 0 auto 0.75rem;
        }

        h1 {
            margin: 0;
            color: #b05a6e;
            font-family: "Playfair Display", Georgia, serif;
            font-size: clamp(1.65rem, 3.5vw, 2.1rem);
        }

        p {
            margin: 0.8rem 0 0;
            font-size: 1rem;
            line-height: 1.6;
        }

        .timeout-link {
            display: inline-block;
            margin-top: 1rem;
            text-decoration: none;
            border: 2px solid #c56a7f;
            color: #c56a7f;
            padding: 0.5rem 1rem;
            border-radius: 999px;
            font-weight: 700;
        }

        .timeout-link:hover {
            background: #c56a7f;
            color: #fff;
        }
    </style>
</head>
<body>
    <main class="timeout-card" role="main" aria-live="polite">
        <img src="{{ asset('images/waving.PNG') }}" alt="Character waving" class="timeout-image">
        <h1>This session has timed out.</h1>
        <p>Sending you back to the home page now.</p>
        <a class="timeout-link" href="{{ route('home') }}">Go now</a>
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
