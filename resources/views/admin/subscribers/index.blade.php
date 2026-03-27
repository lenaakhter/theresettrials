@extends('layouts.admin')

@section('content')
    <main class="admin-posts">
        <div class="admin-posts__header">
            <div>
                <h1 class="admin-posts__title">Accounts & Subscribers</h1>
                <p class="admin-posts__subtitle">All user accounts, plus opted-in subscriber totals.</p>
                <p class="admin-posts__badge">Total accounts: {{ number_format($totalAccounts) }}</p>
                <p class="admin-posts__badge">Email subscribers (opted in): {{ number_format($subscriberCount) }}</p>
            </div>
            <div class="admin-actions">
                <a href="{{ route('admin.subscribers.export') }}" class="admin-posts__logout admin-posts__logout--link">Export CSV</a>
                <a href="{{ route('admin.subscribers.export-excel') }}" class="admin-posts__logout admin-posts__logout--link">Convert to Excel</a>
            </div>
        </div>

        <section class="admin-editor">
            @if (session('status'))
                <div class="profile-page__success dismissible-notice" data-dismissible-notice>
                    <span>{{ session('status') }}</span>
                    <button type="button" class="dismissible-notice__close" data-notice-close aria-label="Dismiss notification">&times;</button>
                </div>
            @endif

            @if ($errors->any())
                <div class="reader-auth__error dismissible-notice" data-dismissible-notice>
                    <span>{{ $errors->first() }}</span>
                    <button type="button" class="dismissible-notice__close" data-notice-close aria-label="Dismiss notification">&times;</button>
                </div>
            @endif

            @if (session('error'))
                <div class="reader-auth__error dismissible-notice" data-dismissible-notice>
                    <span>{{ session('error') }}</span>
                    <button type="button" class="dismissible-notice__close" data-notice-close aria-label="Dismiss notification">&times;</button>
                </div>
            @endif

            @if ($accounts->isEmpty())
                <p class="admin-recent__empty">No accounts yet.</p>
            @else
                <div class="admin-table-wrap">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Email</th>
                                <th>Display Name</th>
                                <th>Username</th>
                                <th>Email Opt-in</th>
                                <th>Banned Until</th>
                                <th>Joined</th>
                                <th>Ban Controls</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($accounts as $account)
                                <tr class="{{ $account->banned_until && now()->lessThan($account->banned_until) ? 'admin-table__row--banned' : '' }}">
                                    <td>{{ $account->id }}</td>
                                    <td>{{ $account->email }}</td>
                                    <td>{{ $account->display_name ?: '—' }}</td>
                                    <td>{{ $account->username ?: '—' }}</td>
                                    <td>{{ $account->email_notifications_opt_in ? 'Yes' : 'No' }}</td>
                                    <td>
                                        @if ($account->banned_until && now()->lessThan($account->banned_until))
                                            {{ $account->banned_until->format('M d, Y H:i') }}
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td>{{ optional($account->created_at)->format('M d, Y H:i') }}</td>
                                    <td>
                                        @if ($account->is_admin)
                                            <span class="admin-table__muted">Admin account</span>
                                        @else
                                            <div class="admin-table__inline-form">
                                                @if ($account->banned_until && now()->lessThan($account->banned_until))
                                                    <form method="POST" action="{{ route('admin.subscribers.unban', $account) }}" class="admin-table__inline-form">
                                                        @csrf
                                                        <button type="submit" class="admin-posts__logout admin-posts__logout--link">Remove ban</button>
                                                    </form>
                                                @endif
                                                <button
                                                    type="button"
                                                    class="admin-posts__logout admin-posts__logout--link admin-ban-trigger"
                                                    data-user-id="{{ $account->id }}"
                                                    data-user-email="{{ $account->email }}"
                                                >
                                                    Ban account
                                                </button>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        @if (! $account->is_admin)
                                            <form method="POST" action="{{ route('admin.subscribers.destroy', $account) }}" onsubmit="return confirm('Permanently delete account for {{ addslashes($account->email) }}? All their comments will also be deleted. This cannot be undone.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="admin-table__delete-btn">Delete</button>
                                            </form>
                                        @else
                                            —
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="admin-pagination">
                    {{ $accounts->links() }}
                </div>
            @endif
        </section>

        <div class="admin-ban-modal" id="admin-ban-modal" hidden>
            <div class="admin-ban-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="admin-ban-title">
                <button type="button" class="admin-ban-modal__close" id="admin-ban-close" aria-label="Close ban form">&times;</button>
                <h2 id="admin-ban-title" class="admin-posts__title">Ban account</h2>
                <p class="admin-posts__subtitle" id="admin-ban-target"></p>

                <form method="POST" action="{{ route('admin.subscribers.ban', ['user' => '__USER__']) }}" id="admin-ban-form" class="admin-form">
                    @csrf

                    <label for="admin-ban-hours" class="admin-form__label">How many hours?</label>
                    <input id="admin-ban-hours" name="hours" type="number" min="1" max="8760" value="24" required class="admin-form__input">

                    <label for="admin-ban-reason" class="admin-form__label">Reason</label>
                    <textarea id="admin-ban-reason" name="reason" required class="admin-form__textarea" rows="4" placeholder="Explain why this account is being banned."></textarea>

                    <div class="admin-ban-modal__actions">
                        <button type="button" class="admin-posts__logout admin-posts__logout--link" id="admin-ban-cancel">Cancel</button>
                        <button type="submit" class="admin-form__button">Save ban</button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script>
        (() => {
            const modal = document.getElementById('admin-ban-modal');
            const closeBtn = document.getElementById('admin-ban-close');
            const cancelBtn = document.getElementById('admin-ban-cancel');
            const targetText = document.getElementById('admin-ban-target');
            const banForm = document.getElementById('admin-ban-form');
            const triggers = document.querySelectorAll('.admin-ban-trigger');

            if (!modal || !closeBtn || !cancelBtn || !targetText || !banForm || triggers.length === 0) {
                return;
            }

            const actionTemplate = banForm.getAttribute('action');

            const closeModal = () => {
                modal.hidden = true;
            };

            triggers.forEach((trigger) => {
                trigger.addEventListener('click', () => {
                    const userId = trigger.getAttribute('data-user-id');
                    const email = trigger.getAttribute('data-user-email');

                    if (!userId || !actionTemplate) {
                        return;
                    }

                    banForm.setAttribute('action', actionTemplate.replace('__USER__', userId));
                    targetText.textContent = 'Target: ' + (email || 'selected account');
                    modal.hidden = false;
                });
            });

            closeBtn.addEventListener('click', closeModal);
            cancelBtn.addEventListener('click', closeModal);

            banForm.addEventListener('submit', () => {
                const submitButton = banForm.querySelector('button[type="submit"]');
                if (!submitButton) {
                    return;
                }

                submitButton.disabled = true;
                submitButton.textContent = 'Saving...';
            });

            modal.addEventListener('click', (event) => {
                if (event.target === modal) {
                    closeModal();
                }
            });

            window.addEventListener('keydown', (event) => {
                if (event.key === 'Escape' && !modal.hidden) {
                    closeModal();
                }
            });
        })();
    </script>
@endsection
