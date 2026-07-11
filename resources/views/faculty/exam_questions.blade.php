@extends('layouts.app')

@section('title', 'Exam Questions Builder')

@section('content')
<div class="row">
    <!-- Manual Question Creator -->
    <div class="col-lg-5 mb-4">
        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 glass-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0"><i class="bi bi-file-earmark-plus"></i> Add Question Manually</h5>
                <button type="button" class="btn btn-sm btn-outline-info rounded-pill px-2.5 fs-8" data-bs-toggle="modal" data-bs-target="#aiGeneratorModal">
                    <i class="bi bi-robot"></i> AI Generate Questions
                </button>
            </div>
            
            <form action="{{ route('faculty.exams.questions.store', $exam->id) }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label small fw-bold">Question Text</label>
                    <textarea name="question_text" class="form-control" rows="3" placeholder="Write question statement here..." required></textarea>
                </div>
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label class="form-label small fw-bold">Option A</label>
                        <input type="text" name="option_a" class="form-control" placeholder="Option A value" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label small fw-bold">Option B</label>
                        <input type="text" name="option_b" class="form-control" placeholder="Option B value" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label small fw-bold">Option C</label>
                        <input type="text" name="option_c" class="form-control" placeholder="Option C value" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label small fw-bold">Option D</label>
                        <input type="text" name="option_d" class="form-control" placeholder="Option D value" required>
                    </div>
                </div>
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label class="form-label small fw-bold">Correct Option Answer</label>
                        <select name="correct_option" class="form-select" required>
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="C">C</option>
                            <option value="D">D</option>
                        </select>
                    </div>
                    <div class="col-6">
                        <label class="form-label small fw-bold">Question Points</label>
                        <input type="number" name="points" class="form-control" value="1" min="1" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-accent w-100 py-2">Add Question</button>
            </form>
        </div>
    </div>

    <!-- Active Questions List -->
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm rounded-4 p-4 glass-card">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold mb-0">Exam Questions Register</h4>
                <span class="badge bg-primary rounded-pill px-3">{{ $exam->questions->count() }} Questions Added</span>
            </div>
            
            <div class="d-flex flex-column gap-3">
                @forelse($exam->questions as $index => $q)
                    <div class="p-3 border rounded-3 bg-light">
                        <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                            <span class="fw-bold small text-indigo">Q{{ $index + 1 }}. (Points: {{ $q->points }})</span>
                            <form action="{{ route('faculty.exams.questions.delete', [$exam->id, $q->id]) }}" method="POST" onsubmit="return confirm('Remove this question?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger border-0 rounded-circle"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                        <p class="mb-3 small fw-semibold text-dark">{{ $q->question_text }}</p>
                        
                        <div class="row g-2 mb-2 fs-8">
                            <div class="col-md-6"><span class="fw-semibold">A:</span> {{ $q->option_a }}</div>
                            <div class="col-md-6"><span class="fw-semibold">B:</span> {{ $q->option_b }}</div>
                            <div class="col-md-6"><span class="fw-semibold">C:</span> {{ $q->option_c }}</div>
                            <div class="col-md-6"><span class="fw-semibold">D:</span> {{ $q->option_d }}</div>
                        </div>
                        
                        <div class="mt-2 pt-2 border-top fs-8 text-success fw-bold">
                            <i class="bi bi-check-circle-fill"></i> Correct Option: {{ $q->correct_option }}
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5 text-muted small">
                        <i class="bi bi-patch-question fs-1 mb-2"></i>
                        <p>No questions build. Add them manually or click "AI Generate Questions" button.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- =========================================================================
     MODAL: AI Question Generator
     ========================================================================= -->
<div class="modal fade" id="aiGeneratorModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 rounded-4">
            <form id="ai-generator-form">
                <div class="modal-header text-white" style="background: radial-gradient(circle at 10% 20%, rgb(99, 102, 241) 0%, rgb(79, 70, 229) 90%);">
                    <h5 class="modal-title fw-bold"><i class="bi bi-robot"></i> AI MCQ Question Generator</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="alert alert-info small border-0">
                        <i class="bi bi-info-circle-fill"></i> Generates randomized questions based on the exam's subject <strong>({{ $exam->subject->name }})</strong>.
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Select Difficulty Level</label>
                        <select name="difficulty" id="ai-difficulty" class="form-select">
                            <option value="Easy">Easy</option>
                            <option value="Medium" selected>Medium</option>
                            <option value="Hard">Hard</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Select Questions Count</label>
                        <select name="count" id="ai-count" class="form-select">
                            <option value="3">3 Questions</option>
                            <option value="5" selected>5 Questions</option>
                            <option value="10">10 Questions</option>
                        </select>
                    </div>
                    <div class="mb-3" id="ai-topics-container">
                        <label class="form-label small fw-bold">Additional Topics or Guidelines (Optional)</label>
                        <textarea name="topics" id="ai-topics" class="form-control" rows="2" placeholder="e.g. Focus on BCNF decomposition, include SQL query syntax..."></textarea>
                    </div>
                    
                    <div class="d-none text-center py-4" id="ai-loading">
                        <div class="spinner-border text-primary mb-3" role="status"></div>
                        <p class="small text-muted mb-0">Gemini is brainstorming questions details. Please wait...</p>
                    </div>
                </div>
                <div class="modal-footer p-3 bg-light border-top" id="ai-modal-footer">
                    <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Generate Questions</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $("#ai-generator-form").submit(function(e) {
        e.preventDefault();
        
        // Toggle loader UI
        $("#ai-loading").removeClass("d-none");
        $("#ai-difficulty, #ai-count, #ai-topics-container, #ai-modal-footer").addClass("d-none");

        $.ajax({
            url: "{{ route('faculty.exams.questions.ai-generate', $exam->id) }}",
            type: "POST",
            data: {
                difficulty: $("#ai-difficulty").val(),
                count: $("#ai-count").val(),
                topics: $("#ai-topics").val()
            },
            success: function(res) {
                if(res.success) {
                    alert(res.message);
                    location.reload();
                } else {
                    alert("AI generation failed. Please add questions manually.");
                    $("#ai-loading").addClass("d-none");
                    $("#ai-difficulty, #ai-count, #ai-topics-container, #ai-modal-footer").removeClass("d-none");
                }
            },
            error: function() {
                alert("A connection error occurred with the AI engine.");
                $("#ai-loading").addClass("d-none");
                $("#ai-difficulty, #ai-count, #ai-topics-container, #ai-modal-footer").removeClass("d-none");
            }
        });
    });
</script>
@endsection
