@extends('layouts.app')
@section('content')
<div class="container-fluid">

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">{{ $sales->total() }} Total Entries</h5>
        </div>
        <div class="card-body">

            {{-- Filter bar --}}
            <form method="GET" action="{{ route('admin.statistics.details') }}">
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
                               placeholder="Student Name" value="{{ request('student_name') }}">
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
                    <div class="col-xl-3 col-md-6">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="success" {{ request('status') == 'success' ? 'selected' : '' }}>Success</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="failed"  {{ request('status') == 'failed'  ? 'selected' : '' }}>Failed</option>
                        </select>
                    </div>
                    <div class="col-xl-6 col-md-12 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-primary">Search</button>
                        <a href="{{ route('admin.statistics.details') }}" class="btn btn-soft-secondary">Clear</a>
                    </div>
                </div>
            </form>

            {{-- Table --}}
            <div class="table-responsive">
                <table class="table table-bordered table-nowrap align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Dish Name</th>
                            <th>Student Name</th>
                            <th>School</th>
                            <th>Cafeteria</th>
                            <th>Order Type</th>
                            <th>Qty</th>
                            <th>Payment Mode</th>
                            <th>Payment Status</th>
                            <th>Date</th>
                            <th>Credits(QAR)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sales as $row)
                            <tr>
                                <td>{{ $row->id }}</td>
                                <td>{{ $row->dish_name ?? '—' }} x {{ $row->qty }}</td>
                                <td>{{ $row->student_name ?? '—' }}</td>
                                <td>{{ $row->school_name ?? '—' }}</td>
                                <td>{{ $row->cafeteria_name ?? '—' }}</td>
                                <td>{{ $row->order_type }}</td>
                                <td>{{ $row->qty }}</td>
                                <td>
                                    @php
                                        $mode = strtolower($row->payment_mode ?? '');
                                        $modeLabel = match($mode) {
                                            'cash'                       => 'Cash',
                                            'credit', 'creditcard', 'card' => 'Credit Card',
                                            'wallet'                     => 'Wallet',
                                            'pos'                        => 'POS',
                                            default                      => ucfirst($mode ?: '—'),
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
                                <td>{{ \Carbon\Carbon::parse($row->created_at)->format('d-M-Y') }}</td>
                                <td>{{ rtrim(rtrim(number_format($row->credits, 2), '0'), '.') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="11" class="text-center text-muted py-3">No data available</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-3">
                {{ $sales->links() }}
            </div>

        </div>
    </div>

</div>
@endsection
