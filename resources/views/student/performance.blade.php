@extends('layouts.app')

@section('title', 'AI Performance & Study Recommendations')

@section('content')
<!-- Top Profile Summary -->
<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm p-4 h-100 glass-card">
            <h5 class="fw-bold mb-3"><i class="bi bi-clock-history text-primary"></i> Attendance Status</h5>
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="text-muted small">Academic presence rate:</span>
                <strong class="text-dark fs-5">{{ $attendanceRate }}%</strong>
            </div>
            <div class="progress mb-2" style="height: 8px;">
                <div class="progress-bar {{ $attendanceRate < 75 ? 'bg-danger' : 'bg-success' }}" role="progressbar" style="width: {{ $attendanceRate }}%;"></div>
            </div>
            @if($attendanceRate < 75)
                <small class="text-danger"><i class="bi bi-exclamation-triangle-fill"></i> Warning: Attendance is below the 75% minimum semester requirement.</small>
            @else
                <small class="text-success"><i class="bi bi-check-circle-fill"></i> Status Clear. You satisfy minimum presence benchmarks.</small>
            @endif
        </div>
    </div>

    <div class="col-md-6">
        <div class="card border-0 shadow-sm p-4 h-100 glass-card">
            <h5 class="fw-bold mb-3"><i class="bi bi-award-fill text-success"></i> Academic Quiz Scores</h5>
            <div class="d-flex flex-wrap gap-2">
                @forelse($quizResults as $res)
                    <div class="p-2 border rounded-3 bg-light d-flex flex-column align-items-center" style="min-width: 100px;">
                        <span class="fs-9 text-muted fw-bold text-uppercase">{{ $res->exam->subject->code }}</span>
                        <strong class="fs-6 mt-1 text-dark">{{ $res->marks_obtained }} / {{ $res->exam->total_marks }}</strong>
                        <span class="badge {{ $res->passed ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} fs-10 mt-1">
                            {{ $res->passed ? 'Pass' : 'Fail' }}
                        </span>
                    </div>
                @empty
                    <p class="text-muted small py-2">No quiz scorecards compiled.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Performance Prediction output -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm p-4 h-100 text-white glass-card" style="background: radial-gradient(circle at 10% 20%, rgb(15, 23, 42) 0%, rgb(30, 41, 59) 90%);">
            <h5 class="fw-bold mb-3"><i class="bi bi-cpu text-info"></i> AI Performance & Failure Prediction</h5>
            <hr class="border-secondary mb-4">
            
            <div class="fs-8 lh-base text-white-50 font-monospace">
                @php
                    // Transform newlines to HTML breaks and clean formatting
                    $formattedPrediction = str_replace("\n", '<br>', $predictionText);
                    $formattedPrediction = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $formattedPrediction);
                    $formattedPrediction = preg_replace('/\*(.*?)\*/', '<em>$1</em>', $formattedPrediction);
                @endphp
                {!! $formattedPrediction !!}
            </div>
        </div>
    </div>

    <!-- Recommendations output -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm p-4 h-100 glass-card">
            <h5 class="fw-bold mb-3 text-dark"><i class="bi bi-stars text-indigo"></i> AI Personalized Study Recommendations</h5>
            <hr class="mb-4">
            
            <div class="fs-8 lh-base text-muted">
                @php
                    $formattedRecommendation = str_replace("\n", '<br>', $recommendationText);
                    $formattedRecommendation = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $formattedRecommendation);
                    $formattedRecommendation = preg_replace('/\*(.*?)\*/', '<em>$1</em>', $formattedRecommendation);
                @endphp
                {!! $formattedRecommendation !!}
            </div>
        </div>
    </div>
</div>
@endsection
