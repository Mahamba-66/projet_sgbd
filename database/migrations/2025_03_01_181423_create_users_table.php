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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email', 191)->unique();
            $table->string('password');
            $table->enum('role', ['voter', 'candidate', 'admin'])->default('voter');
            $table->string('voter_card_number', 191)->unique()->nullable();
            $table->string('nin' , 191)->unique()->nullable();
            $table->string('region')->nullable();
            $table->string('department')->nullable();
            $table->string('commune')->nullable();
            $table->string('polling_station')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
