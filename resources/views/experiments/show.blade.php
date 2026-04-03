@extends('layouts.app')

@section('content')
<section class="experiment-timeline">
    <div class="experiment-timeline__header">
        <h1 class="experiment-timeline__title">{{ $experiment->title }}</h1>
        <p style="color: #8C7B7F; margin: 0.5rem 0 0;">{{ $experiment->description }}</p>
    </div>


    @if ($experimentResources->isNotEmpty() && !$experiment->archived)
        <div class="experiment-timeline__header" style="margin-bottom: 1.5rem;">
            <div class="product-box product-box--experiment product-box--currently-testing">
                <h3 class="product-box__title">Currently Testing</h3>
                <div class="product-box__list">
                    @foreach ($experimentResources as $res)
                        <a href="{{ $res->product_url }}" target="_blank" rel="noopener" class="product-box__item">
                            @if ($res->image_url)
                                <img src="{{ $res->image_url }}" alt="{{ $res->name }}" class="product-box__img">
                            @endif
                            <span class="product-box__name">{{ $res->name }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <div class="experiment-timeline__grid">
        <!-- Timeline with circles -->
        <div class="experiment-timeline__line">
            @forelse($experiment->entries as $entry)
                <div class="experiment-timeline__entry">
                    <div class="experiment-timeline__circle">
                        <div class="experiment-timeline__circle-date">
                            {{ $entry->entry_date->format('M d') }}
                        </div>
                    </div>
                </div>
            @empty
                <div style="padding: 2rem; text-align: center; color: #8C7B7F;">
                    No entries yet. 
                    @if(auth()->user() && auth()->user()->is_admin)
                        <a href="{{ route('admin.experiments.add-entry', $experiment) }}" style="color: #c56a7f; text-decoration: underline;">Add the first entry</a>
                    @endif
                </div>
            @endforelse
        </div>

        <!-- Updates content -->
        <div class="experiment-timeline__updates">
            @forelse($experiment->entries as $entry)
                <div class="experiment-timeline__update-item">
                    <span class="experiment-timeline__update-type">{{ $entry->type }}</span>
                    <p class="experiment-timeline__update-content">{{ $entry->content }}</p>
                    <p class="experiment-timeline__update-meta">{{ $entry->entry_date->format('F d, Y') }}</p>
                </div>
            @empty
            @endforelse

            @if(auth()->user() && auth()->user()->is_admin)
                <div style="margin-top: 2rem; padding: 1.5rem; background: #f0f0f0; border-radius: 8px; text-align: center;">
                    <p style="margin: 0 0 1rem; color: #8C7B7F;">No entries yet. Add updates to this experiment.</p>
                    <a href="{{ route('admin.experiments.add-entry', $experiment) }}" style="display: inline-block; background: #c56a7f; color: white; padding: 0.5rem 1rem; border-radius: 6px; text-decoration: none; font-weight: 700;">Add Entry</a>
                </div>
            @endif
        </div>
    </div>

    <!-- Comments Section -->
    <div class="experiment-comments">
        <h2 class="experiment-comments__title">Discussion</h2>

        @if (session('success'))
            <div class="comments-section__status dismissible-notice" data-dismissible-notice>
                <span>{{ session('success') }}</span>
                <button type="button" class="dismissible-notice__close" data-notice-close aria-label="Dismiss notification">&times;</button>
            </div>
        @endif
        
        @if(auth()->check())
            <div class="experiment-comments__composer">
                <form action="{{ route('experiments.comment', $experiment) }}" method="POST" class="comment-form" data-async-comment-form>
                    @csrf
                    <textarea
                        name="content"
                        rows="4"
                        required
                        class="comment-form__textarea"
                        placeholder="Share your thoughts..."
                    ></textarea>
                    <button type="submit" class="comment-form__button">Post comment</button>
                </form>
            </div>
        @else
            <p class="comment-item__login-hint" style="text-align: center; margin-top: 0;">
                <a href="{{ route('login') }}" style="color: #c56a7f; text-decoration: underline;">Log in</a> to leave a comment
            </p>
        @endif

        <div class="experiment-comments__list">
        @forelse($comments as $comment)
            @include('experiments.partials.comment', ['comment' => $comment, 'experiment' => $experiment, 'level' => 0, 'likedCommentIds' => $likedCommentIds ?? []])
        @empty
            <p style="color: #8C7B7F; text-align: center;">No comments yet. Be the first to share!</p>
        @endforelse
        </div>
    </div>
</section>

@auth
    <script>
        (() => {
            const tokenNode = document.querySelector('meta[name="csrf-token"]');
            if (!tokenNode) {
                return;
            }

            const token = tokenNode.getAttribute('content');

            document.addEventListener('click', (event) => {
                const replyToggle = event.target.closest('[data-reply-toggle]');
                if (replyToggle) {
                    const formId = replyToggle.getAttribute('data-reply-toggle');
                    const form = formId ? document.getElementById(formId) : null;
                    if (form) {
                        form.classList.toggle('comment-form--hidden');
                    }

                    return;
                }

                const likeBtn = event.target.closest('[data-like-btn]');
                if (!likeBtn) {
                    return;
                }

                const commentId = likeBtn.getAttribute('data-like-btn');
                const url = likeBtn.getAttribute('data-like-url');

                if (!commentId || !url) {
                    return;
                }

                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json',
                    },
                })
                    .then((response) => response.json())
                    .then((data) => {
                        likeBtn.setAttribute('data-liked', data.liked ? 'true' : 'false');

                        const countEl = document.querySelector('[data-like-count="' + commentId + '"]');
                        if (countEl) {
                            const plural = data.likes_count === 1 ? 'like' : 'likes';
                            countEl.textContent = data.likes_count + ' ' + plural;
                            countEl.setAttribute('data-liked', data.liked ? 'true' : 'false');
                        }
                    })
                    .catch(() => {
                        // Keep the current UI state if a like request fails.
                    });
            });

            const refreshDiscussion = async () => {
                const response = await fetch(window.location.href, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                });
                const html = await response.text();
                const parsed = new DOMParser().parseFromString(html, 'text/html');
                const nextDiscussion = parsed.querySelector('.experiment-comments');
                const currentDiscussion = document.querySelector('.experiment-comments');

                if (nextDiscussion && currentDiscussion) {
                    currentDiscussion.replaceWith(nextDiscussion);
                }
            };

            document.addEventListener('submit', async (event) => {
                const form = event.target.closest('form[data-async-comment-form]');
                if (!form) {
                    return;
                }

                event.preventDefault();

                const submitButton = form.querySelector('button[type="submit"]');
                if (submitButton) {
                    submitButton.disabled = true;
                }

                try {
                    await fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': token,
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'text/html',
                        },
                        body: new FormData(form),
                    });

                    await refreshDiscussion();
                } catch (error) {
                    // Keep current page state if request fails.
                } finally {
                    if (submitButton) {
                        submitButton.disabled = false;
                    }
                }
            });

            document.addEventListener('keydown', (event) => {
                if (event.key !== 'Enter' || event.shiftKey) return;
                const textarea = event.target.closest('.comment-form__textarea');
                if (!textarea) return;
                const form = textarea.closest('form[data-async-comment-form]');
                if (!form) return;
                event.preventDefault();
                form.dispatchEvent(new Event('submit', { bubbles: true, cancelable: true }));
            });
        })();
    </script>
@endauth
@endsection
