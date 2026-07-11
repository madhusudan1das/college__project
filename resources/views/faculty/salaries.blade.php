@extends('layouts.app')

@section('title', 'My Payroll Slips')

@section('content')
<div class="card border-0 shadow-sm rounded-4 p-4 glass-card">
    <h4 class="fw-bold mb-4"><i class="bi bi-wallet2 text-primary"></i> Faculty Salary Slip Registry</h4>
    
    <div class="table-responsive">
        <table class="table align-middle table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Billing Period</th>
                    <th>Base Salary</th>
                    <th>Allowances</th>
                    <th>Deductions</th>
                    <th>Net Net Pay</th>
                    <th>Status</th>
                    <th>Payment Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($salaries as $sal)
                    <tr>
                        <td><strong>{{ $sal->created_at->format('F Y') }}</strong></td>
                        <td>₹{{ number_format($sal->base_salary, 2) }}</td>
                        <td><span class="text-success">+₹{{ number_format($sal->bonuses, 2) }}</span></td>
                        <td><span class="text-danger">-₹{{ number_format($sal->deductions, 2) }}</span></td>
                        <td><strong>₹{{ number_format($sal->net_salary, 2) }}</strong></td>
                        <td>
                            @if($sal->status === 'paid')
                                <span class="badge bg-success"><i class="bi bi-check-circle"></i> Paid</span>
                            @else
                                <span class="badge bg-warning text-dark"><i class="bi bi-clock-history"></i> Pending</span>
                            @endif
                        </td>
                        <td>
                            @if($sal->payment_date)
                                <small class="text-muted">{{ $sal->payment_date }}</small>
                            @else
                                <small class="text-muted">Awaiting bank dispatch</small>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted small">No payroll records logged for your profile.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
