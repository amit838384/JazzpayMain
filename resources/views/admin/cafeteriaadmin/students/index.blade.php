@extends('layouts.app')
@section('content')
<div class="container-fluid">

    {{-- Page header --}}
    <div class="card mb-3">
        <div class="card-body py-3 px-4 d-flex align-items-center justify-content-between">
            <h5 class="mb-0 fw-semibold" style="color:#2e2e7a;">Students</h5>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.cafeteria_students_export') }}?{{ http_build_query(request()->query()) }}"
                   class="btn btn-sm rounded-circle" style="background:#2e2e7a; color:#fff;" title="Export Excel">
                    <i class="bx bxs-file fs-16"></i>
                </a>
            </div>
        </div>
    </div>

    {{-- Total entries --}}
    <div class="mb-2 fw-medium">{{ $student->total() }} Total Entries</div>

    {{-- Filters --}}
    <form method="GET" action="{{ route('admin.cafeteria_students') }}">
        <div class="row g-2 mb-3">
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
                    <input type="text" name="student_name" class="form-control border-start-0"
                           placeholder="Student name" value="{{ request('student_name') }}">
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
                    <input type="text" name="admission_no" class="form-control border-start-0"
                           placeholder="Admission no" value="{{ request('admission_no') }}">
                </div>
            </div>
            <div class="col-12 d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4"
                        style="background:#2e2e7a; border-color:#2e2e7a;">Search</button>
                <a href="{{ route('admin.cafeteria_students') }}" class="btn btn-secondary px-4">Clear</a>
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
                            <th>Admission No</th>
                            <th>Name</th>
                            <th>Parent</th>
                            <th>Mobile</th>
                            <th>Parent Email</th>
                            <th>School</th>
                            <th>Credits</th>
                            <th>Daily Limit</th>
                            <th>Verified</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($student as $row)
                            @php
                                $par = $parent->firstWhere('id', $row->parent_id);
                                $restricted = isset($restrictedFoods[$row->id])
                                    ? $restrictedFoods[$row->id]->pluck('name')->filter()->implode(', ')
                                    : 'N/A';
                            @endphp
                            <tr>
                                <td>{{ $row->id }}</td>
                                <td>{{ $row->student_name }}</td>
                                <td>{{ $par->name ?? '-' }}</td>
                                <td>{{ $par->mobile ?? '-' }}</td>
                                <td>{{ $par->email ?? '-' }}</td>
                                <td>{{ $school->school_name ?? '-' }}</td>
                                <td>{{ $row->wallet_balance }}</td>
                                <td>{{ $row->spend_limit }}</td>
                                <td>{{ $row->verified }}</td>
                                <td class="text-center1">
                                    {{-- Amount change icon --}}
                                    <a href="javascript:void(0);"
                                       data-bs-toggle="modal"
                                       data-bs-target="#amountchnage{{ $row->id }}"
                                       title="Add Credit">
                                        <i class="ri-exchange-dollar-line" style="font-size:20px;"></i>
                                    </a>

                                    {{-- More Details --}}
                                    <button type="button" class="btn btn-warning btn-sm ms-1"
                                            data-bs-toggle="modal"
                                            data-bs-target="#showModal{{ $row->id }}">
                                        <i class="bi bi-pencil-square"></i> More Details
                                    </button>

                                    {{-- Verify --}}
                                    <button type="button" class="btn btn-primary btn-sm ms-1"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editModal{{ $row->id }}">
                                        <i class="bi bi-pencil-square"></i> Verify
                                    </button>

                                    {{-- Amount Modal --}}
                                    <div class="modal fade" id="amountchnage{{ $row->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" style="max-width:480px;">
                                            <form action="{{ route('admin.cafeteria_store_amount', $row->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-content">
                                                    <div class="modal-header border-0">
                                                        <h5 class="modal-title fw-semibold">Student</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p><span class="fw-bold">Full Name</span>: {{ $row->student_name }}</p>
                                                        <p><span class="fw-bold">Current Credit</span>: {{ $row->wallet_balance }}</p>
                                                        <p><span class="fw-bold">Topup Amount Limit</span>: {{ $row->spend_limit }}</p>
                                                        <input type="hidden" name="student_id" value="{{ $row->id }}">
                                                        <div class="mb-3">
                                                            <label class="form-label">Amount</label>
                                                            <input type="number" class="form-control" name="amount" placeholder="Enter Amount">
                                                        </div>
                                                        <div class="text-center">
                                                            <button type="submit" class="btn btn-success">+ Add</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                    {{-- More Details Modal --}}
                                    <div class="modal fade" id="showModal{{ $row->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" style="max-width:480px;">
                                            <div class="modal-content">
                                                <div class="modal-header border-0">
                                                    <h5 class="modal-title fw-semibold">Student</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p class="mb-2"><span class="fw-bold">Full Name</span>: {{ $row->student_name }}</p>
                                                    <p class="mb-2"><span class="fw-bold">Parent Name</span>: {{ $par->name ?? '-' }}</p>
                                                    <p class="mb-2"><span class="fw-bold">Parent Mobile</span>: {{ $par->mobile ?? '-' }}</p>
                                                    <p class="mb-2"><span class="fw-bold">Parent Email</span>: {{ $par->email ?? '-' }}</p>
                                                    <p class="mb-2"><span class="fw-bold">School Name</span>: {{ $school->school_name ?? '-' }}</p>
                                                    <p class="mb-2"><span class="fw-bold">Grade</span>: {{ $row->grade }}</p>
                                                    <p class="mb-2"><span class="fw-bold">Gender</span>: {{ $row->gender }}</p>
                                                    <p class="mb-2"><span class="fw-bold">Date of Birth</span>: {{ $row->dob }}</p>
                                                    <p class="mb-2"><span class="fw-bold">Spend Limit</span>: {{ $row->spend_limit }}</p>
                                                    <p class="mb-2"><span class="fw-bold">Wallet Balance</span>: {{ $row->wallet_balance }}</p>
                                                    <p class="mb-2"><span class="fw-bold">Verified</span>: {{ $row->verified ? 'Yes' : 'No' }}</p>
                                                    <p class="mb-2"><span class="fw-bold">Restrict Ingredients</span>: {{ $restricted ?: 'N/A' }}</p>
                                                    <p class="mb-0"><span class="fw-bold">Status</span>:
                                                        <span class="{{ $row->status == 1 ? 'text-success' : 'text-danger' }}">
                                                            {{ $row->status == 1 ? 'Active' : 'Inactive' }}
                                                        </span>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Verify Modal --}}
                                    <div class="modal fade" id="editModal{{ $row->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" style="max-width:480px;">
                                            <form method="POST" action="{{ route('admin.students_verify', $row->id) }}">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-content">
                                                    <div class="modal-header border-0">
                                                        <h5 class="modal-title fw-semibold">Verify Student</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p class="mb-2"><span class="fw-bold">Student Name</span>: {{ $row->student_name }}</p>
                                                        <p class="mb-2"><span class="fw-bold">Admission No</span>: {{ $row->admission_no ?? $row->id }}</p>
                                                        <p class="mb-3"><span class="fw-bold">Current Status</span>:
                                                            <span class="{{ $row->verified ? 'text-success' : 'text-warning' }}">
                                                                {{ $row->verified ? 'Verified' : 'Not Verified' }}
                                                            </span>
                                                        </p>
                                                        <div class="mb-2">
                                                            <label class="form-label">Set Verification Status</label>
                                                            <select name="verified" class="form-select" required>
                                                                <option value="1" {{ $row->verified ? 'selected' : '' }}>Yes - Verified</option>
                                                                <option value="0" {{ !$row->verified ? 'selected' : '' }}>No - Not Verified</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer border-0">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-primary"
                                                                style="background:#2e2e7a; border-color:#2e2e7a;">Update</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center text-muted py-4">No records found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{-- Pagination --}}
            @if($student->hasPages())
                <div class="d-flex justify-content-end px-3 py-2">
                    {{ $student->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>

</div>
@endsection