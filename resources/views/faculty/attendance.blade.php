@extends('layouts.app')

@section('title', 'Mark Student Attendance')

@section('content')
<div class="card border-0 shadow-sm rounded-4 p-4 glass-card">
    <h4 class="fw-bold mb-4"><i class="bi bi-check-all text-primary"></i> Class Lecture Attendance Roll Call</h4>

    <!-- Class Selector Forms -->
    <form action="{{ route('faculty.attendance') }}" method="GET" class="mb-4">
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label small fw-bold">Select Section Class</label>
                <select name="class_id" class="form-select" required>
                    <option value="">Choose Class</option>
                    @foreach($classes as $c)
                        <option value="{{ $c->id }}" {{ request('class_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-bold">Select Subject</label>
                <select name="subject_id" class="form-select" required>
                    <option value="">Choose Subject</option>
                    @foreach($subjects as $sub)
                        <option value="{{ $sub->id }}" {{ request('subject_id') == $sub->id ? 'selected' : '' }}>{{ $sub->name }} ({{ $sub->code }})</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold">Select Date</label>
                <input type="date" name="date" class="form-control" value="{{ $selectedDate }}" required>
            </div>
            <div class="col-md-1 d-grid align-items-end">
                <button type="submit" class="btn btn-primary rounded-circle" style="width:46px; height:46px;" title="Load Students"><i class="bi bi-arrow-clockwise fs-5"></i></button>
            </div>
        </div>
    </form>

    @if($selectedClass && $selectedSubject)
        <hr class="mb-4">
        
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h5 class="fw-bold mb-0">Class Sheet: {{ $selectedClass->name }}</h5>
                <span class="text-muted small">Subject: {{ $selectedSubject->name }} | Date: {{ $selectedDate }}</span>
            </div>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-sm btn-outline-success rounded-pill px-2.5" onclick="checkAll('present')">All Present</button>
                <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-2.5" onclick="checkAll('absent')">All Absent</button>
            </div>
        </div>

        <form id="attendance-sheet-form">
            <input type="hidden" name="class_id" value="{{ $selectedClass->id }}">
            <input type="hidden" name="subject_id" value="{{ $selectedSubject->id }}">
            <input type="hidden" name="date" value="{{ $selectedDate }}">

            <div class="table-responsive">
                <table class="table align-middle table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Roll Number</th>
                            <th>Student Name</th>
                            <th class="text-center">Present</th>
                            <th class="text-center">Absent</th>
                            <th class="text-center">Late</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $student)
                            @php
                                $status = $student->attendance->first()->status ?? 'present';
                            @endphp
                            <tr>
                                <td><span class="badge bg-secondary-subtle text-secondary fw-semibold">{{ $student->roll_no }}</span></td>
                                <td><strong>{{ $student->user->name }}</strong></td>
                                <td class="text-center">
                                    <input class="form-check-input radio-attendance" type="radio" name="attendance[{{ $student->id }}]" value="present" id="att-p-{{ $student->id }}" {{ $status === 'present' ? 'checked' : '' }}>
                                </td>
                                <td class="text-center">
                                    <input class="form-check-input radio-attendance" type="radio" name="attendance[{{ $student->id }}]" value="absent" id="att-a-{{ $student->id }}" {{ $status === 'absent' ? 'checked' : '' }}>
                                </td>
                                <td class="text-center">
                                    <input class="form-check-input radio-attendance" type="radio" name="attendance[{{ $student->id }}]" value="late" id="att-l-{{ $student->id }}" {{ $status === 'late' ? 'checked' : '' }}>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">No students are currently enrolled in this class section.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if(count($students) > 0)
                <div class="d-flex justify-content-end mt-4">
                    <button type="submit" class="btn btn-accent px-5 py-2">Save Lecture Attendance Sheets</button>
                </div>
            @endif
        </form>
    @else
        <div class="text-center py-5 text-muted">
            <i class="bi bi-card-checklist fs-1 mb-2"></i>
            <p>Please select class parameters, subject, and click refresh to load the student registers.</p>
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
    // Submit Attendance sheet via AJAX
    $("#attendance-sheet-form").submit(function(e) {
        e.preventDefault();
        
        $.ajax({
            url: "{{ route('faculty.attendance.store') }}",
            type: "POST",
            data: $(this).serialize(),
            success: function(res) {
                if(res.success) {
                    alert(res.message);
                } else {
                    alert("Failed to submit sheet details.");
                }
            },
            error: function() {
                alert("A connection error occurred while submitting.");
            }
        });
    });

    // Mark check all radio buttons helper
    function checkAll(status) {
        $(`.radio-attendance[value="${status}"]`).prop('checked', true);
    }
</script>
@endsection
