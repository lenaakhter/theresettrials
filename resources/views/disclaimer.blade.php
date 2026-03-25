<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Disclaimer</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <x-navbar />
    <main class="max-w-6xl mx-auto p-6">
        <h1>Disclaimer</h1>
        <p>This website shares personal experiences for informational purposes only and is not medical advice.</p>
        <p>Please consult a qualified healthcare professional before making health decisions.</p>
    </main>
</body>
</html>
