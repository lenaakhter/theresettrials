<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('images/waving.PNG') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <title>Admin - The Reset Trials</title>
</head>
<body>
    <nav class="admin-navbar">
        <h2>Admin Lair</h2>
        <ul class="admin-nav-items">
            <li><a href="{{ route('admin.posts.create') }}">New Post</a></li>
            <li><a href="{{ route('admin.experiments.index') }}">Experiments</a></li>
            <li><a href="{{ route('admin.subscribers.index') }}">Subscribers</a></li>
            <li><a href="{{ route('admin.admins.create') }}">New Admin</a></li>
            <li><a href="/" class="admin-toggle">← Back to Site</a></li>
            <li>
                <form action="{{ route('admin.logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit">Logout</button>
                </form>
            </li>
        </ul>
    </nav>

    <main class="app-body">
        @yield('content')
    </main>
</body>
</html>
