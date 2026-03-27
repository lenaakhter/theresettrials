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

        @php
            $hasAvatar = ! old('remove_profile_photo') && filled($user->profile_photo);
        @endphp

        <form method="POST" action="{{ route('profile.update') }}" class="admin-form" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="profile-page__identity">
                <div class="profile-page__avatar-wrap">
                    <label for="profile_photo" class="profile-page__avatar-picker" title="Change profile photo">
                        <span id="profile-avatar-placeholder" class="profile-page__avatar-layer {{ $hasAvatar ? 'profile-page__avatar-layer--hidden' : '' }}">
                            <span class="profile-page__avatar-large profile-page__avatar-large--placeholder">
                                {{ strtoupper(substr($user->display_name ?: $user->name, 0, 1)) }}
                            </span>
                        </span>

                        <span id="profile-avatar-image-wrap" class="profile-page__avatar-layer {{ $hasAvatar ? '' : 'profile-page__avatar-layer--hidden' }}">
                            <img
                                src="{{ $hasAvatar ? asset($user->profile_photo) : '' }}"
                                alt="Profile photo preview"
                                class="profile-page__avatar-large"
                                id="profile-avatar-preview"
                                style="object-position: {{ $currentAvatarFocusX }}% {{ $currentAvatarFocusY }}%;"
                            >
                        </span>

                    </label>

                    <div class="profile-page__avatar-actions">
                        <button type="button" class="profile-page__avatar-edit" id="profile-avatar-actions-toggle" aria-label="Profile photo options" aria-expanded="false">
                            <svg viewBox="0 0 24 24" role="presentation" focusable="false">
                                <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zm17.71-10.04a1.003 1.003 0 0 0 0-1.42l-2.5-2.5a1.003 1.003 0 0 0-1.42 0l-1.96 1.96 3.75 3.75 2.13-1.79z"/>
                            </svg>
                        </button>

                        <div class="profile-page__avatar-menu" id="profile-avatar-menu" hidden>
                            <button type="button" class="profile-page__avatar-menu-btn" id="profile-avatar-upload-action">Upload photo</button>
                            <button type="button" class="profile-page__avatar-menu-btn" id="profile-avatar-remove-action">Remove photo</button>
                        </div>
                    </div>
                </div>

                <p class="profile-page__display-name">{{ $currentDisplayName }}</p>

                <input id="profile_photo" name="profile_photo" type="file" accept="image/*" class="profile-page__file-input">
                <input id="remove_profile_photo" name="remove_profile_photo" type="checkbox" value="1" class="profile-page__file-input" {{ old('remove_profile_photo') ? 'checked' : '' }}>
                <input id="avatar_focus_x" name="avatar_focus_x" type="hidden" value="{{ $currentAvatarFocusX }}">
                <input id="avatar_focus_y" name="avatar_focus_y" type="hidden" value="{{ $currentAvatarFocusY }}">
                <p class="profile-page__avatar-help">Tap the avatar to upload. Drag the image inside the circle to reposition.</p>
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
        </form>

        <div id="avatar-editor" class="avatar-editor" hidden>
            <div class="avatar-editor__dialog" role="dialog" aria-modal="true" aria-labelledby="avatar-editor-title">
                <button type="button" class="avatar-editor__close" id="avatar-editor-close" aria-label="Close preview">&times;</button>
                <h2 id="avatar-editor-title" class="avatar-editor__title">Preview your profile photo</h2>
                <p class="avatar-editor__subtitle">Drag the photo to position it in the circle.</p>

                <div class="avatar-editor__circle" id="avatar-editor-circle">
                    <span id="avatar-editor-placeholder" class="avatar-editor__placeholder">
                        {{ strtoupper(substr($user->display_name ?: $user->name, 0, 1)) }}
                    </span>
                    <img id="avatar-editor-image" class="avatar-editor__image avatar-editor__image--hidden" src="" alt="Large avatar preview">
                </div>

                <button type="button" class="admin-form__button" id="avatar-editor-done">Use this photo</button>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    (() => {
        const input = document.getElementById('profile_photo');
        const emailNotifToggle = document.getElementById('email_notifications_opt_in');
        const emailNotifState = document.getElementById('email-notif-state');
        const displayNameInput = document.getElementById('display_name');
        const usernameInput = document.getElementById('username');
        const profileToast = document.getElementById('profile-save-toast');
        const profileToastClose = document.getElementById('profile-save-toast-close');

        if (profileToast && profileToastClose) {
            requestAnimationFrame(() => {
                profileToast.classList.add('is-visible');
            });

            profileToastClose.addEventListener('click', () => {
                profileToast.classList.remove('is-visible');
                profileToast.classList.add('is-hidden');
            });
        }

        const preview = document.getElementById('profile-avatar-preview');
        const placeholderLayer = document.getElementById('profile-avatar-placeholder');
        const imageLayer = document.getElementById('profile-avatar-image-wrap');
        const focusXInput = document.getElementById('avatar_focus_x');
        const focusYInput = document.getElementById('avatar_focus_y');
        const profileForm = document.querySelector('form[action="{{ route('profile.update') }}"]');
        const removeCheckbox = document.getElementById('remove_profile_photo');
        const avatarActionsToggle = document.getElementById('profile-avatar-actions-toggle');
        const avatarMenu = document.getElementById('profile-avatar-menu');
        const uploadAction = document.getElementById('profile-avatar-upload-action');
        const removeAction = document.getElementById('profile-avatar-remove-action');
        const avatarEditor = document.getElementById('avatar-editor');
        const avatarEditorCircle = document.getElementById('avatar-editor-circle');
        const avatarEditorPlaceholder = document.getElementById('avatar-editor-placeholder');
        const avatarEditorImage = document.getElementById('avatar-editor-image');
        const avatarEditorDone = document.getElementById('avatar-editor-done');
        const avatarEditorClose = document.getElementById('avatar-editor-close');
        const passwordFields = document.getElementById('password-fields');
        const togglePasswordFields = document.getElementById('toggle-password-fields');

        if (!input || !preview || !focusXInput || !focusYInput || !profileForm || !emailNotifToggle || !emailNotifState || !displayNameInput || !usernameInput || !avatarEditor || !avatarEditorCircle || !avatarEditorImage || !avatarEditorDone || !avatarActionsToggle || !avatarMenu || !uploadAction || !removeAction || !avatarEditorPlaceholder || !removeCheckbox || !avatarEditorClose || !passwordFields || !togglePasswordFields) {
            return;
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

                previousPreviewSrc = preview.getAttribute('src') || '';
                previousFocusX = parseFloat(focusXInput.value || '50');
                previousFocusY = parseFloat(focusYInput.value || '50');
                previousRemoveChecked = removeCheckbox.checked;

                showInlineToast(successMessage);
                return true;
            } catch (error) {
                showInlineToast(error?.message || 'Could not save changes.', true);
                return false;
            } finally {
                isSaving = false;
            }
        };

        profileForm.addEventListener('submit', async (event) => {
            event.preventDefault();
            await submitProfileForm('Profile updated.');
        });

        emailNotifToggle.addEventListener('change', async () => {
            updateNotifStateLabel();
            const didSave = await submitProfileForm('Notification preference saved.');

            if (!didSave) {
                emailNotifToggle.checked = !emailNotifToggle.checked;
                updateNotifStateLabel();
            }
        });

        let isDragging = false;
        let activePointerId = null;
        let hasPendingSelection = false;
        let previousPreviewSrc = preview.getAttribute('src') || '';
        let previousFocusX = parseFloat(focusXInput.value || '50');
        let previousFocusY = parseFloat(focusYInput.value || '50');
        let previousRemoveChecked = removeCheckbox.checked;

        const clamp = (value, min, max) => Math.min(max, Math.max(min, value));

        const applyFocus = (x, y) => {
            const clampedX = clamp(x, 0, 100);
            const clampedY = clamp(y, 0, 100);

            focusXInput.value = clampedX.toFixed(2);
            focusYInput.value = clampedY.toFixed(2);
            preview.style.objectPosition = `${clampedX}% ${clampedY}%`;
            avatarEditorImage.style.objectPosition = `${clampedX}% ${clampedY}%`;
        };

        const focusFromPointer = (event) => {
            const rect = avatarEditorCircle.getBoundingClientRect();
            const pointX = event.clientX ?? (event.touches && event.touches[0] ? event.touches[0].clientX : null);
            const pointY = event.clientY ?? (event.touches && event.touches[0] ? event.touches[0].clientY : null);

            if (pointX === null || pointY === null) {
                return;
            }

            const relativeX = ((pointX - rect.left) / rect.width) * 100;
            const relativeY = ((pointY - rect.top) / rect.height) * 100;
            applyFocus(relativeX, relativeY);
        };

        const showPreviewImage = () => {
            imageLayer.classList.remove('profile-page__avatar-layer--hidden');
            placeholderLayer.classList.add('profile-page__avatar-layer--hidden');
            avatarEditorImage.classList.remove('avatar-editor__image--hidden');
            avatarEditorPlaceholder.classList.add('avatar-editor__placeholder--hidden');
            removeAction.disabled = false;
        };

        const showPlaceholder = () => {
            imageLayer.classList.add('profile-page__avatar-layer--hidden');
            placeholderLayer.classList.remove('profile-page__avatar-layer--hidden');
            avatarEditorImage.classList.add('avatar-editor__image--hidden');
            avatarEditorPlaceholder.classList.remove('avatar-editor__placeholder--hidden');
            removeAction.disabled = true;
        };

        const setRemovePhoto = (value) => {
            removeCheckbox.checked = value;
        };

        const setMenuOpen = (open) => {
            avatarMenu.hidden = !open;
            avatarActionsToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        };

        const openEditor = () => {
            avatarEditor.hidden = false;
            document.body.classList.add('is-avatar-editor-open');
        };

        const closeEditor = () => {
            avatarEditor.hidden = true;
            document.body.classList.remove('is-avatar-editor-open');
            isDragging = false;
            activePointerId = null;
        };

        const abortPendingSelection = () => {
            if (!hasPendingSelection) {
                closeEditor();
                return;
            }

            preview.src = previousPreviewSrc;
            avatarEditorImage.src = previousPreviewSrc;
            applyFocus(previousFocusX, previousFocusY);
            removeCheckbox.checked = previousRemoveChecked;

            if (previousPreviewSrc) {
                showPreviewImage();
            } else {
                showPlaceholder();
            }

            input.value = '';
            hasPendingSelection = false;
            closeEditor();
        };

        applyFocus(parseFloat(focusXInput.value || '50'), parseFloat(focusYInput.value || '50'));

        if (preview.getAttribute('src')) {
            avatarEditorImage.src = preview.getAttribute('src');
            showPreviewImage();
            setRemovePhoto(false);
        } else {
            showPlaceholder();
        }

        avatarActionsToggle.addEventListener('click', (event) => {
            event.preventDefault();
            setMenuOpen(avatarMenu.hidden);
        });

        uploadAction.addEventListener('click', () => {
            setMenuOpen(false);
            input.click();
        });

        removeAction.addEventListener('click', () => {
            if (removeAction.disabled) {
                return;
            }

            setMenuOpen(false);
            closeEditor();
            preview.src = '';
            avatarEditorImage.src = '';
            showPlaceholder();
            applyFocus(50, 50);
            setRemovePhoto(true);
            input.value = '';
            hasPendingSelection = false;
            submitProfileForm('Profile photo removed.');
        });

        input.addEventListener('change', () => {
            const [file] = input.files || [];

            if (!file) {
                return;
            }

            const reader = new FileReader();
            reader.onload = (loadEvent) => {
                previousPreviewSrc = preview.getAttribute('src') || '';
                previousFocusX = parseFloat(focusXInput.value || '50');
                previousFocusY = parseFloat(focusYInput.value || '50');
                previousRemoveChecked = removeCheckbox.checked;

                preview.src = loadEvent.target?.result || '';
                avatarEditorImage.src = preview.src;
                showPreviewImage();
                applyFocus(50, 50);
                setRemovePhoto(false);
                hasPendingSelection = true;
                openEditor();
            };

            reader.readAsDataURL(file);
        });

        avatarEditorCircle.addEventListener('pointerdown', (event) => {
            if (!avatarEditorImage.getAttribute('src')) {
                return;
            }

            event.preventDefault();
            isDragging = true;
            activePointerId = event.pointerId;
            avatarEditorCircle.classList.add('is-dragging');
            avatarEditorCircle.setPointerCapture(event.pointerId);
            focusFromPointer(event);
        });

        avatarEditorCircle.addEventListener('pointermove', (event) => {
            if (!isDragging || activePointerId !== event.pointerId) {
                return;
            }

            event.preventDefault();
            focusFromPointer(event);
        });

        avatarEditorCircle.addEventListener('pointerup', (event) => {
            if (activePointerId !== event.pointerId) {
                return;
            }

            isDragging = false;
            activePointerId = null;
            avatarEditorCircle.releasePointerCapture(event.pointerId);
            avatarEditorCircle.classList.remove('is-dragging');
        });

        avatarEditorCircle.addEventListener('pointercancel', (event) => {
            if (activePointerId !== event.pointerId) {
                return;
            }

            isDragging = false;
            activePointerId = null;
            avatarEditorCircle.classList.remove('is-dragging');
        });

        avatarEditorDone.addEventListener('click', () => {
            hasPendingSelection = false;
            closeEditor();
            submitProfileForm('Profile photo updated.');
        });
        avatarEditorClose.addEventListener('click', abortPendingSelection);

        avatarEditor.addEventListener('click', (event) => {
            if (event.target === avatarEditor) {
                abortPendingSelection();
            }
        });

        window.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && !avatarEditor.hidden) {
                abortPendingSelection();
            }
        });

        window.addEventListener('click', (event) => {
            if (!avatarMenu.hidden && !event.target.closest('.profile-page__avatar-actions')) {
                setMenuOpen(false);
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
    })();
</script>
@endpush
