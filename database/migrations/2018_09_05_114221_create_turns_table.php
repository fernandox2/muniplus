<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTurnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('turns', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('relationship_id')->unsigned();
            $table->integer('schedule_id')->unsigned();
            $table->date('inicio');
            $table->date('fin');
            $table->foreign('schedule_id')->references('id')->on('schedules');
            $table->foreign('relationship_id')->references('id')->on('relationships');
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
        Schema::dropIfExists('turns');
    }
}
