@extends('layouts.app')

@section('title', 'Manage Notice Board')

@section('content')
<div class="row">
    <!-- Notice Creator Form -->
    <div class="col-lg-5 mb-4">
        <div class="card border-0 shadow-sm rounded-4 p-4 glass-card">
            <h5 class="fw-bold mb-3"><i class="bi bi-megaphone-fill text-primary"></i> Publish New Notice</h5>
            <form action="{{ route('admin.notices.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label small fw-bold">Notice Title</label>
                    <input type="text" name="title" id="notice-title" class="form-control" placeholder="e.g. End Semester Exam Fee Payment schedule" required>
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <label class="form-label small fw-bold mb-0">Notice Content</label>
                        <button type="button" class="btn btn-sm btn-outline-info rounded-pill px-2 py-0 fs-8" id="ai-summarize-btn">
                            <i class="bi bi-magic"></i> AI Smart Summarize
                        </button>
                    </div>
                    <textarea name="content" id="notice-content" class="form-control" rows="6" placeholder="Write full details about this announcement..." required></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Target View Audience</label>
                    <select name="target_role" class="form-select" required>
                        <option value="all">All Roles (Public)</option>
                        <option value="faculty">Faculty Members only</option>
                        <option value="student">Students only</option>
                    </select>
                </div>
                
                <!-- Hidden summary populated by AJAX or default action -->
                <div class="mb-3 d-none animate__animated animate__fadeIn" id="summary-container">
                    <label class="form-label small fw-bold text-success"><i class="bi bi-cpu"></i> Preview AI-Generated Summary</label>
                    <div class="p-3 bg-success bg-opacity-10 border border-success border-opacity-20 rounded-3 small" id="summary-preview"></div>
                </div>

                <button type="submit" class="btn btn-accent w-100 py-2 mt-2">Publish Notice</button>
            </form>
        </div>
    </div>

    <!-- Active Notices Log -->
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm rounded-4 p-4 glass-card">
            <h4 class="fw-bold mb-4">Active Board Announcements</h4>
            
            <div class="d-flex flex-column gap-3">
                @forelse($notices as $notice)
                    <div class="p-3 border rounded-3 bg-light">
                        <div class="d-flex justify-content-between align-items-start gap-2">
                            <div>
                                <h6 class="fw-bold mb-1">{{ $notice->title }}</h6>
                                <span class="badge bg-secondary-subtle text-secondary small mb-2">To: {{ ucfirst($notice->target_role) }}</span>
                                <small class="text-muted d-block mb-2">
                                    Published by: {{ $notice->publisher->name ?? 'System' }} on {{ $notice->created_at->format('M d, Y H:i') }}
                                </small>
                            </div>
                            <form action="{{ route('admin.notices.delete', $notice->id) }}" method="POST" onsubmit="return confirm('Delete this notice?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                        <p class="small text-muted mb-2">{{ $notice->content }}</p>
                        @if($notice->summary)
                            <div class="p-2 bg-info bg-opacity-10 rounded border-start border-info border-3 fs-8">
                                <strong><i class="bi bi-robot"></i> AI Bullet Summary:</strong> {{ $notice->summary }}
                            </div>
                        @endif
                    </div>
                @empty
                    <p class="text-muted text-center py-4">No active notices are currently pinned on the bulletin board.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $("#ai-summarize-btn").click(function() {
            const content = $("#notice-content").val().trim();
            if(!content) {
                alert("Please write the notice content first before invoking the AI generator.");
                return;
            }

            $("#ai-summarize-btn").prop("disabled", true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Summarizing...');

            $.ajax({
                url: "{{ route('admin.notices.ai-summarize') }}",
                type: "POST",
                data: { content: content },
                success: function(res) {
                    $("#ai-summarize-btn").prop("disabled", false).html('<i class="bi bi-magic"></i> AI Smart Summarize');
                    if(res.success) {
                        $("#summary-container").removeClass("d-none");
                        $("#summary-preview").text(res.summary);
                    } else {
                        alert("AI summarization failed. Proceed with manual notice.");
                    }
                },
                error: function() {
                    $("#ai-summarize-btn").prop("disabled", false).html('<i class="bi bi-magic"></i> AI Smart Summarize');
                    alert("A network timeout occurred while fetching the AI model.");
                }
            });
        });
    });
</script>
@endsection
