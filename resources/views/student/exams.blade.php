@extends('layouts.app')

@section('title', 'Online Exams')

@section('content')
<div class="card border-0 shadow-sm rounded-4 p-4 glass-card">
    <h4 class="fw-bold mb-4"><i class="bi bi-pencil-square text-primary"></i> Online Examination Center</h4>
    
    <div class="table-responsive">
        <table class="table align-middle table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Exam Topic</th>
                    <th>Subject</th>
                    <th>Exam Date</th>
                    <th>Duration</th>
                    <th>Status / Score</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($exams as $exam)
                    @php
                        $userAttempt = $exam->results->first(); // Loaded query scope matched student user
                        $isPast = Carbon\Carbon::now()->isAfter($exam->exam_date);
                    @endphp
                    <tr>
                        <td>
                            <div class="fw-bold small">{{ $exam->title }}</div>
                            <small class="text-muted">{{ $exam->description }}</small>
                        </td>
                        <td><span class="badge bg-secondary-subtle text-secondary fw-semibold">{{ $exam->subject->code }}</span></td>
                        <td><small>{{ $exam->exam_date->format('M d, Y H:i') }}</small></td>
                        <td><small>{{ $exam->duration_minutes }} Minutes</small></td>
                        <td>
                            @if($userAttempt)
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1.5 rounded-pill fw-bold">
                                    Score: {{ $userAttempt->marks_obtained }} / {{ $exam->total_marks }}
                                </span>
                            @else
                                @if($isPast)
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-1.5 rounded-pill">Missed / Active</span>
                                @else
                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-3 py-1.5 rounded-pill">Upcoming</span>
                                @endif
                            @endif
                        </td>
                        <td class="text-end">
                            @if($userAttempt)
                                <a href="{{ route('student.exams.result', $userAttempt->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                    <i class="bi bi-eye"></i> Review Mistakes
                                </a>
                            @else
                                <a href="{{ route('student.exams.attempt', $exam->id) }}" class="btn btn-sm btn-accent rounded-pill px-4">
                                    <i class="bi bi-play-fill"></i> Start Quiz
                                </a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted small">No online examinations are scheduled for your class section.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
