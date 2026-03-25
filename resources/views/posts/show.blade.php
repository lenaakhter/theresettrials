@extends('layouts.app')

@section('content')
<article class="post-single">
    <div class="post-single__inner">
        <p class="post-single__meta">{{ optional($post->published_at)->format('M d, Y') }}</p>
        <h1 class="post-single__title">{{ $post->title }}</h1>

        @if ($post->cover_image)
            <img src="{{ asset($post->cover_image) }}" alt="{{ $post->title }}" class="post-single__image">
        @endif

        <div class="post-single__content">
            {!! nl2br(e($post->content)) !!}
        </div>
    </div>
</article>

@if ($latestPosts->isNotEmpty())
<section class="latest-posts latest-posts--compact">
    <div class="latest-posts__header">
        <h2 class="latest-posts__title">More Posts</h2>
        <a href="{{ route('posts.index') }}" class="latest-posts__view-all">View all</a>
    </div>

    <div class="latest-posts__track">
        @foreach ($latestPosts as $latestPost)
            <article class="latest-post">
                <a href="{{ route('posts.show', $latestPost) }}" class="latest-post__image-wrap">
                    @if ($latestPost->cover_image)
                        <img src="{{ asset($latestPost->cover_image) }}" alt="{{ $latestPost->title }}" class="latest-post__image">
                    @endif
                </a>
                <div class="latest-post__body">
                    <p class="latest-post__meta">{{ optional($latestPost->published_at)->format('M d, Y') }}</p>
                    <h3 class="latest-post__title">
                        <a href="{{ route('posts.show', $latestPost) }}">{{ $latestPost->title }}</a>
                    </h3>
                </div>
            </article>
        @endforeach
    </div>
</section>
@endif
@endsection
