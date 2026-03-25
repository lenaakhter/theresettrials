<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/waving.PNG') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <title>Admin · Add Admin</title>
</head>
<body class="app-body">
    <main class="admin-posts">
        <div class="admin-posts__header">
            <div>
                <h1 class="admin-posts__title">Add Admin Account</h1>
                <p class="admin-posts__subtitle">Create another account with admin access.</p>
            </div>
            <div class="admin-actions">
                <a href="{{ route('admin.posts.create') }}" class="admin-posts__logout admin-posts__logout--link">Write post</a>
                <a href="{{ route('admin.subscribers.index') }}" class="admin-posts__logout admin-posts__logout--link">Subscribers</a>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="admin-posts__logout">Log out</button>
                </form>
            </div>
        </div>

        @if (session('status'))
            <div class="admin-posts__success">{{ session('status') }}</div>
        @endif

        @if ($errors->any())
            <div class="admin-posts__error">{{ $errors->first() }}</div>
        @endif

        <section class="admin-layout">
            <form method="POST" action="{{ route('admin.admins.store') }}" class="admin-editor admin-form">
                @csrf

                <label for="name" class="admin-form__label">Name</label>
                <input id="name" name="name" type="text" value="{{ old('name') }}" required class="admin-form__input">

                <label for="email" class="admin-form__label">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required class="admin-form__input" autocomplete="email">

                <label for="password" class="admin-form__label">Password</label>
                <input id="password" name="password" type="password" required class="admin-form__input" autocomplete="new-password">

                <label for="password_confirmation" class="admin-form__label">Confirm Password</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required class="admin-form__input" autocomplete="new-password">

                <button type="submit" class="admin-form__button">Create Admin</button>
            </form>

            <aside class="admin-recent">
                <h2 class="admin-recent__title">Recent Admins</h2>
                @forelse ($recentAdmins as $adminUser)
                    <div class="admin-recent__item">
                        <p class="admin-recent__item-title">{{ $adminUser->name }}</p>
                        <p class="admin-recent__item-meta">{{ $adminUser->email }}</p>
                    </div>
                @empty
                    <p class="admin-recent__empty">No admin accounts yet.</p>
                @endforelse
            </aside>
        </section>
    </main>
</body>
</html>
