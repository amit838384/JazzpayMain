@extends('layouts.app')
@section('content')
<div class="container-fluid">

    <div class="card">
        <div class="card-body py-3 px-4">
            <h5 class="mb-0 fw-semibold" style="color:#2e2e7a;">Order Details</h5>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="mb-3">
                        <div class="text-muted small">ID</div>
                        <div class="fw-medium">{{ $order->id }}</div>
                    </div>
                    <div class="mb-3">
                        <div class="text-muted small">Invoice Number</div>
                        <div class="fw-semibold">{{ $order->transaction_no ?? '--' }}</div>
                    </div>
                    <div class="mb-3">
                        <div class="text-muted small">Invoice Date</div>
                        <div class="fw-semibold">
                            {{ $order->date ? \Carbon\Carbon::parse($order->date)->format('d-M-Y h:i A') : '-' }}
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="text-muted small">Student Name</div>
                        <div class="fw-bold">{{ $student->student_name ?? '-' }}</div>
                    </div>
                    <div class="mb-3">
                        <div class="text-muted small">Admission Number</div>
                        <div class="fw-semibold">{{ $student->admission_no ?? '-' }}</div>
                    </div>
                    <div class="mb-0">
                        <div class="text-muted small">Payment Mode</div>
                        <div class="fw-semibold">
                            Paid By {{ ucfirst($order->payment_type ?? '-') }} : QAR {{ number_format($order->payable, 2) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-body p-0">
                    <table class="table table-bordered mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Name</th>
                                <th>Price</th>
                                <th>Qty</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($items as $item)
                                <tr>
                                    <td>{{ $item->dish_name ?? '-' }}</td>
                                    <td>{{ number_format($item->dish_price, 2) }}</td>
                                    <td>{{ $item->qty }}</td>
                                    <td>{{ number_format($item->total_price, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">No items found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-body d-flex flex-wrap gap-4 align-items-center">
            <div>
                <span class="fw-bold">Payment Status</span>:
                @if($order->payment_status == 1)
                    <span class="text-success fw-semibold">Success</span>
                @elseif($order->payment_status == 2)
                    <span class="text-danger fw-semibold">Failed</span>
                @else
                    <span class="text-warning fw-semibold">Pending</span>
                @endif
            </div>
            <div><span class="fw-bold">Total</span>: QAR {{ number_format($order->total_amount, 2) }}</div>
            <div>
                <span class="fw-bold">Discount %</span>: QAR {{ number_format($order->total_amount - $order->after_discount, 2) }}
                ({{ $order->discount }}%)
            </div>
            <div><span class="fw-bold">Payable</span>: QAR {{ number_format($order->payable, 2) }}</div>
        </div>
    </div>

</div>
@endsection