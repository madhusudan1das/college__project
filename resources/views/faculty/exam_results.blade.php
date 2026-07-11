@extends('layouts.app')

@section('title', 'Exam Evaluation Results')

@section('content')
<div class="card border-0 shadow-sm rounded-4 p-4 glass-card">
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3 mb-4">
        <div>
            <h4 class="fw-bold mb-0">Gradebook Evaluations Ledger</h4>
            <span class="text-muted small">Quiz: {{ $exam->title }} | Max Marks: {{ $exam->total_marks }}</span>
        </div>
        <a href="{{ route('faculty.exams') }}" class="btn btn-outline-secondary rounded-pill px-4"><i class="bi bi-chevron-left"></i> Back to Exams</a>
    </div>

    <div class="table-responsive">
        <table class="table align-middle table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Roll Number</th>
                    <th>Student Name</th>
                    <th>Correct Answers</th>
                    <th>Wrong Answers</th>
                    <th>Marks Obtained</th>
                    <th>Status Result</th>
                    <th>Attempt Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($exam->results as $res)
                    <tr>
                        <td><span class="badge bg-secondary-subtle text-secondary fw-semibold">{{ $res->student->roll_no }}</span></td>
                        <td><strong>{{ $res->student->user->name ?? 'Unknown' }}</strong></td>
                        <td><span class="text-success fw-bold">{{ $res->correct_answers }}</span> / {{ $res->total_questions }}</td>
                        <td><span class="text-danger fw-bold">{{ $res->wrong_answers }}</span> / {{ $res->total_questions }}</td>
                        <td><strong class="text-dark fs-6">{{ $res->marks_obtained }}</strong> / {{ $exam->total_marks }}</td>
                        <td>
                            @if($res->passed)
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1 rounded-pill"><i class="bi bi-check2"></i> Passed</span>
                            @else
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-1 rounded-pill"><i class="bi bi-x-lg"></i> Failed</span>
                            @endif
                        </td>
                        <td><small class="text-muted">{{ $res->created_at->format('M d, Y H:i') }}</small></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted small">No students have attempted this online exam yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
