<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => \App\Http\Middleware\EnsureUserIsAdmin::class,
            'profile.complete' => \App\Http\Middleware\EnsureProfileIsComplete::class,
            'not.banned' => \App\Http\Middleware\EnsureUserIsNotBanned::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->report(function (Throwable $exception): void {
            if (app()->runningInConsole()) {
                return;
            }

            if ($exception instanceof TransportExceptionInterface) {
                return;
            }

            $statusCode = $exception instanceof HttpExceptionInterface
                ? $exception->getStatusCode()
                : 500;

            // Avoid inbox noise from common not-found/unauthorized traffic.
            if ($statusCode < 500 && $statusCode !== 419) {
                return;
            }

            try {
                $request = request();
                $trace = $exception->getTraceAsString();
                $digestDate = now()->toDateString();
                $digestKey = 'errors:digest:'.$digestDate;

                $digest = Cache::get($digestKey, [
                    'date' => $digestDate,
                    'environment' => app()->environment(),
                    'total' => 0,
                    'items' => [],
                ]);

                $url = $request?->fullUrl() ?? 'n/a';
                $message = $exception->getMessage();
                $fingerprint = sha1($statusCode.'|'.$exception::class.'|'.$message.'|'.$url);

                if (! isset($digest['items'][$fingerprint])) {
                    $digest['items'][$fingerprint] = [
                        'status_code' => $statusCode,
                        'exception_class' => $exception::class,
                        'message' => $message,
                        'url' => $url,
                        'method' => $request?->method(),
                        'user_id' => optional($request?->user())->id,
                        'ip' => $request?->ip(),
                        'count' => 0,
                        'first_seen' => now()->toDateTimeString(),
                        'last_seen' => now()->toDateTimeString(),
                        'trace' => mb_substr($trace, 0, 3000),
                    ];
                }

                $digest['items'][$fingerprint]['count']++;
                $digest['items'][$fingerprint]['last_seen'] = now()->toDateTimeString();
                $digest['total']++;

                Cache::put($digestKey, $digest, now()->addDays(7));
            } catch (Throwable $mailException) {
                logger()->error('Failed to append exception to daily digest.', [
                    'mail_exception' => $mailException->getMessage(),
                    'original_exception' => $exception->getMessage(),
                ]);
            }
        });
    })->create();
