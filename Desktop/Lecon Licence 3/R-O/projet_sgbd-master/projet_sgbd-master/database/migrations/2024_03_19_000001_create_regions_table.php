<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('regions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('code', 10)->unique();
            $table->integer('population')->default(0);
            $table->integer('registered_voters')->default(0);
            $table->integer('required_sponsorships')->default(2000);
            $table->timestamps();
        });

        // Ajout de la colonne region_id à la table users
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('region_id')
                ->nullable()
                ->constrained()
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['region_id']);
            $table->dropColumn('region_id');
        });

        Schema::dropIfExists('regions');
    }
};
