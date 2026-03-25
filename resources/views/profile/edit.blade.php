@extends('layouts.app')

@section('content')
<section class="profile-page">
    <div class="profile-page__card">
        <h1 class="profile-page__title">Your Profile</h1>
        <p class="profile-page__subtitle">Set your display name and profile photo for comments.</p>

        @if (session('status'))
            <div class="profile-page__success">{{ session('status') }}</div>
        @endif

        @if ($errors->any())
            <div class="reader-auth__error">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('profile.update') }}" class="admin-form" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <label for="display_name" class="admin-form__label">Display name</label>
            <input id="display_name" name="display_name" type="text" value="{{ old('display_name', $user->display_name) }}" class="admin-form__input" placeholder="How your name appears in comments">

            <label for="profile_photo" class="admin-form__label">Profile photo (optional)</label>
            <input id="profile_photo" name="profile_photo" type="file" accept="image/*" class="admin-form__input">

            @if ($user->profile_photo)
                <div class="profile-page__avatar-preview-wrap">
                    <img src="{{ asset($user->profile_photo) }}" alt="Current profile photo" class="profile-page__avatar-preview">
                </div>
            @endif

            <button type="submit" class="admin-form__button">Save profile</button>
        </form>
    </div>
</section>
@endsection
