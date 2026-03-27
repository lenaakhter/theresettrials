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
    public function create()
    {
        $recentPosts = Post::query()->latest('created_at')->take(6)->get();
        $subscriberCount = NewsletterSubscriber::query()->count();
        $categories = PostCategory::orderBy('name')->pluck('name');

        return view('admin.posts.create', compact('recentPosts', 'subscriberCount', 'categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules());

        $slug = $this->generateUniqueSlug($data['title']);
        $coverImage = $this->saveCoverImageUpload($request);

        Post::query()->create([
            'title' => $data['title'],
            'slug' => $slug,
            'excerpt' => $data['excerpt'] ?? null,
            'content' => $data['content'],
            'cover_image' => $coverImage,
            'category' => $data['category'] ?? null,
            'published_at' => $this->resolvePublishedAt($request, $data),
        ]);

        return redirect()->route('admin.posts.create')->with('status', 'Post published successfully.');
    }

    public function edit(Post $post)
    {
        $recentPosts  = Post::query()->latest('created_at')->take(6)->get();
        $resources    = $post->resources()->get();
        $allResources = \App\Models\Resource::orderBy('name')->get(['id', 'name']);
        $categories   = PostCategory::orderBy('name')->pluck('name');

        return view('admin.posts.edit', compact('post', 'recentPosts', 'resources', 'allResources', 'categories'));
    }

    public function update(Request $request, Post $post)
    {
        $data = $request->validate($this->rules());
        $coverImage = $this->saveCoverImageUpload($request, $post->cover_image);

        $post->update([
            'title' => $data['title'],
            'excerpt' => $data['excerpt'] ?? null,
            'content' => $data['content'],
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
        $directory = public_path('images/uploads/posts');

        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $filename = Str::uuid()->toString().'.'.$file->getClientOriginalExtension();
        $file->move($directory, $filename);

        if ($currentPath && str_starts_with($currentPath, 'images/uploads/posts/')) {
            $oldPath = public_path($currentPath);
            if (is_file($oldPath)) {
                @unlink($oldPath);
            }
        }

        return 'images/uploads/posts/'.$filename;
    }

    private function resolvePublishedAt(Request $request, array $data)
    {
        return $data['published_at'] ?? now();
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
