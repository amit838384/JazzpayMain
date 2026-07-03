@extends('layouts.app')
@section('content')
<div class="container-fluid">

    {{-- Page header --}}
    <div class="card mb-3">
        <div class="card-body py-3 px-4 d-flex align-items-center justify-content-between">
            <h5 class="mb-0 fw-semibold" style="color:#2e2e7a;">Onsite Sales</h5>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.onsitesales') }}?{{ http_build_query(array_merge(request()->query(), ['export' => 'pdf'])) }}"
                   target="_blank"
                   class="btn btn-sm rounded-circle" style="background:#2e2e7a; color:#fff;" title="Export PDF">
                    <i class="bx bxs-file-pdf fs-16"></i>
                </a>
                <a href="{{ route('admin.onsitesales_excel') }}?{{ http_build_query(request()->query()) }}&t={{ time() }}"
                   class="btn btn-sm rounded-circle" style="background:#2e2e7a; color:#fff;" title="Export Excel">
                    <i class="bx bxs-file fs-16"></i>
                </a>
            </div>
        </div>
    </div>

    {{-- Total entries --}}
    <div class="mb-2 fw-medium">{{ $data->total() }} Total Entries</div>

    {{-- Filters --}}
    <form method="GET" action="{{ route('admin.onsitesales') }}">
        <div class="row g-2 mb-3">
            <div class="col-md-3">
                <input type="date" name="from_date" class="form-control"
                       value="{{ request('from_date') ? \Carbon\Carbon::parse(request('from_date'))->format('Y-m-d') : '' }}">
            </div>
            <div class="col-md-3">
                <input type="date" name="to_date" class="form-control"
                       value="{{ request('to_date') ? \Carbon\Carbon::parse(request('to_date'))->format('Y-m-d') : '' }}">
            </div>
            <div class="col-md-3">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="bx bx-search text-muted"></i>
                    </span>
                    <input type="text" name="parent_number" class="form-control border-start-0"
                           placeholder="Parent number" value="{{ request('parent_number') }}">
                </div>
            </div>
            <div class="col-md-3">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="bx bx-search text-muted"></i>
                    </span>
                    <input type="text" name="student_name" class="form-control border-start-0"
                           placeholder="Student name" value="{{ request('student_name') }}">
                </div>
            </div>
            <div class="col-md-3">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="bx bx-search text-muted"></i>
                    </span>
                    <input type="text" name="invoice_number" class="form-control border-start-0"
                           placeholder="Invoice number" value="{{ request('invoice_number') }}">
                </div>
            </div>
            <div class="col-md-3">
                <select name="school_id" class="form-select" onchange="this.form.submit()">
                    <option value="">Filter By School</option>
                    @foreach($school as $s)
                        <option value="{{ $s->id }}" {{ request('school_id') == $s->id ? 'selected' : '' }}>
                            {{ $s->school_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="cafeteria_id" class="form-select" onchange="this.form.submit()">
                    <option value="">Filter By Cafeteria</option>
                    @foreach($cafeteria as $c)
                        <option value="{{ $c->id }}" {{ request('cafeteria_id') == $c->id ? 'selected' : '' }}>
                            {{ $c->cafeteria_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="payment_mode" class="form-select" onchange="this.form.submit()">
                    <option value="">Payment mode</option>
                    <option value="wallet" {{ request('payment_mode') === 'wallet' ? 'selected' : '' }}>Wallet</option>
                    <option value="cash"   {{ request('payment_mode') === 'cash'   ? 'selected' : '' }}>Cash</option>
                    <option value="pos"    {{ request('payment_mode') === 'pos'    ? 'selected' : '' }}>POS</option>
                </select>
            </div>
            <div class="col-12 d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4"
                        style="background:#2e2e7a; border-color:#2e2e7a;">Search</button>
                <a href="{{ route('admin.onsitesales') }}" class="btn btn-secondary px-4">Clear</a>
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
                            <th>Invoice Number</th>
                            <th>Date</th>
                            <th>Dish Name</th>
                            <th>Student Name</th>
                            <th>School</th>
                            <th>Cafeteria</th>
                            <th>Payment Mode</th>
                            <th>Payment Status</th>
                            <th>Credits (QAR)</th>
                            <th>Card</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data as $row)
                            @php
                                $di  = $dish->firstWhere('id', $row->dish_id);
                                $stu = $student->firstWhere('id', $row->student_id);
                                $sch = $school->firstWhere('id', $row->school_id);
                                $caf = $cafeteria->firstWhere('id', $row->cafeteria_id);
                            @endphp
                            <tr>
                                <td>{{ Str::limit($row->transaction_no ?? '--', 10) }}</td>
                                <td>{{ $row->date }}</td>
                                <td>{{ $di->dish_name ?? '-' }}</td>
                                <td>{{ $stu->student_name ?? '-' }}</td>
                                <td>{{ $sch->school_name ?? '-' }}</td>
                                <td>{{ $caf->cafeteria_name ?? '-' }}</td>
                                <td>{{ $row->payment_type ?? '--' }}</td>
                                <td>
                                    @if($row->payment_status == 1)
                                        <span class="text-success fw-medium">Success</span>
                                    @else
                                        <span class="text-warning fw-medium">Pending</span>
                                    @endif
                                </td>
                                <td>{{ $row->grand_total }}</td>
                                <td>{{ $row->creditcard ? 'Yes' : 'No' }}</td>
                                <td class="text-center">
                                    <div class="dropdown d-inline-block">
                                        <button class="btn btn-sm btn-link text-dark p-1" data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded fs-18"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <button class="dropdown-item text-danger"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#invalidModal{{ $row->id }}">
                                                    <i class="ri-close-circle-line me-1"></i> Invalid
                                                </button>
                                            </li>
                                            <li>
                                                <button class="dropdown-item text-warning"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#refundModal{{ $row->id }}">
                                                    <i class="ri-refund-2-line me-1"></i> Refund
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
                                    <a href="{{ route('admin.onsitesales_view', $row->id) }}" class="btn btn-sm btn-link text-info p-1" title="View">
                                        <i class="ri-eye-line fs-18"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-link text-secondary p-1"
                                            title="Print" onclick="window.print()">
                                        <i class="ri-printer-line fs-18"></i>
                                    </button>

                                    {{-- Invalid Confirm Modal (must stay inside the loop, uses $row & $stu) --}}
                                    <div class="modal fade" id="invalidModal{{ $row->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" style="max-width:480px;">
                                            <form method="POST" action="{{ route('admin.onsitesales_invalid', $row->id) }}">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-content">
                                                    <div class="modal-header border-0">
                                                        <h5 class="modal-title fw-semibold">Confirm</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p class="mb-1">Are you sure you want to mark as invalid?</p>
                                                        <p class="mb-0"><span class="fw-bold">Student Name</span>: {{ $stu->student_name ?? '-' }}</p>
                                                    </div>
                                                    <div class="modal-footer border-0">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                                                        <button type="submit" class="btn btn-primary"
                                                                style="background:#2e2e7a; border-color:#2e2e7a;">Yes</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                    {{-- Refund Confirm Modal (must stay inside the loop, uses $row & $stu) --}}
                                    <div class="modal fade" id="refundModal{{ $row->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" style="max-width:480px;">
                                            <form method="POST" action="{{ route('admin.onsitesales_refund', $row->id) }}">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-content">
                                                    <div class="modal-header border-0">
                                                        <h5 class="modal-title fw-semibold">Confirm</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p class="mb-1">Are you sure you want to refund?</p>
                                                        <p class="mb-0"><span class="fw-bold">Student Name</span>: {{ $stu->student_name ?? '-' }}</p>
                                                    </div>
                                                    <div class="modal-footer border-0">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                                                        <button type="submit" class="btn btn-primary"
                                                                style="background:#2e2e7a; border-color:#2e2e7a;">Yes</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center text-muted py-4">No records found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($data->hasPages())
                <div class="d-flex justify-content-end px-3 py-2">
                    {{ $data->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>

</div>
@endsection