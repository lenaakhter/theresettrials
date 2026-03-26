@extends('layouts.admin')

@section('content')
<main class="admin-posts">
    <div class="admin-posts__header">
        <div>
            <h1 class="admin-posts__title">Create New Experiment</h1>
            <p class="admin-posts__subtitle">Add a new current experiment for the public tracker.</p>
        </div>
    </div>

    @if($errors->any())
        <div class="admin-flash admin-flash--error" data-flash>
            <span>{{ $errors->first() }}</span>
            <button type="button" class="admin-flash__close" data-flash-close aria-label="Dismiss notification">&times;</button>
        </div>
    @endif

    <section class="admin-layout admin-layout--single">
        <form action="{{ route('admin.experiments.store') }}" method="POST" class="admin-editor">
            @csrf

            <label for="title" class="admin-form__label">Title</label>
            <input type="text" id="title" name="title" value="{{ old('title') }}" required class="admin-form__input">

            <label for="description" class="admin-form__label">Description</label>
            <textarea id="description" name="description" rows="6" required class="admin-form__textarea">{{ old('description') }}</textarea>

            <label for="category" class="admin-form__label">Category</label>
            <input type="text" id="category" name="category" value="{{ old('category') }}" class="admin-form__input">

            <label for="status" class="admin-form__label">Status</label>
            <select id="status" name="status" required class="admin-form__input">
                <option value="">Select status</option>
                <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="paused" {{ old('status') === 'paused' ? 'selected' : '' }}>Paused</option>
                <option value="completed" {{ old('status') === 'completed' ? 'selected' : '' }}>Completed</option>
            </select>

            <div class="admin-layout admin-layout--dates">
                <div>
                    <label for="start_date" class="admin-form__label">Start Date</label>
                    <input type="date" id="start_date" name="start_date" value="{{ old('start_date') }}" required class="admin-form__input">
                </div>
                <div>
                    <label for="end_date" class="admin-form__label">End Date</label>
                    <input type="date" id="end_date" name="end_date" value="{{ old('end_date') }}" class="admin-form__input">
                </div>
            </div>

            <div class="admin-actions">
                <button type="submit" class="admin-form__button">Create Experiment</button>
                <a href="{{ route('admin.experiments.index') }}" class="admin-posts__logout admin-posts__logout--link">Cancel</a>
            </div>
        </form>
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
</script>
@endsection
