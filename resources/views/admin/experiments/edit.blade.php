@extends('layouts.admin')

@section('content')
<main class="admin-posts">
    <div class="admin-posts__header">
        <div>
            <h1 class="admin-posts__title">Edit Experiment</h1>
            <p class="admin-posts__subtitle">Update the current experiment details and visibility.</p>
        </div>
    </div>

    @if($errors->any())
        <div class="admin-flash admin-flash--error" data-flash>
            <span>{{ $errors->first() }}</span>
            <button type="button" class="admin-flash__close" data-flash-close aria-label="Dismiss notification">&times;</button>
        </div>
    @endif

    <section class="admin-layout admin-layout--single">
        <form action="{{ route('admin.experiments.update', $experiment) }}" method="POST" class="admin-editor">
            @csrf
            @method('PUT')

            <label for="title" class="admin-form__label">Title</label>
            <input type="text" id="title" name="title" value="{{ old('title', $experiment->title) }}" required class="admin-form__input">

            <label for="description" class="admin-form__label">Description</label>
            <textarea id="description" name="description" rows="6" required class="admin-form__textarea">{{ old('description', $experiment->description) }}</textarea>

            <label for="category" class="admin-form__label">Category</label>
            <input type="text" id="category" name="category" value="{{ old('category', $experiment->category) }}" class="admin-form__input">

            <label for="status" class="admin-form__label">Status</label>
            <select id="status" name="status" required class="admin-form__input">
                <option value="active" {{ old('status', $experiment->status) === 'active' ? 'selected' : '' }}>Active</option>
                <option value="paused" {{ old('status', $experiment->status) === 'paused' ? 'selected' : '' }}>Paused</option>
                <option value="completed" {{ old('status', $experiment->status) === 'completed' ? 'selected' : '' }}>Completed</option>
            </select>

            <div class="admin-layout admin-layout--dates">
                <div>
                    <label for="start_date" class="admin-form__label">Start Date</label>
                    <input type="date" id="start_date" name="start_date" value="{{ old('start_date', $experiment->start_date->format('Y-m-d')) }}" required class="admin-form__input">
                </div>
                <div>
                    <label for="end_date" class="admin-form__label">End Date</label>
                    <input type="date" id="end_date" name="end_date" value="{{ old('end_date', $experiment->end_date?->format('Y-m-d')) }}" class="admin-form__input">
                </div>
            </div>

            <div class="admin-actions">
                <button type="submit" class="admin-form__button">Update Experiment</button>
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
