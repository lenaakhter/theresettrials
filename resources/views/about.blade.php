<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>About Us</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <x-navbar />
    <main class="max-w-6xl mx-auto p-6">
        <h1>About Us</h1>
        <p>Welcome to our blog. This is the about page.</p>
    </main>
</body>
</html>
