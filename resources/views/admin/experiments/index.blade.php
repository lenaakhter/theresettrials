@extends('layouts.admin')

@section('content')
<main class="admin-posts">
    <div class="admin-posts__header">
        <div>
            <h1 class="admin-posts__title">Manage Experiments</h1>
            <p class="admin-posts__subtitle">Create, update, archive, and remove current experiment tracking.</p>
        </div>
        <div class="admin-actions">
            <a href="{{ route('admin.experiments.create') }}" class="admin-posts__logout admin-posts__logout--link">Create Experiment</a>
        </div>
    </div>

    @if(session('success'))
        <div class="admin-flash admin-flash--success" data-flash>
            <span>{{ session('success') }}</span>
            <button type="button" class="admin-flash__close" data-flash-close aria-label="Dismiss notification">&times;</button>
        </div>
    @endif

    <section class="admin-editor admin-editor--wide">
        @if($experiments->count())
            <div class="admin-table-wrap">
                <table class="admin-table admin-experiments-table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Start Date</th>
                            <th>Visibility</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($experiments as $experiment)
                            <tr data-experiment-row data-experiment-id="{{ $experiment->id }}">
                                <td>{{ $experiment->title }}</td>
                                <td>{{ $experiment->category ?? 'N/A' }}</td>
                                <td>
                                    <span class="admin-badge admin-badge--{{ $experiment->status }}">
                                        {{ ucfirst($experiment->status) }}
                                    </span>
                                </td>
                                <td>{{ $experiment->start_date->format('M d, Y') }}</td>
                                <td>
                                    <span class="admin-badge {{ $experiment->archived ? 'admin-badge--archived' : 'admin-badge--visible' }}" data-archive-state>
                                        {{ $experiment->archived ? 'Archived' : 'Visible' }}
                                    </span>
                                </td>
                                <td class="admin-experiments-table__actions">
                                    <a href="{{ route('experiments.show', $experiment) }}" class="admin-posts__logout admin-posts__logout--link">View</a>
                                    <a href="{{ route('admin.experiments.edit', $experiment) }}" class="admin-posts__logout admin-posts__logout--link">Edit</a>
                                    <form action="{{ route('admin.experiments.archive', $experiment) }}" method="POST" data-archive-form>
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="admin-posts__logout" data-archive-button>
                                            {{ $experiment->archived ? 'Unarchive' : 'Archive' }}
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.experiments.destroy', $experiment) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="admin-posts__logout admin-posts__logout--danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="admin-recent__empty">No experiments yet. <a href="{{ route('admin.experiments.create') }}" class="admin-inline-link">Create one now.</a></p>
        @endif
    </section>
</main>

<script>
document.addEventListener('click', function (event) {
    const closeButton = event.target.closest('[data-flash-close]');
    if (closeButton) {
        const flash = closeButton.closest('[data-flash]');
        if (flash) {
            flash.remove();
        }
    }
});

document.querySelectorAll('[data-archive-form]').forEach(function (form) {
    form.addEventListener('submit', async function (event) {
        event.preventDefault();

        const button = form.querySelector('[data-archive-button]');
        const row = form.closest('[data-experiment-row]');
        const badge = row ? row.querySelector('[data-archive-state]') : null;
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        if (!button || !row || !badge || !token) {
            form.submit();
            return;
        }

        const originalLabel = button.textContent.trim();
        button.disabled = true;
        button.textContent = 'Saving...';

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({ _method: 'PATCH' }),
            });

            if (!response.ok) {
                throw new Error('Archive request failed');
            }

            const payload = await response.json();
            const archived = Boolean(payload.archived);

            badge.textContent = archived ? 'Archived' : 'Visible';
            badge.classList.toggle('admin-badge--archived', archived);
            badge.classList.toggle('admin-badge--visible', !archived);
            button.textContent = archived ? 'Unarchive' : 'Archive';

            const existingFlash = document.querySelector('[data-flash]');
            if (existingFlash) {
                existingFlash.remove();
            }

            const flash = document.createElement('div');
            flash.className = 'admin-flash admin-flash--success';
            flash.setAttribute('data-flash', '');
            flash.innerHTML = '<span>' + payload.message + '</span><button type="button" class="admin-flash__close" data-flash-close aria-label="Dismiss notification">&times;</button>';

            const header = document.querySelector('.admin-posts__header');
            if (header) {
                header.insertAdjacentElement('afterend', flash);
            }
        } catch (error) {
            button.textContent = originalLabel;

            const existingFlash = document.querySelector('[data-flash]');
            if (existingFlash) {
                existingFlash.remove();
            }

            const flash = document.createElement('div');
            flash.className = 'admin-flash admin-flash--error';
            flash.setAttribute('data-flash', '');
            flash.innerHTML = '<span>Could not update the archive state. Please try again.</span><button type="button" class="admin-flash__close" data-flash-close aria-label="Dismiss notification">&times;</button>';

            const header = document.querySelector('.admin-posts__header');
            if (header) {
                header.insertAdjacentElement('afterend', flash);
            }
        } finally {
            button.disabled = false;
            if (button.textContent === 'Saving...') {
                button.textContent = originalLabel;
            }
        }
    });
});
</script>
@endsection
