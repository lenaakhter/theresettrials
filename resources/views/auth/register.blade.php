<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <x-navbar />
    <main class="max-w-6xl mx-auto p-6">
        <h1>Register</h1>
        <p>Create your account. This is the register page.</p>
    </main>
</body>
</html>
