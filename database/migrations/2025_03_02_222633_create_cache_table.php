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
        Schema::create('cache', function (Blueprint $table) {
            // Limite la longueur de la colonne 'key' à 191 caractères
            $table->string('key', 191)->primary();  // 191 caractères pour éviter de dépasser la limite de 1000 octets
            $table->mediumText('value');
            $table->integer('expiration');
        });

        Schema::create('cache_locks', function (Blueprint $table) {
            // Limite la longueur de la colonne 'key' à 191 caractères pour éviter l'erreur
            $table->string('key', 191)->primary();  // 191 caractères pour éviter de dépasser la limite de 1000 octets
            $table->string('owner');
            $table->integer('expiration');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cache');
        Schema::dropIfExists('cache_locks');
    }
};
