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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('address');
            $table->string('postal_code', 20);
            $table->string('city');
            $table->string('citizen_id', 20)->nullable()->unique();
            $table->date('citizen_id_validity')->nullable();
            $table->string('email')->unique();
            $table->string('phone', 20);
            $table->string('birth_place')->nullable();
            $table->string('nationality')->nullable();
            $table->string('current_job_title')->nullable();
            $table->foreignId('current_company_id')->nullable()->constrained('companies')->onUpdate('cascade');
            $table->foreignId('leader_id')->nullable()->constrained('users')->onUpdate('cascade')->onDelete('set null');
            $table->softDeletes();
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
        Schema::dropIfExists('students');
    }
};
