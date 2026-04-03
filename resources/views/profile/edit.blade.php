@extends('layouts.app')

@section('content')
<section class="profile-page">
    <div class="profile-page__card">
        <h1 class="profile-page__title">Your Profile</h1>
        <p class="profile-page__subtitle">Set how your profile appears to the community.</p>

        @if (session('status'))
            <div class="profile-toast" id="profile-save-toast" role="status" aria-live="polite">
                <span>{{ session('status') }}</span>
                <button type="button" class="profile-toast__close" id="profile-save-toast-close" aria-label="Dismiss notification">&times;</button>
            </div>
        @endif

        @if ($errors->any())
            <div class="reader-auth__error dismissible-notice" data-dismissible-notice>
                <span>{{ $errors->first() }}</span>
                <button type="button" class="dismissible-notice__close" data-notice-close aria-label="Dismiss notification">&times;</button>
            </div>
        @endif

        <form method="POST" action="{{ route('profile.update') }}" class="admin-form">
            @csrf
            @method('PUT')

            <div class="profile-page__identity">
                <span class="profile-page__identity-badge" aria-hidden="true">{{ strtoupper(substr($currentDisplayName, 0, 1)) }}</span>

                <p class="profile-page__display-name">{{ $currentDisplayName }}</p>
            </div>

            <label for="display_name" class="admin-form__label">Display name</label>
            <div class="profile-page__inline-save">
                <input id="display_name" name="display_name" type="text" value="{{ old('display_name', $currentDisplayName) }}" class="admin-form__input" placeholder="How your name appears in comments" autocomplete="nickname" required data-inline-save>
                <button type="submit" class="profile-page__inline-save-btn" hidden data-inline-save-btn>Save</button>
            </div>

            <label for="username" class="admin-form__label">Username</label>
            <div class="profile-page__inline-save">
                <input
                    id="username"
                    name="username"
                    type="text"
                    value="{{ old('username', $currentUsername) }}"
                    class="admin-form__input"
                    placeholder="letters, numbers, and underscores"
                    autocomplete="username"
                    required
                    data-inline-save
                    {{ ! $canEditUsername ? 'readonly' : '' }}
                >
                <button type="submit" class="profile-page__inline-save-btn" hidden data-inline-save-btn>Save</button>
            </div>
            @if (! $canEditUsername && $nextUsernameChangeAt)
                <p class="profile-page__hint">You can change your username again on {{ $nextUsernameChangeAt->format('M j, Y') }}. Usernames can only be changed once every 14 days.</p>
            @else
                <p class="profile-page__hint">Usernames must be unique and can only be changed once every 14 days.</p>
            @endif

            <div class="profile-page__meta">
                <p><span>Email:</span> {{ $user->email }}</p>
                <p><span>Member since:</span> {{ $user->created_at->format('F Y') }}</p>
            </div>

            <div class="profile-page__notif-row">
                <div>
                    <p class="profile-page__notif-label">Email notifications</p>
                    <p class="profile-page__notif-state" id="email-notif-state">{{ $user->email_notifications_opt_in ? 'Opted in' : 'Opted out' }}</p>
                </div>
                <div class="profile-page__inline-save profile-page__inline-save--switch">
                    <input type="hidden" name="email_notifications_opt_in" value="0">
                    <label class="profile-switch" for="email_notifications_opt_in">
                        <input
                            id="email_notifications_opt_in"
                            name="email_notifications_opt_in"
                            type="checkbox"
                            value="1"
                            {{ old('email_notifications_opt_in', $user->email_notifications_opt_in ? '1' : '0') == '1' ? 'checked' : '' }}
                        >
                        <span class="profile-switch__slider" aria-hidden="true"></span>
                    </label>
                </div>
            </div>

            <button type="button" id="toggle-password-fields" class="profile-page__toggle-password">Change password</button>

            <div id="password-fields" class="profile-page__password-fields" hidden>
                <h2 class="profile-page__section-title">Change password</h2>

                <label for="current_password" class="admin-form__label">Current password</label>
                <div class="profile-page__inline-save">
                    <input id="current_password" name="current_password" type="password" class="admin-form__input" autocomplete="current-password" data-inline-save>
                    <button type="submit" class="profile-page__inline-save-btn" hidden data-inline-save-btn>Save</button>
                </div>

                <label for="password" class="admin-form__label">New password</label>
                <div class="profile-page__inline-save">
                    <input id="password" name="password" type="password" class="admin-form__input" autocomplete="new-password" data-inline-save>
                    <button type="submit" class="profile-page__inline-save-btn" hidden data-inline-save-btn>Save</button>
                </div>

                <label for="password_confirmation" class="admin-form__label">Confirm new password</label>
                <div class="profile-page__inline-save">
                    <input id="password_confirmation" name="password_confirmation" type="password" class="admin-form__input" autocomplete="new-password" data-inline-save>
                    <button type="submit" class="profile-page__inline-save-btn" hidden data-inline-save-btn>Save</button>
                </div>
            </div>

            <button type="submit" class="admin-form__button">Save profile changes</button>
        </form>

        <div class="profile-danger-zone">
            <h2 class="profile-danger-zone__title">Delete Account</h2>
            <p class="profile-danger-zone__text">This will permanently delete your account and all your comments. This cannot be undone.</p>
            <form method="POST" action="{{ route('profile.destroy') }}" id="delete-account-form">
                @csrf
                @method('DELETE')
                <button type="button" class="profile-danger-zone__btn" id="delete-account-trigger">Delete My Account</button>
            </form>
        </div>

        <div class="profile-delete-modal" id="profile-delete-modal" hidden>
            <div class="profile-delete-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="delete-modal-title">
                <h2 class="profile-delete-modal__title" id="delete-modal-title">Delete your account?</h2>
                <p class="profile-delete-modal__text">This will permanently delete your account and all your comments. <strong>This cannot be undone.</strong></p>
                <div class="profile-delete-modal__actions">
                    <button type="button" class="profile-delete-modal__cancel" id="delete-modal-cancel">Cancel</button>
                    <button type="button" class="profile-delete-modal__confirm" id="delete-modal-confirm">Yes, delete my account</button>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    (() => {
        const emailNotifToggle = document.getElementById('email_notifications_opt_in');
        const emailNotifState = document.getElementById('email-notif-state');
        const profileForm = document.querySelector('form[action="{{ route('profile.update') }}"]');
        const profileToast = document.getElementById('profile-save-toast');
        const profileToastClose = document.getElementById('profile-save-toast-close');
        const passwordFields = document.getElementById('password-fields');
        const togglePasswordFields = document.getElementById('toggle-password-fields');

        if (!emailNotifToggle || !emailNotifState || !profileForm || !passwordFields || !togglePasswordFields) {
            return;
        }

        if (profileToast && profileToastClose) {
            requestAnimationFrame(() => {
                profileToast.classList.add('is-visible');
            });

            profileToastClose.addEventListener('click', () => {
                profileToast.classList.remove('is-visible');
                profileToast.classList.add('is-hidden');
            });
        }

        const showInlineToast = (message, isError = false) => {
            let toast = document.getElementById('profile-save-toast');

            if (!toast) {
                toast = document.createElement('div');
                toast.id = 'profile-save-toast';
                toast.className = 'profile-toast';
                toast.innerHTML = '<span></span><button type="button" class="profile-toast__close" aria-label="Dismiss notification">&times;</button>';
                document.body.appendChild(toast);

                const closeBtn = toast.querySelector('.profile-toast__close');
                closeBtn.addEventListener('click', () => {
                    toast.classList.remove('is-visible');
                    toast.classList.add('is-hidden');
                });
            }

            toast.classList.remove('is-hidden', 'is-error');
            if (isError) {
                toast.classList.add('is-error');
            }
            toast.querySelector('span').textContent = message;
            requestAnimationFrame(() => toast.classList.add('is-visible'));
        };

        const updateNotifStateLabel = () => {
            emailNotifState.textContent = emailNotifToggle.checked ? 'Opted in' : 'Opted out';
        };

        let inlineInputs = [];
        let isSaving = false;

        const submitProfileForm = async (successMessage = 'Profile updated.') => {
            if (isSaving) {
                return false;
            }

            isSaving = true;

            try {
                const payload = new FormData(profileForm);

                const response = await fetch(profileForm.action, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: payload,
                });

                if (!response.ok) {
                    let errorMessage = 'Could not save changes.';

                    try {
                        const body = await response.json();
                        const firstError = body?.errors ? Object.values(body.errors)[0] : null;

                        if (Array.isArray(firstError) && firstError[0]) {
                            errorMessage = firstError[0];
                        } else if (typeof body?.message === 'string') {
                            errorMessage = body.message;
                        }
                    } catch (_err) {
                        // Ignore JSON parse errors and keep fallback message.
                    }

                    throw new Error(errorMessage);
                }

                updateNotifStateLabel();

                inlineInputs.forEach((field) => {
                    if (field.type === 'password') {
                        field.value = '';
                        field.dataset.initialValue = '';
                    } else if (field.type === 'checkbox') {
                        field.dataset.initialChecked = field.checked ? '1' : '0';
                    } else {
                        field.dataset.initialValue = field.value || '';
                    }

                    const wrapper = field.closest('.profile-page__inline-save');
                    const button = wrapper ? wrapper.querySelector('[data-inline-save-btn]') : null;

                    if (button) {
                        button.hidden = true;
                    }
                });

                showInlineToast(successMessage);
                return true;
            } catch (error) {
                showInlineToast(error?.message || 'Could not save changes.', true);
                return false;
            } finally {
                isSaving = false;
            }
        };

        emailNotifToggle.addEventListener('change', async () => {
            updateNotifStateLabel();
            const didSave = await submitProfileForm('Notification preference saved.');

            if (!didSave) {
                emailNotifToggle.checked = !emailNotifToggle.checked;
                updateNotifStateLabel();
            }
        });

        togglePasswordFields.addEventListener('click', () => {
            passwordFields.hidden = !passwordFields.hidden;
            togglePasswordFields.textContent = passwordFields.hidden ? 'Change password' : 'Hide password fields';
        });

        inlineInputs = Array.from(document.querySelectorAll('[data-inline-save]'));

        const hasChanged = (field) => {
            if (field.hasAttribute('readonly')) {
                return false;
            }

            if (field.type === 'checkbox') {
                const initialChecked = field.dataset.initialChecked === '1';

                return field.checked !== initialChecked;
            }

            if (field.type === 'password') {
                return field.value.length > 0;
            }

            return field.value !== (field.dataset.initialValue || '');
        };

        const syncInlineButton = (field) => {
            const wrapper = field.closest('.profile-page__inline-save');

            if (!wrapper) {
                return;
            }

            const button = wrapper.querySelector('[data-inline-save-btn]');

            if (!button) {
                return;
            }

            button.hidden = !hasChanged(field);
        };

        inlineInputs.forEach((field) => {
            if (field.type === 'checkbox') {
                field.dataset.initialChecked = field.checked ? '1' : '0';
            } else {
                field.dataset.initialValue = field.value || '';
            }
            field.addEventListener('input', () => syncInlineButton(field));
            field.addEventListener('change', () => syncInlineButton(field));
            syncInlineButton(field);
        });

        // Delete account modal
        const deleteModal = document.getElementById('profile-delete-modal');
        const deleteTrigger = document.getElementById('delete-account-trigger');
        const deleteCancel = document.getElementById('delete-modal-cancel');
        const deleteConfirm = document.getElementById('delete-modal-confirm');
        const deleteForm = document.getElementById('delete-account-form');

        if (deleteModal && deleteTrigger && deleteCancel && deleteConfirm && deleteForm) {
            deleteTrigger.addEventListener('click', () => {
                deleteModal.hidden = false;
                deleteModal.offsetHeight; // force reflow
                deleteModal.classList.add('is-open');
                deleteCancel.focus();
            });

            const closeDeleteModal = () => {
                deleteModal.classList.remove('is-open');
                deleteModal.addEventListener('transitionend', () => { deleteModal.hidden = true; }, { once: true });
            };

            deleteCancel.addEventListener('click', closeDeleteModal);
            deleteConfirm.addEventListener('click', () => deleteForm.submit());
            deleteModal.addEventListener('click', (e) => { if (e.target === deleteModal) closeDeleteModal(); });
            document.addEventListener('keydown', (e) => { if (e.key === 'Escape' && !deleteModal.hidden) closeDeleteModal(); });
        }
    })();
</script>
@endpush
