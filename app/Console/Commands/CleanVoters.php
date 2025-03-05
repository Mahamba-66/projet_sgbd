<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CleanVoters extends Command
{
    protected $signature = 'voters:clean';
    protected $description = 'Supprimer tous les électeurs de la base de données';

    public function handle()
    {
        DB::table('users')->where('role', 'voter')->delete();
        $this->info('Tous les électeurs ont été supprimés.');
    }
}
