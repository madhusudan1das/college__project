@extends('layouts.app')

@section('title', 'Exam Evaluation Details')

@section('content')
<div class="card border-0 shadow-sm rounded-4 p-4 glass-card mb-4">
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3">
        <div>
            <h4 class="fw-bold mb-0">Quiz Review: {{ $result->exam->title }}</h4>
            <span class="text-muted small">Subject: {{ $result->exam->subject->name }} | Attempted on: {{ $result->created_at->format('M d, Y H:i') }}</span>
        </div>
        <a href="{{ route('student.exams') }}" class="btn btn-outline-secondary rounded-pill px-4"><i class="bi bi-chevron-left"></i> Back to Exams</a>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Score statistics card -->
    <div class="col-md-4">
        <div class="card border-0 shadow-sm p-4 h-100 glass-card text-center">
            <span class="text-muted small d-block mb-1 fw-bold">MARKS ACQUIRED</span>
            <h1 class="display-3 fw-bold text-primary mb-2">{{ $result->marks_obtained }}</h1>
            <span class="text-muted d-block small mb-3">out of {{ $result->exam->total_marks }} total marks</span>
            
            <div class="progress mb-3" style="height: 10px;">
                @php
                    $rate = ($result->marks_obtained / $result->exam->total_marks) * 100;
                @endphp
                <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $rate }}%;" aria-valuenow="{{ $rate }}" aria-valuemin="0" aria-valuemax="100"></div>
            </div>

            @if($result->passed)
                <span class="badge bg-success px-4 py-2 rounded-pill fs-7"><i class="bi bi-patch-check-fill"></i> Passed Evaluation</span>
            @else
                <span class="badge bg-danger px-4 py-2 rounded-pill fs-7"><i class="bi bi-x-octagon-fill"></i> Failed (Under 40%)</span>
            @endif
        </div>
    </div>

    <!-- Answers count summary -->
    <div class="col-md-8">
        <div class="card border-0 shadow-sm p-4 h-100 glass-card">
            <h5 class="fw-bold mb-4">Question Verification Index</h5>
            <div class="row g-3 text-center">
                <div class="col-4 border-end">
                    <span class="text-muted small d-block mb-1">Total Questions</span>
                    <h3 class="fw-bold mb-0 text-dark">{{ $result->total_questions }}</h3>
                </div>
                <div class="col-4 border-end">
                    <span class="text-muted small d-block mb-1 text-success">Correct Options</span>
                    <h3 class="fw-bold mb-0 text-success">{{ $result->correct_answers }}</h3>
                </div>
                <div class="col-4">
                    <span class="text-muted small d-block mb-1 text-danger">Incorrect Options</span>
                    <h3 class="fw-bold mb-0 text-danger">{{ $result->wrong_answers }}</h3>
                </div>
            </div>
        </div>
    </div>
</div>

<h4 class="fw-bold mb-4">Detailed Question Mistakes Checklist</h4>
<div class="d-flex flex-column gap-4">
    @foreach($result->exam->questions as $index => $q)
        @php
            $selected = $result->answers_json[$q->id] ?? null;
            $isCorrect = $selected === $q->correct_option;
        @endphp
        <div class="card border-0 shadow-sm rounded-4 p-4 glass-card border-start border-4 {{ $isCorrect ? 'border-success' : 'border-danger' }}">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="fw-bold text-indigo small">QUESTION {{ $index + 1 }}</span>
                @if($isCorrect)
                    <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1 rounded-pill small"><i class="bi bi-check-circle"></i> Correct Option (+{{ $q->points }})</span>
                @else
                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-1 rounded-pill small"><i class="bi bi-x-circle"></i> Incorrect (0 Points)</span>
                @endif
            </div>

            <h6 class="fw-bold text-dark mb-4">{{ $q->question_text }}</h6>

            <div class="row g-3 fs-8 mb-3">
                <div class="col-md-6 p-2 rounded-3 border {{ $q->correct_option === 'A' ? 'bg-success bg-opacity-10 border-success' : ($selected === 'A' ? 'bg-danger bg-opacity-10 border-danger' : 'bg-light') }}">
                    <strong>A.</strong> {{ $q->option_a }}
                </div>
                <div class="col-md-6 p-2 rounded-3 border {{ $q->correct_option === 'B' ? 'bg-success bg-opacity-10 border-success' : ($selected === 'B' ? 'bg-danger bg-opacity-10 border-danger' : 'bg-light') }}">
                    <strong>B.</strong> {{ $q->option_b }}
                </div>
                <div class="col-md-6 p-2 rounded-3 border {{ $q->correct_option === 'C' ? 'bg-success bg-opacity-10 border-success' : ($selected === 'C' ? 'bg-danger bg-opacity-10 border-danger' : 'bg-light') }}">
                    <strong>C.</strong> {{ $q->option_c }}
                </div>
                <div class="col-md-6 p-2 rounded-3 border {{ $q->correct_option === 'D' ? 'bg-success bg-opacity-10 border-success' : ($selected === 'D' ? 'bg-danger bg-opacity-10 border-danger' : 'bg-light') }}">
                    <strong>D.</strong> {{ $q->option_d }}
                </div>
            </div>

            <div class="d-flex gap-4 fs-8 pt-2 border-top">
                <span>Selected Option: <strong class="{{ $isCorrect ? 'text-success' : 'text-danger' }}">{{ $selected ?? 'Unanswered' }}</strong></span>
                <span>Correct Option Key: <strong class="text-success">{{ $q->correct_option }}</strong></span>
            </div>
        </div>
    @endforeach
</div>
@endsection
