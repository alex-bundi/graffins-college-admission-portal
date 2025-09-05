<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('applicant_course', function (Blueprint $table) {
            $table->id();
            $table->string('applicant_id');
            $table->string('academic_year')->nullable();
            $table->string('intake')->nullable();
            $table->string('department_code')->nullable();
            $table->integer('mode_of_study')->nullable();
            $table->string('course_code')->nullable();
            $table->string('course_level')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->date('application_date')->nullable();
            $table->timestamps();

            // foreign keys
            $table->foreign('applicant_id')
                ->references('id')
                ->on('applicant');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applicant_course', function (Blueprint $table) {
            //
        });
    }
};
