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
    @stack('scripts')
</body>
</html>