<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('voter_validation_errors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('upload_attempt_id')->constrained();
            $table->string('cin')->nullable();
            $table->string('numero_electeur')->nullable();
            $table->string('error_type');
            $table->text('error_message');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('voter_validation_errors');
    }
};
