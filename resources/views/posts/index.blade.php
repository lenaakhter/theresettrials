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
                <p class="blog-featured-experiments__subtitle">Currently ongoing</p>
            </div>

            @if (($activeExperiments ?? collect())->isNotEmpty())
                <div class="blog-featured-experiments__grid">
                    @foreach ($activeExperiments as $experiment)
                        <article class="blog-featured-card">
                            <div class="blog-featured-card__label">Currently ongoing</div>
                            <h3 class="blog-featured-card__title">
                                <a href="{{ route('experiments.show', $experiment) }}">{{ $experiment->title }}</a>
                            </h3>
                            <p class="blog-featured-card__excerpt">{{ $experiment->description }}</p>
                            <p class="blog-featured-card__meta">Started {{ $experiment->start_date->format('M d, Y') }}</p>
                        </article>
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
            <div class="content-empty-state posts-empty">
                <img src="{{ asset('images/reading.PNG') }}" alt="Reading illustration" class="content-empty-state__image">
                <p class="content-empty-state__text">There isn’t anything at the moment... watch this space 👀...</p>
            </div>
        @endforelse
    </div>
</section>
@endsection
