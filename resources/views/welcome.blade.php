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
        <div class="content-empty-state latest-posts__empty">
            <img src="{{ asset('images/reading.PNG') }}" alt="Reading illustration" class="content-empty-state__image">
            <p class="content-empty-state__text">There isn’t anything at the moment... watch this space 👀...</p>
        </div>
    @else
        <div class="latest-posts__track" role="region" aria-label="Latest posts board">
            @foreach ($latestPosts as $post)
                @php
                    $i = $loop->index;
                    $tile = match($i) { 0 => 'featured', 1 => 'tall', 4 => 'wide', default => '' };
                @endphp
                <article class="latest-post{{ $tile ? ' latest-post--'.$tile : '' }}">
                    <a href="{{ route('posts.show', $post) }}" class="latest-post__image-wrap">
                        @if ($post->cover_image_url)
                            <img src="{{ $post->cover_image_url }}" alt="{{ $post->title }}" class="latest-post__image">
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

@if($activeCategories->isNotEmpty())
<section class="exploring-map">
    <div class="exploring-map__header">
        <h2 class="exploring-map__title">What I'm Exploring</h2>
        <p class="exploring-map__subtitle">Tap a branch to jump into posts for that area.</p>
    </div>

    <div class="exploring-map__canvas">
        @php
            $mapCategories = $activeCategories->values();
            $categoryStyles = [
                'exploring-map__node--supplements',
                'exploring-map__node--lifestyle',
                'exploring-map__node--nutrition',
                'exploring-map__node--exercise',
                'exploring-map__node--hormones',
                'exploring-map__node--habits',
            ];

            $totalCategories = max($mapCategories->count(), 1);
            $startAngle = -M_PI / 2;
            $step = (2 * M_PI) / $totalCategories;
            $radiusX = 335;
            $radiusY = 220;
        @endphp

        <svg viewBox="0 0 1000 620" class="exploring-map__svg" role="img" aria-label="Mind map of topics being explored">
            @foreach($mapCategories as $index => $categoryName)
                @php
                    $angle = $startAngle + ($index * $step);
                    $centerX = 500 + ($radiusX * cos($angle));
                    $centerY = 310 + ($radiusY * sin($angle));
                    $nodeWidth = min(max(182, 124 + (strlen($categoryName) * 5)), 260);
                    $nodeHeight = 100;
                    $nodeX = $centerX - ($nodeWidth / 2);
                    $nodeY = $centerY - ($nodeHeight / 2);

                    $control1X = 500 + (($centerX - 500) * 0.42);
                    $control1Y = 310 + (($centerY - 310) * 0.22);
                    $control2X = 500 + (($centerX - 500) * 0.76);
                    $control2Y = 310 + (($centerY - 310) * 0.84);
                @endphp
                <path
                    class="exploring-map__line"
                    d="M500 310 C{{ round($control1X, 2) }} {{ round($control1Y, 2) }}, {{ round($control2X, 2) }} {{ round($control2Y, 2) }}, {{ round($centerX, 2) }} {{ round($centerY, 2) }}"
                />
            @endforeach

            <foreignObject x="376" y="212" width="248" height="196">
                <div xmlns="http://www.w3.org/1999/xhtml" class="exploring-map__node exploring-map__node--center">
                    What I'm Exploring
                </div>
            </foreignObject>

            @foreach($mapCategories as $index => $categoryName)
                @php
                    $angle = $startAngle + ($index * $step);
                    $centerX = 500 + ($radiusX * cos($angle));
                    $centerY = 310 + ($radiusY * sin($angle));
                    $nodeWidth = min(max(182, 124 + (strlen($categoryName) * 5)), 260);
                    $nodeHeight = 100;
                    $nodeX = $centerX - ($nodeWidth / 2);
                    $nodeY = $centerY - ($nodeHeight / 2);
                    $nodeClass = $categoryStyles[$index % count($categoryStyles)];
                @endphp
                <foreignObject x="{{ round($nodeX, 2) }}" y="{{ round($nodeY, 2) }}" width="{{ round($nodeWidth, 2) }}" height="{{ $nodeHeight }}">
                    <div xmlns="http://www.w3.org/1999/xhtml" class="exploring-map__node-wrap" style="justify-content: center;">
                        <a href="{{ route('posts.index', ['category' => $categoryName]) }}" class="exploring-map__node {{ $nodeClass }}">{{ $categoryName }}</a>
                    </div>
                </foreignObject>
            @endforeach
        </svg>

        <div class="exploring-map-mobile" aria-label="Mobile list of topics being explored">
            <div class="exploring-map-mobile__center">What I'm Exploring</div>

            <div class="exploring-map-mobile__items">
                @foreach($mapCategories as $index => $categoryName)
                    @php
                        $nodeClass = $categoryStyles[$index % count($categoryStyles)];
                    @endphp
                    <a href="{{ route('posts.index', ['category' => $categoryName]) }}" class="exploring-map__node {{ $nodeClass }}">{{ $categoryName }}</a>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif

<section class="experimenting">
    <div class="experimenting__header">
        <h2 class="experimenting__title">Currently Experimenting</h2>
        <p class="experimenting__subtitle">New experiments I'm testing right now</p>
    </div>

    <div class="experimenting__grid">
        <div class="experimenting__image-section">
            <div class="experimenting__image-wrap">
                <img src="{{ asset('images/experiment.png') }}" alt="Currently experimenting">
            </div>
        </div>

        <div class="experimenting__cards-section">
            @forelse($experiments ?? [] as $experiment)
                <a href="{{ route('experiments.show', $experiment) }}" style="text-decoration: none;">
                    <div class="experimenting__item">
                        <div class="experimenting__card">
                            <div class="experimenting__card-content">
                                <h3 class="experimenting__card-title">{{ $experiment->title }}</h3>
                                <p class="experimenting__card-description">{{ $experiment->description }}</p>
                                <p class="experimenting__card-meta">Started: {{ $experiment->start_date->format('M Y') }}</p>
                            </div>
                        </div>
                    </div>
                </a>
            @empty
                <div class="content-empty-state content-empty-state--experimenting">
                    <img src="{{ asset('images/reading.PNG') }}" alt="Reading illustration" class="content-empty-state__image">
                    <p class="content-empty-state__text">There isn’t anything at the moment... watch this space 👀...</p>
                </div>
            @endforelse
        </div>
    </div>
</section>
@endsection