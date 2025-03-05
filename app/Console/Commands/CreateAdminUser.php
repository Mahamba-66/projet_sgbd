<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser extends Command
{
    protected $signature = 'admin:create {name} {email} {password}';
    protected $description = 'Créer un utilisateur administrateur';

    public function handle()
    {
        $admin = User::create([
            'name' => $this->argument('name'),
            'email' => $this->argument('email'),
            'password' => Hash::make($this->argument('password')),
            'role' => 'admin'
        ]);

        $this->info('Administrateur créé avec succès!');
        $this->table(
            ['Nom', 'Email', 'Rôle'],
            [[$admin->name, $admin->email, $admin->role]]
        );
    }
}
