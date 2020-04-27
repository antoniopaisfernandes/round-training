<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProgramEditionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('program_editions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id');
            $table->string('name', 50);
            $table->foreignId('company_id');
            $table->decimal('cost')->default(0);
            $table->string('supplier');
            $table->text('supplier_certifications')->nullable();
            $table->string('teacher_name');
            $table->date('starts_at')->nullable()->index();
            $table->date('ends_at')->nullable()->index();
            $table->string('location', 100)->nullable();
            $table->date('evaluation_notification_date')->nullable();
            $table->foreignId('created_by');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('program_id')->references('id')->on('programs')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('program_editions');
    }
}
