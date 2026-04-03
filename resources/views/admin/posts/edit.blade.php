@extends('layouts.admin')

@section('content')
    <main class="admin-posts">
        <div class="admin-posts__header">
            <div>
                <h1 class="admin-posts__title">Edit Post</h1>
                <p class="admin-posts__subtitle">Update your existing post details.</p>
            </div>
            <div class="admin-actions">
                <a href="{{ route('admin.posts.create') }}" class="admin-posts__logout admin-posts__logout--link">Write post</a>
                <a href="{{ route('admin.admins.create') }}" class="admin-posts__logout admin-posts__logout--link">Add admin</a>
            </div>
        </div>

        @if (session('status'))
            <div class="admin-flash admin-flash--success" data-flash>
                <span>{{ session('status') }}</span>
                <button type="button" class="admin-flash__close" data-flash-close aria-label="Dismiss notification">&times;</button>
            </div>
        @endif

        @if ($errors->any())
            <div class="admin-flash admin-flash--error" data-flash>
                <span>{{ $errors->first() }}</span>
                <button type="button" class="admin-flash__close" data-flash-close aria-label="Dismiss notification">&times;</button>
            </div>
        @endif

        <section class="admin-layout">
            <div class="admin-editor-stack">
                <form method="POST" action="{{ route('admin.posts.update', $post) }}" class="admin-editor" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <label for="title" class="admin-form__label">Title</label>
                    <input id="title" name="title" type="text" value="{{ old('title', $post->title) }}" required class="admin-form__input">

                    <label for="excerpt" class="admin-form__label">Excerpt</label>
                    <textarea id="excerpt" name="excerpt" rows="3" class="admin-form__textarea">{{ old('excerpt', $post->excerpt) }}</textarea>

                    <label class="admin-form__label">Post Content</label>
                    <p class="admin-posts__subtitle" style="margin: -0.35rem 0 0.5rem;">
                        Word-style editing: choose block style (Normal, H2, H3, H4) and type your content.
                    </p>
                    <input id="content_json" name="content" type="hidden" value="{{ old('content', $post->content) }}">
                    <div class="content-builder" data-content-builder data-initial='@json(old("content", $post->content))'>
                        <div class="content-builder__rows" data-content-rows></div>
                        <div class="content-builder__actions">
                            <button type="button" class="admin-form__button" data-add-block="text">Add Text Block</button>
                            <button type="button" class="admin-form__button" data-add-block="tiktok">Add TikTok Embed</button>
                        </div>
                    </div>

                    <label for="cover_image_upload" class="admin-form__label">Cover Image (optional)</label>
                    @if ($post->cover_image_url)
                        <img src="{{ $post->cover_image_url }}" alt="Current cover image" style="max-width: 220px; border-radius: 10px; margin-bottom: 0.55rem;">
                    @endif
                    <input id="cover_image_upload" name="cover_image_upload" type="file" accept="image/*" class="admin-form__input">
                    <p class="admin-posts__subtitle" style="margin: -0.35rem 0 0.5rem;">Upload a new image to replace the current one (max 4MB).</p>

                    <label for="category" class="admin-form__label">Category (optional)</label>
                    <select id="category" name="category" class="admin-form__input">
                        <option value="">— None —</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ old('category', $post->category) === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>

                    <label for="published_at" class="admin-form__label">Publish Date/Time (optional)</label>
                    <input id="published_at" name="published_at" type="datetime-local" value="{{ old('published_at', optional($post->published_at)->format('Y-m-d\TH:i')) }}" class="admin-form__input">
                    <p class="admin-posts__subtitle" style="margin: -0.35rem 0 0.5rem;">Leave blank to publish immediately.</p>

                    <button type="submit" class="admin-form__button">Save Changes</button>
                </form>

                <form method="POST" action="{{ route('admin.posts.destroy', $post) }}" onsubmit="return confirm('Delete this post? This cannot be undone.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="admin-form__button admin-form__button--delete">Delete Post</button>
                </form>

                <div class="admin-products">
                    <h2 class="admin-products__title">Products / Resources</h2>

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
                                    <form method="POST" action="{{ route('admin.resources.destroy-inline', $resource) }}" onsubmit="return confirm('Unlink this product?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="admin-resource-card__delete" title="Unlink">&times;</button>
                                    </form>
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
                        <form method="POST" action="{{ route('admin.posts.resources.store', $post) }}" class="admin-products__form">
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
                </div>
            </div>


        </section>
    </main>
@endsection

@push('scripts')
<script>
(() => {
    const builder = document.querySelector('[data-content-builder]');
    if (!builder) return;

    const rowsEl = builder.querySelector('[data-content-rows]');
    const contentInput = document.getElementById('content_json');
    const form = contentInput?.closest('form');

    const parseBlocks = (raw) => {
        if (!raw) return [];
        try {
            const payload = JSON.parse(raw);
            if (payload && Array.isArray(payload.blocks)) {
                return payload.blocks;
            }
        } catch (error) {
            return [{ type: 'paragraph', text: raw }];
        }
        return [];
    };

    const defaultBlocks = [{ type: 'paragraph', text: '' }];
    let blocks = parseBlocks(builder.dataset.initial || contentInput.value);
    if (!blocks.length) {
        blocks = defaultBlocks;
    }

    const styleValueForBlock = (block) => {
        if (block.type === 'heading') {
            const level = [2, 3, 4].includes(Number(block.level)) ? Number(block.level) : 2;
            return `heading-${level}`;
        }
        if (block.type === 'tiktok') return 'tiktok';
        return 'paragraph';
    };

    const blockFromStyle = (styleValue, previousBlock) => {
        const prevText = previousBlock?.text || '';
        const prevUrl = previousBlock?.url || '';

        if (styleValue === 'tiktok') {
            return { type: 'tiktok', url: prevUrl };
        }

        if (styleValue.startsWith('heading-')) {
            return {
                type: 'heading',
                level: Number(styleValue.split('-')[1]) || 2,
                text: prevText,
            };
        }

        return { type: 'paragraph', text: prevText };
    };

    const escapeHtml = (value) => String(value)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#39;');

    const render = () => {
        rowsEl.innerHTML = '';

        blocks.forEach((block, index) => {
            const row = document.createElement('div');
            row.className = 'content-builder__row';

            const styleValue = styleValueForBlock(block);
            const isTikTok = styleValue === 'tiktok';
            const value = isTikTok ? (block.url || '') : (block.text || '');

            row.innerHTML = `
                <div class="content-builder__row-head">
                    <div class="content-builder__row-title">
                        <strong>${index + 1}. Block</strong>
                        <select class="admin-form__input content-builder__style-select" data-style>
                            <option value="paragraph" ${styleValue === 'paragraph' ? 'selected' : ''}>Normal text</option>
                            <option value="heading-2" ${styleValue === 'heading-2' ? 'selected' : ''}>Heading 2</option>
                            <option value="heading-3" ${styleValue === 'heading-3' ? 'selected' : ''}>Heading 3</option>
                            <option value="heading-4" ${styleValue === 'heading-4' ? 'selected' : ''}>Heading 4</option>
                            <option value="tiktok" ${styleValue === 'tiktok' ? 'selected' : ''}>TikTok embed</option>
                        </select>
                    </div>
                    <div class="content-builder__row-controls">
                        <button type="button" class="admin-posts__logout admin-posts__logout--link" data-move="up">Up</button>
                        <button type="button" class="admin-posts__logout admin-posts__logout--link" data-move="down">Down</button>
                        <button type="button" class="admin-posts__logout admin-posts__logout--link" data-remove>Remove</button>
                    </div>
                </div>
                ${isTikTok ? `
                    <label class="admin-form__label" style="margin-top: 0.2rem;">TikTok URL</label>
                    <input type="text" class="admin-form__input" data-value value="${escapeHtml(value)}" placeholder="https://www.tiktok.com/@user/video/1234567890123456789">
                ` : `
                    <label class="admin-form__label" style="margin-top: 0.2rem;">Content</label>
                    <textarea rows="5" class="admin-form__textarea" data-value>${escapeHtml(value)}</textarea>
                `}
            `;

            const styleInput = row.querySelector('[data-style]');
            styleInput.addEventListener('change', () => {
                blocks[index] = blockFromStyle(styleInput.value, blocks[index]);
                render();
            });

            row.querySelector('[data-remove]').addEventListener('click', () => {
                blocks.splice(index, 1);
                if (!blocks.length) blocks.push({ type: 'paragraph', text: '' });
                render();
            });

            row.querySelector('[data-move="up"]').addEventListener('click', () => {
                if (index === 0) return;
                [blocks[index - 1], blocks[index]] = [blocks[index], blocks[index - 1]];
                render();
            });

            row.querySelector('[data-move="down"]').addEventListener('click', () => {
                if (index >= blocks.length - 1) return;
                [blocks[index], blocks[index + 1]] = [blocks[index + 1], blocks[index]];
                render();
            });

            const valueInput = row.querySelector('[data-value]');
            if (valueInput) {
                valueInput.addEventListener('input', () => {
                    if (styleInput.value === 'tiktok') {
                        blocks[index].url = valueInput.value;
                    } else {
                        blocks[index].text = valueInput.value;
                    }
                });
            }

            rowsEl.appendChild(row);
        });
    };

    builder.querySelectorAll('[data-add-block]').forEach((btn) => {
        btn.addEventListener('click', () => {
            const type = btn.getAttribute('data-add-block');
            if (type === 'tiktok') {
                blocks.push({ type: 'tiktok', url: '' });
            } else {
                blocks.push({ type: 'paragraph', text: '' });
            }
            render();
        });
    });

    form?.addEventListener('submit', () => {
        contentInput.value = JSON.stringify({ version: 1, blocks });
    });

    render();
})();
</script>
@endpush
