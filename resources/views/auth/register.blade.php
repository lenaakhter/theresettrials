@extends('layouts.app')

@section('content')
<section class="reader-auth">
    <div class="reader-auth__card">
        <h1 class="reader-auth__title">Create account</h1>
        <p class="reader-auth__subtitle">Sign up to comment, reply, and personalize your profile.</p>

        @if ($errors->any())
            <div class="reader-auth__error dismissible-notice" data-dismissible-notice>
                <span>{{ $errors->first() }}</span>
                <button type="button" class="dismissible-notice__close" data-notice-close aria-label="Dismiss notification">&times;</button>
            </div>
        @endif

        <form method="POST" action="{{ route('register.store') }}" class="admin-form">
            @csrf

            <label for="name" class="admin-form__label">Name</label>
            <input id="name" name="name" type="text" value="{{ old('name') }}" required class="admin-form__input">

            <label for="display_name" class="admin-form__label">Display name</label>
            <input id="display_name" name="display_name" type="text" value="{{ old('display_name', old('name')) }}" required class="admin-form__input">

            <label for="username" class="admin-form__label">Username</label>
            <input id="username" name="username" type="text" value="{{ old('username') }}" required class="admin-form__input" placeholder="letters, numbers, and underscores" autocomplete="username">

            <label for="email" class="admin-form__label">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="email" class="admin-form__input">

            <fieldset class="admin-form__fieldset">
                <legend class="admin-form__label">Email notifications</legend>
                <label class="admin-form__check-wrap">
                    <input type="radio" name="email_notifications_opt_in" value="1" {{ old('email_notifications_opt_in') === '1' ? 'checked' : '' }} required>
                    <span>Opt in to email updates</span>
                </label>
                <label class="admin-form__check-wrap">
                    <input type="radio" name="email_notifications_opt_in" value="0" {{ old('email_notifications_opt_in') === '0' ? 'checked' : '' }} required>
                    <span>Opt out of email updates</span>
                </label>
            </fieldset>

            <label for="password" class="admin-form__label">Password</label>
            <input id="password" name="password" type="password" required autocomplete="new-password" class="admin-form__input">

            <label for="password_confirmation" class="admin-form__label">Confirm password</label>
            <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password" class="admin-form__input">

            <button type="submit" class="admin-form__button">Create account</button>
        </form>

        <p class="reader-auth__switch">Already have an account? <a href="{{ route('login') }}">Log in</a></p>
    </div>
</section>
@endsection
