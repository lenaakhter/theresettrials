@extends('layouts.app')

@section('content')
<section class="blog-index">
    <div class="blog-index__header">
        <h1 class="blog-index__title">{{ $category ? $category : 'All Posts' }}</h1>
        <p class="blog-index__subtitle">
            {{ $category ? 'Posts filed under '.$category.'.' : 'Experiments, notes, and practical updates from The Reset Trials.' }}
        </p>
    </div>

    @if (! $category)
        <section class="blog-featured-experiments">
            <div class="blog-featured-experiments__header">
                <h2 class="blog-featured-experiments__title">Currently Testing</h2>
            </div>

            @if (($activeExperiments ?? collect())->isNotEmpty())
                <div class="blog-featured-experiments__grid">
                    @foreach ($activeExperiments as $experiment)
                        <a href="{{ route('experiments.show', $experiment) }}" class="blog-featured-card blog-featured-card--link">
                            <div class="blog-featured-card__label">Currently ongoing</div>
                            <h3 class="blog-featured-card__title">{{ $experiment->title }}</h3>
                            <p class="blog-featured-card__excerpt">{{ $experiment->description }}</p>
                            <p class="blog-featured-card__meta">Started {{ $experiment->start_date->format('M d, Y') }}</p>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="content-empty-state content-empty-state--featured-experiments">
                    <img src="{{ asset('images/reading.PNG') }}" alt="Reading illustration" class="content-empty-state__image">
                    <p class="content-empty-state__text">There isn’t anything at the moment... watch this space 👀...</p>
                </div>
            @endif
        </section>
    @endif

    <div class="blog-index__posts-heading">
        <h2 class="blog-index__posts-title">{{ $category ? 'Posts' : 'More Posts' }}</h2>
    </div>

    <div class="posts-grid">
        @forelse ($posts as $post)
            <article class="post-card">
                <a href="{{ route('posts.show', $post) }}" class="post-card__image-wrap">
                    @if ($post->cover_image_url)
                        <img src="{{ $post->cover_image_url }}" alt="{{ $post->title }}" class="post-card__image">
                    @else
                        <div class="post-card__image post-card__image--placeholder"></div>
                    @endif
                </a>

                <div class="post-card__body">
                    <p class="post-card__meta">{{ optional($post->published_at)->format('M d, Y') }}</p>
                    <h2 class="post-card__title">
                        <a href="{{ route('posts.show', $post) }}">{{ $post->title }}</a>
                    </h2>
                    @if ($post->category)
                        <span class="post-card__category">{{ $post->category }}</span>
                    @endif
                    @if ($post->excerpt)
                        <p class="post-card__excerpt">{{ $post->excerpt }}</p>
                    @endif
                </div>
            </article>
        @empty
            <div class="content-empty-state posts-empty">
                <img src="{{ asset('images/reading.PNG') }}" alt="Reading illustration" class="content-empty-state__image">
                <p class="content-empty-state__text">There isn’t anything at the moment... watch this space 👀...</p>
            </div>
        @endforelse
    </div>
</section>
@endsection
