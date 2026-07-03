@extends('layouts.app')

@section('content')

<div class="container my-4">
    <div class="card shadow-lg rounded-3">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0 text-light">Order Invoice</h4>
        </div>

        <div class="card-body">
            <div class="row mb-4">
                <!-- Left Side -->
                <div class="col-md-6 border-end">
                    <h6 class="text-muted mb-3">Student / Order Info</h6>
                    <ul class="list-unstyled">
                        <li><strong>ID:</strong> {{ $order->id }}</li>
                        <li><strong>Invoice Number:</strong> {{ $order->transaction_no }}</li>
                        <li><strong>Invoice Date:</strong> {{ \Carbon\Carbon::parse($order->date)->format('d-M-Y h:i A') }}</li>
                        <br>
                        <br>
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
                    <table class="table table-striped table-bordered">
                        <thead class="table-dark">
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
                            $discountPercent = $order->discount; // e.g. 30 means 30%
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
</div>

@endsection


