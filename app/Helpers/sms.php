<?php

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

if (! function_exists('send_sms')) {
    /**
     * Send SMS via SMSala.
     *
     * @param string $phone   International format, no '+'. e.g. 97455608088
     * @param string $message
     * @param bool   $unicode True for Arabic / non-GSM text
     * @return bool
     */
    function send_sms(string $phone, string $message, bool $unicode = false): bool
    {
        $phone = preg_replace('/\D/', '', $phone);

        try {
            $res = Http::timeout(15)
                ->withOptions(['force_ip_resolve' => 'v4'])
                ->post('https://api2.smsala.com/SendSmsV2', [[
                    'apiToken'           => env('SMSALA_TOKEN'),
                    'messageType'        => '1',
                    'messageEncoding'    => $unicode ? '2' : '1',
                    'destinationAddress' => $phone,
                    'sourceAddress'      => env('SMSALA_SENDER_ID'),
                    'messageText'        => $message,
                    'userReferenceId'    => (string) Illuminate\Support\Str::uuid(),
                ]]);

            $ok = ($res->json('0.OperationCode') ?? -1) === 0;

            if (! $ok) {
                Log::warning('SMS failed', ['phone' => $phone, 'response' => $res->json()]);
            }

            return $ok;
        } catch (\Throwable $e) {
            Log::error('SMS exception', ['phone' => $phone, 'error' => $e->getMessage()]);
            return false;
        }
    }
}