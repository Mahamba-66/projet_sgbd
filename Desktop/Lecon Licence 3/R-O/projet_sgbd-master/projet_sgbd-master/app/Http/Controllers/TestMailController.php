<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerificationEmail;
use App\Models\User;

class TestMailController extends Controller
{
    public function testMail()
    {
        try {
            $user = new User([
                'name' => 'Test User',
                'email' => 'diaoseydina62@gmail.com',
                'verification_code' => '123456'
            ]);

            Mail::to($user->email)->send(new VerificationEmail($user));

            return response()->json([
                'success' => true,
                'message' => 'Email envoyé avec succès à ' . $user->email
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'config' => [
                    'driver' => config('mail.default'),
                    'host' => config('mail.mailers.smtp.host'),
                    'port' => config('mail.mailers.smtp.port'),
                    'username' => config('mail.mailers.smtp.username'),
                    'encryption' => config('mail.mailers.smtp.encryption'),
                    'from' => config('mail.from.address'),
                ]
            ], 500);
        }
    }
}
