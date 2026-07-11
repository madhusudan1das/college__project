@extends('layouts.app')

@section('title', 'Manage Leave Applications')

@section('content')
<div class="card border-0 shadow-sm rounded-4 p-4 glass-card">
    <h4 class="fw-bold mb-4">Leave Application Audit Ledger</h4>
    
    <div class="table-responsive">
        <table class="table align-middle table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Requester</th>
                    <th>Role</th>
                    <th>Leave Type</th>
                    <th>Duration</th>
                    <th>Reason</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($leaves as $leave)
                    <tr id="row-leave-{{ $leave->id }}">
                        <td>
                            <div class="fw-bold">{{ $leave->user->name ?? 'Unknown' }}</div>
                            <small class="text-muted">{{ $leave->user->email ?? '' }}</small>
                        </td>
                        <td>
                            <span class="badge {{ $leave->user->role_id == 2 ? 'bg-primary' : 'bg-success' }} rounded-pill">
                                {{ ucfirst($leave->user->role->name ?? '') }}
                            </span>
                        </td>
                        <td><strong class="text-capitalize small">{{ $leave->leave_type }}</strong></td>
                        <td>
                            <span class="d-block small fw-semibold">{{ $leave->start_date }} to {{ $leave->end_date }}</span>
                            @php
                                $start = Carbon\Carbon::parse($leave->start_date);
                                $end = Carbon\Carbon::parse($leave->end_date);
                                $days = $start->diffInDays($end) + 1;
                            @endphp
                            <small class="text-muted">Total: {{ $days }} {{ Str::plural('Day', $days) }}</small>
                        </td>
                        <td><p class="mb-0 text-muted small" style="max-width: 250px;">{{ $leave->reason }}</p></td>
                        <td id="status-cell-{{ $leave->id }}">
                            @if($leave->status === 'pending')
                                <span class="badge bg-warning text-dark"><i class="bi bi-clock-history"></i> Pending</span>
                            @elseif($leave->status === 'approved')
                                <span class="badge bg-success"><i class="bi bi-check-circle"></i> Approved</span>
                            @else
                                <span class="badge bg-danger"><i class="bi bi-x-circle"></i> Rejected</span>
                                <small class="d-block text-danger fs-8 mt-1" style="max-width: 150px;">Reason: {{ $leave->rejection_reason }}</small>
                            @endif
                        </td>
                        <td class="text-end" id="action-cell-{{ $leave->id }}">
                            @if($leave->status === 'pending')
                                <button class="btn btn-sm btn-success rounded-pill px-3 me-1" onclick="approveLeave({{ $leave->id }})">
                                    <i class="bi bi-check2"></i> Approve
                                </button>
                                <button class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="openRejectModal({{ $leave->id }})">
                                    <i class="bi bi-x-lg"></i> Reject
                                </button>
                            @else
                                <span class="text-muted small">Reviewed</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">No leave requests are currently submitted.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- =========================================================================
     MODAL: Reject Leave Request
     ========================================================================= -->
<div class="modal fade" id="rejectLeaveModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 rounded-4">
            <form id="reject-leave-form">
                <input type="hidden" id="reject-leave-id">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title fw-bold"><i class="bi bi-exclamation-triangle-fill"></i> Reject Leave Application</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Provide Rejection Reason</label>
                        <textarea class="form-control" name="rejection_reason" id="rejection_reason" rows="3" placeholder="Explain why this request is denied..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer p-3 bg-light border-top">
                    <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger rounded-pill px-4">Confirm Rejection</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // AJAX: Approve Request
    function approveLeave(id) {
        if(confirm("Approve this leave request?")) {
            $.ajax({
                url: `/admin/leaves/${id}/approve`,
                type: "POST",
                success: function(res) {
                    if(res.success) {
                        alert(res.message);
                        $(`#status-cell-${id}`).html('<span class="badge bg-success"><i class="bi bi-check-circle"></i> Approved</span>');
                        $(`#action-cell-${id}`).html('<span class="text-muted small">Reviewed</span>');
                    } else {
                        alert("Failed to action approval.");
                    }
                },
                error: function() {
                    alert("A connection error occurred.");
                }
            });
        }
    }

    // Helper: open reject dialog
    function openRejectModal(id) {
        $("#reject-leave-id").val(id);
        $("#rejection_reason").val('');
        $("#rejectLeaveModal").modal('show');
    }

    // AJAX: Reject Request
    $("#reject-leave-form").submit(function(e) {
        e.preventDefault();
        const id = $("#reject-leave-id").val();
        const reason = $("#rejection_reason").val();

        $.ajax({
            url: `/admin/leaves/${id}/reject`,
            type: "POST",
            data: { rejection_reason: reason },
            success: function(res) {
                if(res.success) {
                    alert(res.message);
                    $("#rejectLeaveModal").modal('hide');
                    $(`#status-cell-${id}`).html(`
                        <span class="badge bg-danger"><i class="bi bi-x-circle"></i> Rejected</span>
                        <small class="d-block text-danger fs-8 mt-1" style="max-width: 150px;">Reason: ${reason}</small>
                    `);
                    $(`#action-cell-${id}`).html('<span class="text-muted small">Reviewed</span>');
                } else {
                    alert("Failed to reject leave.");
                }
            },
            error: function() {
                alert("A connection error occurred.");
            }
        });
    });
</script>
@endsection
