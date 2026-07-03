@extends('layouts.app')
@section('content')
<div class="container-fluid">

    {{-- Total entries + export icons --}}
    <div class="d-flex align-items-center justify-content-between mb-2">
        <div class="fw-medium">{{ $topup->total() }} Total Entries</div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.topuplist_export_pdf') }}?{{ http_build_query(request()->query()) }}"
               target="_blank"
               class="btn btn-sm" style="background:#2e2e7a; color:#fff;" title="Export PDF">
                <i class="bx bxs-file-pdf fs-16"></i>
            </a>
            <a href="{{ route('admin.topuplist_export_excel') }}?{{ http_build_query(request()->query()) }}"
               class="btn btn-sm" style="background:#2e2e7a; color:#fff;" title="Export Excel">
                <i class="bx bxs-file fs-16"></i>
            </a>
        </div>
    </div>

    {{-- Filters --}}
    <form method="GET" action="{{ route('admin.topuplist_parents') }}">
        <div class="row g-2 mb-3">
            <div class="col-md-3">
                <input type="date" name="from_date" class="form-control"
                       placeholder="From Date" value="{{ request('from_date') }}">
            </div>
            <div class="col-md-3">
                <input type="date" name="to_date" class="form-control"
                       placeholder="To Date" value="{{ request('to_date') }}">
            </div>
            <div class="col-md-3">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="bx bx-search text-muted"></i>
                    </span>
                    <input type="text" name="mobile" class="form-control border-start-0"
                           placeholder="Mobile" value="{{ request('mobile') }}">
                </div>
            </div>
            <div class="col-md-3">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="bx bx-search text-muted"></i>
                    </span>
                    <input type="text" name="parent_name" class="form-control border-start-0"
                           placeholder="Parent name" value="{{ request('parent_name') }}">
                </div>
            </div>
            <div class="col-md-3">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="bx bx-search text-muted"></i>
                    </span>
                    <input type="text" name="transaction_number" class="form-control border-start-0"
                           placeholder="Transaction number" value="{{ request('transaction_number') }}">
                </div>
            </div>
            <div class="col-md-3">
                <select name="school_id" class="form-select" onchange="this.form.submit()">
                    <option value="">Select School</option>
                    @foreach($school as $s)
                        <option value="{{ $s->id }}" {{ request('school_id') == $s->id ? 'selected' : '' }}>
                            {{ $s->school_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="">Status</option>
                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Pending</option>
                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Success</option>
                    <option value="2" {{ request('status') === '2' ? 'selected' : '' }}>Failed</option>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100"
                        style="background:#2e2e7a; border-color:#2e2e7a;">Search</button>
                <a href="{{ route('admin.topuplist_parents') }}" class="btn btn-secondary w-100">Clear</a>
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
                            <th>Sr No</th>
                            <th>Mobile</th>
                            <th>Name</th>
                            <th>Transaction Number</th>
                            <th>School</th>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($topup as $row)
                            @php
                                $par = $data->firstWhere('id', $row->parent_id);
                                $sch = $par ? $school->firstWhere('id', $par->school_id) : null;

                                $statusClass = 'bg-warning';
                                $statusText  = 'Pending';
                                if ($row->payment_status == 1) {
                                    $statusClass = 'bg-success';
                                    $statusText  = 'Success';
                                } elseif ($row->payment_status == 2) {
                                    $statusClass = 'bg-danger';
                                    $statusText  = 'Failed';
                                }
                            @endphp
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $par->mobile ?? '-' }}</td>
                                <td>{{ $par->name ?? '-' }}</td>
                                <td>{{ $row->transaction_number ?? '-' }}</td>
                                <td>{{ $sch->school_name ?? '-' }}</td>
                                <td>{{ $row->created_at }}</td>
                                <td>{{ $row->amount }}</td>
                                <td>
                                    <span class="badge {{ $statusClass }}">{{ $statusText }}</span>
                                </td>
                                <td>
                                    {{-- Show Change Status button ONLY for Pending (0) or Failed (2) --}}
                                    @if($row->payment_status == 0 || $row->payment_status == 2)
                                        <button type="button" class="btn btn-sm btn-primary"
                                                data-bs-toggle="modal"
                                                data-bs-target="#statusModal{{ $row->id }}">
                                            Change Status
                                        </button>

                                        <div class="modal fade" id="statusModal{{ $row->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <form method="POST" action="{{ route('admin.topuplist_parentschangeStatus') }}">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{ $row->id }}">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Change Status</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label class="form-label">Select Status</label>
                                                                <select class="form-select" name="payment_status" required>
                                                                    <option value="">Payment Status*</option>
                                                                    <option value="1">Success</option>
                                                                    <option value="2">Failed</option>
                                                                </select>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">Transaction Number</label>
                                                                <input type="text" class="form-control"
                                                                       name="transaction_number"
                                                                       value="{{ $row->transaction_number }}" required>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn btn-success">Update</button>
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">No records found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{-- Pagination --}}
            @if($topup->hasPages())
                <div class="d-flex justify-content-end px-3 py-2">
                    {{ $topup->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>

</div>
@endsection