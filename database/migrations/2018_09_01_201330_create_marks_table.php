<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMarksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('marks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('codigo');
            $table->integer('type_mark_id')->unsigned();
            $table->time('hora');
            $table->date('fecha');
            $table->integer('dia');
            $table->string('ubicacion')->nullable();
            $table->boolean('calculada')->default(false);
            $table->string('checksum')->nullable();
            $table->string('comentario')->nullable();
            $table->integer('excepcion')->nullable();
            $table->foreign('type_mark_id')->references('id')->on('type_marks');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('marks');
    }
}
