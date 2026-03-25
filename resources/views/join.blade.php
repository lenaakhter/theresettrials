@extends('layouts.app')

@section('content')
<section class="join-page">
    <div class="join-page__inner">
        <h1 class="join-page__title">Join Us</h1>
        <p class="join-page__subtitle">Drop your email below and I’ll send updates when new experiments and posts go live.</p>

        @if (session('success'))
            <p class="join-page__message join-page__message--success">{{ session('success') }}</p>
        @endif

        @if ($errors->any())
            <div class="join-page__message join-page__message--error">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('join.store') }}" class="join-page__form">
            @csrf
            <label for="email" class="join-page__label">Email address</label>
            <input
                type="email"
                id="email"
                name="email"
                class="join-page__input"
                value="{{ old('email') }}"
                required
                autocomplete="email"
                placeholder="you@example.com"
            >

            <button type="submit" class="join-page__submit">Join the list</button>
        </form>
    </div>
</section>
@endsection
