@extends('layouts.app')
@section('content')
<div class="container-fluid">

    {{-- Page header --}}
    <div class="card mb-3">
        <div class="card-body py-3 px-4 d-flex align-items-center justify-content-between">
            <h5 class="mb-0 fw-semibold" style="color:#2e2e7a;">Parent</h5>
            <a href="javascript:void(0)" class="btn fw-medium px-4"
               style="background:#2e2e7a; border-color:#2e2e7a; color:#fff;"
               data-bs-toggle="modal" data-bs-target="#inviteModal">
                Invite Parent
            </a>
        </div>
    </div>

    {{-- Total + accepted/sent counts --}}
    <div class="d-flex align-items-center justify-content-between mb-2 flex-wrap gap-2">
        <div class="fw-medium">{{ $data->total() }} Total Entries</div>
        <div class="d-flex gap-3">
            <span class="fw-medium text-success">{{ $accepted }} Accepted</span>
            <span class="fw-medium text-warning">{{ $sent }} Sent</span>
        </div>
    </div>

    {{-- Search + Filter --}}
    <form method="GET" action="{{ route('admin.parents') }}">
        <div class="row g-2 mb-3">
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="bx bx-search text-muted"></i>
                    </span>
                    <input type="text" name="mobile" class="form-control border-start-0"
                           placeholder="Mobile" value="{{ request('mobile') }}">
                </div>
            </div>
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="bx bx-search text-muted"></i>
                    </span>
                    <input type="text" name="name" class="form-control border-start-0"
                           placeholder="Name" value="{{ request('name') }}">
                </div>
            </div>
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="bx bx-search text-muted"></i>
                    </span>
                    <input type="text" name="email" class="form-control border-start-0"
                           placeholder="Email" value="{{ request('email') }}">
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
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4 w-100"
                        style="background:#2e2e7a; border-color:#2e2e7a;">Search</button>
                <a href="{{ route('admin.parents') }}" class="btn btn-secondary px-4 w-100">Clear</a>
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
                            <th>S#</th>
                            <th>Name</th>
                            <th>Mobile</th>
                            <th>Role</th>
                            <th>School</th>
                            <th>Invite Code</th>
                            <th>Sent Date</th>
                            <th>Accepted Date</th>
                            <th>Balance (QAR)</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data as $row)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $row->name }}</td>
                                <td>{{ $row->mobile }}</td>
                                <td>{{ $row->role }}</td>
                                <td>
                                    @foreach ($school as $sch)
                                        @if($row->school_id == $sch->id)
                                            {{ $sch->school_name }}
                                        @endif
                                    @endforeach
                                </td>
                                <td>{{ $row->invite_code }}</td>
                                <td>{{ $row->sent_date }}</td>
                                <td>{{ $row->accepted_date ?? '--' }}</td>
                                <td>{{ $row->topup_balance }}</td>
                                <td>
                                    <form action="{{ route('admin.parentschangeStatus', $row->id) }}" method="POST"
                                          onsubmit="return confirm('Are you sure?')">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-sm {{ $row->status == 1 ? 'btn-warning' : 'btn-danger' }}">
                                            {{ $row->status == 1 ? 'Active' : 'Inactive' }}
                                        </button>
                                    </form>
                                </td>
                                <td>
                                    {{-- Show --}}
                                    <button type="button" class="btn btn-warning btn-sm"
                                            data-bs-toggle="modal" data-bs-target="#showModal{{ $row->id }}">
                                        <i class="bi bi-pencil-square"></i> Show
                                    </button>

                                    <div class="modal fade" id="showModal{{ $row->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Parent Details</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p><span class="fw-bold">Full Name</span>: {{ $row->name }}</p>
                                                    <p><span class="fw-bold">Mobile</span>: {{ $row->mobile }}</p>
                                                    <p><span class="fw-bold">Email</span>: {{ $row->email }}</p>
                                                    <p><span class="fw-bold">Invite Code</span>: {{ $row->invite_code }}</p>
                                                    <p><span class="fw-bold">Sent Date</span>: {{ $row->sent_date }}</p>
                                                    <p><span class="fw-bold">Accepted Date</span>: {{ $row->accepted_date ?? '--' }}</p>
                                                    <p><span class="fw-bold">Balance</span>: {{ $row->topup_balance }}</p>
                                                    <p><span class="fw-bold">Status</span>:
                                                        <span class="{{ $row->status == 1 ? 'text-warning' : 'text-danger' }}">
                                                            {{ $row->status == 1 ? 'Active' : 'Inactive' }}
                                                        </span>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Reset Password --}}
                                    <button type="button" class="btn btn-primary btn-sm"
                                            data-bs-toggle="modal" data-bs-target="#editModal{{ $row->id }}">
                                        <i class="bi bi-pencil-square"></i> Reset Password
                                    </button>

                                    {{-- Reset Password Modal (must stay inside the loop, uses $row) --}}
                                    <div class="modal fade" id="editModal{{ $row->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" style="max-width:480px;">
                                            <form method="POST" action="{{ route('admin.parents_reset_password', $row->id) }}">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-content">
                                                    <div class="modal-header border-0">
                                                        <h5 class="modal-title fw-semibold">Reset Password</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p class="mb-3"><span class="fw-bold">Parent</span>: {{ $row->name }} ({{ $row->mobile }})</p>
                                                        <div class="mb-3">
                                                            <label class="form-label">New Password</label>
                                                            <input type="password" name="password" class="form-control"
                                                                   placeholder="Enter new password" minlength="6" required>
                                                        </div>
                                                        <div class="mb-2">
                                                            <label class="form-label">Confirm Password</label>
                                                            <input type="password" name="password_confirmation" class="form-control"
                                                                   placeholder="Confirm new password" minlength="6" required>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer border-0">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-primary"
                                                                style="background:#2e2e7a; border-color:#2e2e7a;">Reset Password</button>
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


    {{-- Invite Parent Modal --}}
    <div class="modal fade" id="inviteModal" tabindex="-1" aria-labelledby="inviteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('admin.parents_store') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="inviteModalLabel">Invite Parent</h5>
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
                            <label class="form-label">Full Name</label>
                            <input type="text" name="name" class="form-control" placeholder="Enter user name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="text" name="email" class="form-control" placeholder="Enter user email" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Mobile No</label>
                            <input type="number" name="mobile" class="form-control" placeholder="Enter user mobile no" required>
                        </div>
                        <div class="mb-3 d-none">
                            <label class="form-label">Balance</label>
                            <input type="hide" name="balance" class="form-control" placeholder="Enter balance" required value="0">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Send Invite</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>

@if ($errors->any())
<script>
    document.addEventListener('DOMContentLoaded', function () {
        new bootstrap.Modal(document.getElementById('inviteModal')).show();
    });
</script>
@endif
@endsection