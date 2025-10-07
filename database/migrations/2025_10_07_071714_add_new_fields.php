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
            //  $table->string('academic_year')->nullable();
            $table->string('intake_code')->nullable();
            $table->string('intake_description')->nullable();
            $table->string('tutor_code')->nullable();
            $table->string('tutor_name')->nullable();
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
