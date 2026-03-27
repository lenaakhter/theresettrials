@extends('layouts.app')

@section('content')
<section class="info-page info-page--resources">
    <div class="info-page__inner">
        <header class="info-page__hero">
            <p class="info-page__kicker">Real Stuff I Use</p>
            <h1 class="info-page__title">Links &amp; Resources</h1>
        </header>

        <p class="static-page__subtitle" style="margin-bottom: 1.5rem;">Products I have used and linked directly to the blog posts and experiments where I mention them.</p>

        @if ($currentlyTesting->isNotEmpty())
            <div class="product-box product-box--currently-testing" style="margin-bottom: 2rem;">
                <h3 class="product-box__title">Currently Testing</h3>
                <div class="product-box__list">
                    @foreach ($currentlyTesting as $res)
                        <a href="{{ route('experiments.show', $res->linkable) }}" class="product-box__item">
                            @if ($res->image_url)
                                <img src="{{ $res->image_url }}" alt="{{ $res->name }}" class="product-box__img">
                            @endif
                            <span class="product-box__name">{{ $res->name }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        @if ($resources->isEmpty())
            <p class="static-page__coming-soon" style="margin-top: 1rem;">Nothing here yet - check back soon!</p>
        @else
            <div class="resource-grid resources-page__grid">
                @foreach ($resources as $resource)
                    <div class="resource-card">
                        @if ($resource->image_url)
                            <a href="{{ $resource->product_url }}" target="_blank" rel="noopener" class="resource-card__img-link">
                                <img src="{{ $resource->image_url }}" alt="{{ $resource->name }}" class="resource-card__img">
                            </a>
                        @endif
                        <div class="resource-card__body">
                            <h3 class="resource-card__name">{{ $resource->name }}</h3>
                            <a href="{{ $resource->product_url }}" target="_blank" rel="noopener" class="resource-card__buy-link">View product →</a>
                            @if ($resource->linkable)
                                @php
                                    $linked = $resource->linkable;
                                    $isPost = $linked instanceof \App\Models\Post;
                                    $linkedUrl = $isPost
                                        ? route('posts.show', $linked->slug)
                                        : route('experiments.show', $linked);
                                    $linkedLabel = $isPost ? 'Read the blog post' : 'See the experiment';
                                @endphp
                                <a href="{{ $linkedUrl }}" class="resource-card__linked-label">{{ $linkedLabel }} →</a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
@endsection
