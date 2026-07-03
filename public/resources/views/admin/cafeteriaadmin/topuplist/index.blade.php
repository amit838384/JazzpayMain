@extends('layouts.app')
@section('content')
<div class="container-fluid">

    {{-- Page header --}}
    <div class="card mb-3">
        <div class="card-body py-3 px-4">
            <h5 class="mb-0 fw-semibold" style="color:#2e2e7a;">Cafeteria Topups</h5>
        </div>
    </div>

    {{-- Total entries --}}
    <div class="mb-2 fw-medium">{{ $data->total() }} Total Entries</div>

    {{-- Search --}}
    <form method="GET" action="{{ route('admin.cafeteria_topuplist') }}">
        <div class="d-flex gap-3 mb-3 flex-wrap align-items-center">
            <div style="max-width:360px; flex:1;">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="bx bx-search text-muted"></i>
                    </span>
                    <input type="text" name="student_name" class="form-control border-start-0"
                           placeholder="Student name" value="{{ request('student_name') }}">
                </div>
            </div>
            <button type="submit" class="btn btn-primary px-4"
                    style="background:#2e2e7a; border-color:#2e2e7a;">Search</button>
            <a href="{{ route('admin.cafeteria_topuplist') }}" class="btn btn-secondary px-4">Clear</a>
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
                            <th>Student Name</th>
                            <th>Cafeteria</th>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data as $row)
                            @php
                                $stu = $student->firstWhere('id', $row->student_id);
                            @endphp
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $stu->student_name ?? '-' }}</td>
                                <td>{{ $cafeteria->cafeteria_name ?? '-' }}</td>
                                <td>
                                    {{ $row->date
                                        ? \Carbon\Carbon::parse($row->date)->format('d-M-Y h:i A')
                                        : '-' }}
                                </td>
                                <td>{{ $row->total_price }}</td>
                                <td>
                                    @if($row->payment_status == 1)
                                        <span class="text-success fw-medium">Success</span>
                                    @elseif($row->payment_status == 2)
                                        <span class="text-danger fw-medium">Failed</span>
                                    @else
                                        <span class="text-warning fw-medium">Pending</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">No records found.</td>
                            </tr>
                        @endforelse

                        {{-- Total Credits row --}}
                        @if($data->count())
                            <tr class="table-light fw-bold">
                                <td colspan="4" class="text-end">Total Credits</td>
                                <td colspan="2">{{ $totalAmount }} QAR</td>
                            </tr>
                        @endif
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