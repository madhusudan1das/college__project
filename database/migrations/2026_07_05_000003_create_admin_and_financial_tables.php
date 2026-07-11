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
        // 1. Create Fees Table
        Schema::create('fees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->string('title');
            $table->decimal('amount', 10, 2);
            $table->date('due_date');
            $table->enum('status', ['unpaid', 'paid', 'partial'])->default('unpaid');
            $table->timestamps();
        });

        // 2. Create Payments Table
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fee_id')->constrained('fees')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->decimal('amount_paid', 10, 2);
            $table->dateTime('payment_date');
            $table->string('payment_method');
            $table->string('transaction_id')->unique();
            $table->string('status')->default('completed');
            $table->timestamps();
        });

        // 3. Create Receipts Table
        Schema::create('receipts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_id')->constrained('payments')->onDelete('cascade');
            $table->string('receipt_no')->unique();
            $table->string('file_path');
            $table->timestamps();
        });

        // 4. Create Salary Records Table
        Schema::create('salary_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('faculty_id')->constrained('faculty')->onDelete('cascade');
            $table->decimal('base_salary', 10, 2);
            $table->decimal('bonuses', 10, 2)->default(0.00);
            $table->decimal('deductions', 10, 2)->default(0.00);
            $table->decimal('net_salary', 10, 2);
            $table->date('payment_date')->nullable();
            $table->enum('status', ['paid', 'pending'])->default('pending');
            $table->timestamps();
        });

        // 5. Create Leave Requests Table
        Schema::create('leave_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('leave_type'); // e.g. sick, casual, emergency
            $table->date('start_date');
            $table->date('end_date');
            $table->text('reason');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->foreignId('actioned_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('rejection_reason')->nullable();
            $table->timestamps();
        });

        // 6. Create Notices Table
        Schema::create('notices', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->foreignId('published_by')->constrained('users')->onDelete('cascade');
            $table->enum('target_role', ['all', 'faculty', 'student'])->default('all');
            $table->text('summary')->nullable(); // Set by AI notice summarization
            $table->timestamps();
        });

        // 7. Create Complaints Table
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->string('category')->nullable(); // set by AI complaint categorization
            $table->enum('status', ['pending', 'in_progress', 'resolved'])->default('pending');
            $table->text('ai_comment')->nullable();
            $table->timestamps();
        });

        // 8. Create Messages Table
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('receiver_id')->constrained('users')->onDelete('cascade');
            $table->string('subject');
            $table->text('body');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
        Schema::dropIfExists('complaints');
        Schema::dropIfExists('notices');
        Schema::dropIfExists('leave_requests');
        Schema::dropIfExists('salary_records');
        Schema::dropIfExists('receipts');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('fees');
    }
};
