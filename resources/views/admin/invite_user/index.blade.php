@extends('layouts.app')
@section('content')
<div class="container-fluid">

    {{-- Page header --}}
    <div class="card mb-3">
        <div class="card-body py-3 px-4 d-flex align-items-center justify-content-between">
            <h5 class="mb-0 fw-semibold" style="color:#2e2e7a;">Invite School Users</h5>
            <a href="javascript:void(0)" class="btn fw-medium px-4"
               style="background:#2e2e7a; border-color:#2e2e7a; color:#fff;"
               data-bs-toggle="modal" data-bs-target="#inviteModal">
                Invite School Users
            </a>
        </div>
    </div>

    {{-- Total entries --}}
    <div class="mb-2 fw-medium">{{ $users->total() }} Total Entries</div>

    {{-- Search + Filter --}}
    <form method="GET" action="{{ route('admin.invite_users') }}" id="filterForm">
        <div class="d-flex gap-3 mb-3 flex-wrap">
            {{-- Email search --}}
            <div style="max-width:360px; flex:1;">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="bx bx-search text-muted"></i>
                    </span>
                    <input type="text" name="email" class="form-control border-start-0"
                           placeholder="Email" value="{{ request('email') }}">
                </div>
            </div>
            {{-- Filter By School --}}
            <div style="min-width:260px;">
                <select name="school_id" class="form-select" onchange="this.form.submit()">
                    <option value="">Filter By School</option>
                    @foreach($school as $s)
                        <option value="{{ $s->id }}" {{ request('school_id') == $s->id ? 'selected' : '' }}>
                            {{ $s->school_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            {{-- Search button --}}
            <button type="submit" class="btn btn-primary px-4"
                    style="background:#2e2e7a; border-color:#2e2e7a;">Search</button>
            {{-- Clear --}}
            <a href="{{ route('admin.invite_users') }}" class="btn btn-secondary px-4">Clear</a>
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
                            <th>Email</th>
                            <th>Role</th>
                            <th>School</th>
                            <th>Invite Code</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $row)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $row->email }}</td>
                                <td>{{ $row->role }}</td>
                                <td>
                                    @foreach ($school as $sch)
                                        @if($row->school_id == $sch->id)
                                            {{ $sch->school_name }}
                                        @endif
                                    @endforeach
                                </td>
                                <td>{{ $row->invite_code }}</td>
                                <td>
                                    <button type="button" class="btn btn-sm {{ $row->status == 1 ? 'btn-warning' : 'btn-danger' }}">
                                        {{ $row->status == 1 ? 'Active' : 'Inactive' }}
                                    </button>
                                </td>
                                <td>
                                    <form method="POST" action="{{ route('admin.resend_invite_users_store', $row->email) }}"
                                          onsubmit="return confirm('Are you sure you want to Resend invite?');">
                                        @csrf
                                        <button type="submit" style="height:34px; background:transparent; border:none;">
                                            <i class="ri-arrow-right-s-fill" style="font-size:20px; color:#333;"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">No records found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
				@if($users->hasPages())
					<div class="d-flex justify-content-end px-3 py-2">
						{{ $users->appends(request()->query())->links() }}
					</div>
				@endif
            </div>
        </div>
    </div>

</div>

{{-- Invite Modal --}}
<div class="modal fade" id="inviteModal" tabindex="-1" aria-labelledby="inviteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('admin.invite_users_store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="inviteModalLabel">Invite School User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                        <label class="form-label">User Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email') }}" placeholder="Enter user email" required>
                        @error('email')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-control" placeholder="Enter user name" required>
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

@if ($errors->any())
<script>
    document.addEventListener('DOMContentLoaded', function () {
        new bootstrap.Modal(document.getElementById('inviteModal')).show();
    });
</script>
@endif
@endsection