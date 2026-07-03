<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $order->id }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 14px; margin: 20px; }
        .card { border: 1px solid #ddd; border-radius: 8px; overflow: hidden; }
        .card-header { background: #0d6efd; color: #fff; padding: 10px 20px; }
        .card-body { padding: 20px; }
        h4, h6 { margin: 0; }
        .row { display: flex; flex-wrap: wrap; margin: -10px; }
        .col-md-6 { flex: 0 0 50%; max-width: 50%; padding: 10px; }
        .border-end { border-right: 1px solid #ddd; }
        ul { list-style: none; padding: 0; margin: 0; }
        ul li { margin-bottom: 6px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table th, table td { border: 1px solid #ddd; padding: 8px; }
        table th { background: #212529; color: #fff; }
        table td.text-end { text-align: right; }
        table td.text-center { text-align: center; }
        .bg-light { background: #f8f9fa; }
        .border { border: 1px solid #ddd; }
        .rounded { border-radius: 8px; }
        .p-4 { padding: 20px; }
        .text-end { text-align: right; }
        .text-success { color: #198754; }
        .text-primary { color: #0d6efd; }
        .fw-bold { font-weight: bold; }
        .fs-5 { font-size: 1.1rem; }
        .fs-4 { font-size: 1.3rem; }
    </style>
</head>
<body>

<div class="card">
    <div class="card-header">
        <h4>Order Invoice</h4>
    </div>

    <div class="card-body">
        <div class="row mb-4">
            <!-- Left Side -->
            <div class="col-md-6 border-end">
                <h6 class="text-muted mb-3">Student / Order Info</h6>
                <ul>
                    <li><strong>ID:</strong> {{ $order->id }}</li>
                    <li><strong>Invoice Number:</strong> {{ $order->transaction_no }}</li>
                    <li><strong>Invoice Date:</strong> {{ \Carbon\Carbon::parse($order->date)->format('d-M-Y h:i A') }}</li>
                     <li><strong>Student Name:</strong> {{ $student->student_name }}</li>
                    <li><strong>Admission Number:</strong> {{ $student->admission_no ?? '-' }}</li>
                    <li>
                        <strong>Payment Mode:</strong>
                        @if($order->wallet_used > 0)
                            Paid By Wallet : QAR {{ number_format($order->wallet_used,2) }}
                        @else
                            {{ ucfirst($order->payment_type) }}
                        @endif
                    </li>
                </ul>
            </div>

            <!-- Right Side -->
            <div class="col-md-6">
                <h6 class="text-muted mb-3">Order Items</h6>
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th class="text-end">Price</th>
                            <th class="text-center">Qty</th>
                            <th class="text-end">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->details as $detail)
                            <tr>
                                   @foreach($dish as $dis)
                                    @if($dis->id == $detail->dish_id)
                                    <td>{{ $dis->dish_name }}</td>
                                    @endif
                                    @endforeach
                                <td class="text-end">{{ number_format($detail->dish_price,2) }}</td>
                                <td class="text-center">{{ $detail->qty }}</td>
                                <td class="text-end fw-bold">{{ number_format($detail->total_price,2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Footer Summary -->
        <div class="p-4 bg-light border rounded">
            <div class="row">
                <div class="col-md-4">
                    <h6 class="mb-0">Payment Status:</h6>
                    <span class="fs-5 fw-bold text-success">Success</span>
                </div>
                <div class="col-md-8 text-end">
                    @php
                        $total = $order->total_amount;
                        $discountPercent = $order->discount; // percentage
                        $discountAmount = ($total * $discountPercent) / 100;
                        $payable = $total - $discountAmount;
                    @endphp

                    <p class="mb-1 fs-5"><strong>Total:</strong> QAR {{ number_format($total, 2) }}</p>
                    <p class="mb-1 fs-5">
                        <strong>Discount ({{ $discountPercent }}%):</strong> 
                        - QAR {{ number_format($discountAmount, 2) }}
                    </p>
                    <p class="mb-0 fs-4 fw-bold text-primary">
                        Payable: QAR {{ number_format($payable, 2) }}
                    </p>
                </div>
            </div>
        </div>

    </div>
</div>

</body>
</html>
