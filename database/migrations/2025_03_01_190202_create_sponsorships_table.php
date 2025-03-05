<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sponsorships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('voter_id')->constrained('users');
            $table->foreignId('candidate_id')->constrained('users');
            $table->foreignId('sponsorship_period_id')->constrained('sponsorship_periods');
            $table->enum('status', ['pending', 'validated', 'rejected'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->timestamps();
            
            // Un électeur ne peut parrainer qu'une seule fois par période
            $table->unique(['voter_id', 'sponsorship_period_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sponsorships');
    }
};
