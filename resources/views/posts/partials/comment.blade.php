<article class="comment-item" style="margin-left: {{ $level * 1.2 }}rem;">
    <div class="comment-item__header">
        <div class="comment-item__avatar comment-item__avatar--placeholder">{{ strtoupper(substr($comment->user->comment_name, 0, 1)) }}</div>

        <div>
            <p class="comment-item__name">{{ $comment->user->comment_name }}</p>
            <p class="comment-item__time">{{ $comment->created_at->diffForHumans() }}</p>
        </div>
    </div>

    <p class="comment-item__body">{{ $comment->body }}</p>

    <div class="comment-item__actions">
        <p class="comment-item__likes" data-like-count="{{ $comment->id }}" data-liked="{{ in_array($comment->id, $likedCommentIds ?? [], true) ? 'true' : 'false' }}">{{ $comment->likes_count }} {{ $comment->likes_count === 1 ? 'like' : 'likes' }}</p>

        @auth
            <button type="button" class="comment-item__heart" data-like-btn="{{ $comment->id }}" data-like-url="{{ route('comments.like', $comment) }}" data-liked="{{ in_array($comment->id, $likedCommentIds ?? [], true) ? 'true' : 'false' }}" aria-label="Like comment"></button>

            <div class="comment-item__tail-actions">
                <button type="button" class="comment-item__text-action" data-reply-toggle="reply-{{ $comment->id }}">Reply</button>

                @if (Auth::user()->is_admin || Auth::id() === $comment->user_id)
                    <form method="POST" action="{{ route('comments.delete', $comment) }}" class="comment-item__delete-form" data-async-comment-form onsubmit="return confirm('Delete this comment?');">
                        @csrf
                        <button type="submit" class="comment-item__text-action">Delete</button>
                    </form>
                @endif
            </div>
        @else
            <p class="comment-item__login-hint"><a href="{{ route('login') }}">Log in</a> to like or reply.</p>
        @endauth
    </div>

    @auth
        <form method="POST" action="{{ route('comments.reply', $comment) }}" class="comment-form comment-form--reply comment-form--hidden" id="reply-{{ $comment->id }}" data-async-comment-form>
            @csrf
            <input type="hidden" name="post_slug" value="{{ $post->slug }}">
            <textarea name="body" rows="2" required class="comment-form__textarea" placeholder="Write a reply..."></textarea>
            <button type="submit" class="comment-form__button">Post reply</button>
        </form>
    @endauth

    @if ($comment->repliesRecursive->isNotEmpty())
        <p class="comment-item__replies-label">Replies</p>
    @endif

    @foreach ($comment->repliesRecursive as $reply)
        @include('posts.partials.comment', ['comment' => $reply, 'post' => $post, 'level' => $level + 1, 'likedCommentIds' => $likedCommentIds ?? []])
    @endforeach
</article>
