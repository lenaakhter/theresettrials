@extends('layouts.admin')

@section('content')
    <main class="admin-posts">
        <div class="admin-posts__header">
            <div>
                <h1 class="admin-posts__title">Edit Post</h1>
                <p class="admin-posts__subtitle">Update your existing post details.</p>
            </div>
            <div class="admin-actions">
                <a href="{{ route('admin.posts.create') }}" class="admin-posts__logout admin-posts__logout--link">Write post</a>
                <a href="{{ route('admin.admins.create') }}" class="admin-posts__logout admin-posts__logout--link">Add admin</a>
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
            <div class="admin-editor-stack">
                <form method="POST" action="{{ route('admin.posts.update', $post) }}" class="admin-editor" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <label for="title" class="admin-form__label">Title</label>
                    <input id="title" name="title" type="text" value="{{ old('title', $post->title) }}" required class="admin-form__input">

                    <label for="excerpt" class="admin-form__label">Excerpt</label>
                    <textarea id="excerpt" name="excerpt" rows="3" class="admin-form__textarea">{{ old('excerpt', $post->excerpt) }}</textarea>

                    <label for="content" class="admin-form__label">Post Content</label>
                    <textarea id="content" name="content" rows="12" required class="admin-form__textarea">{{ old('content', $post->content) }}</textarea>

                    <label for="cover_image_upload" class="admin-form__label">Cover Image (optional)</label>
                    @if ($post->cover_image_url)
                        <img src="{{ $post->cover_image_url }}" alt="Current cover image" style="max-width: 220px; border-radius: 10px; margin-bottom: 0.55rem;">
                    @endif
                    <input id="cover_image_upload" name="cover_image_upload" type="file" accept="image/*" class="admin-form__input">
                    <p class="admin-posts__subtitle" style="margin: -0.35rem 0 0.5rem;">Upload a new image to replace the current one (max 4MB).</p>

                    <label for="category" class="admin-form__label">Category (optional)</label>
                    <select id="category" name="category" class="admin-form__input">
                        <option value="">— None —</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ old('category', $post->category) === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>

                    <label for="published_at" class="admin-form__label">Publish Date/Time (optional)</label>
                    <input id="published_at" name="published_at" type="datetime-local" value="{{ old('published_at', optional($post->published_at)->format('Y-m-d\TH:i')) }}" class="admin-form__input">
                    <p class="admin-posts__subtitle" style="margin: -0.35rem 0 0.5rem;">Leave blank to publish immediately.</p>

                    <button type="submit" class="admin-form__button">Save Changes</button>
                </form>

                <form method="POST" action="{{ route('admin.posts.destroy', $post) }}" onsubmit="return confirm('Delete this post? This cannot be undone.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="admin-form__button admin-form__button--delete">Delete Post</button>
                </form>

                <div class="admin-products">
                    <h2 class="admin-products__title">Products / Resources</h2>

                    @if ($resources->isNotEmpty())
                        <div class="admin-resource-list">
                            @foreach ($resources as $resource)
                                <div class="admin-resource-card">
                                    @if ($resource->image_url)
                                        <img src="{{ $resource->image_url }}" alt="{{ $resource->name }}" class="admin-resource-card__img">
                                    @endif
                                    <div class="admin-resource-card__body">
                                        <p class="admin-resource-card__name">{{ $resource->name }}</p>
                                        <a href="{{ $resource->product_url }}" target="_blank" rel="noopener noreferrer" class="admin-resource-card__link">View product</a>
                                    </div>
                                    <form method="POST" action="{{ route('admin.resources.destroy-inline', $resource) }}" onsubmit="return confirm('Unlink this product?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="admin-resource-card__delete" title="Unlink">&times;</button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="admin-posts__subtitle">No products linked yet.</p>
                    @endif

                    <h3 class="admin-products__subtitle">Link an existing product</h3>
                    @if ($allResources->isEmpty())
                        <p class="admin-posts__subtitle">No products in the system yet. <a href="{{ route('admin.resources.create') }}" style="color:#c56a7f;">Add one here.</a></p>
                    @else
                        <form method="POST" action="{{ route('admin.posts.resources.store', $post) }}" class="admin-products__form">
                            @csrf
                            <select name="resource_id" required class="admin-form__input">
                                <option value="">— Select a product —</option>
                                @foreach ($allResources as $r)
                                    <option value="{{ $r->id }}">{{ $r->name }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="admin-form__button">Link Product</button>
                        </form>
                        <p class="admin-posts__subtitle" style="margin-top:0.5rem;">Need a new product? <a href="{{ route('admin.resources.create') }}" style="color:#c56a7f;">Create one in Resources.</a></p>
                    @endif
                </div>
            </div>


        </section>
    </main>
@endsection
