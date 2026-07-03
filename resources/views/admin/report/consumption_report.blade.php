@extends('layouts.app')
@section('content')
<div class="container-fluid">

    {{-- ============ SECTION 1: Student Wallet Balance ============ --}}
    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="card-title mb-0">Student Wallet Balance</h5>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.all_consumption.export', ['section'=>'wallet','type'=>'pdf'] + request()->only(['wallet_school_id'])) }}"
                   class="btn btn-sm btn-soft-danger" title="Export as PDF"><i class="bx bxs-file-pdf fs-16"></i></a>
                <a href="{{ route('admin.all_consumption.export', ['section'=>'wallet','type'=>'excel'] + request()->only(['wallet_school_id'])) }}"
                   class="btn btn-sm btn-soft-success" title="Export as Excel"><i class="bx bxs-file fs-16"></i></a>
            </div>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.all_consumption') }}" class="row g-3 mb-3">
                <div class="col-xl-4 col-md-6">
                    <label class="form-label">Select School</label>
                    <div class="d-flex gap-2">
                        <select name="wallet_school_id" class="form-select" onchange="this.form.submit()">
                            <option value="">Select School</option>
                            @foreach($schools as $school)
                                <option value="{{ $school->id }}" {{ request('wallet_school_id') == $school->id ? 'selected' : '' }}>
                                    {{ $school->school_name ?? ('School #'.$school->id) }}
                                </option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-primary">Search</button>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered table-nowrap align-middle mb-0">
                    <thead class="table-light">
                        <tr><th>Sl No</th><th>Student Name</th><th>Grade</th><th>Admission No</th><th>Wallet Balance (QAR)</th></tr>
                    </thead>
                    <tbody>
                        @forelse($walletStudents as $i => $row)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $row->student_name ?? '—' }}</td>
                                <td>{{ $row->grade ?? '—' }}</td>
                                <td>{{ $row->admission_no ?? '—' }}</td>
                                <td>{{ number_format((float) $row->wallet_balance, 2) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center text-muted py-3">No Data Available</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ============ SECTION 2: Consumption Report ============ --}}
    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="card-title mb-0">Consumption Report</h5>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.all_consumption.export', ['section'=>'consumption','type'=>'pdf'] + request()->only(['cr_date','cr_school_id'])) }}"
                   class="btn btn-sm btn-soft-danger" title="Export as PDF"><i class="bx bxs-file-pdf fs-16"></i></a>
                <a href="{{ route('admin.all_consumption.export', ['section'=>'consumption','type'=>'excel'] + request()->only(['cr_date','cr_school_id'])) }}"
                   class="btn btn-sm btn-soft-success" title="Export as Excel"><i class="bx bxs-file fs-16"></i></a>
            </div>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.all_consumption') }}" class="row g-3 mb-3 align-items-end">
                <div class="col-xl-4 col-md-6">
                    <label class="form-label">Date</label>
                    <input type="text" name="cr_date" class="form-control flatpickr-input"
                           data-provider="flatpickr" data-date-format="d-M-Y"
                           placeholder="DD-MMM-YYYY" value="{{ request('cr_date') }}">
                </div>
                <div class="col-xl-4 col-md-6">
                    <label class="form-label">Select School</label>
                    <select name="cr_school_id" class="form-select">
                        <option value="">Select School</option>
                        @foreach($schools as $school)
                            <option value="{{ $school->id }}" {{ request('cr_school_id') == $school->id ? 'selected' : '' }}>
                                {{ $school->school_name ?? ('School #'.$school->id) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-xl-2 col-md-6">
                    <button type="submit" class="btn btn-primary w-100">Search</button>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered table-nowrap align-middle mb-0">
                    <thead class="table-light">
                        <tr><th>Sl No</th><th>Student Name</th><th>Grade</th><th>Total Quantity</th><th>Total Amount (QAR)</th></tr>
                    </thead>
                    <tbody>
                        @forelse($consumption as $i => $row)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $row->student_name ?? '—' }}</td>
                                <td>{{ $row->grade ?? '—' }}</td>
                                <td>{{ $row->total_qty }}</td>
                                <td>{{ number_format($row->total_amount, 2) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center text-muted py-3">No Data Available</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ============ SECTION 3: Sales Summary Dishes Wise ============ --}}
    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="card-title mb-0">Sales Summary Dishes Wise</h5>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.all_consumption.export', ['section'=>'sales','type'=>'pdf'] + request()->only(['ss_from','ss_to','cafeteria_id'])) }}"
                   class="btn btn-sm btn-soft-danger" title="Export as PDF"><i class="bx bxs-file-pdf fs-16"></i></a>
                <a href="{{ route('admin.all_consumption.export', ['section'=>'sales','type'=>'excel'] + request()->only(['ss_from','ss_to','cafeteria_id'])) }}"
                   class="btn btn-sm btn-soft-success" title="Export as Excel"><i class="bx bxs-file fs-16"></i></a>
            </div>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.all_consumption') }}" class="row g-3 mb-3 align-items-end">
                <div class="col-xl-3 col-md-6">
                    <label class="form-label">Date</label>
                    <input type="text" name="ss_from" class="form-control"
                           data-provider="flatpickr" data-date-format="d-M-Y"
                           placeholder="DD-MMM-YYYY" value="{{ request('ss_from', date('d-M-Y')) }}">
                </div>
                <div class="col-xl-3 col-md-6">
                    <label class="form-label">Date</label>
                    <input type="text" name="ss_to" class="form-control"
                           data-provider="flatpickr" data-date-format="d-M-Y"
                           placeholder="DD-MMM-YYYY" value="{{ request('ss_to', date('d-M-Y')) }}">
                </div>
                <div class="col-xl-3 col-md-6">
                    <label class="form-label">Cafeteria Users</label>
                    <select name="cafeteria_id" class="form-select">
                        <option value="">Select Cafeteria User</option>
                        @foreach($cafeteriaUsers as $cu)
                            <option value="{{ $cu->cafeteria_id }}" {{ request('cafeteria_id') == $cu->cafeteria_id ? 'selected' : '' }}>
                                {{ $cu->name ?? 'N/A' }} ({{ $cu->email ?? 'N/A' }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-xl-3 col-md-6">
                    <button type="submit" class="btn btn-primary w-100">Search</button>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered table-nowrap align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Sl No.</th><th>Dish Id</th><th>Dish Name</th><th>Number of Customers</th>
                            <th>Invoice Quantity</th><th>Total Amount</th><th>Return Quantity</th>
                            <th>Return Amount</th><th>Discount Amount</th><th>Net Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($salesSummary as $i => $row)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $row->dish_id }}</td>
                                <td>{{ $row->dish_name ?? '—' }}</td>
                                <td>{{ $row->num_customers }}</td>
                                <td>{{ $row->invoice_qty }}</td>
                                <td>{{ number_format($row->total_amount, 2) }}</td>
                                <td>0</td>
                                <td>0.00</td>
                                <td>0.00</td>
                                <td>{{ number_format($row->total_amount, 2) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="10" class="text-center text-muted py-3">No Data Available</td></tr>
                        @endforelse

                        @if($salesSummary->count())
                            <tr class="table-light fw-bold">
                                <td colspan="4">Grand Total</td>
                                <td>{{ $salesSummary->sum('invoice_qty') }}</td>
                                <td>{{ number_format($salesSummary->sum('total_amount'), 2) }}</td>
                                <td>0</td><td>0.00</td><td>0.00</td>
                                <td>{{ number_format($salesSummary->sum('total_amount'), 2) }}</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection
