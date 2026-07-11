@extends('layouts.app')

@section('title', 'Faculty Dashboard')

@section('content')
<!-- Welcome banner -->
<div class="card border-0 shadow-sm rounded-4 p-4 mb-4 text-white glass-card" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);">
    <div class="d-flex align-items-center gap-3">
        <i class="bi bi-mortarboard-fill fs-1 text-info"></i>
        <div>
            <h4 class="fw-bold mb-0">Welcome back, {{ $faculty->user->name }}!</h4>
            <span class="small text-white-50">Department: {{ $faculty->department->name }} | Designation: {{ $faculty->designation }}</span>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Student Count -->
    <div class="col-lg-3 col-sm-6">
        <div class="card border-0 shadow-sm p-4 h-100 glass-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted d-block small mb-1 fw-bold">CLASS STUDENTS</span>
                    <h3 class="mb-0 fw-bold">{{ $stats['students_count'] }}</h3>
                </div>
                <div class="stat-icon" style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);">
                    <i class="bi bi-people-fill"></i>
                </div>
            </div>
            <div class="mt-3">
                <span class="small text-muted"><i class="bi bi-info-circle"></i> Students in department classes</span>
            </div>
        </div>
    </div>

    <!-- Subjects Count -->
    <div class="col-lg-3 col-sm-6">
        <div class="card border-0 shadow-sm p-4 h-100 glass-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted d-block small mb-1 fw-bold">SUBJECTS TAUGHT</span>
                    <h3 class="mb-0 fw-bold">{{ $stats['subjects_count'] }}</h3>
                </div>
                <div class="stat-icon" style="background: linear-gradient(135deg, #10b981 0%, #047857 100%);">
                    <i class="bi bi-journal-text"></i>
                </div>
            </div>
            <div class="mt-3 text-muted small">
                @foreach($faculty->subjects as $sub)
                    <span class="badge bg-secondary-subtle text-secondary me-1">{{ $sub->code }}</span>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Exams created -->
    <div class="col-lg-3 col-sm-6">
        <div class="card border-0 shadow-sm p-4 h-100 glass-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted d-block small mb-1 fw-bold">EXAMS SCHEMAS</span>
                    <h3 class="mb-0 fw-bold">{{ $stats['exams_count'] }}</h3>
                </div>
                <div class="stat-icon" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                    <i class="bi bi-patch-question-fill"></i>
                </div>
            </div>
            <div class="mt-3">
                <span class="small text-muted"><a href="{{ route('faculty.exams') }}" class="text-decoration-none text-warning fw-medium"><i class="bi-arrow-right"></i> Manage Exams</a></span>
            </div>
        </div>
    </div>

    <!-- Messages inbox -->
    <div class="col-lg-3 col-sm-6">
        <div class="card border-0 shadow-sm p-4 h-100 glass-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted d-block small mb-1 fw-bold">INBOX QUERIES</span>
                    <h3 class="mb-0 fw-bold">{{ $stats['queries_received'] }}</h3>
                </div>
                <div class="stat-icon" style="background: linear-gradient(135deg, #a855f7 0%, #7e22ce 100%);">
                    <i class="bi bi-chat-left-text-fill"></i>
                </div>
            </div>
            <div class="mt-3">
                <span class="small text-muted"><a href="{{ route('faculty.queries') }}" class="text-decoration-none text-purple fw-medium"><i class="bi-arrow-right"></i> View student queries</a></span>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Classes List -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm p-4 h-100 glass-card">
            <h5 class="fw-bold mb-3"><i class="bi bi-calendar3 text-primary"></i> Academic Sections Assigned</h5>
            <div class="list-group list-group-flush">
                @forelse($teachingClasses as $c)
                    <div class="list-group-item d-flex justify-content-between align-items-center py-3 bg-transparent">
                        <div>
                            <strong class="d-block">{{ $c->name }}</strong>
                            <small class="text-muted">Section code: {{ $c->code }}</small>
                        </div>
                        <a href="{{ route('faculty.attendance', ['class_id' => $c->id, 'subject_id' => $faculty->subjects->first()->id ?? '']) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">Mark Presence</a>
                    </div>
                @empty
                    <p class="text-muted small">No class parameters assigned to this department block.</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Notices Feed -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm p-4 h-100 glass-card">
            <h5 class="fw-bold mb-3"><i class="bi bi-megaphone-fill text-primary"></i> Board Announcement Bulletins</h5>
            <div class="d-flex flex-column gap-3">
                @forelse($notices as $notice)
                    <div class="p-2.5 border rounded-3 bg-light bg-opacity-50">
                        <span class="badge bg-secondary-subtle text-secondary fs-9 mb-1.5">{{ $notice->created_at->format('M d, Y') }}</span>
                        <h6 class="fw-bold small mb-1">{{ $notice->title }}</h6>
                        <p class="small text-muted mb-0">{{ $notice->summary ?? Str::limit($notice->content, 120) }}</p>
                    </div>
                @empty
                    <p class="text-muted small text-center py-3">No board updates are pinned.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
