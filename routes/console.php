<?php

use App\Mail\ErrorDigestMail;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('errors:send-digest {--date=}', function () {
    $date = trim((string) $this->option('date'));
    if ($date === '') {
        $date = now()->subDay()->toDateString();
    }

    $digestKey = 'errors:digest:'.$date;
    $digest = Cache::get($digestKey);

    if (! is_array($digest) || empty($digest['total'])) {
        $this->info('No captured errors found for '.$date.'.');

        return;
    }

    try {
        Mail::to('theresettrials@gmail.com')->send(new ErrorDigestMail($digest));
        Cache::forget($digestKey);
        $this->info('Sent error digest for '.$date.' and cleared cached digest data.');
    } catch (\Throwable $exception) {
        report($exception);
        $this->error('Failed to send error digest: '.$exception->getMessage());
    }
})->purpose('Send daily error digest email');

Schedule::command('errors:send-digest')->dailyAt('00:05');
