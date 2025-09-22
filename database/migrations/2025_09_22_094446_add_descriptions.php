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
            //
            $table->string('course_description')->nullable();
            $table->string('level_description')->nullable();
            $table->string('department_description')->nullable();
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
