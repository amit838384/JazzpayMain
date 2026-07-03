@extends('layouts.app')
@section('content')
<div class="container-fluid">

    {{-- Page header --}}
    <div class="card mb-3">
        <div class="card-body py-3 px-4 d-flex align-items-center justify-content-between">
            <h5 class="mb-0 fw-semibold" style="color:#2e2e7a;">Pre-orders</h5>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.cafeteria_pre_orders') }}?{{ http_build_query(array_merge(request()->query(), ['export' => 'pdf'])) }}"
                   target="_blank"
                   class="btn btn-sm rounded-circle" style="background:#2e2e7a; color:#fff;" title="Export PDF">
                    <i class="bx bxs-file-pdf fs-16"></i>
                </a>
                <a href="{{ route('admin.cafeteria_pre_orders') }}?{{ http_build_query(array_merge(request()->query(), ['export' => 'csv'])) }}"
                   class="btn btn-sm rounded-circle" style="background:#2e2e7a; color:#fff;" title="Export CSV">
                    <i class="bx bxs-file fs-16"></i>
                </a>
            </div>
        </div>
    </div>

    {{-- Total entries --}}
    <div class="mb-2 fw-medium">{{ $preorder->total() }} Total Entries</div>

    {{-- Filters --}}
    <form method="GET" action="{{ route('admin.cafeteria_pre_orders') }}">
        <div class="row g-2 mb-3">
            <div class="col-md-3">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 small text-muted">From Date</span>
                    <input type="date" name="fdate" class="form-control border-start-0"
                           value="{{ request('fdate') }}">
                </div>
            </div>
            <div class="col-md-3">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 small text-muted">To Date</span>
                    <input type="date" name="tdate" class="form-control border-start-0"
                           value="{{ request('tdate') }}">
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
                <select name="cafeteria_filter" class="form-select" onchange="this.form.submit()">
                    <option value="">Filter By Cafeteria</option>
                    @foreach($cafeterias as $c)
                        <option value="{{ $c->id }}" {{ request('cafeteria_filter') == $c->id ? 'selected' : '' }}>
                            {{ $c->cafeteria_name }}
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
                    <option value="3" {{ request('status') === '3' ? 'selected' : '' }}>Refunded</option>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100"
                        style="background:#2e2e7a; border-color:#2e2e7a;">Search</button>
                <a href="{{ route('admin.cafeteria_pre_orders') }}" class="btn btn-secondary w-100">Clear</a>
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
                            <th>Dish Name</th>
                            <th>Student Name</th>
                            <th>School</th>
                            <th>Cafeteria</th>
                            <th>Meal Type</th>
                            <th>Note</th>
                            <th>Addons</th>
                            <th>Payment Mode</th>
                            <th>Transa... Number</th>
                            <th>Payment Status</th>
                            <th>Date</th>
                            <th>Credits...</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($preorder as $row)
                            @php
                                $di  = $dish->firstWhere('id', $row->dish_id);
                                $stu = $student->firstWhere('id', $row->student_id);
                                $sch = $school->firstWhere('id', $row->school_id);
                                $cafName = ($cafeteria && $cafeteria->id == $row->cafeteria_id)
                                    ? $cafeteria->cafeteria_name
                                    : ($cafeterias->firstWhere('id', $row->cafeteria_id)->cafeteria_name ?? '-');
                                $cat = $dish_category->firstWhere('id', $di->dish_category_id ?? null);
                            @endphp
                            <tr>
                                <td>{{ $row->id }}</td>
                                <td>{{ Str::limit($di->dish_name ?? '-', 20) }}</td>
                                <td>{{ $stu->student_name ?? '-' }}</td>
                                <td>{{ Str::limit($sch->school_name ?? '-', 15) }}</td>
                                <td>{{ $cafName }}</td>
                                <td>{{ $cat->meal_type ?? $row->meal_type ?? '-' }}</td>
                                <td>{{ $row->note ?? '-' }}</td>
                                <td>{{ Str::limit($row->addons ?? '-', 12) }}</td>
                                <td>{{ $row->payment_type ?? '--' }}</td>
                                <td>{{ $row->transaction_no ?? '--' }}</td>
                                <td>
                                    @if($row->payment_status == 1)
                                        <span class="text-success fw-medium">Success</span>
                                    @elseif($row->payment_status == 2)
                                        <span class="text-danger fw-medium">Failed</span>
                                    @elseif($row->payment_status == 3)
                                        <span class="text-info fw-medium">Refunded</span>
                                    @else
                                        <span class="text-warning fw-medium">Pending</span>
                                    @endif
                                </td>
                                <td>{{ $row->date }}</td>
                                <td>{{ $row->total_price }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="13" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="bx bx-error-circle fs-24 d-block mb-1"></i>
                                        No Data Available
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{-- Pagination --}}
            @if($preorder->hasPages())
                <div class="d-flex justify-content-end px-3 py-2">
                    {{ $preorder->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>

</div>
@endsection