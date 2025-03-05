<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Création de la table jobs
        Schema::create('jobs', function (Blueprint $table) {
            $table->id(); // Colonne id auto-incrémentée
            $table->string('queue', 191)->index(); // Index sur la colonne queue
            $table->longText('payload');
            $table->unsignedTinyInteger('attempts');
            $table->unsignedInteger('reserved_at')->nullable();
            $table->unsignedInteger('available_at');
            $table->unsignedInteger('created_at');
        });

        // Création de la table job_batches
        Schema::create('job_batches', function (Blueprint $table) {
            $table->id(); // Utilisation d'un id auto-incrémenté comme clé primaire
            $table->string('name', 191);
            $table->unsignedInteger('total_jobs')->default(0); // Utilisation de unsignedInteger pour les entiers
            $table->unsignedInteger('pending_jobs')->default(0);
            $table->unsignedInteger('failed_jobs')->default(0);
            $table->longText('failed_job_ids');
            $table->mediumText('options')->nullable();
            $table->unsignedInteger('cancelled_at')->nullable();
            $table->unsignedInteger('created_at');
            $table->unsignedInteger('finished_at')->nullable();
        });

        // Création de la table failed_jobs
        Schema::create('failed_jobs', function (Blueprint $table) {
            $table->id(); // Colonne id auto-incrémentée
            $table->string('uuid', 191)->unique(); // UUID unique
            $table->text('connection');
            $table->text('queue', 191);
            $table->longText('payload');
            $table->longText('exception');
            $table->timestamp('failed_at')->useCurrent(); // Valeur par défaut pour le timestamp
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Suppression des tables dans l'ordre inverse
        Schema::dropIfExists('failed_jobs');
        Schema::dropIfExists('job_batches');
        Schema::dropIfExists('jobs');
    }
};
