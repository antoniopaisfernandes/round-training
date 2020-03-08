<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProgramEditionSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('program_edition_schedules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('program_edition_id');
            $table->dateTime('starts_at');
            $table->dateTime('ends_at');
            $table->dateTime('interval_start')->nullable();
            $table->unsignedSmallInteger('interval_minutes')->nullable();

            $table->timestamps();

            $table->foreign('program_edition_id')->references('id')->on('program_editions')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('program_edition_schedules');
    }
}
