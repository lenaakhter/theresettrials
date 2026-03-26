@extends('layouts.app')

@section('content')
<section class="hero">
    <div class="hero__character-wrap">
        <img src="{{ asset('images/croppedwave.PNG') }}" alt="Character" class="hero__character-img">
    </div>

    <div class="hero__content">
        <div class="hero__text-box">
            <h1 class="hero__title">Testing What Actually Works for PCOS</h1>
             <p class="hero__subtitle">I’m experimenting with diets, supplements, and routines to manage PCOS - sharing what actually helps, what doesn't, and everything I learn along the way.</p>
        </div>
    </div>
</section>

<section class="latest-posts">
    <div class="latest-posts__header">
        <h2 class="latest-posts__title">Latest Posts</h2>
        <a href="{{ route('posts.index') }}" class="latest-posts__view-all">See more</a>
    </div>

    @if ($latestPosts->isEmpty())
        <p class="latest-posts__empty">No posts yet. Run migrations and seeders to add starter posts.</p>
    @else
        <div class="latest-posts__track" role="region" aria-label="Latest posts board">
            @foreach ($latestPosts as $post)
                @php
                    $i = $loop->index;
                    $tile = match($i) { 0 => 'featured', 1 => 'tall', 4 => 'wide', default => '' };
                @endphp
                <article class="latest-post{{ $tile ? ' latest-post--'.$tile : '' }}">
                    <a href="{{ route('posts.show', $post) }}" class="latest-post__image-wrap">
                        @if ($post->cover_image)
                            <img src="{{ asset($post->cover_image) }}" alt="{{ $post->title }}" class="latest-post__image">
                        @endif
                    </a>

                    <div class="latest-post__body">
                        <p class="latest-post__meta">{{ optional($post->published_at)->format('M d, Y') }}</p>
                        <h3 class="latest-post__title">
                            <a href="{{ route('posts.show', $post) }}">{{ $post->title }}</a>
                        </h3>
                        <p class="latest-post__excerpt">{{ $post->excerpt }}</p>
                    </div>
                </article>
            @endforeach
        </div>
    @endif
</section>
@endsection