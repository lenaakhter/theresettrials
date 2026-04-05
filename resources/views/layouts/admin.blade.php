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
        <a href="{{ route('admin.experiments.index') }}" class="admin-navbar__brand">Admin Lair</a>
        <ul class="admin-nav-items">
            <li class="admin-nav-items__group">
                <details class="admin-nav-dropdown">
                    <summary>Content</summary>
                    <div class="admin-nav-dropdown__menu">
                        <a href="{{ route('admin.posts.index') }}">Posts</a>
                        <a href="{{ route('admin.posts.create') }}">New Post</a>
                        <a href="{{ route('admin.experiments.index') }}">Experiments</a>
                        <a href="{{ route('admin.resources.index') }}">Resources</a>
                        <a href="{{ route('admin.categories.index') }}">Categories</a>
                    </div>
                </details>
            </li>
            <li class="admin-nav-items__group">
                <details class="admin-nav-dropdown">
                    <summary>Pages</summary>
                    <div class="admin-nav-dropdown__menu">
                        <a href="{{ route('admin.pages.about.edit') }}">Edit About</a>
                        <a href="{{ route('admin.pages.disclaimer.edit') }}">Edit Disclaimer</a>
                    </div>
                </details>
            </li>
            <li><a href="{{ route('admin.subscribers.index') }}">Subscribers</a></li>
            <li><a href="{{ route('admin.admins.create') }}">Admins</a></li>
            <li><a href="/" class="admin-toggle">Back to Site</a></li>
            <li>
                <form action="{{ route('admin.logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="admin-nav-items__logout">Logout</button>
                </form>
            </li>
        </ul>
    </nav>

    <main class="app-body">
        @yield('content')
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

        const adminDropdowns = document.querySelectorAll('.admin-nav-dropdown');
        const dropdownCloseTimers = new WeakMap();

        const closeAdminDropdown = (dropdown) => {
            if (dropdown?.hasAttribute('open')) {
                dropdown.removeAttribute('open');
            }
        };

        const clearDropdownCloseTimer = (dropdown) => {
            const timerId = dropdownCloseTimers.get(dropdown);
            if (!timerId) {
                return;
            }

            window.clearTimeout(timerId);
            dropdownCloseTimers.delete(dropdown);
        };

        const scheduleDropdownClose = (dropdown) => {
            clearDropdownCloseTimer(dropdown);
            const timerId = window.setTimeout(() => closeAdminDropdown(dropdown), 180);
            dropdownCloseTimers.set(dropdown, timerId);
        };

        adminDropdowns.forEach((dropdown) => {
            dropdown.addEventListener('mouseenter', () => clearDropdownCloseTimer(dropdown));
            dropdown.addEventListener('mouseleave', () => scheduleDropdownClose(dropdown));

            dropdown.addEventListener('toggle', () => {
                if (!dropdown.open) {
                    return;
                }

                adminDropdowns.forEach((otherDropdown) => {
                    if (otherDropdown !== dropdown) {
                        clearDropdownCloseTimer(otherDropdown);
                        closeAdminDropdown(otherDropdown);
                    }
                });
            });
        });

        document.addEventListener('click', (event) => {
            if (event.target.closest('.admin-nav-dropdown')) {
                return;
            }

            adminDropdowns.forEach((dropdown) => {
                clearDropdownCloseTimer(dropdown);
                closeAdminDropdown(dropdown);
            });
        });
    </script>
    @stack('scripts')
</body>
</html>
