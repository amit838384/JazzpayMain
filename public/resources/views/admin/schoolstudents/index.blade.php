@extends('layouts.app')
@section('content')
<div class="container-fluid">

    {{-- Page header --}}
    <div class="card mb-3">
        <div class="card-body py-3 px-4 d-flex align-items-center justify-content-between">
            <h5 class="mb-0 fw-semibold" style="color:#2e2e7a;">Students</h5>
            <div class="d-flex gap-2">
                <a href="javascript:void(0)" class="btn fw-medium px-4"
                   style="background:#2e2e7a; border-color:#2e2e7a; color:#fff;"
                   data-bs-toggle="modal" data-bs-target="#inviteModal">
                    Add Student
                </a>
            </div>
        </div>
    </div>

    {{-- Total entries --}}
    <div class="mb-2 fw-medium">{{ $data->total() }} Total Entries</div>

    {{-- Search + Filter --}}
    <form method="GET" action="{{ route('admin.students') }}">
        <div class="row g-2 mb-3">
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="bx bx-search text-muted"></i></span>
                    <input type="text" name="mobile" class="form-control border-start-0"
                           placeholder="Mobile" value="{{ request('mobile') }}">
                </div>
            </div>
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="bx bx-search text-muted"></i></span>
                    <input type="text" name="student_name" class="form-control border-start-0"
                           placeholder="Student name" value="{{ request('student_name') }}">
                </div>
            </div>
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="bx bx-search text-muted"></i></span>
                    <input type="text" name="parent_name" class="form-control border-start-0"
                           placeholder="Parent name" value="{{ request('parent_name') }}">
                </div>
            </div>
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="bx bx-search text-muted"></i></span>
                    <input type="text" name="admission_no" class="form-control border-start-0"
                           placeholder="Admission no" value="{{ request('admission_no') }}">
                </div>
            </div>
            <div class="col-md-4">
                <select name="school_id" class="form-select" onchange="this.form.submit()">
                    <option value="">Filter By School</option>
                    @foreach($school as $s)
                        <option value="{{ $s->id }}" {{ request('school_id') == $s->id ? 'selected' : '' }}>
                            {{ $s->school_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="">Status</option>
                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="col-12 d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4"
                        style="background:#2e2e7a; border-color:#2e2e7a;">Search</button>
                <a href="{{ route('admin.students') }}" class="btn btn-secondary px-4">Clear</a>
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
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data as $row)
                            @php
                                $par = $parent->firstWhere('id', $row->parent_id);
                                $sch = $school->firstWhere('id', $row->school_id);
                                $restricted = isset($restrictedFoods[$row->id])
                                    ? $restrictedFoods[$row->id]->pluck('name')->filter()->implode(', ')
                                    : 'N/A';
                            @endphp
                            <tr>
                                <td>{{ $row->admission_no ?? $row->id }}</td>
                                <td>{{ $row->student_name }}</td>
                                <td>{{ $par->name ?? '-' }}</td>
                                <td>{{ $par->mobile ?? '-' }}</td>
                                <td>{{ $par->email ?? '-' }}</td>
                                <td>{{ $sch->school_name ?? '-' }}</td>
                                <td>{{ $row->wallet_balance }}</td>
                                <td>{{ $row->spend_limit }}</td>
                                <td>{{ $row->verified }}</td>
                                <td>
                                    <form action="{{ route('admin.studentschangeStatus', $row->id) }}" method="POST"
                                          onsubmit="return confirm('Are you sure?')">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-sm {{ $row->status == 1 ? 'btn-warning' : 'btn-danger' }}">
                                            {{ $row->status == 1 ? 'Active' : 'Inactive' }}
                                        </button>
                                    </form>
                                </td>
                                <td>
                                    {{-- More Details button --}}
                                    <button type="button" class="btn btn-warning btn-sm"
                                            data-bs-toggle="modal" data-bs-target="#showModal{{ $row->id }}">
                                        <i class="bi bi-pencil-square"></i> More Details
                                    </button>

                                    {{-- Verify button --}}
                                    <button type="button" class="btn btn-primary btn-sm"
                                            data-bs-toggle="modal" data-bs-target="#editModal{{ $row->id }}">
                                        <i class="bi bi-pencil-square"></i> Verify
                                    </button>

                                    {{-- Edit button --}}
                                    <button type="button" class="btn btn-success btn-sm"
                                            data-bs-toggle="modal" data-bs-target="#editStudentModal{{ $row->id }}">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </button>

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
                                                    <p class="mb-2"><span class="fw-bold">School Name</span>: {{ $sch->school_name ?? '-' }}</p>
                                                    <p class="mb-2"><span class="fw-bold">Grade</span>: {{ $row->grade }}</p>
                                                    <p class="mb-2"><span class="fw-bold">Gender</span>: {{ $row->gender }}</p>
                                                    <p class="mb-2"><span class="fw-bold">Date of Birth</span>: {{ $row->dob }}</p>
                                                    <p class="mb-2"><span class="fw-bold">Spend Limit</span>: {{ $row->spend_limit }}</p>
                                                    <p class="mb-2"><span class="fw-bold">Wallet Balance</span>: {{ $row->wallet_balance }}</p>
                                                    <p class="mb-2"><span class="fw-bold">Verified</span>: {{ $row->verified ? 'Yes' : 'No' }}</p>
                                                    <p class="mb-2"><span class="fw-bold">Restricted Ingredients</span>: {{ $restricted ?: 'N/A' }}</p>
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

                                    {{-- Edit Modal --}}
                                    <div class="modal fade" id="editStudentModal{{ $row->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <form method="POST" action="{{ route('admin.students-update-new', $row->id) }}">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Edit Student</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body text-start">
                                                        <div class="mb-3">
                                                            <label class="form-label">Select School</label>
                                                            <select name="school_id" class="form-select" required>
                                                                <option value="">-- Select School --</option>
                                                                @foreach($school as $s)
                                                                    <option value="{{ $s->id }}" {{ $row->school_id == $s->id ? 'selected' : '' }}>{{ $s->school_name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Select Parent</label>
                                                            <select name="parent_id" class="form-select">
                                                                <option value="">-- Select Parent --</option>
                                                                @foreach($parent as $p)
                                                                    <option value="{{ $p->id }}" {{ $row->parent_id == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Admission No</label>
                                                            <input type="text" name="admission_no" class="form-control" value="{{ $row->admission_no }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Student Name</label>
                                                            <input type="text" name="student_name" class="form-control" value="{{ $row->student_name }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Grade</label>
                                                            <input type="text" name="grade" class="form-control" value="{{ $row->grade }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Gender</label>
                                                            <select name="gender" class="form-select" required>
                                                                <option value="Male"   {{ $row->gender == 'Male'   ? 'selected' : '' }}>Male</option>
                                                                <option value="Female" {{ $row->gender == 'Female' ? 'selected' : '' }}>Female</option>
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Date of Birth</label>
                                                            <input type="text" name="dob" class="form-control" value="{{ $row->dob }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Wallet Balance</label>
                                                            <input type="number" name="wallet_balance" class="form-control" value="{{ $row->wallet_balance }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Spend Limit</label>
                                                            <input type="number" name="spend_limit" class="form-control" value="{{ $row->spend_limit }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Verified</label>
                                                            <select name="verified" class="form-select">
                                                                <option value="1" {{ $row->verified ? 'selected' : '' }}>Yes</option>
                                                                <option value="0" {{ !$row->verified ? 'selected' : '' }}>No</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-primary" style="background:#2e2e7a; border-color:#2e2e7a;">Update</button>
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
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
            {{-- Pagination --}}
            @if($data->hasPages())
                <div class="d-flex justify-content-end px-3 py-2">
                    {{ $data->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- Add Student Modal --}}
    <div class="modal fade" id="inviteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('admin.students_store') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Student</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Select School</label>
                            <select name="school_id" class="form-select" required>
                                <option value="">-- Select School --</option>
                                @foreach($school as $s)
                                    <option value="{{ $s->id }}">{{ $s->school_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Select Parent</label>
                            <select name="parent_id" class="form-select">
                                <option value="">-- Select Parent --</option>
                                @foreach($parent as $p)
                                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Admission No</label>
                            <input type="text" name="admission_no" class="form-control" placeholder="Enter admission no" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Student Name</label>
                            <input type="text" name="student_name" class="form-control" placeholder="Enter student name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Grade</label>
                            <input type="text" name="grade" class="form-control" placeholder="Enter grade" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Gender</label>
                            <select name="gender" class="form-select" required>
                                <option value="">-- Select Gender --</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Date of Birth</label>
                            <input type="date" name="dob" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Wallet Balance</label>
                            <input type="number" name="wallet_balance" class="form-control" placeholder="Enter wallet balance" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Spend Limit</label>
                            <input type="number" name="spend_limit" class="form-control" placeholder="Enter spend limit" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Verified</label>
                            <select name="verified" class="form-select" required>
                                <option value="">-- Select Option --</option>
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
