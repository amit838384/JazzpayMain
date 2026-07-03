<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ManageSubscriptions extends Command
{
   
    protected $signature = 'subscriptions:manage';

    protected $description = 'Manage active subscriptions daily (reduce days, expire, auto-renew)';

  
    public function handle()
    {
        Log::info('🕒 Subscription Cron started at ' . now());
        $today = Carbon::today();

        $subscriptions = Subscription_Model::whereIn('status', ['active', 'paused'])->get();

        foreach ($subscriptions as $sub) {
            try {
                if ($sub->status === 'paused') {
                    continue; 
                }

                if ($sub->remaining_days > 0) {
                    $sub->remaining_days -= 1;
                }

                if ($today->gt(Carbon::parse($sub->end_date))) {
                    $sub->status = 'completed';
                }

                if ($sub->auto_renew && $sub->status === 'completed') {
                    $newStart = $today;
                    $newEnd = $today->copy()->addDays($sub->duration_days - 1);
                    $sub->start_date = $newStart;
                    $sub->end_date = $newEnd;
                    $sub->remaining_days = $sub->duration_days;
                    $sub->status = 'active';
                }

                $sub->save();
            } catch (\Exception $e) {
                Log::error('Error managing subscription ID ' . $sub->id . ': ' . $e->getMessage());
            }
        }

        Log::info('✅ Subscription Cron completed at ' . now());
    }
}
