<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExercisesTable extends Migration
{
    public function up()
    {
        Schema::create('exercises', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('course_id');
            $table->unsignedBigInteger('cat_id');
            $table->string('name');
            $table->integer('points');
            $table->timestamps();

            $table->foreign('course_id')->references('id')->on('courses');
            $table->foreign('cat_id')->references('id')->on('domain_categories');
        });
    }

    public function down()
    {
        Schema::dropIfExists('exercises');
    }
}
