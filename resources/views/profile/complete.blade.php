@extends('layouts.app')

@section('content')
<section class="reader-auth">
    <div class="reader-auth__card">
        <h1 class="reader-auth__title">Complete your profile</h1>
        <p class="reader-auth__subtitle">Before continuing, please set your display name and username.</p>

        @if ($errors->any())
            <div class="reader-auth__error dismissible-notice" data-dismissible-notice>
                <span>{{ $errors->first() }}</span>
                <button type="button" class="dismissible-notice__close" data-notice-close aria-label="Dismiss notification">&times;</button>
            </div>
        @endif

        <form method="POST" action="{{ route('profile.complete.update') }}" class="admin-form">
            @csrf
            @method('PUT')

            <label for="display_name" class="admin-form__label">Display name</label>
            <input
                id="display_name"
                name="display_name"
                type="text"
                value="{{ old('display_name', $user->display_name ?: $user->name) }}"
                class="admin-form__input"
                required
                autocomplete="nickname"
            >

            <label for="username" class="admin-form__label">Username</label>
            <input
                id="username"
                name="username"
                type="text"
                value="{{ old('username', $suggestedUsername) }}"
                class="admin-form__input"
                placeholder="letters, numbers, and underscores"
                required
                autocomplete="username"
                {{ ! $canEditUsername ? 'readonly' : '' }}
            >

            @if (! $canEditUsername && $nextUsernameChangeAt)
                <p class="profile-page__hint">You can change your username again on {{ $nextUsernameChangeAt->format('M j, Y') }}.</p>
            @endif

            <button type="submit" class="admin-form__button">Continue</button>
        </form>
    </div>
</section>
@endsection
