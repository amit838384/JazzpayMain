<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Models\PreOrder_Model;
use App\Models\SchoolUser_Model;
use App\Models\School_Model;
use App\Models\SchoolStudent_Model;
use App\Models\ManageCafe\Dish_Model;
use App\Mail\PreOrderMail;

class SendPreOrderMails extends Command
{
    protected $signature = 'preorders:send-mails';
    protected $description = 'Email new pre-order details to the matching school users';

    public function handle()
{
    \Log::info('[preorders:send-mails] STARTED at ' . now());   // <-- add

   $orders = PreOrder_Model::where(function ($q) {
        $q->whereNull('mail_sent')->orWhere('mail_sent', 0);
    })
    ->where('status', 1)
    ->orderBy('id')
    ->get();

    if ($orders->isEmpty()) {
        \Log::info('[preorders:send-mails] No new pre-orders.');  // <-- add
        $this->info('No new pre-orders to email.');
        return 0;
    }

    $sent = 0; $skipped = 0;                                      // <-- add

    foreach ($orders as $order) {
        try {
            $emails = \App\Models\User::where('school_id', $order->school_id)
            ->where('role', 'schooladmin')
            ->where('status', 1)
            ->whereNotNull('email')
            ->pluck('email')
            ->filter()
            ->unique()
            ->values();

            if ($emails->isEmpty()) {
                \Log::warning("[preorders:send-mails] Order #{$order->id}: no school user for school_id {$order->school_id}");
                $order->mail_sent = 1;
                $order->save();
                $skipped++;                                        // <-- add
                continue;
            }

            $dish    = Dish_Model::find($order->dish_id);
            $student = SchoolStudent_Model::find($order->student_id);
            $school  = School_Model::find($order->school_id);

            Mail::to($emails->toArray())->send(new PreOrderMail($order, $dish, $student, $school));

            $order->mail_sent = 1;
            $order->save();
            $sent++;                                               // <-- add

            \Log::info("[preorders:send-mails] Sent order #{$order->id} to: " . $emails->implode(', ')); // <-- add
            $this->info("Emailed pre-order #{$order->id} to: " . $emails->implode(', '));
        } catch (\Exception $e) {
            \Log::error("[preorders:send-mails] Order #{$order->id} failed: " . $e->getMessage());
        }
    }

    \Log::info("[preorders:send-mails] DONE. Sent: {$sent}, Skipped: {$skipped}"); // <-- add
    return 0;
}

}
