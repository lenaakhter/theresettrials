<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Experiment;
use App\Models\Post;
use App\Models\Resource;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ResourceController extends Controller
{
    public function index()
    {
        $resources = Resource::with('linkable')->latest()->get();
        return view('admin.resources.index', compact('resources'));
    }

    public function create()
    {
        $posts = Post::query()->published()->latest('published_at')->get(['id', 'title', 'slug']);
        $experiments = Experiment::orderBy('title')->get(['id', 'title']);
        return view('admin.resources.create', compact('posts', 'experiments'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'image_url'     => ['nullable', 'string', 'max:500'],
            'image_upload'  => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:4096'],
            'product_url'   => ['required', 'url', 'max:500'],
            'linkable_type' => ['nullable', 'in:post,experiment'],
            'linkable_id'   => ['nullable', 'integer'],
        ]);

        Resource::create([
            'name'          => $data['name'],
            'image_url'     => $this->saveResourceImage($request, $data['image_url'] ?? null),
            'product_url'   => $data['product_url'],
            'linkable_type' => $this->resolveLinkableType($data['linkable_type'] ?? null),
            'linkable_id'   => $data['linkable_id'] ?? null,
        ]);

        return redirect()->route('admin.resources.index')->with('status', 'Resource added successfully.');
    }

    public function edit(Resource $resource)
    {
        $posts = Post::query()->published()->latest('published_at')->get(['id', 'title', 'slug']);
        $experiments = Experiment::orderBy('title')->get(['id', 'title']);
        return view('admin.resources.edit', compact('resource', 'posts', 'experiments'));
    }

    public function update(Request $request, Resource $resource)
    {
        $data = $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'image_url'     => ['nullable', 'string', 'max:500'],
            'image_upload'  => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:4096'],
            'product_url'   => ['required', 'url', 'max:500'],
            'linkable_type' => ['nullable', 'in:post,experiment'],
            'linkable_id'   => ['nullable', 'integer'],
        ]);

        $resource->update([
            'name'          => $data['name'],
            'image_url'     => $this->saveResourceImage($request, $data['image_url'] ?? null, $resource->getRawImagePath()),
            'product_url'   => $data['product_url'],
            'linkable_type' => $this->resolveLinkableType($data['linkable_type'] ?? null),
            'linkable_id'   => $data['linkable_id'] ?? null,
        ]);

        return redirect()->route('admin.resources.index')->with('status', 'Resource updated successfully.');
    }

    public function destroy(Resource $resource)
    {
        $this->deleteResourceImageFromKnownPublicRoots($resource->getRawImagePath());
        $resource->delete();
        return redirect()->route('admin.resources.index')->with('status', 'Resource deleted.');
    }

    public function storeForPost(Request $request, Post $post)
    {
        $request->validate([
            'resource_id' => ['required', 'integer', 'exists:resources,id'],
        ]);

        Resource::where('id', $request->resource_id)->update([
            'linkable_type' => Post::class,
            'linkable_id'   => $post->id,
        ]);

        return redirect()->route('admin.posts.edit', $post)->with('status', 'Product linked.');
    }

    public function storeForExperiment(Request $request, Experiment $experiment)
    {
        $request->validate([
            'resource_id' => ['required', 'integer', 'exists:resources,id'],
        ]);

        Resource::where('id', $request->resource_id)->update([
            'linkable_type' => Experiment::class,
            'linkable_id'   => $experiment->id,
        ]);

        return redirect()->route('admin.experiments.edit', $experiment)->with('status', 'Product linked.');
    }

    public function destroyInline(Request $request, Resource $resource)
    {
        $linkable = $resource->linkable;

        $resource->update(['linkable_type' => null, 'linkable_id' => null]);

        if ($linkable instanceof Post) {
            return redirect()->route('admin.posts.edit', $linkable)->with('status', 'Product unlinked.');
        }
        if ($linkable instanceof Experiment) {
            return redirect()->route('admin.experiments.edit', $linkable)->with('status', 'Product unlinked.');
        }

        return redirect()->route('admin.resources.index')->with('status', 'Product unlinked.');
    }

    private function saveResourceImage(Request $request, ?string $urlInput, ?string $existing = null): ?string
    {
        if ($request->hasFile('image_upload') && $request->file('image_upload')->isValid()) {
            $file     = $request->file('image_upload');
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $dir      = $this->resourceUploadDirectory();
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
            $file->move($dir, $filename);

            if ($existing && str_starts_with($existing, 'images/uploads/resources/')) {
                $this->deleteResourceImageFromKnownPublicRoots($existing);
            }

            return 'images/uploads/resources/' . $filename;
        }

        if ($urlInput !== null && $urlInput !== '') {
            if ($existing && str_starts_with($existing, 'images/uploads/resources/')) {
                $this->deleteResourceImageFromKnownPublicRoots($existing);
            }

            return $urlInput;
        }

        return $existing;
    }

    private function resourceUploadDirectory(): string
    {
        $siteGroundPublicHtml = $this->siteGroundPublicHtmlPath();

        if ($siteGroundPublicHtml !== null) {
            return $siteGroundPublicHtml . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'resources';
        }

        return public_path('images/uploads/resources');
    }

    private function deleteResourceImageFromKnownPublicRoots(?string $relativePath): void
    {
        if (! $relativePath || ! str_starts_with($relativePath, 'images/uploads/resources/')) {
            return;
        }

        $trimmedPath = ltrim($relativePath, '/\\');
        $candidatePaths = [
            public_path($trimmedPath),
        ];

        $siteGroundPublicHtml = $this->siteGroundPublicHtmlPath();
        if ($siteGroundPublicHtml !== null) {
            $candidatePaths[] = $siteGroundPublicHtml . DIRECTORY_SEPARATOR . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $trimmedPath);
        }

        $legacyNestedPublicHtml = base_path('public_html');
        if (is_dir($legacyNestedPublicHtml)) {
            $candidatePaths[] = $legacyNestedPublicHtml . DIRECTORY_SEPARATOR . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $trimmedPath);
        }

        foreach (array_unique($candidatePaths) as $path) {
            if (is_file($path)) {
                @unlink($path);
            }
        }
    }

    private function siteGroundPublicHtmlPath(): ?string
    {
        $candidatePaths = [
            dirname(base_path()) . DIRECTORY_SEPARATOR . 'public_html',
            base_path('public_html'),
        ];

        foreach ($candidatePaths as $candidatePath) {
            if (is_dir($candidatePath)) {
                return $candidatePath;
            }
        }

        return null;
    }

    private function resolveLinkableType(?string $type): ?string
    {
        return match ($type) {
            'post'       => \App\Models\Post::class,
            'experiment' => \App\Models\Experiment::class,
            default      => null,
        };
    }
}