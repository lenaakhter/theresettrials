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

<section class="about-blog">
    <div class="about-blog__inner">
        <div class="about-blog__content">
            <div class="about-blog__text-box">
            <h2 class="about-blog__title">About This Blog</h2>
            <p>I started this blog to build a space where I can share and understand what actually works for PCOS beyond just medical advice. From my experience, most of the support I’ve received has been limited to prescriptions like birth control or diabetes medication, without much guidance on what to do beyond that.</p>
            <p>PCOS feels like so much more than just taking medication. It's something that requires real lifestyle changes, consistency, and care. For someone like me, who prefers a low-maintenance lifestyle, that’s been a challenge. You want to invest in yourself and feel better, but it’s hard to know what’s genuinely worth it.</p>
            <p>There's so much advice online, but a lot of it feels driven by trends or marketing rather than what actually works. This blog is where I test things for myself, honestly and without filters, to figure out what truly makes a difference.</p>
            <p>I also hope to create something that helps others like me, who want a realistic, low-maintenance approach to managing PCOS while still feeling better in themselves.</p>
            </div>
        </div>

        <div class="about-blog__image-wrap">
            <img src="{{ asset('images/medicine.png') }}" alt="Medicine illustration" class="about-blog__image">
        </div>
    </div>
</section>
@endsection