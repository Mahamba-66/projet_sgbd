<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerificationEmail;
use App\Models\User;

class TestEmail extends Command
{
    protected $signature = 'email:test';
    protected $description = 'Test email configuration';

    public function handle()
    {
        $this->info('Testing email configuration...');

        try {
            $user = new User([
                'name' => 'Test User',
                'email' => 'diaoseydina62@gmail.com',
                'verification_code' => '123456'
            ]);

            Mail::to($user->email)->send(new VerificationEmail($user));

            $this->info('Email sent successfully!');
            $this->info('Please check your inbox at: ' . $user->email);
            
        } catch (\Exception $e) {
            $this->error('Error sending email: ' . $e->getMessage());
            $this->info('Mail configuration:');
            $this->table(['Setting', 'Value'], [
                ['MAIL_MAILER', config('mail.default')],
                ['MAIL_HOST', config('mail.mailers.smtp.host')],
                ['MAIL_PORT', config('mail.mailers.smtp.port')],
                ['MAIL_USERNAME', config('mail.mailers.smtp.username')],
                ['MAIL_ENCRYPTION', config('mail.mailers.smtp.encryption')],
                ['MAIL_FROM_ADDRESS', config('mail.from.address')],
            ]);
        }
    }
}
