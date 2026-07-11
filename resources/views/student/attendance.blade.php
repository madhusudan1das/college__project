@extends('layouts.app')

@section('title', 'My Attendance Report')

@section('content')
<div class="row">
    <!-- Subject Wise Breakdown Cards -->
    <div class="col-lg-4 mb-4">
        <div class="card border-0 shadow-sm rounded-4 p-4 glass-card">
            <h5 class="fw-bold mb-4"><i class="bi bi-pie-chart text-primary"></i> Subject Performance</h5>
            
            <div class="d-flex flex-column gap-4">
                @foreach($subjects as $sub)
                    @php
                        $subAtt = $breakdown->get($sub->id) ?? collect();
                        $present = $subAtt->whereIn('status', ['present', 'late'])->sum('count');
                        $absent = $subAtt->where('status', 'absent')->sum('count');
                        $total = $present + $absent;
                        $rate = $total > 0 ? round(($present / $total) * 100) : 100;
                        
                        $progClass = 'bg-success';
                        if($rate < 75) $progClass = 'bg-danger';
                        elseif($rate < 85) $progClass = 'bg-warning';
                    @endphp
                    <div>
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="fw-bold small">{{ $sub->code }} <small class="text-muted">({{ $sub->name }})</small></span>
                            <span class="fw-bold text-dark small">{{ $rate }}%</span>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar {{ $progClass }}" role="progressbar" style="width: {{ $rate }}%;" aria-valuenow="{{ $rate }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div class="d-flex justify-content-between mt-1 text-muted fs-9">
                            <span>Present: {{ $present }}</span>
                            <span>Absent: {{ $absent }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Attendance logs -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 p-4 glass-card">
            <h4 class="fw-bold mb-4">Daily Attendance Records</h4>
            
            <div class="table-responsive" style="max-height: 450px;">
                <table class="table align-middle table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th>Subject</th>
                            <th>Status</th>
                            <th>Marked By</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($records as $rec)
                            <tr>
                                <td><strong>{{ $rec->date }}</strong></td>
                                <td><span class="fw-bold small">{{ $rec->subject->name ?? '' }}</span> <small class="text-muted">({{ $rec->subject->code ?? '' }})</small></td>
                                <td>
                                    @if($rec->status === 'present')
                                        <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1 rounded-pill">Present</span>
                                    @elseif($rec->status === 'absent')
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-1 rounded-pill">Absent</span>
                                    @else
                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-3 py-1 rounded-pill">Late</span>
                                    @endif
                                </td>
                                <td><small class="text-muted">{{ $rec->faculty->user->name ?? 'System' }}</small></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted small">No class attendance logs registered.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
