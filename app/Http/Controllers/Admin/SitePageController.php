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

    public function editDisclaimer()
    {
        $disclaimerPage = SitePage::query()->firstOrCreate(
            ['slug' => 'disclaimer'],
            [
                'title' => 'Disclaimer',
                'content' => "I am not a doctor. Nothing on this site should be taken as medical advice.\n\nThe Reset Trials is a personal record of my own experiments with lifestyle, supplements, and habits as someone living with PCOS. Everything I share is based on my own experience - what worked or didn't work for me.\n\nPCOS is a complex condition that affects everyone differently. What I try may not be right for your body, your circumstances, or your health history.\n\nPlease see a doctor or qualified healthcare professional for any medical advice, diagnosis, or treatment. I genuinely encourage everyone with PCOS to maintain a relationship with a doctor they trust - this site is meant to complement that care, not replace it.\n\nAny products or resources I mention are things I have personally used or researched. They are not endorsements, and I am not responsible for any outcomes if you choose to try them. Always check with your healthcare provider before starting any new supplement or health routine.",
            ]
        );

        $disclaimerNote = SitePage::query()->firstOrCreate(
            ['slug' => 'disclaimer-note'],
            [
                'title' => 'Disclaimer note',
                'content' => 'If you are dealing with new symptoms, worsening symptoms, or medication changes, please speak to your doctor first.',
            ]
        );

        return view('admin.pages.disclaimer', compact('disclaimerPage', 'disclaimerNote'));
    }

    public function updateDisclaimer(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:120'],
            'content' => ['required', 'string'],
            'note' => ['required', 'string', 'max:600'],
        ]);

        $disclaimerPage = SitePage::query()->firstOrCreate(['slug' => 'disclaimer']);
        $disclaimerPage->update([
            'title' => $data['title'],
            'content' => $data['content'],
        ]);

        $disclaimerNote = SitePage::query()->firstOrCreate(['slug' => 'disclaimer-note']);
        $disclaimerNote->update([
            'title' => 'Disclaimer note',
            'content' => $data['note'],
        ]);

        return redirect()->route('admin.pages.disclaimer.edit')->with('status', 'Disclaimer page updated.');
    }
}
