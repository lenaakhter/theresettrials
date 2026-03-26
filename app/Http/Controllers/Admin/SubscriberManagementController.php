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

        $subscriberCount = NewsletterSubscriber::query()->count();

        return view('admin.subscribers.index', compact('subscribers', 'subscriberCount'));
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

    public function exportExcel(): StreamedResponse
    {
        $filename = 'newsletter-subscribers-'.now()->format('Ymd-His').'.xls';

        return response()->streamDownload(function (): void {
            echo "<table border='1'>";
            echo '<tr><th>Email</th><th>Joined</th></tr>';

            NewsletterSubscriber::query()
                ->orderBy('created_at')
                ->chunk(500, function ($subscribers): void {
                    foreach ($subscribers as $subscriber) {
                        echo '<tr>';
                        echo '<td>'.e($subscriber->email).'</td>';
                        echo '<td>'.e(optional($subscriber->created_at)->toDateTimeString()).'</td>';
                        echo '</tr>';
                    }
                });

            echo '</table>';
        }, $filename, [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename='.$filename,
        ]);
    }
}
