<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class MakeSuperAdmin extends Command
{
    protected $signature = 'make:superadmin {email}';
    protected $description = 'Make a user superadmin by email';

    public function handle()
    {
        $email = $this->argument('email');
        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("User not found with email: {$email}");
            return 1;
        }

        $user->role = 'superadmin';
        $user->save();

        $this->info("User {$email} is now a superadmin!");
        return 0;
    }
}
