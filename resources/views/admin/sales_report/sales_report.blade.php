@extends('layouts.app')
@section('content')
<div class="container-fluid">

    @php
        $fmt = fn($v) => rtrim(rtrim(number_format((float) $v, 2), '0'), '.');
    @endphp

    <div class="card mb-3">
        <div class="card-body">
            <h4 class="mb-0 text-primary fw-bold">Sales Report</h4>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="card-title mb-0">{{ $rows->count() }} Total Entries</h5>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.all_sales.export', ['type'=>'pdf'] + request()->only(['from_date','to_date','school_id','cafeteria_id'])) }}"
                   class="btn btn-sm btn-soft-danger" title="Export as PDF"><i class="bx bxs-file-pdf fs-16"></i></a>
                <a href="{{ route('admin.all_sales.export', ['type'=>'excel'] + request()->only(['from_date','to_date','school_id','cafeteria_id'])) }}"
                   class="btn btn-sm btn-soft-success" title="Export as Excel"><i class="bx bxs-file fs-16"></i></a>
            </div>
        </div>
        <div class="card-body">

            <form method="GET" action="{{ route('admin.all_sales') }}" class="row g-3 mb-3 align-items-end">
                <div class="col-xl-3 col-md-6">
                    <label class="form-label">From Date</label>
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
                    <label class="form-label">Filter By School</label>
                    <select name="school_id" class="form-select">
                        <option value="">All Schools</option>
                        @foreach($schools as $school)
                            <option value="{{ $school->id }}" {{ request('school_id') == $school->id ? 'selected' : '' }}>
                                {{ $school->school_name ?? ('School #'.$school->id) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-xl-3 col-md-6">
                    <label class="form-label">Filter By Cafeteria</label>
                    <select name="cafeteria_id" class="form-select">
                        <option value="">All Cafeterias</option>
                        @foreach($cafeterias as $cafe)
                            <option value="{{ $cafe->id }}" {{ request('cafeteria_id') == $cafe->id ? 'selected' : '' }}>
                                {{ $cafe->cafeteria_name ?? ('Cafeteria #'.$cafe->id) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Search</button>
                    <a href="{{ route('admin.all_sales') }}" class="btn btn-soft-secondary">Clear</a>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered table-nowrap align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Sr No</th><th>Date</th><th>Pre Order</th><th>Cash</th>
                            <th>Credit Card (Pre)</th><th>Credit Card (Onsite)</th><th>Top Used</th>
                            <th>Gross Amount</th><th>Net Amount</th><th>Cafe Topup</th><th>Parent Topup</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rows as $i => $row)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ \Carbon\Carbon::parse($row->date)->format('d-M-Y') }}</td>
                                <td>{{ $fmt($row->pre_order) }}</td>
                                <td>{{ $fmt($row->cash) }}</td>
                                <td>{{ $fmt($row->cc_pre) }}</td>
                                <td>{{ $fmt($row->cc_onsite) }}</td>
                                <td>{{ $fmt($row->top_used) }}</td>
                                <td>{{ $fmt($row->gross_amount) }}</td>
                                <td>{{ $fmt($row->net_amount) }}</td>
                                <td>{{ $fmt($row->cafe_topup) }}</td>
                                <td>{{ $fmt($row->parent_topup) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="11" class="text-center text-muted py-3">No Data Available</td></tr>
                        @endforelse

                        @if($rows->count())
                            <tr class="table-light fw-bold">
                                <td colspan="2">Grand Total</td>
                                <td>{{ $fmt($rows->sum('pre_order')) }}</td>
                                <td>{{ $fmt($rows->sum('cash')) }}</td>
                                <td>{{ $fmt($rows->sum('cc_pre')) }}</td>
                                <td>{{ $fmt($rows->sum('cc_onsite')) }}</td>
                                <td>{{ $fmt($rows->sum('top_used')) }}</td>
                                <td>{{ $fmt($rows->sum('gross_amount')) }}</td>
                                <td>{{ $fmt($rows->sum('net_amount')) }}</td>
                                <td>{{ $fmt($rows->sum('cafe_topup')) }}</td>
                                <td>{{ $fmt($rows->sum('parent_topup')) }}</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection
