@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="row g-4 mb-4">
    <!-- Total Students KPI -->
    <div class="col-lg-3 col-sm-6">
        <div class="card border-0 shadow-sm p-4 h-100 glass-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted d-block small mb-1 fw-bold">TOTAL STUDENTS</span>
                    <h2 class="mb-0 fw-bold">{{ $stats['total_students'] }}</h2>
                </div>
                <div class="stat-icon" style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);">
                    <i class="bi bi-people-fill"></i>
                </div>
            </div>
            <div class="mt-3">
                <span class="small text-muted"><a href="{{ route('admin.students') }}" class="text-decoration-none text-primary fw-medium"><i class="bi bi-arrow-right"></i> Manage Students</a></span>
            </div>
        </div>
    </div>
    
    <!-- Total Faculty KPI -->
    <div class="col-lg-3 col-sm-6">
        <div class="card border-0 shadow-sm p-4 h-100 glass-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted d-block small mb-1 fw-bold">TOTAL FACULTY</span>
                    <h2 class="mb-0 fw-bold">{{ $stats['total_faculty'] }}</h2>
                </div>
                <div class="stat-icon" style="background: linear-gradient(135deg, #10b981 0%, #047857 100%);">
                    <i class="bi bi-person-workspace"></i>
                </div>
            </div>
            <div class="mt-3">
                <span class="small text-muted"><a href="{{ route('admin.faculty') }}" class="text-decoration-none text-success fw-medium"><i class="bi bi-arrow-right"></i> Manage Faculty</a></span>
            </div>
        </div>
    </div>

    <!-- Active Notices KPI -->
    <div class="col-lg-3 col-sm-6">
        <div class="card border-0 shadow-sm p-4 h-100 glass-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted d-block small mb-1 fw-bold">DEPARTMENTS</span>
                    <h2 class="mb-0 fw-bold">{{ $stats['total_departments'] }}</h2>
                </div>
                <div class="stat-icon" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                    <i class="bi bi-building"></i>
                </div>
            </div>
            <div class="mt-3">
                <span class="small text-muted"><a href="{{ route('admin.departments') }}" class="text-decoration-none text-warning fw-medium"><i class="bi bi-arrow-right"></i> Manage Departments</a></span>
            </div>
        </div>
    </div>

    <!-- Pending Complaints KPI -->
    <div class="col-lg-3 col-sm-6">
        <div class="card border-0 shadow-sm p-4 h-100 glass-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <span class="text-muted d-block small mb-1 fw-bold">PENDING ACTIONS</span>
                    <h2 class="mb-0 fw-bold">{{ $stats['pending_leaves'] + $stats['pending_complaints'] }}</h2>
                </div>
                <div class="stat-icon" style="background: linear-gradient(135deg, #ef4444 0%, #b91c1c 100%);">
                    <i class="bi bi-exclamation-circle-fill"></i>
                </div>
            </div>
            <div class="mt-3 text-muted small">
                <span>{{ $stats['pending_leaves'] }} leaves, {{ $stats['pending_complaints'] }} complaints</span>
            </div>
        </div>
    </div>
</div>

<!-- Financial Stats Summary -->
<div class="card border-0 shadow-sm rounded-4 p-4 mb-4 glass-card">
    <h5 class="fw-bold mb-4"><i class="bi bi-wallet2 text-primary"></i> Tuition Fee Financial Progress</h5>
    <div class="row g-4 text-center">
        <div class="col-md-4 border-end border-light">
            <span class="text-muted small d-block mb-1">TOTAL INVOICED</span>
            <h3 class="fw-bold mb-0 text-dark">₹{{ number_format($stats['total_billed'], 2) }}</h3>
        </div>
        <div class="col-md-4 border-end border-light">
            <span class="text-muted small d-block mb-1">TOTAL COLLECTED</span>
            <h3 class="fw-bold mb-0 text-success">₹{{ number_format($stats['total_collected'], 2) }}</h3>
        </div>
        <div class="col-md-4">
            <span class="text-muted small d-block mb-1">OUTSTANDING OUTSTANDING</span>
            <h3 class="fw-bold mb-0 text-danger">₹{{ number_format($stats['outstanding_fees'], 2) }}</h3>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Department Distribution Chart -->
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm p-4 h-100 glass-card">
            <h5 class="fw-bold mb-4"><i class="bi bi-bar-chart-fill text-primary"></i> Students by Department</h5>
            <div style="height: 300px; position: relative;">
                <canvas id="deptChart"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Attendance Rate Doughnut Chart -->
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm p-4 h-100 glass-card">
            <h5 class="fw-bold mb-4"><i class="bi bi-pie-chart-fill text-primary"></i> Collective Attendance Rate</h5>
            <div style="height: 230px; position: relative;" class="d-flex align-items-center justify-content-center">
                <canvas id="attendanceChart" style="max-height:100%;"></canvas>
            </div>
            <div class="text-center mt-3 small">
                <strong class="text-success fs-5">{{ $attendanceRate }}%</strong> Overall Presence Rate
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // 1. Department Students Bar Chart
        const deptCtx = document.getElementById('deptChart').getContext('2d');
        const deptChart = new Chart(deptCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($chartDepts) !!},
                datasets: [{
                    label: 'No. of Students',
                    data: {!! json_encode($chartStudentCounts) !!},
                    backgroundColor: 'rgba(99, 102, 241, 0.75)',
                    borderColor: 'rgb(99, 102, 241)',
                    borderWidth: 1,
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { precision: 0 }
                    }
                }
            }
        });

        // 2. Attendance Breakdown Doughnut Chart
        const attCtx = document.getElementById('attendanceChart').getContext('2d');
        const attChart = new Chart(attCtx, {
            type: 'doughnut',
            data: {
                labels: ['Present', 'Absent', 'Late'],
                datasets: [{
                    data: [{{ $presentCount }}, {{ $absentCount }}, {{ $lateCount }}],
                    backgroundColor: ['#10b981', '#ef4444', '#f59e0b'],
                    borderWidth: 2,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { boxWidth: 12, padding: 15 }
                    }
                }
            }
        });
    });
</script>
@endsection
