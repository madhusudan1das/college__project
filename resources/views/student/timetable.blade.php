@extends('layouts.app')

@section('title', 'My Timetable')

@section('content')
<div class="card border-0 shadow-sm rounded-4 p-4 glass-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1"><i class="bi bi-calendar3 text-primary"></i> Class Lecture Schedule</h4>
            <p class="text-muted small mb-0">View your weekly class lecture schedule, topics, assigned faculty instructors, and room locations.</p>
        </div>
    </div>

    @php
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
    @endphp

    <div class="row g-4">
        @foreach($days as $day)
            @php
                $daySlots = $timetable->where('day_of_week', $day);
            @endphp
            @if($daySlots->count() > 0)
                <div class="col-xl-4 col-md-6">
                    <div class="card border-0 shadow-sm h-100 rounded-4 bg-light bg-opacity-50">
                        <div class="card-header border-0 text-white py-3 px-4 rounded-top-4 d-flex justify-content-between align-items-center" style="background: var(--accent-gradient, linear-gradient(135deg, #10b981 0%, #059669 100%)) !important;">
                            <h5 class="fw-bold mb-0"><i class="bi bi-calendar-day"></i> {{ $day }}</h5>
                            <span class="badge bg-white text-success rounded-pill px-2.5 py-1 fw-bold">{{ $daySlots->count() }} Classes</span>
                        </div>
                        <div class="card-body p-4 d-flex flex-column gap-3">
                            @foreach($daySlots as $slot)
                                <div class="p-3 border bg-white rounded-3 shadow-sm d-flex flex-column justify-content-between gap-2 border-start border-4 border-success">
                                    <div>
                                        <div class="d-flex justify-content-between align-items-start">
                                            <span class="badge bg-secondary-subtle text-secondary fw-semibold">{{ $slot->subject->code }}</span>
                                            <small class="text-muted fw-bold"><i class="bi bi-clock"></i> {{ \Carbon\Carbon::parse($slot->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($slot->end_time)->format('h:i A') }}</small>
                                        </div>
                                        <h6 class="fw-bold mt-2 text-dark mb-1">{{ $slot->subject->name }}</h6>
                                        <div class="d-flex align-items-center gap-1.5 mt-2.5">
                                            <span class="text-muted small">Professor:</span>
                                            <strong class="text-dark small"><i class="bi bi-person text-success"></i> {{ $slot->faculty->user->name ?? 'TBA' }}</strong>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center mt-3 text-muted small border-top pt-2">
                                            <span><i class="bi bi-door-open-fill text-success"></i> Room: {{ $slot->room ?? 'N/A' }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        @endforeach

        @if($timetable->count() === 0)
            <div class="col-12 text-center py-5 text-muted">
                <i class="bi bi-calendar-x fs-1 mb-2"></i>
                <p class="mb-0">No lecture schedule slots mapped for your class section.</p>
            </div>
        @endif
    </div>
</div>
@endsection
