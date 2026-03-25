<article class="comment-item" style="margin-left: {{ $level * 1.2 }}rem;">
    <div class="comment-item__header">
        @if ($comment->user->profile_photo)
            <img src="{{ asset($comment->user->profile_photo) }}" alt="{{ $comment->user->comment_name }}" class="comment-item__avatar">
        @else
            <div class="comment-item__avatar comment-item__avatar--placeholder">{{ strtoupper(substr($comment->user->comment_name, 0, 1)) }}</div>
        @endif

        <div>
            <p class="comment-item__name">{{ $comment->user->comment_name }}</p>
            <p class="comment-item__time">{{ $comment->created_at->diffForHumans() }}</p>
        </div>
    </div>

    <p class="comment-item__body">{{ $comment->body }}</p>

    <div class="comment-item__actions">
        <p class="comment-item__likes" data-like-count="{{ $comment->id }}">{{ $comment->likes_count }} {{ $comment->likes_count === 1 ? 'like' : 'likes' }}</p>

        @auth
            <button type="button" class="comment-item__heart" data-like-btn="{{ $comment->id }}" data-like-url="{{ route('comments.like', $comment) }}" data-liked="{{ in_array($comment->id, $likedCommentIds ?? [], true) ? 'true' : 'false' }}" aria-label="Like comment"></button>

            <button type="button" class="comment-item__action-btn" data-reply-toggle="reply-{{ $comment->id }}">Reply</button>

            @if (Auth::user()->is_admin || Auth::id() === $comment->user_id)
                <button type="button" class="comment-item__delete-btn" data-delete-trigger="{{ $comment->id }}" aria-label="Delete comment">Delete</button>
                <div class="delete-modal delete-modal--hidden" id="delete-modal-{{ $comment->id }}">
                    <div class="delete-modal__overlay" data-delete-modal="{{ $comment->id }}"></div>
                    <div class="delete-modal__content">
                        <p class="delete-modal__text">Delete this comment?</p>
                        <p class="delete-modal__subtext">This action cannot be undone.</p>
                        <div class="delete-modal__actions">
                            <button type="button" class="delete-modal__cancel" data-delete-modal="{{ $comment->id }}">Cancel</button>
                            <form method="POST" action="{{ route('comments.delete', $comment) }}" style="display: inline;">
                                @csrf
                                <button type="submit" class="delete-modal__confirm">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        @else
            <p class="comment-item__login-hint"><a href="{{ route('login') }}">Log in</a> to like or reply.</p>
        @endauth
    </div>

    @auth
        <form method="POST" action="{{ route('comments.reply', $comment) }}" class="comment-form comment-form--reply comment-form--hidden" id="reply-{{ $comment->id }}">
            @csrf
            <input type="hidden" name="post_slug" value="{{ $post->slug }}">
            <textarea name="body" rows="2" required class="comment-form__textarea" placeholder="Write a reply..."></textarea>
            <button type="submit" class="comment-form__button">Post reply</button>
        </form>
    @endauth

    @foreach ($comment->repliesRecursive as $reply)
        @include('posts.partials.comment', ['comment' => $reply, 'post' => $post, 'level' => $level + 1, 'likedCommentIds' => $likedCommentIds ?? []])
    @endforeach
</article>
