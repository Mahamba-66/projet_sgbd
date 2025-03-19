<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('upload_attempts', function (Blueprint $table) {
            $table->string('file_path')->after('id');
            // Rendre user_id et ip_address nullable car ils ne sont pas requis pour l'instant
            $table->foreignId('user_id')->nullable()->change();
            $table->string('ip_address')->nullable()->change();
            $table->string('checksum')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('upload_attempts', function (Blueprint $table) {
            $table->dropColumn('file_path');
            // Remettre les colonnes comme avant
            $table->foreignId('user_id')->nullable(false)->change();
            $table->string('ip_address')->nullable(false)->change();
            $table->string('checksum')->nullable(false)->change();
        });
    }
};
