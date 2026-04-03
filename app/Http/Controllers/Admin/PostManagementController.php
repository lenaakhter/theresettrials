<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use App\Models\Post;
use App\Models\PostCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostManagementController extends Controller
{
    public function index()
    {
        $posts = Post::query()->latest('created_at')->get();

        return view('admin.posts.index', compact('posts'));
    }

    public function create()
    {
        $subscriberCount = NewsletterSubscriber::query()->count();
        $categories = PostCategory::orderBy('name')->pluck('name');

        return view('admin.posts.create', compact('subscriberCount', 'categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules());
        $normalizedContent = $this->normalizeContentPayload($data['content']);

        $slug = $this->generateUniqueSlug($data['title']);
        $coverImage = $this->saveCoverImageUpload($request);

        Post::query()->create([
            'title' => $data['title'],
            'slug' => $slug,
            'excerpt' => $data['excerpt'] ?? null,
            'content' => $normalizedContent,
            'cover_image' => $coverImage,
            'category' => $data['category'] ?? null,
            'published_at' => $this->resolvePublishedAt($request, $data),
        ]);

        return redirect()->route('admin.posts.create')->with('status', 'Post published successfully.');
    }

    public function edit(Post $post)
    {
        $resources    = $post->resources()->get();
        $allResources = \App\Models\Resource::orderBy('name')->get(['id', 'name']);
        $categories   = PostCategory::orderBy('name')->pluck('name');

        return view('admin.posts.edit', compact('post', 'resources', 'allResources', 'categories'));
    }

    public function update(Request $request, Post $post)
    {
        $data = $request->validate($this->rules());
        $normalizedContent = $this->normalizeContentPayload($data['content']);
        $coverImage = $this->saveCoverImageUpload($request, $post->cover_image);

        $post->update([
            'title' => $data['title'],
            'excerpt' => $data['excerpt'] ?? null,
            'content' => $normalizedContent,
            'cover_image' => $coverImage,
            'category' => $data['category'] ?? null,
            'published_at' => $this->resolvePublishedAt($request, $data),
        ]);

        return redirect()->route('admin.posts.edit', $post)->with('status', 'Post updated successfully.');
    }

    public function destroy(Post $post)
    {
        $post->delete();

        return redirect()->route('admin.posts.create')->with('status', 'Post deleted successfully.');
    }

    private function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'content' => ['required', 'string'],
            'cover_image_upload' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:4096'],
            'category' => ['nullable', 'string', 'max:100'],
            'published_at' => ['nullable', 'date'],
        ];
    }

    private function saveCoverImageUpload(Request $request, ?string $currentPath = null): ?string
    {
        if (! $request->hasFile('cover_image_upload')) {
            return $currentPath;
        }

        $file = $request->file('cover_image_upload');
        $directory = $this->postUploadDirectory();

        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $filename = Str::uuid()->toString().'.'.$file->getClientOriginalExtension();
        $file->move($directory, $filename);

        if ($currentPath && str_starts_with($currentPath, 'images/uploads/posts/')) {
            $this->deleteCoverImageFromKnownPublicRoots($currentPath);
        }

        return 'images/uploads/posts/'.$filename;
    }

    private function postUploadDirectory(): string
    {
        $siteGroundPublicHtml = base_path('public_html');

        if (is_dir($siteGroundPublicHtml)) {
            return $siteGroundPublicHtml.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.'posts';
        }

        return public_path('images/uploads/posts');
    }

    private function deleteCoverImageFromKnownPublicRoots(string $relativePath): void
    {
        $trimmedPath = ltrim($relativePath, '/\\');
        $candidatePaths = [
            public_path($trimmedPath),
        ];

        $siteGroundPublicHtml = base_path('public_html');
        if (is_dir($siteGroundPublicHtml)) {
            $candidatePaths[] = $siteGroundPublicHtml.DIRECTORY_SEPARATOR.str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $trimmedPath);
        }

        foreach (array_unique($candidatePaths) as $path) {
            if (is_file($path)) {
                @unlink($path);
            }
        }
    }

    private function resolvePublishedAt(Request $request, array $data)
    {
        return $data['published_at'] ?? now();
    }

    private function normalizeContentPayload(string $raw): string
    {
        $decoded = json_decode($raw, true);

        if (! is_array($decoded) || ! isset($decoded['blocks']) || ! is_array($decoded['blocks'])) {
            $text = trim($raw);

            return json_encode([
                'version' => 1,
                'blocks' => $text === ''
                    ? []
                    : [[
                        'type' => 'paragraph',
                        'text' => $text,
                    ]],
            ], JSON_UNESCAPED_SLASHES);
        }

        $normalizedBlocks = [];

        foreach ($decoded['blocks'] as $block) {
            if (! is_array($block) || ! isset($block['type'])) {
                continue;
            }

            $type = (string) $block['type'];

            if ($type === 'paragraph') {
                $text = trim((string) ($block['text'] ?? ''));
                if ($text !== '') {
                    $normalizedBlocks[] = ['type' => 'paragraph', 'text' => $text];
                }
                continue;
            }

            if ($type === 'heading') {
                $text = trim((string) ($block['text'] ?? ''));
                if ($text === '') {
                    continue;
                }
                $level = (int) ($block['level'] ?? 2);
                if (! in_array($level, [2, 3, 4], true)) {
                    $level = 2;
                }
                $normalizedBlocks[] = [
                    'type' => 'heading',
                    'text' => $text,
                    'level' => $level,
                ];
                continue;
            }

            if ($type === 'tiktok') {
                $url = trim((string) ($block['url'] ?? ''));
                if ($url !== '') {
                    $normalizedBlocks[] = [
                        'type' => 'tiktok',
                        'url' => $url,
                    ];
                }
            }
        }

        return json_encode([
            'version' => 1,
            'blocks' => $normalizedBlocks,
        ], JSON_UNESCAPED_SLASHES);
    }

    private function generateUniqueSlug(string $title): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $counter = 2;

        while (Post::query()->where('slug', $slug)->exists()) {
            $slug = $base.'-'.$counter;
            $counter++;
        }

        return $slug;
    }
}
