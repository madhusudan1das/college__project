<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\FacultyController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\AIController;

// ==========================================
// Public Front-End Routes
// ==========================================
Route::get('/', [AuthController::class, 'home'])->name('home');
Route::get('/about', [AuthController::class, 'about'])->name('about');
Route::get('/contact', [AuthController::class, 'contact'])->name('contact');

// ==========================================
// Authentication System Routes
// ==========================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
    Route::post('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');
});

// ==========================================
// Admin Panel (Role ID 1)
// ==========================================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Students CRUD
    Route::get('/students', [AdminController::class, 'students'])->name('students');
    Route::post('/students', [AdminController::class, 'storeStudent'])->name('students.store');
    Route::put('/students/{id}', [AdminController::class, 'updateStudent'])->name('students.update');
    Route::delete('/students/{id}', [AdminController::class, 'deleteStudent'])->name('students.delete');

    // Faculty CRUD
    Route::get('/faculty', [AdminController::class, 'faculty'])->name('faculty');
    Route::post('/faculty', [AdminController::class, 'storeFaculty'])->name('faculty.store');
    Route::put('/faculty/{id}', [AdminController::class, 'updateFaculty'])->name('faculty.update');
    Route::delete('/faculty/{id}', [AdminController::class, 'deleteFaculty'])->name('faculty.delete');

    // Departments CRUD
    Route::get('/departments', [AdminController::class, 'departments'])->name('departments');
    Route::post('/departments', [AdminController::class, 'storeDepartment'])->name('departments.store');
    Route::put('/departments/{id}', [AdminController::class, 'updateDepartment'])->name('departments.update');
    Route::delete('/departments/{id}', [AdminController::class, 'deleteDepartment'])->name('departments.delete');

    // Notice Board CRUD & AI Summary
    Route::get('/notices', [AdminController::class, 'notices'])->name('notices');
    Route::post('/notices', [AdminController::class, 'storeNotice'])->name('notices.store');
    Route::delete('/notices/{id}', [AdminController::class, 'deleteNotice'])->name('notices.delete');
    Route::post('/notices/ai-summarize', [AdminController::class, 'ajaxAIOptimizeNotice'])->name('notices.ai-summarize');

    // Leaves Management
    Route::get('/leaves', [AdminController::class, 'leaves'])->name('leaves');
    Route::post('/leaves/{id}/approve', [AdminController::class, 'approveLeave'])->name('leaves.approve');
    Route::post('/leaves/{id}/reject', [AdminController::class, 'rejectLeave'])->name('leaves.reject');

    // Attendance Reports & AI Risk Predictor
    Route::get('/attendance', [AdminController::class, 'attendance'])->name('attendance');
    Route::post('/attendance/ai-risk', [AdminController::class, 'ajaxAIPredictAttendanceRisk'])->name('attendance.ai-risk');

    // Salaries Payroll Management
    Route::get('/salaries', [AdminController::class, 'salaries'])->name('salaries');
    Route::post('/salaries/generate', [AdminController::class, 'generateSalaries'])->name('salaries.generate');
    Route::post('/salaries/{id}/pay', [AdminController::class, 'paySalary'])->name('salaries.pay');

    // Fee management
    Route::get('/fees', [AdminController::class, 'fees'])->name('fees');
    Route::post('/fees', [AdminController::class, 'storeFee'])->name('fees.store');

    // Complaints tracker
    Route::get('/complaints', [AdminController::class, 'complaints'])->name('complaints');
    Route::post('/complaints/{id}/status', [AdminController::class, 'updateComplaintStatus'])->name('complaints.status');

    // CSV Analytics reports
    Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
    Route::get('/reports/download', [AdminController::class, 'downloadReport'])->name('reports.download');

    // AI Activity log
    Route::get('/ai-logs', [AIController::class, 'logs'])->name('ai-logs');
});

// ==========================================
// Faculty Panel (Role ID 2)
// ==========================================
Route::middleware(['auth', 'role:faculty'])->prefix('faculty')->name('faculty.')->group(function () {
    Route::get('/dashboard', [FacultyController::class, 'dashboard'])->name('dashboard');
    Route::get('/notices', [FacultyController::class, 'notices'])->name('notices');

    // Marks roll call attendance
    Route::get('/attendance', [FacultyController::class, 'showMarkAttendance'])->name('attendance');
    Route::post('/attendance', [FacultyController::class, 'storeAttendance'])->name('attendance.store');

    // Timetable
    Route::get('/timetable', [FacultyController::class, 'timetable'])->name('timetable');

    // Class study material attachments
    Route::get('/study-materials', [FacultyController::class, 'studyMaterials'])->name('study-materials');
    Route::post('/study-materials', [FacultyController::class, 'storeStudyMaterial'])->name('study-materials.store');
    Route::delete('/study-materials/{id}', [FacultyController::class, 'deleteStudyMaterial'])->name('study-materials.delete');

    // Online exams scheduler
    Route::get('/exams', [FacultyController::class, 'exams'])->name('exams');
    Route::post('/exams', [FacultyController::class, 'storeExam'])->name('exams.store');
    Route::get('/exams/{id}/questions', [FacultyController::class, 'examQuestions'])->name('exams.questions');
    Route::post('/exams/{id}/questions', [FacultyController::class, 'storeQuestion'])->name('exams.questions.store');
    Route::post('/exams/{id}/questions/ai-generate', [FacultyController::class, 'ajaxAIGenerateQuestions'])->name('exams.questions.ai-generate');
    Route::delete('/exams/{examId}/questions/{qId}', [FacultyController::class, 'deleteQuestion'])->name('exams.questions.delete');
    Route::post('/exams/{id}/publish', [FacultyController::class, 'publishExam'])->name('exams.publish');
    Route::get('/exams/{id}/results', [FacultyController::class, 'examResults'])->name('exams.results');

    // Applies leave
    Route::get('/leaves', [FacultyController::class, 'leaves'])->name('leaves');
    Route::post('/leaves', [FacultyController::class, 'applyLeave'])->name('leaves.apply');

    // Student inbox queries
    Route::get('/queries', [FacultyController::class, 'queries'])->name('queries');
    Route::post('/queries/reply', [FacultyController::class, 'replyQuery'])->name('queries.reply');

    // View payroll payslip
    Route::get('/salaries', [FacultyController::class, 'salaries'])->name('salaries');
});

// ==========================================
// Student Panel (Role ID 3)
// ==========================================
Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [StudentController::class, 'dashboard'])->name('dashboard');
    Route::get('/notices', [StudentController::class, 'notices'])->name('notices');
    Route::get('/attendance', [StudentController::class, 'attendance'])->name('attendance');
    Route::get('/timetable', [StudentController::class, 'timetable'])->name('timetable');

    // Applies leave
    Route::get('/leaves', [StudentController::class, 'leaves'])->name('leaves');
    Route::post('/leaves', [StudentController::class, 'applyLeave'])->name('leaves.apply');

    // Pay semester fees & checkouts
    Route::get('/fees', [StudentController::class, 'fees'])->name('fees');
    Route::post('/fees/pay', [StudentController::class, 'payFee'])->name('fees.pay');
    Route::get('/fees/receipt/{id}', [StudentController::class, 'viewReceipt'])->name('fees.receipt');

    // Browse and download files
    Route::get('/study-materials', [StudentController::class, 'studyMaterials'])->name('study-materials');

    // Take online exams
    Route::get('/exams', [StudentController::class, 'exams'])->name('exams');
    Route::get('/exams/{id}/attempt', [StudentController::class, 'attemptExam'])->name('exams.attempt');
    Route::post('/exams/{id}/submit', [StudentController::class, 'submitExam'])->name('exams.submit');
    Route::get('/exams/result/{id}', [StudentController::class, 'examResultDetails'])->name('exams.result');

    // Write complaints (AI integrated categorization)
    Route::get('/complaints', [StudentController::class, 'complaints'])->name('complaints');
    Route::post('/complaints', [StudentController::class, 'storeComplaint'])->name('complaints.store');

    // Contact professors
    Route::get('/messages', [StudentController::class, 'messages'])->name('messages');
    Route::post('/messages', [StudentController::class, 'sendMessage'])->name('messages.send');

    // AI performance suggestions
    Route::get('/performance', [StudentController::class, 'performanceRecommendation'])->name('performance');

    // Floating/Page Chatbot dialogue
    Route::post('/chatbot', [AIController::class, 'ajaxChatbot'])->name('chatbot.chat');
});
