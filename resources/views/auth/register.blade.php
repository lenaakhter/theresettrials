@extends('layouts.app')

@section('content')
<section class="reader-auth">
    <div class="reader-auth__card">
        <h1 class="reader-auth__title">Create account</h1>
        <p class="reader-auth__subtitle">Sign up to comment, reply, and personalize your profile.</p>

        @if ($errors->any())
            <div class="reader-auth__error">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('register.store') }}" class="admin-form">
            @csrf

            <label for="name" class="admin-form__label">Name</label>
            <input id="name" name="name" type="text" value="{{ old('name') }}" required class="admin-form__input">

            <label for="display_name" class="admin-form__label">Display name (optional)</label>
            <input id="display_name" name="display_name" type="text" value="{{ old('display_name') }}" class="admin-form__input">

            <label for="email" class="admin-form__label">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="email" class="admin-form__input">

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
