<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\AccountBannedMail;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

class SubscriberManagementController extends Controller
{
    public function index()
    {
        $accounts = User::query()
            ->latest('created_at')
            ->paginate(50);

        $totalAccounts = User::query()->count();
        $subscriberCount = User::query()->where('email_notifications_opt_in', true)->count();

        return view('admin.subscribers.index', compact('accounts', 'subscriberCount', 'totalAccounts'));
    }

    public function ban(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'hours' => ['required', 'integer', 'min:1', 'max:8760'],
            'reason' => ['required', 'string', 'max:2000'],
        ]);

        if ($user->is_admin) {
            return back()->withErrors([
                'hours' => 'Admin accounts cannot be banned from this screen.',
            ]);
        }

        if ($user->banned_until && now()->lessThan($user->banned_until)) {
            return back()->withErrors([
                'hours' => 'This account is already banned until '.$user->banned_until->format('M d, Y H:i').'. Remove the ban first if you want to issue a new one.',
            ]);
        }

        $hours = (int) $data['hours'];
        $reason = trim($data['reason']);
        $banId = 'BAN-'.Str::upper(Str::random(8));

        $user->update([
            'banned_until' => now()->addHours($hours),
            'ban_duration_hours' => $hours,
            'ban_started_at' => now(),
            'ban_reason' => $reason,
            // Rotate remember token so persistent login cookies are invalidated.
            'remember_token' => Str::random(60),
        ]);

        // Force immediate logout for active sessions.
        DB::table('sessions')->where('user_id', $user->id)->delete();

        $appealUrl = route('ban.appeal.create', [
            'ban_id' => $banId,
            'username' => $user->username,
        ]);

        try {
            Mail::to($user->email)->send(new AccountBannedMail($user, $hours, $reason, $banId, $appealUrl));
        } catch (Throwable $exception) {
            report($exception);

            return back()->withErrors([
                'hours' => 'Account was banned, but the notification email could not be sent. Please check mail configuration.',
            ]);
        }

        if (in_array(config('mail.default'), ['log', 'array'], true)) {
            return back()->with('status', 'Account banned for '.$hours.' hours. Notification email was logged locally because MAIL_MAILER='.config('mail.default').'.');
        }

        return back()->with('status', 'Account banned for '.$hours.' hours and notification email sent.');
    }

    public function unban(User $user): RedirectResponse
    {
        $user->update([
            'banned_until' => null,
            'ban_duration_hours' => null,
            'ban_started_at' => null,
            'ban_reason' => null,
        ]);

        return back()->with('status', 'Ban removed early for this account.');
    }

    public function export(): StreamedResponse
    {
        $filename = 'opted-in-subscribers-'.now()->format('Ymd-His').'.csv';

        return response()->streamDownload(function (): void {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, ['email', 'display_name', 'username', 'joined_at']);

            User::query()
                ->where('email_notifications_opt_in', true)
                ->orderBy('created_at')
                ->chunk(500, function ($accounts) use ($handle): void {
                    foreach ($accounts as $account) {
                        fputcsv($handle, [
                            $account->email,
                            $account->display_name,
                            $account->username,
                            optional($account->created_at)->toDateTimeString(),
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
        $filename = 'opted-in-subscribers-'.now()->format('Ymd-His').'.xls';

        return response()->streamDownload(function (): void {
            echo "<table border='1'>";
            echo '<tr><th>Email</th><th>Display Name</th><th>Username</th><th>Joined</th></tr>';

            User::query()
                ->where('email_notifications_opt_in', true)
                ->orderBy('created_at')
                ->chunk(500, function ($accounts): void {
                    foreach ($accounts as $account) {
                        echo '<tr>';
                        echo '<td>'.e($account->email).'</td>';
                        echo '<td>'.e($account->display_name).'</td>';
                        echo '<td>'.e($account->username).'</td>';
                        echo '<td>'.e(optional($account->created_at)->toDateTimeString()).'</td>';
                        echo '</tr>';
                    }
                });

            echo '</table>';
        }, $filename, [
            'Content-Type' => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename='.$filename,
        ]);
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->is_admin) {
            return redirect()->route('admin.subscribers.index')
                ->with('error', 'Admin accounts cannot be deleted. Revoke admin access first.');
        }

        if ($user->profile_photo) {
            $trimmedPath = ltrim($user->profile_photo, '/\\');
            $candidatePaths = [public_path($trimmedPath)];

            $siteGroundPublicHtml = base_path('public_html');
            if (is_dir($siteGroundPublicHtml)) {
                $candidatePaths[] = $siteGroundPublicHtml.DIRECTORY_SEPARATOR.str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $trimmedPath);
            }

            foreach (array_unique($candidatePaths) as $photoPath) {
                if (is_file($photoPath)) {
                    @unlink($photoPath);
                }
            }
        }

        $name = $user->name;
        // Clean up orphaned sessions and password reset tokens
        DB::table('sessions')->where('user_id', $user->id)->delete();
        DB::table('password_reset_tokens')->where('email', $user->email)->delete();

        $user->delete();

        return redirect()->route('admin.subscribers.index')
            ->with('status', "Account for \"{$name}\" has been permanently deleted.");
    }
}
