@extends('layouts.app')

@section('title', 'System Attendance Reports')

@section('content')
<div class="card border-0 shadow-sm rounded-4 p-4 mb-4 glass-card">
    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3 mb-4">
        <h4 class="fw-bold mb-0">Student Attendance Audit Board</h4>
        <button class="btn btn-accent rounded-pill px-4" id="ai-attendance-analysis-btn">
            <i class="bi bi-robot"></i> Run AI Attendance Analysis
        </button>
    </div>

    <!-- Filters -->
    <form action="{{ route('admin.attendance') }}" method="GET" class="mb-4">
        <div class="row g-3">
            <div class="col-md-5">
                <select name="class_id" class="form-select">
                    <option value="">All Academic Classes</option>
                    @foreach($classes as $c)
                        <option value="{{ $c->id }}" {{ request('class_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-5">
                <select name="subject_id" class="form-select">
                    <option value="">All Subjects</option>
                    @foreach($subjects as $sub)
                        <option value="{{ $sub->id }}" {{ request('subject_id') == $sub->id ? 'selected' : '' }}>{{ $sub->name }} ({{ $sub->code }})</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 d-grid">
                <button type="submit" class="btn btn-primary rounded-pill">Filter Sheets</button>
            </div>
        </div>
    </form>

    <!-- AI Output Box -->
    <div class="card border-0 shadow-sm rounded-3 p-4 mb-4 d-none animate__animated animate__fadeIn" id="ai-report-box" style="background: radial-gradient(circle at 10% 20%, rgb(15, 23, 42) 0%, rgb(30, 41, 59) 90%); color:#fff;">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0 fw-bold"><i class="bi bi-stars text-info"></i> AI Attendance & Risk Prediction Insights</h5>
            <button type="button" class="btn-close btn-close-white btn-sm" onclick="$('#ai-report-box').addClass('d-none');"></button>
        </div>
        <div class="fs-8 lh-base text-white-50" id="ai-report-content"></div>
    </div>

    <!-- Table -->
    <div class="table-responsive">
        <table class="table align-middle table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Date</th>
                    <th>Student Name</th>
                    <th>Class</th>
                    <th>Subject</th>
                    <th>Attendance Status</th>
                    <th>Marked By</th>
                </tr>
            </thead>
            <tbody>
                @forelse($records as $rec)
                    <tr>
                        <td><strong>{{ $rec->date }}</strong></td>
                        <td>
                            <div class="fw-bold">{{ $rec->student->user->name ?? 'Unknown' }}</div>
                            <small class="text-muted">Roll: {{ $rec->student->roll_no ?? '' }}</small>
                        </td>
                        <td>{{ $rec->class->name ?? '' }}</td>
                        <td><strong>{{ $rec->subject->code ?? '' }}</strong> <small class="text-muted">({{ $rec->subject->name ?? '' }})</small></td>
                        <td>
                            @if($rec->status === 'present')
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1.5 rounded-pill"><i class="bi bi-check-circle"></i> Present</span>
                            @elseif($rec->status === 'absent')
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-1.5 rounded-pill"><i class="bi bi-x-circle"></i> Absent</span>
                            @else
                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-3 py-1.5 rounded-pill"><i class="bi bi-clock"></i> Late</span>
                            @endif
                        </td>
                        <td><small>{{ $rec->faculty->user->name ?? 'System' }}</small></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">No attendance logs found matching these parameters.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $records->appends(request()->query())->links() }}
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $("#ai-attendance-analysis-btn").click(function() {
            $("#ai-report-box").removeClass("d-none");
            $("#ai-report-content").html(`
                <div class="d-flex align-items-center py-4 justify-content-center">
                    <div class="spinner-border text-info me-3" role="status"></div>
                    <strong>Gemini is analyzing student logs and compiling predictions...</strong>
                </div>
            `);

            $.ajax({
                url: "{{ route('admin.attendance.ai-risk') }}",
                type: "POST",
                success: function(res) {
                    if(res.success) {
                        let formattedText = res.analysis
                            .replace(/\n/g, '<br>')
                            .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
                            .replace(/\*(.*?)\*/g, '<em>$1</em>');
                        $("#ai-report-content").html(formattedText);
                    } else {
                        $("#ai-report-content").html('<p class="text-danger mb-0">AI analysis failed to execute.</p>');
                    }
                },
                error: function() {
                    $("#ai-report-content").html('<p class="text-danger mb-0">A connection timeout occurred with the AI engine.</p>');
                }
            });
        });
    });
</script>
@endsection
