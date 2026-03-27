<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/waving.PNG') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <title>Admin Access</title>
</head>
<body class="app-body">
    <main class="admin-auth">
        <section class="admin-auth__card">
            <h1 class="admin-auth__title">Admin Lair</h1>
            <p class="admin-auth__subtitle">Secure sign in required.</p>

            @if ($errors->any())
                <div class="admin-auth__error dismissible-notice" data-dismissible-notice>
                    <span>{{ $errors->first() }}</span>
                    <button type="button" class="dismissible-notice__close" data-notice-close aria-label="Dismiss notification">&times;</button>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login.attempt') }}" class="admin-form">
                @csrf

                <label for="email" class="admin-form__label">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="username" class="admin-form__input">

                <label for="password" class="admin-form__label">Password</label>
                <input id="password" name="password" type="password" required autocomplete="current-password" class="admin-form__input">

                <label class="admin-form__check-wrap">
                    <input type="checkbox" name="remember" value="1">
                    <span>Remember me</span>
                </label>

                <button type="submit" class="admin-form__button">Log in</button>
            </form>
        </section>
    </main>
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
</body>
</html>
