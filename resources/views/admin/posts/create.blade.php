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

        <section class="admin-layout">
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
                <input id="category" name="category" type="text" value="{{ old('category') }}" placeholder="Supplements" class="admin-form__input">

                <label for="published_at" class="admin-form__label">Publish Date/Time (optional)</label>
                <input id="published_at" name="published_at" type="datetime-local" value="{{ old('published_at') }}" class="admin-form__input">

                <label class="admin-form__check-wrap">
                    <input type="checkbox" name="publish_now" value="1" {{ old('publish_now') ? 'checked' : '' }}>
                    <span>Publish now</span>
                </label>

                <button type="submit" class="admin-form__button">Save Post</button>
            </form>

            <aside class="admin-recent">
                <h2 class="admin-recent__title">Recent Posts</h2>
                @forelse ($recentPosts as $post)
                    <div class="admin-recent__item">
                        <p class="admin-recent__item-title">
                            <a href="{{ route('admin.posts.edit', $post) }}" class="admin-recent__item-link">{{ $post->title }}</a>
                        </p>
                        <p class="admin-recent__item-meta">/{{ $post->slug }}</p>
                    </div>
                @empty
                    <p class="admin-recent__empty">No posts yet.</p>
                @endforelse
            </aside>
        </section>
    </main>
@endsection
