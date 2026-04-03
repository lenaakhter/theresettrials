<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SitePage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SitePageController extends Controller
{
    public function editAbout()
    {
        $aboutPage = SitePage::query()->firstOrCreate(
            ['slug' => 'about'],
            [
                'title' => 'About',
                'content' => 'Keeping this page simple for now. I will share my full journey here soon.',
            ]
        );

        return view('admin.pages.about', compact('aboutPage'));
    }

    public function updateAbout(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:120'],
            'content' => ['required', 'string'],
        ]);

        $aboutPage = SitePage::query()->firstOrCreate(['slug' => 'about']);
        $aboutPage->update($data);

        return redirect()->route('admin.pages.about.edit')->with('status', 'About page updated.');
    }
}
