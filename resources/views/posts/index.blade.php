@extends('layouts.app')

@section('content')
<section class="blog-index">
    <div class="blog-index__header">
        <h1 class="blog-index__title">All Posts</h1>
        <p class="blog-index__subtitle">Experiments, notes, and practical updates from The Reset Trials.</p>
    </div>

    <div class="posts-grid">
        @forelse ($posts as $post)
            <article class="post-card">
                <a href="{{ route('posts.show', $post) }}" class="post-card__image-wrap">
                    @if ($post->cover_image)
                        <img src="{{ asset($post->cover_image) }}" alt="{{ $post->title }}" class="post-card__image">
                    @else
                        <div class="post-card__image post-card__image--placeholder"></div>
                    @endif
                </a>

                <div class="post-card__body">
                    <p class="post-card__meta">{{ optional($post->published_at)->format('M d, Y') }}</p>
                    <h2 class="post-card__title">
                        <a href="{{ route('posts.show', $post) }}">{{ $post->title }}</a>
                    </h2>
                    <p class="post-card__excerpt">{{ $post->excerpt }}</p>
                </div>
            </article>
        @empty
            <p class="posts-empty">No posts yet.</p>
        @endforelse
    </div>

    <div class="blog-pagination">
        {{ $posts->links() }}
    </div>
</section>
@endsection
