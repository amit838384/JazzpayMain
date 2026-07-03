<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PayForService\Subscription_Model;
use Carbon\Carbon;

class CheckSubscriptionStatus extends Command
{
    
    protected $signature = 'subscriptions:check';
    protected $description = 'Check and update subscription statuses based on end date';

    
    public function handle()
    {
        $today = Carbon::today();

        $subscriptions = Subscription_Model::where('status', 'active')->get();

        foreach ($subscriptions as $sub) {
            $endDate = Carbon::parse($sub->end_date);

            if ($today->gt($endDate)) {
                if ($sub->auto_renew) {
                    $newStart = $endDate->copy()->addDay();
                    $newEnd = $newStart->copy()->addDays($sub->duration_days - 1);

                    $sub->update([
                        'start_date' => $newStart,
                        'end_date' => $newEnd,
                        'status' => 'active',
                    ]);

                    $this->info("Subscription ID {$sub->id} auto-renewed till {$newEnd->toDateString()}");
                } else {
                    $sub->update(['status' => 'completed']);
                    $this->info("Subscription ID {$sub->id} marked as completed.");
                }
            }
        }

        $this->info('Subscription check completed.');
        return Command::SUCCESS;
    }
}
