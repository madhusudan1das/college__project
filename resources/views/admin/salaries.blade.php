@extends('layouts.app')

@section('title', 'Manage Faculty Salaries')

@section('content')
<div class="row">
    <!-- Payroll Generator Controls -->
    <div class="col-lg-4 mb-4">
        <div class="card border-0 shadow-sm rounded-4 p-4 glass-card">
            <h5 class="fw-bold mb-3"><i class="bi bi-calendar-check-fill text-primary"></i> Run Monthly Payroll</h5>
            <p class="text-muted small">Generates basic payroll slips for all registered faculty members based on designation coefficients.</p>
            <form action="{{ route('admin.salaries.generate') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label small fw-bold">Select Billing Month</label>
                    <input type="month" name="month" class="form-control" value="{{ date('Y-m') }}" required>
                </div>
                <button type="submit" class="btn btn-accent w-100 py-2">Generate Pay Slips</button>
            </form>
        </div>
    </div>

    <!-- Payroll History List -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 p-4 glass-card">
            <h4 class="fw-bold mb-4">Faculty Payroll Ledger</h4>
            
            <div class="table-responsive">
                <table class="table align-middle table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Faculty Member</th>
                            <th>Base Salary</th>
                            <th>Allowances</th>
                            <th>Net Pay</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($salaries as $sal)
                            <tr id="row-salary-{{ $sal->id }}">
                                <td>
                                    <div class="fw-bold">{{ $sal->faculty->user->name ?? 'Unknown' }}</div>
                                    <small class="text-muted">{{ $sal->faculty->designation ?? '' }}</small>
                                </td>
                                <td>₹{{ number_format($sal->base_salary, 2) }}</td>
                                <td><span class="text-success">+₹{{ number_format($sal->bonuses, 2) }}</span></td>
                                <td><strong>₹{{ number_format($sal->net_salary, 2) }}</strong></td>
                                <td id="status-cell-{{ $sal->id }}">
                                    @if($sal->status === 'paid')
                                        <span class="badge bg-success"><i class="bi bi-cash-coin"></i> Paid</span>
                                        <small class="d-block text-muted fs-8">{{ $sal->payment_date }}</small>
                                    @else
                                        <span class="badge bg-warning text-dark"><i class="bi bi-clock-history"></i> Pending</span>
                                    @endif
                                </td>
                                <td class="text-end" id="action-cell-{{ $sal->id }}">
                                    @if($sal->status === 'pending')
                                        <button class="btn btn-sm btn-primary rounded-pill px-3" onclick="releasePayment({{ $sal->id }})">
                                            <i class="bi bi-bank"></i> Release Payment
                                        </button>
                                    @else
                                        <span class="text-muted small">Completed</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">No monthly salaries generated yet. Choose a month to generate.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function releasePayment(id) {
        if(confirm("Authorize bank transfer and release this salary slip?")) {
            $.ajax({
                url: `/admin/salaries/${id}/pay`,
                type: "POST",
                success: function(res) {
                    if(res.success) {
                        alert(res.message);
                        $(`#status-cell-${id}`).html(`
                            <span class="badge bg-success"><i class="bi bi-cash-coin"></i> Paid</span>
                            <small class="d-block text-muted fs-8">{{ date('Y-m-d') }}</small>
                        `);
                        $(`#action-cell-${id}`).html('<span class="text-muted small">Completed</span>');
                    } else {
                        alert("Transaction declined.");
                    }
                },
                error: function() {
                    alert("A connection error occurred.");
                }
            });
        }
    }
</script>
@endsection
