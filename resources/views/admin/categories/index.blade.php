@extends('layouts.admin')

@section('content')
    <main class="admin-posts">
        <div class="admin-posts__header">
            <div>
                <h1 class="admin-posts__title">Post Categories</h1>
                <p class="admin-posts__subtitle">Manage the categories available for blog posts and the mind map.</p>
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
            <form method="POST" action="{{ route('admin.categories.store') }}" class="admin-editor admin-form">
                @csrf

                <label for="name" class="admin-form__label">New Category Name</label>
                <input id="name" name="name" type="text" value="{{ old('name') }}" required class="admin-form__input" placeholder="e.g. Sleep">
                <p class="admin-posts__subtitle" style="margin: -0.35rem 0 0.5rem;">This will appear in the category dropdown when writing posts, and on the home page mind map.</p>

                <button type="submit" class="admin-form__button">Add Category</button>
            </form>

            <aside class="admin-recent">
                <h2 class="admin-recent__title">Current Categories</h2>
                @forelse ($categories as $category)
                    <div class="admin-recent__item">
                        <div>
                            <p class="admin-recent__item-title">{{ $category->name }}</p>
                        </div>
                        <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" onsubmit="return confirm('Remove category &quot;{{ addslashes($category->name) }}&quot;? Posts using it will keep their category value.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="admin-recent__item-revoke">Remove</button>
                        </form>
                    </div>
                @empty
                    <p class="admin-recent__empty">No categories yet.</p>
                @endforelse
            </aside>
        </section>
    </main>
    <script>
    document.addEventListener('click', function (event) {
        const closeButton = event.target.closest('[data-flash-close]');
        if (closeButton) {
            const flash = closeButton.closest('[data-flash]');
            if (flash) flash.remove();
        }
    });
    </script>
@endsection
