@extends('layouts.app')

@section('content')
<section class="reader-auth">
    <div class="reader-auth__card">
        <h1 class="reader-auth__title">Choose new password</h1>
        <p class="reader-auth__subtitle">Almost there — enter a new password below.</p>

        @if ($errors->any())
            <div class="reader-auth__error">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('password.update') }}" class="admin-form">
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">

            <label for="email" class="admin-form__label">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="email" class="admin-form__input">

            <label for="password" class="admin-form__label">New password</label>
            <input id="password" name="password" type="password" required autocomplete="new-password" class="admin-form__input">

            <label for="password_confirmation" class="admin-form__label">Confirm new password</label>
            <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password" class="admin-form__input">

            <button type="submit" class="admin-form__button">Reset password</button>
        </form>
    </div>
</section>
@endsection
