@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="card shadow-sm rounded-4 border-0">
        <div class="card-body">
            <h5 class="fw-bold mb-3">Café POS Report</h5>
            <p class="text-muted mb-4">{{ count($sales) }} Total Entries</p>

            {{-- Filters --}}
            <form action="{{ route('admin.brand_card_sales') }}" method="GET" class="row g-3 align-items-end mb-4">
                <div class="col-md-3">
                    <label for="from_date" class="form-label">From Date</label>
                    <input type="date" name="from_date" id="from_date" class="form-control"
                        value="">
                </div>

                <div class="col-md-3">
                    <label for="to_date" class="form-label">To Date</label>
                    <input type="date" name="to_date" id="to_date" class="form-control"
                        value="">
                </div>

                <div class="col-md-3">
                    <label for="payment_mode" class="form-label">Payment Mode</label>
                    <select name="payment_mode" id="payment_mode" class="form-select">
                        <option value="">All</option>
                        <option value="Wallet" {{ request('payment_mode') == 'Wallet' ? 'selected' : '' }}>Wallet</option>
                        <option value="Card" {{ request('payment_mode') == 'Card' ? 'selected' : '' }}>Card</option>
                        <option value="Cash" {{ request('payment_mode') == 'Cash' ? 'selected' : '' }}>Cash</option>
                    </select>
                </div>

                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-dark w-50">Search</button>
                    <a href="{{ route('admin.brand_card_sales') }}" class="btn btn-secondary w-50">Clear</a>
                </div>
            </form>

            {{-- Data Table --}}
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Item</th>
                            <th>Student Name</th>
                            <th>School</th>
                            <th>Payment Mode</th>
                            <th>Payment Status</th>
                            <th>Date</th>
                            <th>Credits (QAR)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($sales as $sale)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $sale->item_name ?? '-' }} x {{ $sale->qty ?? '-' }}</td>
                                <td>{{ $sale->stud_name ?? '-' }}</td>
                                <td>{{ $sale->school_name ?? '-' }}</td>
                                <td>{{ $sale->payment_type ?? '-' }}</td>
                                <td>
                                    @if($sale->payment_status == 1)
                                        <span class="badge bg-success">Success</span>
                                    @else
                                        <span class="badge bg-danger">Failed</span>
                                    @endif
                                </td>
                                <td>{{ \Carbon\Carbon::parse($sale->created_at)->format('d M Y h:i A') }}</td>

                                <td>{{ number_format($sale->total_price, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-3">No records found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination if used --}}
            @if(method_exists($sales, 'links'))
                <div class="mt-3">
                    {{ $sales->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
