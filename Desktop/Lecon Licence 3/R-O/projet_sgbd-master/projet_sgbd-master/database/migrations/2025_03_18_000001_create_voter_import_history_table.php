<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('voter_import_history', function (Blueprint $table) {
            $table->id();
            $table->string('file_name');
            $table->integer('total_records');
            $table->integer('valid_records');
            $table->integer('invalid_records');
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('voter_import_history');
    }
};
