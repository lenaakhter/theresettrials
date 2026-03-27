@extends('layouts.admin')

@section('content')
    <main class="admin-posts">
        <div class="admin-posts__header">
            <div>
                <h1 class="admin-posts__title">Add Admin Account</h1>
                <p class="admin-posts__subtitle">Create another account with admin access.</p>
            </div>
        </div>

        @if (session('status'))
            <div class="admin-flash admin-flash--success" data-flash>
                <span>{{ session('status') }}</span>
                <button type="button" class="admin-flash__close" data-flash-close aria-label="Dismiss notification">&times;</button>
            </div>
        @endif

        @if ($errors->any())
            <div class="admin-flash admin-flash--error" data-flash>
                <span>{{ $errors->first() }}</span>
                <button type="button" class="admin-flash__close" data-flash-close aria-label="Dismiss notification">&times;</button>
            </div>
        @endif

        @if (session('error'))
            <div class="admin-flash admin-flash--error" data-flash>
                <span>{{ session('error') }}</span>
                <button type="button" class="admin-flash__close" data-flash-close aria-label="Dismiss notification">&times;</button>
            </div>
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
                        <div>
                            <p class="admin-recent__item-title">{{ $adminUser->name }}</p>
                            <p class="admin-recent__item-meta">{{ $adminUser->email }}</p>
                        </div>
                        @if ($adminUser->id !== auth()->id())
                            <form method="POST" action="{{ route('admin.admins.destroy', $adminUser) }}" onsubmit="return confirm('Revoke admin access for {{ addslashes($adminUser->name) }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="admin-recent__item-revoke">Revoke</button>
                            </form>
                        @endif
                    </div>
                @empty
                    <p class="admin-recent__empty">No admin accounts yet.</p>
                @endforelse
            </aside>
        </section>
    </main>
    <script>
    document.addEventListener('click', function (event) {
        const closeButton = event.target.closest('[data-flash-close]');
        if (closeButton) {
            const flash = closeButton.closest('[data-flash]');
            if (flash) {
                flash.remove();
            }
        }
    });
    </script>
@endsection
