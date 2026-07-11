@extends('layouts.app')

@section('title', 'Student Dashboard')

@section('content')
<!-- Welcome Header banner -->
<div class="card border-0 shadow-sm rounded-4 p-4 mb-4 text-white glass-card" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);">
    <div class="d-flex align-items-center gap-3">
        <div class="avatar-circle rounded-circle border" style="width:60px; height:60px; font-size: 1.5rem; background: var(--accent-gradient);">
            {{ strtoupper(substr($student->user->name, 0, 2)) }}
        </div>
        <div>
            <h4 class="fw-bold mb-0">Welcome back, {{ $student->user->name }}!</h4>
            <span class="small text-white-50">Class: {{ $student->class->name ?? '' }} | Roll No: {{ $student->roll_no }} | Dept: {{ $student->department->name ?? '' }}</span>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Attendance KPI Card -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm p-4 h-100 glass-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted d-block small mb-1 fw-bold">MY ATTENDANCE</span>
                    <h3 class="mb-0 fw-bold">{{ $attendanceRate }}%</h3>
                </div>
                <div class="stat-icon" style="background: linear-gradient(135deg, #10b981 0%, #047857 100%);">
                    <i class="bi bi-check2-square"></i>
                </div>
            </div>
            
            <div class="progress mt-3" style="height: 8px;">
                <div class="progress-bar bg-success" role="progressbar" style="width: {{ $attendanceRate }}%;" aria-valuenow="{{ $attendanceRate }}" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            
            <div class="mt-3">
                <span class="small text-muted"><a href="{{ route('student.attendance') }}" class="text-decoration-none text-success fw-medium"><i class="bi bi-arrow-right"></i> Attendance breakdown</a></span>
            </div>
        </div>
    </div>

    <!-- Outstanding Fees KPI Card -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm p-4 h-100 glass-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted d-block small mb-1 fw-bold">PENDING INVOICES</span>
                    <h3 class="mb-0 fw-bold">₹{{ number_format($pendingFees, 2) }}</h3>
                </div>
                <div class="stat-icon" style="background: linear-gradient(135deg, #ef4444 0%, #b91c1c 100%);">
                    <i class="bi bi-credit-card"></i>
                </div>
            </div>
            <div class="mt-3">
                @if($pendingFees > 0)
                    <span class="small text-muted"><a href="{{ route('student.fees') }}" class="text-decoration-none text-danger fw-medium"><i class="bi bi-arrow-right"></i> Pay Tuition Fee</a></span>
                @else
                    <span class="small text-success fw-semibold"><i class="bi bi-patch-check"></i> Account Clear</span>
                @endif
            </div>
        </div>
    </div>

    <!-- Exam Results Count KPI Card -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm p-4 h-100 glass-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted d-block small mb-1 fw-bold">QUIZZES COMPLETED</span>
                    <h3 class="mb-0 fw-bold">{{ $examResultsCount }} Exams</h3>
                </div>
                <div class="stat-icon" style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);">
                    <i class="bi bi-pencil-square"></i>
                </div>
            </div>
            <div class="mt-3">
                <span class="small text-muted"><a href="{{ route('student.exams') }}" class="text-decoration-none text-primary fw-medium"><i class="bi bi-arrow-right"></i> View results reports</a></span>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Notice Board -->
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm p-4 h-100 glass-card">
            <h5 class="fw-bold mb-3"><i class="bi bi-megaphone-fill text-primary"></i> Bulletins & Announcements</h5>
            <div class="d-flex flex-column gap-3">
                @forelse($notices as $notice)
                    <div class="p-3 border rounded-3 bg-light bg-opacity-70">
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2.5 py-1 mb-2 fs-9">{{ $notice->created_at->format('M d, Y') }}</span>
                        <h6 class="fw-bold mb-1">{{ $notice->title }}</h6>
                        <p class="small text-muted mb-0">{{ $notice->summary ?? Str::limit($notice->content, 120) }}</p>
                    </div>
                @empty
                    <p class="text-muted small py-3 text-center">No college notices are pinned.</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- AI Study Assistant Card -->
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm p-4 h-100 text-white glass-card" style="background: radial-gradient(circle at 10% 20%, rgb(99, 102, 241) 0%, rgb(79, 70, 229) 90%);">
            <h5 class="fw-bold mb-3"><i class="bi bi-stars"></i> AI Academic Guidance</h5>
            <p class="small text-white-50">Need study topic explanations or academic advice? Ask your assistant chatbot floating at the bottom right corner of this screen.</p>
            <div class="p-3 bg-white bg-opacity-10 border border-light border-opacity-10 rounded-3 mb-3 small">
                <strong>Try Asking:</strong>
                <span class="d-block text-white-50 mt-1 italic">"Explain normalization rules in DBMS with examples."</span>
            </div>
            <a href="{{ route('student.performance') }}" class="btn btn-outline-light rounded-pill px-4 mt-auto">Check Personalized AI Recommendations</a>
        </div>
    </div>
</div>
@endsection
