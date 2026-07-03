@extends('layouts.app')
@section('content')
<div class="container-fluid">

    {{-- Page header --}}
    <div class="card mb-3">
        <div class="card-body py-3 px-4 d-flex align-items-center justify-content-between">
            <h5 class="mb-0 fw-semibold" style="color:#2e2e7a;">Pre-orders</h5>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.pre_orders') }}?{{ http_build_query(array_merge(request()->query(), ['export' => 'pdf'])) }}"
                   target="_blank"
                   class="btn btn-sm rounded-circle" style="background:#2e2e7a; color:#fff;" title="Export PDF">
                    <i class="bx bxs-file-pdf fs-16"></i>
                </a>
                <a href="{{ route('admin.pre_orders') }}?{{ http_build_query(array_merge(request()->query(), ['export' => 'excel'])) }}"
                   class="btn btn-sm rounded-circle" style="background:#2e2e7a; color:#fff;" title="Export Excel">
                    <i class="bx bxs-file fs-16"></i>
                </a>
            </div>
        </div>
    </div>

    {{-- Total entries --}}
    <div class="mb-2 fw-medium">{{ $data->total() }} Total Entries</div>

    {{-- Filters --}}
    <form method="GET" action="{{ route('admin.pre_orders') }}">
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
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="">Status</option>
                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Success</option>
                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Pending</option>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100"
                        style="background:#2e2e7a; border-color:#2e2e7a;">Search</button>
                <a href="{{ route('admin.pre_orders') }}" class="btn btn-secondary w-100">Clear</a>
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
                            <th>ID</th>
                            <th>Invoice Number</th>
                            <th>Date</th>
                            <th>Dish Name</th>
                            <th>Student Name</th>
                            <th>School</th>
                            <th>Cafeteria</th>
                            <th>Payment Mode</th>
                            <th>Payment Status</th>
                            <th>Credits</th>
                            <th>Card</th>
                            <th>Addons</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data as $row)
                            @php
                                $di  = $dish->firstWhere('id', $row->dish_id);
                                $stu = $student->firstWhere('id', $row->student_id);
                                $sch = $school->firstWhere('id', $row->school_id);
                                $caf = $cafeteria->firstWhere('id', $row->cafeteria_id)
                                    ?? $cafeteria->firstWhere('school_id', $row->school_id);
                            @endphp
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $row->transaction_no ?? '--' }}</td>
                                <td>{{ $row->date }}</td>
                                <td>{{ Str::limit($di->dish_name ?? '-', 15) }}</td>
                                <td>{{ $stu->student_name ?? '-' }}</td>
                                <td>{{ $sch->school_name ?? '-' }}</td>
                                <td>{{ $caf->cafeteria_name ?? '-' }}</td>
                                <td>{{ $row->payment_type ?? '--' }}</td>
                                <td>
                                    @if($row->payment_status == 1)
                                        <span class="text-success fw-medium">Yes</span>
                                    @else
                                        <span class="text-warning fw-medium">No</span>
                                    @endif
                                </td>
                                <td>{{ $row->total_price }}</td>
                                <td>{{ $row->pos_type ?? '--' }}</td>
                                <td>{{ $row->addons ?? '-' }}</td>
                                <td class="text-center">
                                    <div class="d-flex gap-1 justify-content-center">
                                        {{-- Invalid --}}
                                        <button type="button" class="btn btn-danger btn-sm"
                                                data-bs-toggle="modal"
                                                data-bs-target="#invalidModal{{ $row->id }}">
                                            <i class="ri-close-circle-line"></i> Invalid
                                        </button>
                                        {{-- Refund --}}
                                        <button type="button" class="btn btn-warning btn-sm"
                                                data-bs-toggle="modal"
                                                data-bs-target="#refundModal{{ $row->id }}">
                                            <i class="ri-refund-2-line"></i> Refund
                                        </button>
                                        {{-- Print --}}
                                        <button type="button" class="btn btn-secondary btn-sm"
                                                onclick="window.print()">
                                            <i class="ri-printer-line"></i>
                                        </button>
                                    </div>

                                    {{-- Invalid Confirm Modal (inside loop, uses $row & $stu) --}}
                                    <div class="modal fade" id="invalidModal{{ $row->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" style="max-width:480px;">
                                            <form method="POST" action="{{ route('admin.pre_orders_invalid', $row->id) }}">
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

                                    {{-- Refund Confirm Modal (inside loop, uses $row & $stu) --}}
                                    <div class="modal fade" id="refundModal{{ $row->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" style="max-width:480px;">
                                            <form method="POST" action="{{ route('admin.pre_orders_refund', $row->id) }}">
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
                                <td colspan="13" class="text-center text-muted py-4">No records found.</td>
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