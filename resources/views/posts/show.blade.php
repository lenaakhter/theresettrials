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

        <section class="comments-section" id="comments">
            <h2 class="comments-section__title">Comments</h2>

            @if (session('comment_status'))
                <p class="comments-section__status">{{ session('comment_status') }}</p>
            @endif

            @auth
                <form method="POST" action="{{ route('comments.store', $post) }}" class="comment-form">
                    @csrf
                    <textarea name="body" rows="4" required class="comment-form__textarea" placeholder="Share your thoughts..."></textarea>
                    <button type="submit" class="comment-form__button">Post comment</button>
                </form>
            @else
                <p class="comments-section__login-hint">
                    Want to join the discussion? <a href="{{ route('login') }}">Log in</a> or <a href="{{ route('register') }}">sign up</a> to comment.
                </p>
            @endauth

            @if ($comments->isEmpty())
                <p class="comments-section__empty">No comments yet. Be the first to comment.</p>
            @else
                <div class="comments-list">
                    @foreach ($comments as $comment)
                        @include('posts.partials.comment', ['comment' => $comment, 'post' => $post, 'level' => 0])
                    @endforeach
                </div>
            @endif
        </section>
    </div>
</article>

<script>
const token = document.querySelector('meta[name="csrf-token"]').content;

document.addEventListener('click', (event) => {
    // Handle reply toggle
    const toggle = event.target.closest('[data-reply-toggle]');
    if (toggle) {
        const formId = toggle.getAttribute('data-reply-toggle');
        const form = document.getElementById(formId);
        if (form) form.classList.toggle('comment-form--hidden');
        return;
    }

    // Handle delete trigger
    const deleteTrigger = event.target.closest('[data-delete-trigger]');
    if (deleteTrigger) {
        const commentId = deleteTrigger.getAttribute('data-delete-trigger');
        const modal = document.getElementById(`delete-modal-${commentId}`);
        if (modal) modal.classList.remove('delete-modal--hidden');
        return;
    }

    // Handle delete modal close (overlay click)
    const deleteOverlay = event.target.closest('[data-delete-modal]');
    if (deleteOverlay) {
        const commentId = deleteOverlay.getAttribute('data-delete-modal');
        const modal = document.getElementById(`delete-modal-${commentId}`);
        if (modal) modal.classList.add('delete-modal--hidden');
        return;
    }

    // Handle delete modal cancel button
    const deleteCancel = event.target.closest('.delete-modal__cancel');
    if (deleteCancel) {
        const commentId = deleteCancel.getAttribute('data-delete-modal');
        const modal = document.getElementById(`delete-modal-${commentId}`);
        if (modal) modal.classList.add('delete-modal--hidden');
        return;
    }

    // Handle like button
    const likeBtn = event.target.closest('[data-like-btn]');
    if (!likeBtn) return;

    const commentId = likeBtn.getAttribute('data-like-btn');
    const url = likeBtn.getAttribute('data-like-url');

    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json',
        },
    })
    .then(res => res.json())
    .then(data => {
        // Update heart state
        likeBtn.setAttribute('data-liked', data.liked ? 'true' : 'false');

        // Update like count display
        const countEl = document.querySelector(`[data-like-count="${commentId}"]`);
        if (countEl) {
            const plural = data.likes_count === 1 ? 'like' : 'likes';
            countEl.textContent = `${data.likes_count} ${plural}`;
        }
    })
    .catch(err => console.error('Error toggling like:', err));
});
</script>

@if ($latestPosts->isNotEmpty())
<section class="latest-posts latest-posts--compact">
    <div class="latest-posts__header">
        <h2 class="latest-posts__title">More Posts</h2>
        <a href="{{ route('blogs.index') }}" class="latest-posts__view-all">See more</a>
    </div>

    <div class="latest-posts__track">
        @foreach ($latestPosts as $latestPost)
            @php
                $i = $loop->index;
                $tile = match($i) { 0 => 'featured', 1 => 'tall', 4 => 'wide', default => '' };
            @endphp
            <article class="latest-post{{ $tile ? ' latest-post--'.$tile : '' }}">
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
