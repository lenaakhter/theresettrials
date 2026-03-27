@extends('layouts.app')

@section('content')
<section class="reader-auth">
    <div class="reader-auth__card">
        <h1 class="reader-auth__title">Reset password</h1>
        <p class="reader-auth__subtitle">Enter your email and we'll send you a link to reset your password.</p>

        @if (session('status'))
            <div class="reader-auth__success dismissible-notice" data-dismissible-notice>
                <span>{{ session('status') }}</span>
                <button type="button" class="dismissible-notice__close" data-notice-close aria-label="Dismiss notification">&times;</button>
            </div>
        @endif

        @if ($errors->any())
            <div class="reader-auth__error dismissible-notice" data-dismissible-notice>
                <span>{{ $errors->first() }}</span>
                <button type="button" class="dismissible-notice__close" data-notice-close aria-label="Dismiss notification">&times;</button>
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="admin-form">
            @csrf

            <label for="email" class="admin-form__label">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="email" class="admin-form__input">

            <button type="submit" class="admin-form__button">Send reset link</button>
        </form>

        <p class="reader-auth__switch"><a href="{{ route('login') }}">Back to log in</a></p>
    </div>
</section>
@endsection
