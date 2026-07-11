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
        // 1. Create Attendance Table
        Schema::create('attendance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('class_id')->constrained('classes')->onDelete('cascade');
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
            $table->date('date');
            $table->enum('status', ['present', 'absent', 'late']);
            $table->foreignId('marked_by_faculty_id')->constrained('faculty')->onDelete('cascade');
            $table->timestamps();
        });

        // 2. Create Study Materials Table
        Schema::create('study_materials', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('file_path');
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
            $table->foreignId('class_id')->constrained('classes')->onDelete('cascade');
            $table->foreignId('uploaded_by_faculty_id')->constrained('faculty')->onDelete('cascade');
            $table->timestamps();
        });

        // 3. Create Examinations Table
        Schema::create('examinations', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
            $table->foreignId('class_id')->constrained('classes')->onDelete('cascade');
            $table->integer('duration_minutes');
            $table->integer('total_marks');
            $table->dateTime('exam_date');
            $table->boolean('is_published')->default(false);
            $table->foreignId('created_by_faculty_id')->constrained('faculty')->onDelete('cascade');
            $table->timestamps();
        });

        // 4. Create Exam Questions Table
        Schema::create('exam_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('examination_id')->constrained('examinations')->onDelete('cascade');
            $table->text('question_text');
            $table->string('option_a');
            $table->string('option_b');
            $table->string('option_c');
            $table->string('option_d');
            $table->enum('correct_option', ['A', 'B', 'C', 'D']);
            $table->integer('points')->default(1);
            $table->timestamps();
        });

        // 5. Create Exam Results Table
        Schema::create('exam_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('examination_id')->constrained('examinations')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->integer('total_questions');
            $table->integer('correct_answers');
            $table->integer('wrong_answers');
            $table->integer('marks_obtained');
            $table->boolean('passed');
            $table->json('answers_json'); // Stores student's selected options
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_results');
        Schema::dropIfExists('exam_questions');
        Schema::dropIfExists('examinations');
        Schema::dropIfExists('study_materials');
        Schema::dropIfExists('attendance');
    }
};
