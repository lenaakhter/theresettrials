<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Links & Resources</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <x-navbar />
    <main class="max-w-6xl mx-auto p-6">
        <h1>Links & Resources</h1>
        <p>A curated list of helpful PCOS resources, tools, and references will live here.</p>
    </main>
</body>
</html>
