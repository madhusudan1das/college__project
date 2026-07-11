<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Receipt - {{ $payment->receipt->receipt_no ?? '' }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f1f5f9; padding: 40px 15px; }
        .receipt-card { max-width: 650px; margin: 0 auto; background: #fff; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); padding: 40px; border: 1px solid #e2e8f0; }
        .print-btn { position: fixed; top: 20px; right: 20px; z-index: 9999; }
        @media print {
            body { background: #fff; padding: 0; }
            .receipt-card { box-shadow: none; border: none; padding: 0; max-width: 100%; }
            .print-btn { display: none; }
        }
    </style>
</head>
<body>

    <button onclick="window.print()" class="btn btn-primary rounded-pill px-4 print-btn shadow">
        <i class="bi bi-printer"></i> Print Receipt
    </button>

    <div class="receipt-card">
        <!-- Logo -->
        <div class="text-center mb-4 border-bottom pb-4">
            <h3 class="fw-bold mb-1 text-primary">SMART COLLEGE ERP</h3>
            <span class="text-muted small">Official Student Financial Services Transaction Receipt</span>
        </div>

        <div class="row mb-4">
            <div class="col-6">
                <span class="text-muted d-block small">Billed To:</span>
                <strong>{{ $student->user->name }}</strong>
                <span class="d-block small text-muted">Roll No: {{ $student->roll_no }}</span>
                <span class="d-block small text-muted">Class: {{ $student->class->name ?? '' }}</span>
            </div>
            <div class="col-6 text-end">
                <span class="text-muted d-block small">Receipt Number:</span>
                <strong>{{ $payment->receipt->receipt_no ?? '' }}</strong>
                <span class="d-block small text-muted">Date: {{ $payment->payment_date }}</span>
                <span class="d-block small text-muted">Method: {{ $payment->payment_method }}</span>
            </div>
        </div>

        <div class="table-responsive mb-4">
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>Billing Description</th>
                        <th class="text-end">Amount Paid</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <strong class="small">{{ $payment->fee->title }}</strong>
                            <small class="d-block text-muted">Semester Tuition fee balance invoice code: {{ $payment->fee_id }}</small>
                        </td>
                        <td class="text-end fw-bold">₹{{ number_format($payment->amount_paid, 2) }}</td>
                    </tr>
                    <tr class="table-light">
                        <td class="text-end"><strong>Total Settled:</strong></td>
                        <td class="text-end fw-bold text-success">₹{{ number_format($payment->amount_paid, 2) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="p-3 bg-light rounded-3 d-flex justify-content-between align-items-center mb-4">
            <div>
                <span class="text-muted small d-block">Transaction Reference</span>
                <code class="small text-indigo">{{ $payment->transaction_id }}</code>
            </div>
            <span class="badge bg-success px-3 py-1.5 rounded-pill">TRANSACTION SUCCESS</span>
        </div>

        <div class="text-center text-muted small mt-5 pt-3 border-top">
            Thank you for your payment. Please contact administration for any billing queries.
        </div>
    </div>

</body>
</html>
