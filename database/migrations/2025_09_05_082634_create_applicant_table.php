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
        Schema::create('applicant', function (Blueprint $table) {
            $table->bigIncrements('id')->primary()->index();
            $table->string('first_name')->nullable();
            $table->string('second_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('phone_no')->nullable();
            $table->string('nationality')->nullable();
            $table->string('email')->nullable();
            $table->string('residence')->nullable();
            $table->string('marketing')->nullable();
            $table->integer('allergies')->nullable();
            $table->string('allergy_description')->nullable();
            $table->date('application_date')->nullable();
            $table->string('application_no')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applicant', function (Blueprint $table) {
            //
        });
    }
};
