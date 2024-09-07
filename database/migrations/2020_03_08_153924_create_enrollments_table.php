<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_edition_id');
            $table->foreignId('student_id')->constrained('students')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('company_id')->constrained('companies')->onUpdate('cascade')->onDelete('restrict');
            $table->unsignedSmallInteger('minutes_attended')->nullable();
            $table->unsignedFloat('hours_attended')->nullable()->virtualAs('minutes_attended / 60');
            $table->string('global_evaluation', 20)->nullable();
            $table->string('evaluation_comments')->nullable();
            $table->boolean('program_should_be_repeated')->nullable();
            $table->unsignedTinyInteger('should_be_repeated_in_months')->nullable();
            $table->timestamps();

            $table->unique(['program_edition_id', 'student_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('enrollments');
    }
};
