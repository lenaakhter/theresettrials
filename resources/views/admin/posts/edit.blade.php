<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/waving.PNG') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <title>Admin · Edit Post</title>
</head>
<body class="app-body">
    <main class="admin-posts">
        <div class="admin-posts__header">
            <div>
                <h1 class="admin-posts__title">Edit Post</h1>
                <p class="admin-posts__subtitle">Update your existing post details.</p>
            </div>
            <div class="admin-actions">
                <a href="{{ route('admin.posts.create') }}" class="admin-posts__logout admin-posts__logout--link">Write post</a>
                <a href="{{ route('admin.subscribers.index') }}" class="admin-posts__logout admin-posts__logout--link">Subscribers</a>
                <a href="{{ route('admin.admins.create') }}" class="admin-posts__logout admin-posts__logout--link">Add admin</a>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="admin-posts__logout">Log out</button>
                </form>
            </div>
        </div>

        @if (session('status'))
            <div class="admin-posts__success">{{ session('status') }}</div>
        @endif

        @if ($errors->any())
            <div class="admin-posts__error">{{ $errors->first() }}</div>
        @endif

        <section class="admin-layout">
            <form method="POST" action="{{ route('admin.posts.update', $post) }}" class="admin-editor">
                @csrf
                @method('PUT')

                <label for="title" class="admin-form__label">Title</label>
                <input id="title" name="title" type="text" value="{{ old('title', $post->title) }}" required class="admin-form__input">

                <label for="excerpt" class="admin-form__label">Excerpt</label>
                <textarea id="excerpt" name="excerpt" rows="3" class="admin-form__textarea">{{ old('excerpt', $post->excerpt) }}</textarea>

                <label for="content" class="admin-form__label">Post Content</label>
                <textarea id="content" name="content" rows="12" required class="admin-form__textarea">{{ old('content', $post->content) }}</textarea>

                <label for="cover_image" class="admin-form__label">Cover Image Path (optional)</label>
                <input id="cover_image" name="cover_image" type="text" value="{{ old('cover_image', $post->cover_image) }}" placeholder="images/reading.PNG" class="admin-form__input">

                <label for="published_at" class="admin-form__label">Publish Date/Time (optional)</label>
                <input id="published_at" name="published_at" type="datetime-local" value="{{ old('published_at', optional($post->published_at)->format('Y-m-d\TH:i')) }}" class="admin-form__input">

                <label class="admin-form__check-wrap">
                    <input type="checkbox" name="publish_now" value="1" {{ old('publish_now') ? 'checked' : '' }}>
                    <span>Publish now</span>
                </label>

                <button type="submit" class="admin-form__button">Save Changes</button>
            </form>

            <aside class="admin-recent">
                <h2 class="admin-recent__title">Recent Posts</h2>
                @forelse ($recentPosts as $recentPost)
                    <div class="admin-recent__item">
                        <p class="admin-recent__item-title">
                            <a href="{{ route('admin.posts.edit', $recentPost) }}" class="admin-recent__item-link">{{ $recentPost->title }}</a>
                        </p>
                        <p class="admin-recent__item-meta">/{{ $recentPost->slug }}</p>
                    </div>
                @empty
                    <p class="admin-recent__empty">No posts yet.</p>
                @endforelse
            </aside>
        </section>
    </main>
</body>
</html>
