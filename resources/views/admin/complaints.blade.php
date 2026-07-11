@extends('layouts.app')

@section('title', 'Student Complaints Audit')

@section('content')
<div class="card border-0 shadow-sm rounded-4 p-4 glass-card">
    <h4 class="fw-bold mb-4">Student Grievances Audit Desk</h4>
    
    <div class="table-responsive">
        <table class="table align-middle table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Student Details</th>
                    <th>Grievance Title</th>
                    <th>AI Category Tag</th>
                    <th>AI System Comments</th>
                    <th>Review Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($complaints as $comp)
                    <tr id="row-complaint-{{ $comp->id }}">
                        <td>
                            <div class="fw-bold">{{ $comp->student->user->name ?? 'Unknown' }}</div>
                            <small class="text-muted">Roll: {{ $comp->student->roll_no ?? '' }}</small>
                        </td>
                        <td>
                            <span class="d-block fw-semibold small">{{ $comp->title }}</span>
                            <p class="mb-0 text-muted fs-8 mt-1" style="max-width: 250px;">{{ $comp->description }}</p>
                        </td>
                        <td>
                            @php
                                $badgeClass = 'bg-secondary';
                                if($comp->category === 'Facilities') $badgeClass = 'bg-danger';
                                elseif($comp->category === 'Fees') $badgeClass = 'bg-warning text-dark';
                                elseif($comp->category === 'Academic') $badgeClass = 'bg-primary';
                            @endphp
                            <span class="badge {{ $badgeClass }} rounded-pill px-3 py-1.5 small"><i class="bi bi-tag-fill me-1"></i> {{ $comp->category ?? 'Others' }}</span>
                        </td>
                        <td>
                            @if($comp->ai_comment)
                                <div class="p-2 border rounded-3 bg-light fs-8 text-muted" style="max-width: 220px; line-height: 1.3;">
                                    <strong><i class="bi bi-robot"></i> AI suggestion:</strong> {{ $comp->ai_comment }}
                                </div>
                            @else
                                <span class="text-muted small">None</span>
                            @endif
                        </td>
                        <td id="status-cell-{{ $comp->id }}">
                            @if($comp->status === 'pending')
                                <span class="badge bg-warning text-dark">Pending</span>
                            @elseif($comp->status === 'in_progress')
                                <span class="badge bg-info text-white">In Progress</span>
                            @else
                                <span class="badge bg-success">Resolved</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <select class="form-select form-select-sm d-inline-block" style="width: auto;" onchange="updateStatus({{ $comp->id }}, this.value)">
                                <option value="pending" {{ $comp->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="in_progress" {{ $comp->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="resolved" {{ $comp->status === 'resolved' ? 'selected' : '' }}>Resolved</option>
                            </select>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">No student complaints logged on the dashboard.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function updateStatus(id, val) {
        $.ajax({
            url: `/admin/complaints/${id}/status`,
            type: "POST",
            data: { status: val },
            success: function(res) {
                if(res.success) {
                    alert(res.message);
                    
                    let badgeHtml = '';
                    if(val === 'pending') badgeHtml = '<span class="badge bg-warning text-dark">Pending</span>';
                    else if(val === 'in_progress') badgeHtml = '<span class="badge bg-info text-white">In Progress</span>';
                    else badgeHtml = '<span class="badge bg-success">Resolved</span>';
                    
                    $(`#status-cell-${id}`).html(badgeHtml);
                } else {
                    alert("Failed to update status.");
                }
            },
            error: function() {
                alert("A connection error occurred.");
            }
        });
    }
</script>
@endsection
