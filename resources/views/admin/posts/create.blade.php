@extends('layouts.admin')

@section('content')
    <main class="admin-posts">
        <div class="admin-posts__header">
            <div>
                <h1 class="admin-posts__title">Write a Post</h1>
                <p class="admin-posts__subtitle">Create and publish a new blog entry.</p>
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
            <form method="POST" action="{{ route('admin.posts.store') }}" class="admin-editor" enctype="multipart/form-data">
                @csrf

                <label for="title" class="admin-form__label">Title</label>
                <input id="title" name="title" type="text" value="{{ old('title') }}" required class="admin-form__input">

                <label for="excerpt" class="admin-form__label">Excerpt</label>
                <textarea id="excerpt" name="excerpt" rows="3" class="admin-form__textarea">{{ old('excerpt') }}</textarea>

                <label for="content" class="admin-form__label">Post Content</label>
                <textarea id="content" name="content" rows="12" required class="admin-form__textarea">{{ old('content') }}</textarea>

                <label for="cover_image_upload" class="admin-form__label">Cover Image (optional)</label>
                <input id="cover_image_upload" name="cover_image_upload" type="file" accept="image/*" class="admin-form__input">
                <p class="admin-posts__subtitle" style="margin: -0.35rem 0 0.5rem;">Upload JPG, PNG, WEBP, or GIF (max 4MB).</p>

                <label for="category" class="admin-form__label">Category (optional)</label>
                <select id="category" name="category" class="admin-form__input">
                    <option value="">— None —</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ old('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>

                <label for="published_at" class="admin-form__label">Publish Date/Time (optional)</label>
                <input id="published_at" name="published_at" type="datetime-local" value="{{ old('published_at') }}" class="admin-form__input">
                <p class="admin-posts__subtitle" style="margin: -0.35rem 0 0.5rem;">Leave blank to publish immediately.</p>

                <button type="submit" class="admin-form__button">Save Post</button>
            </form>
        </section>
    </main>
@endsection
