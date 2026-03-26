@extends('layouts.app')

@section('content')
<section class="experiment-timeline">
    <div class="experiment-timeline__header">
        <h1 class="experiment-timeline__title">{{ $experiment->title }}</h1>
        <p style="color: #8C7B7F; margin: 0.5rem 0 0;">{{ $experiment->description }}</p>
    </div>

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
        
        @if(auth()->check())
            <div style="background: #EFE8EB; border: 1px solid #E2D9DD; border-radius: 12px; padding: 1.5rem; margin-bottom: 2rem;">
                <form action="{{ route('experiments.comment', $experiment) }}" method="POST">
                    @csrf
                    <textarea 
                        name="content" 
                        placeholder="Share your thoughts..." 
                        style="width: 100%; min-height: 100px; padding: 0.75rem; border: 1px solid #d5c7cc; border-radius: 8px; font-family: 'Quicksand', sans-serif; font-size: 0.95rem; resize: vertical;"
                        required
                    ></textarea>
                    <button 
                        type="submit" 
                        style="margin-top: 0.75rem; padding: 0.5rem 1rem; background: #c56a7f; color: white; border: none; border-radius: 6px; font-weight: 700; cursor: pointer; font-family: 'Quicksand', sans-serif;"
                    >
                        Post Comment
                    </button>
                </form>
            </div>
        @else
            <p style="color: #8C7B7F; text-align: center;">
                <a href="{{ route('login') }}" style="color: #c56a7f; text-decoration: underline;">Log in</a> to leave a comment
            </p>
        @endif

        @forelse($experiment->comments()->latest()->get() as $comment)
            <div style="background: #EFE8EB; border: 1px solid #E2D9DD; border-radius: 8px; padding: 1rem; margin-bottom: 1rem;">
                <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                    <strong style="color: #2B2B2B;">{{ $comment->user->name }}</strong>
                    <span style="color: #8C7B7F; font-size: 0.85rem;">{{ $comment->created_at->diffForHumans() }}</span>
                </div>
                <p style="margin: 0; color: #5A5A5A; line-height: 1.6;">{{ $comment->content }}</p>
            </div>
        @empty
            <p style="color: #8C7B7F; text-align: center;">No comments yet. Be the first to share!</p>
        @endforelse
    </div>
</section>
@endsection
