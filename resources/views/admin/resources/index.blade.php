@extends('layouts.admin')

@section('content')
<main class="admin-posts">
    <div class="admin-posts__header">
        <div>
            <h1 class="admin-posts__title">Links &amp; Resources</h1>
            <p class="admin-posts__subtitle">Products you're using across blogs and experiments.</p>
        </div>
        <a href="{{ route('admin.resources.create') }}" class="admin-btn admin-btn--primary">+ Add Resource</a>
    </div>

    @if (session('status'))
        <div class="admin-flash admin-flash--success" data-flash>
            <span>{{ session('status') }}</span>
            <button type="button" class="admin-flash__close" data-flash-close aria-label="Dismiss">&times;</button>
        </div>
    @endif

    @if ($resources->isEmpty())
        <p style="color:#8C7B7F; margin-top:2rem;">No resources yet. Add your first one!</p>
    @else
        <div class="admin-resource-list">
            @foreach ($resources as $resource)
                <div class="admin-resource-card">
                    @if ($resource->image_url)
                        <img src="{{ $resource->image_url }}" alt="{{ $resource->name }}" class="admin-resource-card__img">
                    @else
                        <div class="admin-resource-card__img admin-resource-card__img--placeholder"></div>
                    @endif
                    <div class="admin-resource-card__body">
                        <strong class="admin-resource-card__name">{{ $resource->name }}</strong>
                        <a href="{{ $resource->product_url }}" target="_blank" rel="noopener" class="admin-resource-card__url">{{ $resource->product_url }}</a>
                        @if ($resource->linkable)
                            @php
                                $linked = $resource->linkable;
                                $isPost = $linked instanceof \App\Models\Post;
                                $linkedUrl = $isPost
                                    ? route('posts.show', $linked->slug)
                                    : route('experiments.show', $linked);
                                $linkedLabel = ($isPost ? 'Blog: ' : 'Experiment: ') . $linked->title;
                            @endphp
                            <a href="{{ $linkedUrl }}" class="admin-resource-card__linked">{{ $linkedLabel }}</a>
                        @else
                            <span class="admin-resource-card__no-link">No blog/experiment linked</span>
                        @endif
                    </div>
                    <div class="admin-resource-card__actions">
                        <a href="{{ route('admin.resources.edit', $resource) }}" class="admin-btn admin-btn--sm">Edit</a>
                        <form method="POST" action="{{ route('admin.resources.destroy', $resource) }}" onsubmit="return confirm('Delete this resource?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="admin-btn admin-btn--sm admin-btn--danger">Delete</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</main>
@endsection
