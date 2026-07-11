@extends('layouts.app')

@section('title', 'Manage Tuition Fees')

@section('content')
<div class="row">
    <!-- Generate Fee Invoice Form -->
    <div class="col-lg-4 mb-4">
        <div class="card border-0 shadow-sm rounded-4 p-4 glass-card">
            <h5 class="fw-bold mb-3"><i class="bi bi-plus-circle-fill text-primary"></i> Bill Student Invoice</h5>
            <form action="{{ route('admin.fees.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label small fw-bold">Select Student Profile</label>
                    <select name="student_id" class="form-select" required>
                        <option value="">Choose Student</option>
                        @foreach($students as $stud)
                            <option value="{{ $stud->id }}">{{ $stud->user->name }} (Roll: {{ $stud->roll_no }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Billing Title</label>
                    <input type="text" name="title" class="form-control" placeholder="e.g. Fall Semester Tuition 2026" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Amount Billed (₹)</label>
                    <input type="number" name="amount" class="form-control" placeholder="1500.00" step="0.01" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Due Date</label>
                    <input type="date" name="due_date" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-accent w-100 py-2">Generate Invoice</button>
            </form>
        </div>
    </div>

    <!-- Active Bills and Payment Transaction Logs -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 glass-card">
            <h5 class="fw-bold mb-3"><i class="bi bi-credit-card-fill text-primary"></i> Billed Invoices List</h5>
            
            <div class="table-responsive" style="max-height: 350px;">
                <table class="table align-middle table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Student</th>
                            <th>Invoice Description</th>
                            <th>Amount</th>
                            <th>Due Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($fees as $f)
                            <tr>
                                <td>
                                    <div class="fw-bold small">{{ $f->student->user->name ?? 'Unknown' }}</div>
                                    <small class="text-muted fs-8">Roll: {{ $f->student->roll_no ?? '' }}</small>
                                </td>
                                <td><span class="small">{{ $f->title }}</span></td>
                                <td><strong>₹{{ number_format($f->amount, 2) }}</strong></td>
                                <td><small>{{ $f->due_date }}</small></td>
                                <td>
                                    @if($f->status === 'paid')
                                        <span class="badge bg-success">Paid</span>
                                    @elseif($f->status === 'partial')
                                        <span class="badge bg-info text-white">Partial</span>
                                    @else
                                        <span class="badge bg-danger">Unpaid</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-3 text-muted small">No invoices billed yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 p-4 glass-card">
            <h5 class="fw-bold mb-3"><i class="bi bi-receipt-cutoff text-success"></i> Transaction Logs</h5>
            
            <div class="table-responsive" style="max-height: 250px;">
                <table class="table align-middle table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Txn ID</th>
                            <th>Student</th>
                            <th>Amount</th>
                            <th>Method</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $pay)
                            <tr>
                                <td><code class="small text-indigo">{{ $pay->transaction_id }}</code></td>
                                <td><span class="small fw-semibold">{{ $pay->student->user->name ?? '' }}</span></td>
                                <td><strong class="text-success">₹{{ number_format($pay->amount_paid, 2) }}</strong></td>
                                <td><small class="text-muted">{{ $pay->payment_method }}</small></td>
                                <td><small class="text-muted">{{ $pay->payment_date }}</small></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-3 text-muted small">No transaction logs logged.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
