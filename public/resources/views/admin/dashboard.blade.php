@extends('layouts.app')
@section('content')
<div class="container-fluid">

    @php
        // Safe fallbacks so the page renders even before the controller passes real data.
        $ssales = $schoolSales      ?? collect();
        $cafs   = $cafeteriaSummary ?? collect();

        // Helper: sum a formatted ("1,234.00") column back to a number.
        $sumCol = function ($collection, $key) {
            return $collection->sum(fn($r) => (float) str_replace(',', '', $r->{$key} ?? 0));
        };
    @endphp

    {{-- ============================================================
         ROW 1 — Schools & Cafeterias counts
    ============================================================ --}}
    <div class="row">
        <div class="col-md-6">
            <div class="card card-animate">
                <div class="card-body text-center py-4">
                    <h2 class="display-6 fw-bold mb-1">
                        <span class="counter-value" data-target="{{ $scount->count() }}">{{ $scount->count() }}</span>
                    </h2>
                    <p class="text-muted fs-15 mb-0">Schools</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-animate">
                <div class="card-body text-center py-4">
                    <h2 class="display-6 fw-bold mb-1">
                        <span class="counter-value" data-target="{{ $ccount->count() }}">{{ $ccount->count() }}</span>
                    </h2>
                    <p class="text-muted fs-15 mb-0">{{ $role == 'superadmin' ? 'Cafeterias' : 'Students' }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================================
         ROW 2 — Dashboard filter bar
    ============================================================ --}}
    <div class="card">
        <div class="card-body">
            <form method="GET" action="{{ url('/admin') }}">
                <div class="row g-3 align-items-end">
                    <div class="col-xl-3 col-md-6">
                        <label class="form-label">Filter By School</label>
                        <select name="school_id" class="form-select">
                            <option value="">All Schools</option>
                            @foreach($scount as $school)
                                <option value="{{ $school->id }}" {{ request('school_id') == $school->id ? 'selected' : '' }}>
                                    {{ $school->name ?? $school->school_name ?? ('School #'.$school->id) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <label class="form-label">Date</label>
                        <input type="text" name="from_date" class="form-control"
                               data-provider="flatpickr" data-date-format="d-M-Y"
                               placeholder="DD-MMM-YYYY" value="{{ request('from_date') }}">
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <label class="form-label">To Date</label>
                        <input type="text" name="to_date" class="form-control"
                               data-provider="flatpickr" data-date-format="d-M-Y"
                               placeholder="DD-MMM-YYYY" value="{{ request('to_date') }}">
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary w-100">Search</button>
                            <a href="{{ url('/admin') }}" class="btn btn-soft-secondary w-100">Clear</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- ============================================================
         ROW 3 — Summary stat cards
    ============================================================ --}}
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card card-animate">
                <div class="card-body">
                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Total Sales</p>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                        <h4 class="fs-22 fw-semibold ff-secondary mb-0">
                            {{ number_format($totalSales ?? 0, 1) }} <small class="text-muted fs-13">QAR</small>
                        </h4>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-soft-primary rounded fs-3">
                                <i class="bx bx-dollar-circle text-primary"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card card-animate">
                <div class="card-body">
                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Total Pre Order Sales</p>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                        <h4 class="fs-22 fw-semibold ff-secondary mb-0">
                            {{ number_format($totalPreOrderSales ?? 0, 1) }} <small class="text-muted fs-13">QAR</small>
                        </h4>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-soft-warning rounded fs-3">
                                <i class="bx bx-package text-warning"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card card-animate">
                <div class="card-body">
                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Total Onsite Sales</p>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                        <h4 class="fs-22 fw-semibold ff-secondary mb-0">
                            {{ number_format($totalOnsiteSales ?? 0, 1) }} <small class="text-muted fs-13">QAR</small>
                        </h4>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-soft-danger rounded fs-3">
                                <i class="bx bx-trending-up text-danger"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card card-animate">
                <div class="card-body">
                    <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Total Topup</p>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                        <h4 class="fs-22 fw-semibold ff-secondary mb-0">
                            {{ number_format($totalTopup ?? 0, 1) }} <small class="text-muted fs-13">QAR</small>
                        </h4>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-soft-info rounded fs-3">
                                <i class="bx bx-wallet text-info"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================================
         ROW 4 — School-wise Sales table
    ============================================================ --}}
    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="card-title mb-0">{{ $ssales->count() }} Total Entries</h5>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.dashboard.export.school', ['type' => 'pdf'] + request()->only(['school_id','from_date','to_date'])) }}"
                class="btn btn-sm btn-soft-danger" title="Export as PDF"><i class="bx bxs-file-pdf fs-16"></i></a>
                <a href="{{ route('admin.dashboard.export.school', ['type' => 'excel'] + request()->only(['school_id','from_date','to_date'])) }}"
                class="btn btn-sm btn-soft-success" title="Export as Excel"><i class="bx bxs-file fs-16"></i></a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-nowrap align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Sr No</th>
                            <th>School</th>
                            <th>Cafeteria</th>
                            <th>Onsite Sales</th>
                            <th>Cafe Topup</th>
                            <th>Parent Topup</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ssales as $i => $row)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $row->school_name }}</td>
                                <td>{{ $row->cafeteria_name }}</td>
                                <td>{{ $row->onsite_sales }}</td>
                                <td>{{ $row->cafe_topup }}</td>
                                <td>{{ $row->parent_topup }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center text-muted py-3">No data available</td></tr>
                        @endforelse

                        @if($ssales->count())
                            <tr class="table-light fw-bold">
                                <td colspan="3">Grand Total</td>
                                <td>{{ number_format($sumCol($ssales, 'onsite_sales'), 2) }}</td>
                                <td>{{ number_format($sumCol($ssales, 'cafe_topup'), 2) }}</td>
                                <td>{{ number_format($sumCol($ssales, 'parent_topup'), 2) }}</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ============================================================
         ROW 5 — Cafeteria Summary report
    ============================================================ --}}
    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="card-title mb-0">{{ $cafs->count() }} Total Entries</h5>
            <span class="text-muted fw-medium">Summary</span>
        </div>
        <div class="card-body">

            {{-- Cafeteria filter bar --}}
            <form method="GET" action="{{ url('/admin') }}" class="mb-3">
                <div class="row g-3 align-items-end">
                    <div class="col-xl-3 col-md-6">
                        <label class="form-label">Filter By Cafeteria</label>
                        <select name="cafeteria_id" class="form-select">
                            <option value="">All Cafeterias</option>
                            @foreach($cafeterias as $cafeteria)
                                <option value="{{ $cafeteria->id }}" {{ request('cafeteria_id') == $cafeteria->id ? 'selected' : '' }}>
                                    {{ $cafeteria->cafeteria_name ?? ('Cafeteria #'.$cafeteria->id) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <label class="form-label">Date</label>
                        <input type="text" name="cafe_from_date" class="form-control"
                               data-provider="flatpickr" data-date-format="d-M-Y"
                               placeholder="DD-MMM-YYYY" value="{{ request('cafe_from_date') }}">
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <label class="form-label">To Date</label>
                        <input type="text" name="cafe_to_date" class="form-control"
                               data-provider="flatpickr" data-date-format="d-M-Y"
                               placeholder="DD-MMM-YYYY" value="{{ request('cafe_to_date') }}">
                    </div>

                    <div class="col-xl-3 col-md-6">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary w-100">Search</button>
                            <a href="{{ url('/admin') }}" class="btn btn-soft-secondary w-100">Clear</a>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.dashboard.export.cafeteria', ['type' => 'pdf'] + request()->only(['cafeteria_id','cafe_from_date','cafe_to_date'])) }}"
                                class="btn btn-soft-danger" title="Export as PDF"><i class="bx bxs-file-pdf fs-16"></i></a>
                            <a href="{{ route('admin.dashboard.export.cafeteria', ['type' => 'excel'] + request()->only(['cafeteria_id','cafe_from_date','cafe_to_date'])) }}"
                                class="btn btn-soft-success" title="Export as Excel"><i class="bx bxs-file fs-16"></i></a>
                        </div>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered table-nowrap align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Sr No</th>
                            <th>Cafeteria</th>
                            <th>Pre Order</th>
                            <th>Cash</th>
                            <th>Credit card</th>
                            <th>Used Topup Amount</th>
                            <th>Gross Amount</th>
                            <th>Discount</th>
                            <th>Return Amount</th>
                            <th>Net Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($cafs as $i => $row)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $row->cafeteria_name ?? '-' }}</td>
                                <td>{{ $row->pre_order }}</td>
                                <td>{{ $row->cash }}</td>
                                <td>{{ $row->credit_card }}</td>
                                <td>{{ $row->used_topup }}</td>
                                <td>{{ $row->gross_amount }}</td>
                                <td>{{ $row->discount }}</td>
                                <td>{{ $row->return_amount }}</td>
                                <td>{{ $row->net_amount }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="10" class="text-center text-muted py-3">No data available</td></tr>
                        @endforelse

                        @if($cafs->count())
                            <tr class="table-light fw-bold">
                                <td colspan="2">Grand Total</td>
                                <td>{{ number_format($sumCol($cafs, 'pre_order'), 2) }}</td>
                                <td>{{ number_format($sumCol($cafs, 'cash'), 2) }}</td>
                                <td>{{ number_format($sumCol($cafs, 'credit_card'), 2) }}</td>
                                <td>{{ number_format($sumCol($cafs, 'used_topup'), 2) }}</td>
                                <td>{{ number_format($sumCol($cafs, 'gross_amount'), 2) }}</td>
                                <td>{{ number_format($sumCol($cafs, 'discount'), 2) }}</td>
                                <td>{{ number_format($sumCol($cafs, 'return_amount'), 2) }}</td>
                                <td>{{ number_format($sumCol($cafs, 'net_amount'), 2) }}</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection
