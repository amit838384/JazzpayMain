@extends('layouts.app')
@section('content')
<div class="container-fluid">

    <div class="card mb-3">
        <div class="card-body">
            <h4 class="mb-0 text-primary fw-bold">Pay For Service Orders</h4>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="card-title mb-0">{{ $sales->total() }} Total Entries</h5>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.pfs.export', ['type' => 'pdf'] + request()->only(['from_date','to_date','student_name','school_id','cafeteria_id'])) }}"
                   class="btn btn-sm btn-soft-danger" title="Export as PDF"><i class="bx bxs-file-pdf fs-16"></i></a>
                <a href="{{ route('admin.pfs.export', ['type' => 'excel'] + request()->only(['from_date','to_date','student_name','school_id','cafeteria_id'])) }}"
                   class="btn btn-sm btn-soft-success" title="Export as Excel"><i class="bx bxs-file fs-16"></i></a>
            </div>
        </div>
        <div class="card-body">

            {{-- Filter bar --}}
            <form method="GET" action="{{ route('admin.pfs_Service') }}">
                <div class="row g-3 mb-3">
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
                        <label class="form-label">Student Name</label>
                        <input type="text" name="student_name" class="form-control"
                               placeholder="Student name" value="{{ request('student_name') }}">
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
                    <div class="col-xl-3 col-md-6 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-primary">Search</button>
                        <a href="{{ route('admin.pfs_Service') }}" class="btn btn-soft-secondary">Clear</a>
                    </div>
                </div>
            </form>

            {{-- Table --}}
            <div class="table-responsive">
                <table class="table table-bordered table-nowrap align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Service Name</th>
                            <th>Student Name</th>
                            <th>School</th>
                            <th>Cafeteria</th>
                            <th>Grade</th>
                            <th>Date</th>
                            <th>Payment Mode</th>
                            <th>Payment Status</th>
                            <th>Credits(QAR)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sales as $row)
                            <tr>
                                <td>{{ $row->id }}</td>
                                <td>{{ $row->service_name ?? '—' }}</td>
                                <td>{{ $row->student_name ?? '—' }}</td>
                                <td>{{ $row->school_name ?? '—' }}</td>
                                <td>{{ $row->cafeteria_name ?? '—' }}</td>
                                <td>{{ $row->grade ?? '—' }}</td>
                                <td>{{ \Carbon\Carbon::parse($row->created_at)->format('d-M-Y') }}</td>
                                <td>
                                    @php
                                        $mode = strtolower($row->payment_mode ?? '');
                                        $modeLabel = match($mode) {
                                            'cash'                         => 'Cash',
                                            'credit', 'creditcard', 'card' => 'Credit Card',
                                            'wallet'                       => 'Wallet',
                                            default                        => ucwords($mode ?: '—'),
                                        };
                                    @endphp
                                    {{ $modeLabel }}
                                </td>
                                <td>
                                    @if($row->payment_status == 1)
                                        <span class="badge bg-success">Success</span>
                                    @elseif($row->payment_status == 2)
                                        <span class="badge bg-danger">Failed</span>
                                    @else
                                        <span class="badge bg-warning">Pending</span>
                                    @endif
                                </td>
                                <td>{{ rtrim(rtrim(number_format($row->credits, 2), '0'), '.') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="10" class="text-center text-muted py-3">No data available</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">{{ $sales->links() }}</div>

        </div>
    </div>

</div>
@endsection
