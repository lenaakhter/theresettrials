@php
    $currentType = old('linkable_type', $resource
        ? ($resource->linkable_type === \App\Models\Post::class ? 'post' : ($resource->linkable_type === \App\Models\Experiment::class ? 'experiment' : ''))
        : '');
    $currentId = old('linkable_id', $resource?->linkable_id);
@endphp

<label for="name" class="admin-form__label">Product Name</label>
<input id="name" name="name" type="text" required class="admin-form__input"
    value="{{ old('name', $resource?->name) }}" placeholder="e.g. Inositol by Wholesome Story">

<label for="image_upload" class="admin-form__label">Product Image (upload or paste URL)</label>
@if($resource?->image_url)
    <img src="{{ $resource->image_url }}" alt="Current image" style="max-height:80px; margin-bottom:0.5rem; border-radius:8px; display:block;">
@endif
<input id="image_upload" name="image_upload" type="file" accept="image/*" class="admin-form__input">
<p class="admin-posts__subtitle" style="margin: -0.3rem 0 0.4rem;">Or paste an image URL instead:</p>
<input id="image_url" name="image_url" type="text" class="admin-form__input"
    value="{{ old('image_url', $resource?->image_url) }}" placeholder="https://... (leave blank if uploading)">

<label for="product_url" class="admin-form__label">Product Link (where to buy)</label>
<input id="product_url" name="product_url" type="url" required class="admin-form__input"
    value="{{ old('product_url', $resource?->product_url) }}" placeholder="https://amazon.com/...">

<label for="linkable_type" class="admin-form__label">Link to Blog or Experiment (optional)</label>
<select id="linkable_type" name="linkable_type" class="admin-form__input" onchange="updateLinkableOptions(this.value)">
    <option value="">— Not linked —</option>
    <option value="post" {{ $currentType === 'post' ? 'selected' : '' }}>Blog Post</option>
    <option value="experiment" {{ $currentType === 'experiment' ? 'selected' : '' }}>Experiment</option>
</select>

<div id="linkable-post" style="{{ $currentType === 'post' ? '' : 'display:none;' }} margin-top:0.5rem;">
    <label for="linkable_id_post" class="admin-form__label">Select Blog Post</label>
    <select name="linkable_id" id="linkable_id_post" class="admin-form__input">
        <option value="">— Choose post —</option>
        @foreach ($posts as $post)
            <option value="{{ $post->id }}" {{ ($currentType === 'post' && $currentId == $post->id) ? 'selected' : '' }}>
                {{ $post->title }}
            </option>
        @endforeach
    </select>
</div>

<div id="linkable-experiment" style="{{ $currentType === 'experiment' ? '' : 'display:none;' }} margin-top:0.5rem;">
    <label for="linkable_id_experiment" class="admin-form__label">Select Experiment</label>
    <select name="linkable_id" id="linkable_id_experiment" class="admin-form__input">
        <option value="">— Choose experiment —</option>
        @foreach ($experiments as $experiment)
            <option value="{{ $experiment->id }}" {{ ($currentType === 'experiment' && $currentId == $experiment->id) ? 'selected' : '' }}>
                {{ $experiment->title }}
            </option>
        @endforeach
    </select>
</div>

@push('scripts')
<script>
function updateLinkableOptions(type) {
    document.getElementById('linkable-post').style.display = type === 'post' ? '' : 'none';
    document.getElementById('linkable-experiment').style.display = type === 'experiment' ? '' : 'none';
}
</script>
@endpush
