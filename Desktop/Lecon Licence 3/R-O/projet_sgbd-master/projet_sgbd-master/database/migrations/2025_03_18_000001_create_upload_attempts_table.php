<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Créer la table upload_attempts si elle n'existe pas
        if (!Schema::hasTable('upload_attempts')) {
            Schema::create('upload_attempts', function (Blueprint $table) {
                $table->id();
                $table->string('file_path');
                $table->enum('status', ['pending', 'completed', 'rejected']);
                $table->text('error_message')->nullable();
                $table->timestamps();
            });
        }

        // Créer la table voter_validation_errors si elle n'existe pas
        if (!Schema::hasTable('voter_validation_errors')) {
            Schema::create('voter_validation_errors', function (Blueprint $table) {
                $table->id();
                $table->foreignId('upload_attempt_id')->constrained()->onDelete('cascade');
                $table->string('cin');
                $table->string('numero_electeur');
                $table->string('error_type');
                $table->text('error_message');
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('voter_validation_errors');
        Schema::dropIfExists('upload_attempts');
    }
};
