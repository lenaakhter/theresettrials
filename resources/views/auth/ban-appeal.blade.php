@extends('layouts.app')

@section('content')
<section class="reader-auth">
    <div class="reader-auth__card">
        <h1 class="reader-auth__title">Appeal your ban</h1>
        <p class="reader-auth__subtitle">Tell us why your account ban should be reviewed.</p>

        @if ($errors->any())
            <div class="reader-auth__error dismissible-notice" data-dismissible-notice>
                <span>{{ $errors->first() }}</span>
                <button type="button" class="dismissible-notice__close" data-notice-close aria-label="Dismiss notification">&times;</button>
            </div>
        @endif

        <form method="POST" action="{{ route('ban.appeal.store') }}" class="admin-form">
            @csrf

            <label for="ban_id" class="admin-form__label">Ban ID</label>
            <input id="ban_id" name="ban_id" type="text" value="{{ old('ban_id', $banId) }}" class="admin-form__input" readonly required>

            <label for="username" class="admin-form__label">Username</label>
            <input id="username" name="username" type="text" value="{{ old('username', $username) }}" class="admin-form__input" readonly required>

            <label for="appeal" class="admin-form__label">Appeal</label>
            <textarea id="appeal" name="appeal" rows="6" class="admin-form__textarea" required>{{ old('appeal') }}</textarea>

            <button type="submit" class="admin-form__button">Submit appeal</button>
        </form>
    </div>
</section>
@endsection
