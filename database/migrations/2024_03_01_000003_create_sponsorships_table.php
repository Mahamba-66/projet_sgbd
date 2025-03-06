<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sponsorships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('voter_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('candidate_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('region_id')->constrained()->onDelete('cascade');
            $table->string('status')->default('pending');
            $table->string('rejection_reason')->nullable();
            $table->timestamp('validated_at')->nullable();
            $table->timestamps();

            // Un électeur ne peut parrainer qu'une seule fois
            $table->unique('voter_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('sponsorships');
    }
};
