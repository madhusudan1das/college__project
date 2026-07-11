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
        // 1. Create Students Table
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('department_id')->constrained('departments')->onDelete('cascade');
            $table->foreignId('class_id')->constrained('classes')->onDelete('cascade');
            $table->string('roll_no')->unique();
            $table->string('admission_no')->unique();
            $table->date('dob');
            $table->string('gender');
            $table->text('address')->nullable();
            $table->timestamps();
        });

        // 2. Create Faculty Table
        Schema::create('faculty', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('department_id')->constrained('departments')->onDelete('cascade');
            $table->string('designation');
            $table->string('qualification');
            $table->date('joining_date');
            $table->string('gender');
            $table->text('address')->nullable();
            $table->timestamps();
        });

        // 3. Create Subject Faculty Pivot Table
        Schema::create('subject_faculty', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
            $table->foreignId('faculty_id')->constrained('faculty')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subject_faculty');
        Schema::dropIfExists('faculty');
        Schema::dropIfExists('students');
    }
};
