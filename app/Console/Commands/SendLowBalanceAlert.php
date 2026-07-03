<?php
    
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Models\SchoolParent_Model;

class SendLowBalanceAlert extends Command
{
    protected $signature   = 'sms:low-balance-alert';
    protected $description = 'Send low balance SMS alert to parents (once per day per user)';

    public function handle(): void
    {
        Log::info('LowBalanceAlert cron STARTED', ['time' => now()->toDateTimeString()]);

        $parents = SchoolParent_Model::where('topup_balance', '<', 50)->get();

        Log::info('LowBalanceAlert found parents with low balance', [
            'total' => $parents->count(),
        ]);

        $sent    = 0;
        $skipped = 0;
        $failed  = 0;

        foreach ($parents as $parent) {

            // ── One message per user per day ──────────────────────────
            $cacheKey = 'low_balance_sms_' . $parent->id . '_' . now()->format('Y-m-d');

            if (Cache::has($cacheKey)) {
                Log::info('LowBalanceAlert SKIPPED (already sent today)', [
                    'parent_id' => $parent->id,
                    'name'      => $parent->name,
                    'mobile'    => $parent->mobile,
                    'balance'   => $parent->topup_balance,
                    'date'      => now()->format('Y-m-d'),
                ]);
                $skipped++;
                continue;
            }

            // ── Build message ─────────────────────────────────────────
            $message = "Dear {$parent->name}, your school portal wallet balance is low. "
                     . "Current balance: {$parent->topup_balance}. "
                     . "Please top up to continue using services.";

            Log::info('LowBalanceAlert attempting SMS', [
                'parent_id' => $parent->id,
                'name'      => $parent->name,
                'mobile'    => $parent->mobile,
                'balance'   => $parent->topup_balance,
                'message'   => $message,
            ]);

            // ── Send SMS ──────────────────────────────────────────────
            $smsSent = send_sms($parent->mobile, $message);

            if ($smsSent) {
                // Lock this user for today (expires at midnight)
                $secondsUntilMidnight = now()->secondsUntilEndOfDay() + 1;
                Cache::put($cacheKey, true, $secondsUntilMidnight);

                Log::info('LowBalanceAlert SMS sent successfully', [
                    'parent_id'            => $parent->id,
                    'name'                 => $parent->name,
                    'mobile'               => $parent->mobile,
                    'balance'              => $parent->topup_balance,
                    'next_alert_available' => now()->addSeconds($secondsUntilMidnight)->toDateTimeString(),
                ]);
                $sent++;
            } else {
                Log::warning('LowBalanceAlert SMS FAILED', [
                    'parent_id' => $parent->id,
                    'name'      => $parent->name,
                    'mobile'    => $parent->mobile,
                    'balance'   => $parent->topup_balance,
                ]);
                $failed++;
            }
        }

        Log::info('LowBalanceAlert cron FINISHED', [
            'total_low_balance' => $parents->count(),
            'sms_sent'          => $sent,
            'skipped_today'     => $skipped,
            'failed'            => $failed,
        ]);
    }
}