@extends('layouts.admin')

@section('content')
    <main class="admin-posts">
        <div class="admin-posts__header">
            <div>
                <h1 class="admin-posts__title">Edit Disclaimer Page</h1>
                <p class="admin-posts__subtitle">Update the disclaimer title, body text, and highlighted note.</p>
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

        <section class="admin-layout admin-layout--single">
            <form method="POST" action="{{ route('admin.pages.disclaimer.update') }}" class="admin-editor">
                @csrf
                @method('PUT')

                <label for="title" class="admin-form__label">Page Title</label>
                <input id="title" name="title" type="text" value="{{ old('title', $disclaimerPage->title) }}" required class="admin-form__input">

                <label for="content" class="admin-form__label">Main Content</label>
                <textarea id="content" name="content" rows="14" required class="admin-form__textarea">{{ old('content', $disclaimerPage->content) }}</textarea>

                <label for="note" class="admin-form__label">Highlighted Red Box Text</label>
                <textarea id="note" name="note" rows="3" required class="admin-form__textarea">{{ old('note', $disclaimerNote->content) }}</textarea>

                <button type="submit" class="admin-form__button">Save Disclaimer Page</button>
            </form>
        </section>
    </main>
@endsection
