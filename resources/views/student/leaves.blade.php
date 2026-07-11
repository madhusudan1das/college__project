@extends('layouts.app')

@section('title', 'Apply for Leave')

@section('content')
<div class="row">
    <!-- Apply Form -->
    <div class="col-lg-5 mb-4">
        <div class="card border-0 shadow-sm rounded-4 p-4 glass-card">
            <h5 class="fw-bold mb-3"><i class="bi bi-calendar-plus text-primary"></i> Leave Application Form</h5>
            <form action="{{ route('student.leaves.apply') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label small fw-bold">Leave Classification</label>
                    <select name="leave_type" class="form-select" required>
                        <option value="sick">Sick Leave</option>
                        <option value="casual">Casual Leave</option>
                        <option value="emergency">Emergency Leave</option>
                    </select>
                </div>
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label class="form-label small fw-bold">Start Date</label>
                        <input type="date" name="start_date" class="form-control" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label small fw-bold">End Date</label>
                        <input type="date" name="end_date" class="form-control" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Detailed Explanation</label>
                    <textarea name="reason" class="form-control" rows="4" placeholder="Explain details..." required></textarea>
                </div>
                <button type="submit" class="btn btn-accent w-100 py-2">Submit Leave request</button>
            </form>
        </div>
    </div>

    <!-- Leave History -->
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm rounded-4 p-4 glass-card">
            <h4 class="fw-bold mb-4">Leave Application Log</h4>
            
            <div class="table-responsive">
                <table class="table align-middle table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Type</th>
                            <th>Dates</th>
                            <th>Status</th>
                            <th>Resolution Info</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($leaves as $leave)
                            <tr>
                                <td><span class="badge bg-secondary-subtle text-secondary text-capitalize">{{ $leave->leave_type }}</span></td>
                                <td>
                                    <span class="d-block small fw-bold">{{ $leave->start_date }} to {{ $leave->end_date }}</span>
                                    @php
                                        $days = Carbon\Carbon::parse($leave->start_date)->diffInDays(Carbon\Carbon::parse($leave->end_date)) + 1;
                                    @endphp
                                    <small class="text-muted">Total: {{ $days }} days</small>
                                </td>
                                <td>
                                    @if($leave->status === 'pending')
                                        <span class="badge bg-warning text-dark"><i class="bi bi-clock-history"></i> Pending</span>
                                    @elseif($leave->status === 'approved')
                                        <span class="badge bg-success"><i class="bi bi-check-circle"></i> Approved</span>
                                    @else
                                        <span class="badge bg-danger"><i class="bi bi-x-circle"></i> Rejected</span>
                                    @endif
                                </td>
                                <td>
                                    @if($leave->status === 'rejected' && $leave->rejection_reason)
                                        <small class="text-danger d-block text-wrap" style="max-width: 150px;">{{ $leave->rejection_reason }}</small>
                                    @elseif($leave->status === 'approved')
                                        <small class="text-muted">Authorized by Dean</small>
                                    @else
                                        <small class="text-muted">Awaiting review</small>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted small">No leave logs available.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
