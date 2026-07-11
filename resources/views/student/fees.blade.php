@extends('layouts.app')

@section('title', 'Manage Tuition Fees')

@section('content')
<div class="row">
    <!-- Unpaid Invoices -->
    <div class="col-lg-7 mb-4">
        <div class="card border-0 shadow-sm rounded-4 p-4 glass-card h-100">
            <h5 class="fw-bold mb-3"><i class="bi bi-receipt text-primary"></i> Pending Tuition Invoices</h5>
            
            <div class="d-flex flex-column gap-3">
                @php $hasUnpaid = false; @endphp
                @foreach($fees as $fee)
                    @if($fee->status !== 'paid')
                        @php $hasUnpaid = true; @endphp
                        <div class="p-3 border border-danger border-opacity-10 rounded-3 bg-danger bg-opacity-5 d-flex justify-content-between align-items-center gap-3">
                            <div>
                                <h6 class="fw-bold mb-1 text-dark">{{ $fee->title }}</h6>
                                <span class="small text-danger d-block mb-1">Due Date: {{ $fee->due_date }}</span>
                                <strong class="fs-5 text-dark">₹{{ number_format($fee->amount, 2) }}</strong>
                            </div>
                            <button class="btn btn-accent rounded-pill px-4" onclick="openCheckoutModal({{ $fee->id }}, '{{ addslashes($fee->title) }}', {{ $fee->amount }})">
                                <i class="bi bi-credit-card-2-front"></i> Pay Now
                            </button>
                        </div>
                    @endif
                @endforeach

                @if(!$hasUnpaid)
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-patch-check-fill text-success fs-1 mb-2"></i>
                        <p class="mb-0">Excellent! All billed invoices have been paid.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Payment Transaction Logs -->
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm rounded-4 p-4 glass-card h-100">
            <h5 class="fw-bold mb-3"><i class="bi bi-journal-text text-success"></i> Transaction Logs</h5>
            
            <div class="d-flex flex-column gap-3" style="max-height: 400px; overflow-y: auto;">
                @forelse($payments as $pay)
                    <div class="p-3 border rounded-3 bg-light d-flex justify-content-between align-items-center">
                        <div>
                            <strong class="d-block text-success">+₹{{ number_format($pay->amount_paid, 2) }}</strong>
                            <small class="text-muted d-block mt-0.5">Method: {{ $pay->payment_method }}</small>
                            <code class="fs-9 text-indigo">{{ $pay->transaction_id }}</code>
                        </div>
                        <a href="{{ route('student.fees.receipt', $pay->id) }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3" target="_blank">
                            <i class="bi bi-file-earmark-arrow-down"></i> Receipt
                        </a>
                    </div>
                @empty
                    <p class="text-muted small text-center py-4">No past payment transaction logs available.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- =========================================================================
     MODAL: Simulated Credit Card Checkout
     ========================================================================= -->
<div class="modal fade" id="checkoutModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 rounded-4 shadow">
            <form id="checkout-form">
                <input type="hidden" name="fee_id" id="checkout-fee-id">
                <div class="modal-header bg-primary text-white" style="background: var(--accent-gradient) !important;">
                    <h5 class="modal-title fw-bold"><i class="bi bi-credit-card"></i> Secured Fee Checkout</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="p-3 bg-light rounded-3 mb-4 d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small d-block">Invoice Billed</span>
                            <strong class="small" id="checkout-title-preview"></strong>
                        </div>
                        <div class="text-end">
                            <span class="text-muted small d-block">Billed Amount</span>
                            <strong class="text-primary fs-5" id="checkout-amount-preview"></strong>
                        </div>
                    </div>

                    <div class="alert alert-danger d-none" id="checkout-error-box"></div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Cardholder Name</label>
                        <input type="text" class="form-control" placeholder="e.g. John Doe" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Credit Card Number</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="bi bi-credit-card-2-back"></i></span>
                            <input type="text" name="card_number" class="form-control" placeholder="4111222233334444" minlength="16" maxlength="16" required>
                        </div>
                    </div>
                    <div class="row g-2">
                        <div class="col-6">
                            <label class="form-label small fw-bold">Expiration Date</label>
                            <input type="text" name="expiry" class="form-control" placeholder="MM/YY" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold">CVV Security Code</label>
                            <input type="text" name="cvv" class="form-control" placeholder="123" minlength="3" maxlength="3" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer p-3 bg-light border-top">
                    <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Authorize Transaction</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Open checkout modal
    function openCheckoutModal(feeId, title, amount) {
        $("#checkout-fee-id").val(feeId);
        $("#checkout-title-preview").text(title);
        $("#checkout-amount-preview").text("₹" + Number(amount).toFixed(2));
        
        $("#checkout-error-box").addClass("d-none").html('');
        $("#checkoutModal").modal('show');
    }

    // Submit Checkout form via AJAX
    $("#checkout-form").submit(function(e) {
        e.preventDefault();
        $("#checkout-error-box").addClass("d-none").html('');
        
        $.ajax({
            url: "{{ route('student.fees.pay') }}",
            type: "POST",
            data: $(this).serialize(),
            success: function(res) {
                if(res.success) {
                    alert(res.message);
                    location.reload();
                } else {
                    $("#checkout-error-box").removeClass("d-none").text(res.message);
                }
            },
            error: function() {
                $("#checkout-error-box").removeClass("d-none").text("An internal error occurred. Please verify credit card number inputs.");
            }
        });
    });
</script>
@endsection
