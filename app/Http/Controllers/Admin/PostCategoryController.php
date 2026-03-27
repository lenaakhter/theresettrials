<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PostCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PostCategoryController extends Controller
{
    public function index()
    {
        $categories = PostCategory::orderBy('name')->get();

        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:post_categories,name'],
        ]);

        PostCategory::create(['name' => trim($data['name'])]);

        return redirect()->route('admin.categories.index')->with('status', "Category \"{$data['name']}\" added.");
    }

    public function destroy(PostCategory $category): RedirectResponse
    {
        $category->delete();

        return redirect()->route('admin.categories.index')->with('status', "Category \"{$category->name}\" removed.");
    }
}
