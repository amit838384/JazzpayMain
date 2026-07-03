<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EmailTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $templates = [
            [
                'email_type'    => 'Signup',
                'email_content' => "Hello,\n\nThank you for signing up on {{appName}} ! As part of the verification process, we've sent you a One-Time Password (OTP) to the email address you provided.\nPlease enter the OTP below to complete the verification process and activate your account:\n{{otp}}\n\nThe OTP is valid for next 10 minutes. If you did not initiate this request or did not sign up for our service, please ignore this email. Thank you for choosing {{appName}} !\n\nBest regards,\n\n{{appName}}",
            ],
            [
                'email_type'    => 'Forgot Password',
                'email_content' => "Hello,\n\nUse below code to reset your account password. Your one-time password is {{otp}} and valid for only 10 minutes.\n\nBest regards,\n\n{{appName}}",
            ],
            [
                'email_type'    => 'Low Balance',
                'email_content' => "Dear {{name}},\n\nWe noticed that your account balance is currently below {{limit}}. We wanted to notify you about this so that you can take necessary action to top up your wallet.\n\nBest regards,\n\n{{appName}}",
            ],
            [
                'email_type'    => 'Invitation',
                'email_content' => "Dear Parent,\n\nWe're thrilled to welcome you to the {{appName}} family! To get started, simply follow one of these two easy steps to create your account.\n\nBest regards,\n\n{{appName}}",
            ],
            [
                'email_type'    => 'Service Fee',
                'email_content' => "Dear {{name}},\n\nThis is a reminder that your service fee of {{amount}} for {{month}}-{{year}} is due. Please ensure timely payment.\n\nBest regards,\n\n{{appName}}",
            ],
            [
                'email_type'    => 'Web Signup',
                'email_content' => "Dear {{name}},\n\nWelcome to {{appName}}! We're excited to have you on board. Your account has been created successfully.\n\nBest regards,\n\n{{appName}}",
            ],
            [
                'email_type'    => 'Feedback',
                'email_content' => "Dear {{name}},\n\nThank you for your feedback! Please find the details below:\n\n{{feedbackDetails}}\n\nBest regards,\n\n{{appName}}",
            ],
        ];

        foreach ($templates as $template) {
            DB::table('email_templates')->insert([
                'email_type'    => $template['email_type'],
                'email_content' => $template['email_content'],
                'created_at'    => $now,
                'updated_at'    => $now,
            ]);
        }
    }
}