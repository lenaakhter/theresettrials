@extends('layouts.admin')

@section('content')
    <main class="admin-posts">
        <div class="admin-posts__header">
            <div>
                <h1 class="admin-posts__title">Posts</h1>
                <p class="admin-posts__subtitle">All published and scheduled blog posts.</p>
            </div>
            <a href="{{ route('admin.posts.create') }}" class="admin-form__button" style="align-self: center;">+ New Post</a>
        </div>

        @if (session('status'))
            <div class="admin-flash admin-flash--success" data-flash>
                <span>{{ session('status') }}</span>
                <button type="button" class="admin-flash__close" data-flash-close aria-label="Dismiss notification">&times;</button>
            </div>
        @endif

        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Published</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($posts as $post)
                        <tr>
                            <td>
                                <a href="{{ route('posts.show', $post) }}" target="_blank" class="admin-table__title-link">{{ $post->title }}</a>
                            </td>
                            <td>{{ $post->category ?? '—' }}</td>
                            <td>{{ optional($post->published_at)->format('M d, Y') ?? 'Draft' }}</td>
                            <td class="admin-table__actions">
                                <a href="{{ route('admin.posts.edit', $post) }}" class="admin-table__edit-btn">Edit</a>
                                <form method="POST" action="{{ route('admin.posts.destroy', $post) }}" onsubmit="return confirm('Delete &quot;{{ addslashes($post->title) }}&quot;? This cannot be undone.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="admin-table__delete-btn">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="admin-table__empty">No posts yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
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
