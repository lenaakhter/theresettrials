<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('images/waving.PNG') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <title>The Reset Trials</title>
</head>
<body>
    <x-navbar />
    @unless(request()->routeIs('join.*'))
        <x-mailing-popup />
    @endunless
    @yield('content')
    <x-footer />
    <script>
        document.addEventListener('click', (event) => {
            const closeButton = event.target.closest('[data-notice-close]');
            if (!closeButton) {
                return;
            }

            const notice = closeButton.closest('[data-dismissible-notice]');
            if (notice) {
                notice.remove();
            }
        });
    </script>
    @stack('scripts')
</body>
</html>