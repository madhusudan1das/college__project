@extends('layouts.app')

@section('title', 'Lodge Complaint')

@section('content')
<div class="row">
    <!-- Log Complaint Form -->
    <div class="col-lg-5 mb-4">
        <div class="card border-0 shadow-sm rounded-4 p-4 glass-card">
            <h5 class="fw-bold mb-3"><i class="bi bi-shield-exclamation text-primary"></i> File Grievance Report</h5>
            <p class="text-muted small">Submit reports regarding campus assets, billing discrepancies, or class operations. Our AI engine will auto-categorize and recommend immediate actions.</p>
            <form action="{{ route('student.complaints.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label small fw-bold">Report Title</label>
                    <input type="text" name="title" class="form-control" placeholder="e.g. Wi-Fi connection failing in CSE block" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Detailed Narrative</label>
                    <textarea name="description" class="form-control" rows="5" placeholder="Provide full context, equipment serials, date occurred, etc..." required></textarea>
                </div>
                <button type="submit" class="btn btn-accent w-100 py-2">Submit Grievance</button>
            </form>
        </div>
    </div>

    <!-- Complaint Logs History -->
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm rounded-4 p-4 glass-card">
            <h4 class="fw-bold mb-4">Logged Complaints Track</h4>
            
            <div class="d-flex flex-column gap-3">
                @forelse($complaints as $comp)
                    <div class="p-3 border rounded-3 bg-light bg-opacity-70">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge bg-secondary-subtle text-secondary small">Category: {{ $comp->category ?? 'Determining...' }}</span>
                            @if($comp->status === 'pending')
                                <span class="badge bg-warning text-dark"><i class="bi bi-clock-history"></i> Pending</span>
                            @elseif($comp->status === 'in_progress')
                                <span class="badge bg-info text-white"><i class="bi bi-gear-fill"></i> In Progress</span>
                            @else
                                <span class="badge bg-success"><i class="bi bi-check-circle"></i> Resolved</span>
                            @endif
                        </div>
                        <h6 class="fw-bold mb-1 text-dark">{{ $comp->title }}</h6>
                        <p class="small text-muted mb-2">{{ $comp->description }}</p>
                        
                        @if($comp->ai_comment)
                            <div class="p-2 bg-info bg-opacity-10 border-start border-info border-3 rounded fs-8 text-muted">
                                <strong><i class="bi bi-robot"></i> AI Administrator Comment:</strong> {{ $comp->ai_comment }}
                            </div>
                        @endif
                    </div>
                @empty
                    <p class="text-muted small py-4 text-center">No grievances logged for your account.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
