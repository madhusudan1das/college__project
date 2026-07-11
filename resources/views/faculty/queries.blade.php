@extends('layouts.app')

@section('title', 'Student Queries')

@section('content')
<div class="card border-0 shadow-sm rounded-4 p-4 glass-card">
    <h4 class="fw-bold mb-4"><i class="bi bi-chat-left-text text-primary"></i> Enrolled Students Query Inbox</h4>
    
    <div class="d-flex flex-column gap-3">
        @forelse($messages as $msg)
            <div class="p-3 border rounded-3 bg-light" id="msg-card-{{ $msg->id }}">
                <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                    <div>
                        <strong class="d-block text-dark">{{ $msg->subject }}</strong>
                        <small class="text-muted">From student: {{ $msg->sender->name }} | Received: {{ $msg->created_at->diffForHumans() }}</small>
                    </div>
                    <button class="btn btn-sm btn-accent rounded-pill px-3" onclick="openReplyModal({{ $msg->sender_id }}, '{{ addslashes($msg->subject) }}')">
                        <i class="bi bi-reply-fill"></i> Reply Query
                    </button>
                </div>
                <p class="small text-muted mb-0 lh-relaxed p-2 bg-white rounded border">{{ $msg->body }}</p>
            </div>
        @empty
            <p class="text-muted py-5 text-center small">No student inbox messages available.</p>
        @endforelse
    </div>
</div>

<!-- =========================================================================
     MODAL: Reply to Student Query
     ========================================================================= -->
<div class="modal fade" id="replyQueryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 rounded-4">
            <form id="reply-query-form">
                <input type="hidden" name="receiver_id" id="reply-receiver-id">
                <div class="modal-header bg-primary text-white" style="background: var(--accent-gradient) !important;">
                    <h5 class="modal-title fw-bold"><i class="bi bi-reply"></i> Dispatch Reply Query</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Subject</label>
                        <input type="text" name="subject" id="reply-subject" class="form-control" readonly required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Message Details</label>
                        <textarea class="form-control" name="body" rows="4" placeholder="Write academic instructions here..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer p-3 bg-light border-top">
                    <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Send Reply</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Open reply modal helper
    function openReplyModal(senderId, subject) {
        $("#reply-receiver-id").val(senderId);
        $("#reply-subject").val(subject.startsWith("Re: ") ? subject : "Re: " + subject);
        $("#replyQueryModal").modal('show');
    }

    // Submit Reply via AJAX
    $("#reply-query-form").submit(function(e) {
        e.preventDefault();
        
        $.ajax({
            url: "{{ route('faculty.queries.reply') }}",
            type: "POST",
            data: $(this).serialize(),
            success: function(res) {
                if(res.success) {
                    alert(res.message);
                    $("#replyQueryModal").modal('hide');
                    $("#reply-query-form")[0].reset();
                } else {
                    alert("Sending query reply failed.");
                }
            },
            error: function() {
                alert("A connection error occurred.");
            }
        });
    });
</script>
@endsection
