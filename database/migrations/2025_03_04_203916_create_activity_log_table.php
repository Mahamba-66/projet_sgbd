<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivityLogTable extends Migration
{
    public function up()
    {
        Schema::connection(config('activitylog.database_connection'))->create(config('activitylog.table_name'), function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('log_name')->nullable();
            $table->text('description');
            $table->string('subject_type', 191)->nullable();  // Limite la longueur à 191 caractères
            $table->unsignedBigInteger('subject_id')->nullable();  // Sujet ID est un identifiant, pas besoin de string
            $table->string('causer_type', 191)->nullable();  // Limite la longueur à 191 caractères
            $table->unsignedBigInteger('causer_id')->nullable();  // Causer ID est un identifiant
            $table->json('properties')->nullable();
            $table->timestamps();
            $table->index(['subject_type', 'subject_id']);  // Crée un index sur les deux colonnes
        });
    }

    public function down()
    {
        Schema::connection(config('activitylog.database_connection'))->dropIfExists(config('activitylog.table_name'));
    }
}
