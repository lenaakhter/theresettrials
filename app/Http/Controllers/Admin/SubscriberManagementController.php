<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SubscriberManagementController extends Controller
{
    public function index()
    {
        $subscribers = NewsletterSubscriber::query()
            ->latest('created_at')
            ->paginate(50);

        return view('admin.subscribers.index', compact('subscribers'));
    }

    public function export(): StreamedResponse
    {
        $filename = 'newsletter-subscribers-'.now()->format('Ymd-His').'.csv';

        return response()->streamDownload(function (): void {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, ['email', 'created_at']);

            NewsletterSubscriber::query()
                ->orderBy('created_at')
                ->chunk(500, function ($subscribers) use ($handle): void {
                    foreach ($subscribers as $subscriber) {
                        fputcsv($handle, [
                            $subscriber->email,
                            optional($subscriber->created_at)->toDateTimeString(),
                        ]);
                    }
                });

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }
}
