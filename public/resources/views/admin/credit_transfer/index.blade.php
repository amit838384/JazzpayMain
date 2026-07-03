@extends('layouts.app')
@section('content')
<div class="container-fluid">

    {{-- Page header --}}
    <div class="card mb-3">
        <div class="card-body py-3 px-4">
            <h5 class="mb-0 fw-semibold" style="color:#2e2e7a;">Credit Transfer</h5>
        </div>
    </div>

    {{-- Total entries --}}
    <div class="mb-2 fw-medium">{{ $data->total() }} Total Entries</div>

    {{-- Filters --}}
    <form method="GET" action="{{ route('admin.credit_transfer') }}">
        <div class="row g-2 mb-3">
            <div class="col-md-3">
                <input type="date" name="from_date" class="form-control"
                       value="{{ request('from_date') }}">
            </div>
            <div class="col-md-3">
                <input type="date" name="to_date" class="form-control"
                       value="{{ request('to_date') }}">
            </div>
            <div class="col-md-3">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="bx bx-search text-muted"></i>
                    </span>
                    <input type="text" name="student_name" class="form-control border-start-0"
                           placeholder="Student Name" value="{{ request('student_name') }}">
                </div>
            </div>
            <div class="col-md-3">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="bx bx-search text-muted"></i>
                    </span>
                    <input type="text" name="parent_name" class="form-control border-start-0"
                           placeholder="Parent Name" value="{{ request('parent_name') }}">
                </div>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="">Status</option>
                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Success</option>
                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Pending</option>
                    <option value="2" {{ request('status') === '2' ? 'selected' : '' }}>Failed</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="transaction_type" class="form-select" onchange="this.form.submit()">
                    <option value="">Transaction Type</option>
                    <option value="Parent Transfer" {{ request('transaction_type') === 'Parent Transfer' ? 'selected' : '' }}>Parent Transfer</option>
                    <option value="Refund"          {{ request('transaction_type') === 'Refund'          ? 'selected' : '' }}>Refund</option>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100"
                        style="background:#2e2e7a; border-color:#2e2e7a;">Search</button>
                <a href="{{ route('admin.credit_transfer') }}" class="btn btn-secondary w-100">Clear</a>
            </div>
        </div>
    </form>

    {{-- Table --}}
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Transaction ID</th>
                            <th>Student Name</th>
                            <th>Parent Name</th>
                            <th>Amount</th>
                            <th>Transaction Type</th>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data as $row)
                            @php
                                $par = $parent->firstWhere('id', $row->parent_id);

                                $statusClass = 'bg-warning';
                                $statusText  = 'Pending';
                                if ($row->wallet_payment_status == 1) {
                                    $statusClass = 'bg-success';
                                    $statusText  = 'Success';
                                } elseif ($row->wallet_payment_status == 2) {
                                    $statusClass = 'bg-danger';
                                    $statusText  = 'Failed';
                                }
                            @endphp
                            <tr>
                                <td>{{ $row->transaction_id }}</td>
                                <td>{{ $row->student_name }}</td>
                                <td>{{ $par->name ?? '-' }}</td>
                                <td>{{ $row->wallet_balance }}</td>
                                <td>{{ $row->transaction_type ?? '-' }}</td>
                                <td>{{ \Carbon\Carbon::parse($row->updated_at)->format('d-M-Y h:i A') }}</td>
                                <td>
                                    <span class="badge {{ $statusClass }}">{{ $statusText }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">No records found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{-- Pagination --}}
            @if($data->hasPages())
                <div class="d-flex justify-content-end px-3 py-2">
                    {{ $data->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>

</div>
@endsection