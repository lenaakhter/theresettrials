<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostManagementController extends Controller
{
    public function create()
    {
        $recentPosts = Post::query()->latest('created_at')->take(6)->get();
        $subscriberCount = NewsletterSubscriber::query()->count();

        return view('admin.posts.create', compact('recentPosts', 'subscriberCount'));
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules());

        $slug = $this->generateUniqueSlug($data['title']);

        Post::query()->create([
            'title' => $data['title'],
            'slug' => $slug,
            'excerpt' => $data['excerpt'] ?? null,
            'content' => $data['content'],
            'cover_image' => $data['cover_image'] ?? null,
            'published_at' => $this->resolvePublishedAt($request, $data),
        ]);

        return redirect()->route('admin.posts.create')->with('status', 'Post published successfully.');
    }

    public function edit(Post $post)
    {
        $recentPosts = Post::query()->latest('created_at')->take(6)->get();

        return view('admin.posts.edit', compact('post', 'recentPosts'));
    }

    public function update(Request $request, Post $post)
    {
        $data = $request->validate($this->rules());

        $post->update([
            'title' => $data['title'],
            'excerpt' => $data['excerpt'] ?? null,
            'content' => $data['content'],
            'cover_image' => $data['cover_image'] ?? null,
            'published_at' => $this->resolvePublishedAt($request, $data),
        ]);

        return redirect()->route('admin.posts.edit', $post)->with('status', 'Post updated successfully.');
    }

    private function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'content' => ['required', 'string'],
            'cover_image' => ['nullable', 'string', 'max:255'],
            'published_at' => ['nullable', 'date'],
            'publish_now' => ['nullable', 'boolean'],
        ];
    }

    private function resolvePublishedAt(Request $request, array $data)
    {
        if ($request->boolean('publish_now') && empty($data['published_at'])) {
            return now();
        }

        return $data['published_at'] ?? null;
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
