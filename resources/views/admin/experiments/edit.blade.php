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

    <section class="admin-products">
        <h2 class="admin-products__title">Products / Resources</h2>

        @if (session('status'))
            <div class="admin-flash admin-flash--success" data-flash>
                <span>{{ session('status') }}</span>
                <button type="button" class="admin-flash__close" data-flash-close aria-label="Dismiss notification">&times;</button>
            </div>
        @endif

        @if ($resources->isNotEmpty())
            <div class="admin-resource-list">
                @foreach ($resources as $resource)
                    <div class="admin-resource-card">
                        @if ($resource->image_url)
                            <img src="{{ $resource->image_url }}" alt="{{ $resource->name }}" class="admin-resource-card__img">
                        @endif
                        <div class="admin-resource-card__body">
                            <p class="admin-resource-card__name">{{ $resource->name }}</p>
                            <a href="{{ $resource->product_url }}" target="_blank" rel="noopener noreferrer" class="admin-resource-card__link">View product</a>
                        </div>
                        <div style="display:flex; align-items:center; gap:0.5rem;">
                            <form method="POST" action="{{ route('admin.resources.destroy-inline', $resource) }}" onsubmit="return confirm('Unlink this product?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="admin-resource-card__delete" title="Unlink">&times;</button>
                            </form>
                            <form method="POST" action="{{ route('admin.resources.destroy', $resource) }}" onsubmit="return confirm('Delete this resource everywhere? This cannot be undone.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="admin-btn admin-btn--sm admin-btn--danger">Delete</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="admin-posts__subtitle">No products linked yet.</p>
        @endif

        <h3 class="admin-products__subtitle">Link an existing product</h3>
        @if ($allResources->isEmpty())
            <p class="admin-posts__subtitle">No products in the system yet. <a href="{{ route('admin.resources.create') }}" style="color:#c56a7f;">Add one here.</a></p>
        @else
            <form method="POST" action="{{ route('admin.experiments.resources.store', $experiment) }}" class="admin-products__form">
                @csrf
                <select name="resource_id" required class="admin-form__input">
                    <option value="">— Select a product —</option>
                    @foreach ($allResources as $r)
                        <option value="{{ $r->id }}">{{ $r->name }}</option>
                    @endforeach
                </select>
                <button type="submit" class="admin-form__button">Link Product</button>
            </form>
            <p class="admin-posts__subtitle" style="margin-top:0.5rem;">Need a new product? <a href="{{ route('admin.resources.create') }}" style="color:#c56a7f;">Create one in Resources.</a></p>
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
</script>
@endsection
