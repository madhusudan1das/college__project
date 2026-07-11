@extends('layouts.app')

@section('title', 'Manage Online Exams')

@section('content')
<div class="row">
    <!-- Schedule Exam Form -->
    <div class="col-lg-4 mb-4">
        <div class="card border-0 shadow-sm rounded-4 p-4 glass-card">
            <h5 class="fw-bold mb-3"><i class="bi bi-calendar2-week text-primary"></i> Create Online Exam</h5>
            
            <form action="{{ route('faculty.exams.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label small fw-bold">Exam Title</label>
                    <input type="text" name="title" class="form-control" placeholder="e.g. DBMS Normalization Quiz" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Select Target Class</label>
                    <select name="class_id" class="form-select" required>
                        <option value="">Choose Class</option>
                        @foreach($classes as $c)
                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Select Subject</label>
                    <select name="subject_id" class="form-select" required>
                        <option value="">Choose Subject</option>
                        @foreach($subjects as $sub)
                            <option value="{{ $sub->id }}">{{ $sub->name }} ({{ $sub->code }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label class="form-label small fw-bold">Duration (Min)</label>
                        <input type="number" name="duration_minutes" class="form-control" placeholder="15" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label small fw-bold">Total Marks</label>
                        <input type="number" name="total_marks" class="form-control" placeholder="10" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Scheduled DateTime</label>
                    <input type="datetime-local" name="exam_date" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Short Description/Rules</label>
                    <textarea name="description" class="form-control" rows="3" placeholder="Description of topics covered..."></textarea>
                </div>
                <button type="submit" class="btn btn-accent w-100 py-2 mt-2">Publish Exam Card</button>
            </form>
        </div>
    </div>

    <!-- Active Exams List -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 p-4 glass-card">
            <h4 class="fw-bold mb-4">Scheduled Online Quizzes</h4>
            
            <div class="table-responsive">
                <table class="table align-middle table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Exam Name</th>
                            <th>Class Target</th>
                            <th>Timing & Duration</th>
                            <th>Q Count</th>
                            <th>Score Release</th>
                            <th class="text-end">Question & Marks Audit</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($exams as $exam)
                            <tr>
                                <td>
                                    <div class="fw-bold small">{{ $exam->title }}</div>
                                    <span class="badge bg-secondary-subtle text-secondary small">{{ $exam->subject->code ?? '' }}</span>
                                </td>
                                <td><span class="small">{{ $exam->class->name ?? '' }}</span></td>
                                <td>
                                    <span class="d-block small fw-bold">{{ $exam->exam_date->format('M d, Y H:i') }}</span>
                                    <small class="text-muted">Length: {{ $exam->duration_minutes }} Min | Max: {{ $exam->total_marks }} Marks</small>
                                </td>
                                <td>
                                    <span class="badge bg-info text-white">{{ $exam->questions->count() }} Qs</span>
                                </td>
                                <td>
                                    @if($exam->is_published)
                                        <span class="badge bg-success"><i class="bi bi-eye"></i> Published</span>
                                    @else
                                        <span class="badge bg-warning text-dark"><i class="bi bi-eye-slash"></i> Hidden</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="d-flex justify-content-end gap-1">
                                        <a href="{{ route('faculty.exams.questions', $exam->id) }}" class="btn btn-xs btn-outline-primary rounded-pill px-2.5" title="Build Questions">
                                            <i class="bi bi-patch-question"></i> Questions
                                        </a>
                                        <a href="{{ route('faculty.exams.results', $exam->id) }}" class="btn btn-xs btn-outline-success rounded-pill px-2.5" title="Grades Ledger">
                                            <i class="bi bi-card-list"></i> Grades
                                        </a>
                                        @if(!$exam->is_published)
                                            <form action="{{ route('faculty.exams.publish', $exam->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Release grades to students boards?');">
                                                @csrf
                                                <button type="submit" class="btn btn-xs btn-outline-warning rounded-pill px-2.5" title="Publish Grades">
                                                    <i class="bi bi-send"></i> Publish
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted small">No online quizzes have been created yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
