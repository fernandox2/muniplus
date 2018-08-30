<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProgramsTable extends Migration
{

    public function up()
    {
        Schema::create('programs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('dia_id')->unsigned();
            $table->integer('schedule_id')->unsigned();
            $table->time('entrada1')->nullable();
            $table->time('salida1')->nullable();
            $table->time('entrada2')->nullable();
            $table->time('salida2')->nullable();
            $table->foreign('dia_id')->references('id')->on('days')->onDelete('cascade');
            $table->foreign('schedule_id')->references('id')->on('schedules')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('programs');
    }
}
