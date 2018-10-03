<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssistancesTable extends Migration
{

    public function up()
    {
        Schema::create('assistances', function (Blueprint $table) {
            $table->increments('id');
            $table->date('fecha');
            $table->integer('codigo');
            $table->time('entrada1')->nullable();
            $table->time('salida1')->nullable();
            $table->time('entrada2')->nullable();
            $table->time('salida2')->nullable();
            $table->time('horas_trabajadas')->nullable();
            $table->string('comentario')->nullable();
            $table->boolean('calculada')->default(false);
            $table->integer('excepcion')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('assistances');
    }
}
