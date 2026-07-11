@extends('layouts.app')

@section('title', 'Attempting Quiz: ' . $exam->title)

@section('content')
<div class="card border-0 shadow-sm rounded-4 p-4 glass-card mb-4">
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3">
        <div>
            <h4 class="fw-bold mb-0">Active Quiz: {{ $exam->title }}</h4>
            <span class="text-muted small">Max Marks: {{ $exam->total_marks }} | Questions: {{ $exam->questions->count() }}</span>
        </div>
        <div class="p-3 bg-danger text-white rounded-3 d-flex align-items-center gap-2 shadow-sm font-monospace" style="font-size: 1.25rem;">
            <i class="bi bi-stopwatch-fill animate__animated animate__pulse animate__infinite"></i>
            <span id="countdown-timer">--:--</span>
        </div>
    </div>
</div>

<form id="quiz-attempt-form">
    <div class="d-flex flex-column gap-4">
        @foreach($exam->questions as $index => $q)
            <div class="card border-0 shadow-sm rounded-4 p-4 glass-card">
                <span class="fw-bold text-indigo small mb-2 d-block">QUESTION {{ $index + 1 }} (Points: {{ $q->points }})</span>
                <h6 class="fw-bold text-dark lh-base mb-4">{{ $q->question_text }}</h6>
                
                <div class="d-flex flex-column gap-3">
                    <div class="form-check p-3 border rounded-3 bg-light bg-opacity-50">
                        <input class="form-check-input ms-0 me-3" type="radio" name="answers[{{ $q->id }}]" value="A" id="opt-a-{{ $q->id }}">
                        <label class="form-check-label w-100" for="opt-a-{{ $q->id }}">
                            <strong>A.</strong> {{ $q->option_a }}
                        </label>
                    </div>
                    <div class="form-check p-3 border rounded-3 bg-light bg-opacity-50">
                        <input class="form-check-input ms-0 me-3" type="radio" name="answers[{{ $q->id }}]" value="B" id="opt-b-{{ $q->id }}">
                        <label class="form-check-label w-100" for="opt-b-{{ $q->id }}">
                            <strong>B.</strong> {{ $q->option_b }}
                        </label>
                    </div>
                    <div class="form-check p-3 border rounded-3 bg-light bg-opacity-50">
                        <input class="form-check-input ms-0 me-3" type="radio" name="answers[{{ $q->id }}]" value="C" id="opt-c-{{ $q->id }}">
                        <label class="form-check-label w-100" for="opt-c-{{ $q->id }}">
                            <strong>C.</strong> {{ $q->option_c }}
                        </label>
                    </div>
                    <div class="form-check p-3 border rounded-3 bg-light bg-opacity-50">
                        <input class="form-check-input ms-0 me-3" type="radio" name="answers[{{ $q->id }}]" value="D" id="opt-d-{{ $q->id }}">
                        <label class="form-check-label w-100" for="opt-d-{{ $q->id }}">
                            <strong>D.</strong> {{ $q->option_d }}
                        </label>
                    </div>
                </div>
            </div>
        @endforeach

        <div class="d-flex justify-content-end mb-5">
            <button type="submit" class="btn btn-accent px-5 py-3 fs-6 rounded-pill shadow-lg" id="submit-quiz-btn">Submit Exam Papers</button>
        </div>
    </div>
</form>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Set Exam Timer: Duration in seconds
        let timeRemaining = {{ $exam->duration_minutes * 60 }};
        
        function updateTimerDisplay() {
            let minutes = Math.floor(timeRemaining / 60);
            let seconds = timeRemaining % 60;
            
            // Format padding zeros
            let displayMin = minutes < 10 ? "0" + minutes : minutes;
            let displaySec = seconds < 10 ? "0" + seconds : seconds;
            
            $("#countdown-timer").text(displayMin + ":" + displaySec);
        }
        
        updateTimerDisplay();

        const interval = setInterval(function() {
            timeRemaining--;
            updateTimerDisplay();
            
            if(timeRemaining <= 0) {
                clearInterval(interval);
                alert("Time has expired! Submitting answers automatically.");
                submitQuiz();
            }
        }, 1000);

        // Submit form handler
        $("#quiz-attempt-form").submit(function(e) {
            e.preventDefault();
            if(confirm("Confirm quiz submission? Check answers before saving.")) {
                clearInterval(interval);
                submitQuiz();
            }
        });

        // AJAX Post function
        function submitQuiz() {
            $("#submit-quiz-btn").prop("disabled", true).text("Submitting answers...");
            
            $.ajax({
                url: "{{ route('student.exams.submit', $exam->id) }}",
                type: "POST",
                data: $("#quiz-attempt-form").serialize(),
                success: function(res) {
                    if(res.success) {
                        alert(`${res.message} Total score: ${res.score}`);
                        window.location.href = "{{ route('student.exams') }}";
                    } else {
                        alert(res.message);
                        window.location.href = "{{ route('student.exams') }}";
                    }
                },
                error: function() {
                    alert("A critical error occurred. Checking connection and saving inputs locally.");
                    window.location.href = "{{ route('student.exams') }}";
                }
            });
        }
    });
</script>
@endsection
