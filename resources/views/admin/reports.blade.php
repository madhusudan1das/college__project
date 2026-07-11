@extends('layouts.app')

@section('title', 'System Reports Generator')

@section('content')
<div class="card border-0 shadow-sm rounded-4 p-5 glass-card">
    <h4 class="fw-bold mb-3"><i class="bi bi-file-earmark-bar-graph text-primary"></i> Analytical Reports Center</h4>
    <p class="text-muted mb-5">Download comma-separated value (CSV) logs compiled directly from active SQL relational rows.</p>
    
    <div class="row g-4">
        <!-- Students -->
        <div class="col-md-4">
            <div class="card border border-light shadow-sm p-4 text-center rounded-3 h-100 bg-light bg-opacity-50">
                <i class="bi bi-people-fill text-primary fs-1 mb-3"></i>
                <h5 class="fw-bold mb-2">Students Registry</h5>
                <p class="text-muted small mb-4">Export name databases, departments, classes, and roll details for active student profiles.</p>
                <a href="{{ route('admin.reports.download', ['type' => 'students']) }}" class="btn btn-primary rounded-pill w-100 py-2">
                    <i class="bi bi-download"></i> Download CSV
                </a>
            </div>
        </div>
        
        <!-- Fees -->
        <div class="col-md-4">
            <div class="card border border-light shadow-sm p-4 text-center rounded-3 h-100 bg-light bg-opacity-50">
                <i class="bi bi-wallet2 text-success fs-1 mb-3"></i>
                <h5 class="fw-bold mb-2">Tuition Billings</h5>
                <p class="text-muted small mb-4">Export list of tuition fee invoices billed, outstanding balances, and status indicators.</p>
                <a href="{{ route('admin.reports.download', ['type' => 'fees']) }}" class="btn btn-success rounded-pill w-100 py-2">
                    <i class="bi bi-download"></i> Download CSV
                </a>
            </div>
        </div>

        <!-- Complaints -->
        <div class="col-md-4">
            <div class="card border border-light shadow-sm p-4 text-center rounded-3 h-100 bg-light bg-opacity-50">
                <i class="bi bi-exclamation-triangle-fill text-danger fs-1 mb-3"></i>
                <h5 class="fw-bold mb-2">Student Grievances</h5>
                <p class="text-muted small mb-4">Export detailed report of student complaints, AI tags, administrative remarks, and status logs.</p>
                <a href="{{ route('admin.reports.download', ['type' => 'complaints']) }}" class="btn btn-danger rounded-pill w-100 py-2">
                    <i class="bi bi-download"></i> Download CSV
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
