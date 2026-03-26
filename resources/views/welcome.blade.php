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

<section class="exploring-map">
    <div class="exploring-map__header">
        <h2 class="exploring-map__title">What I'm Exploring</h2>
        <p class="exploring-map__subtitle">Tap a branch to jump into posts for that area.</p>
    </div>

    <div class="exploring-map__canvas">
        <svg viewBox="0 0 1000 620" class="exploring-map__svg" role="img" aria-label="Mind map of topics being explored">
            <path class="exploring-map__line" d="M500 310 C365 280, 285 220, 210 150" />
            <path class="exploring-map__line" d="M500 310 C360 315, 275 315, 180 305" />
            <path class="exploring-map__line" d="M500 310 C365 360, 285 440, 210 510" />
            <path class="exploring-map__line" d="M500 310 C635 275, 720 215, 810 150" />
            <path class="exploring-map__line" d="M500 310 C640 315, 730 320, 820 310" />
            <path class="exploring-map__line" d="M500 310 C635 365, 720 445, 810 510" />

            <foreignObject x="376" y="212" width="248" height="196">
                <div xmlns="http://www.w3.org/1999/xhtml" class="exploring-map__node exploring-map__node--center">
                    What I'm Exploring
                </div>
            </foreignObject>

            <foreignObject x="118" y="86" width="210" height="110">
                <div xmlns="http://www.w3.org/1999/xhtml" class="exploring-map__node-wrap exploring-map__node-wrap--left">
                    <a href="{{ route('posts.index', ['category' => 'Supplements']) }}" class="exploring-map__node exploring-map__node--supplements">Supplements</a>
                </div>
            </foreignObject>

            <foreignObject x="82" y="255" width="210" height="110">
                <div xmlns="http://www.w3.org/1999/xhtml" class="exploring-map__node-wrap exploring-map__node-wrap--left">
                    <a href="{{ route('posts.index', ['category' => 'Lifestyle']) }}" class="exploring-map__node exploring-map__node--lifestyle">Lifestyle</a>
                </div>
            </foreignObject>

            <foreignObject x="132" y="446" width="210" height="110">
                <div xmlns="http://www.w3.org/1999/xhtml" class="exploring-map__node-wrap exploring-map__node-wrap--left">
                    <a href="{{ route('posts.index', ['category' => 'Nutrition']) }}" class="exploring-map__node exploring-map__node--nutrition">Nutrition</a>
                </div>
            </foreignObject>

            <foreignObject x="688" y="86" width="200" height="110">
                <div xmlns="http://www.w3.org/1999/xhtml" class="exploring-map__node-wrap exploring-map__node-wrap--right">
                    <a href="{{ route('posts.index', ['category' => 'Exercise']) }}" class="exploring-map__node exploring-map__node--exercise">Exercise</a>
                </div>
            </foreignObject>

            <foreignObject x="688" y="255" width="210" height="110">
                <div xmlns="http://www.w3.org/1999/xhtml" class="exploring-map__node-wrap exploring-map__node-wrap--right">
                    <a href="{{ route('posts.index', ['category' => 'Hormones']) }}" class="exploring-map__node exploring-map__node--hormones">Hormones</a>
                </div>
            </foreignObject>

            <foreignObject x="666" y="434" width="248" height="132">
                <div xmlns="http://www.w3.org/1999/xhtml" class="exploring-map__node-wrap exploring-map__node-wrap--right">
                    <a href="{{ route('posts.index', ['category' => 'Low-Maintenance Habits']) }}" class="exploring-map__node exploring-map__node--habits">Low-Maintenance Habits</a>
                </div>
            </foreignObject>
        </svg>

        <div class="exploring-map-mobile" aria-label="Mobile list of topics being explored">
            <div class="exploring-map-mobile__center">What I'm Exploring</div>

            <div class="exploring-map-mobile__items">
                <a href="{{ route('posts.index', ['category' => 'Supplements']) }}" class="exploring-map__node exploring-map__node--supplements">Supplements</a>
                <a href="{{ route('posts.index', ['category' => 'Lifestyle']) }}" class="exploring-map__node exploring-map__node--lifestyle">Lifestyle</a>
                <a href="{{ route('posts.index', ['category' => 'Nutrition']) }}" class="exploring-map__node exploring-map__node--nutrition">Nutrition</a>
                <a href="{{ route('posts.index', ['category' => 'Exercise']) }}" class="exploring-map__node exploring-map__node--exercise">Exercise</a>
                <a href="{{ route('posts.index', ['category' => 'Hormones']) }}" class="exploring-map__node exploring-map__node--hormones">Hormones</a>
                <a href="{{ route('posts.index', ['category' => 'Low-Maintenance Habits']) }}" class="exploring-map__node exploring-map__node--habits">Low-Maintenance Habits</a>
            </div>
        </div>
    </div>
</section>
@endsection