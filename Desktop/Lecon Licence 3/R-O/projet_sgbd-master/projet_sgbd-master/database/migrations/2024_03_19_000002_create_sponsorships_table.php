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
            $table->foreignId('voter_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('candidate_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('region_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['pending', 'validated', 'rejected'])->default('pending');
            $table->timestamp('validation_date')->nullable();
            $table->string('rejection_reason')->nullable();
            $table->timestamps();

            // Un électeur ne peut parrainer qu'une seule fois un même candidat
            $table->unique(['voter_id', 'candidate_id']);
            
            // Index pour améliorer les performances des requêtes fréquentes
            $table->index(['status', 'region_id']);
            $table->index(['candidate_id', 'status']);
            $table->index(['voter_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sponsorships');
    }
};
