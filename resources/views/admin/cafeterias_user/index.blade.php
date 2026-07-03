@extends('layouts.app')
@section('content')
<div class="container-fluid">

    {{-- Page header --}}
    <div class="card mb-3">
        <div class="card-body py-3 px-4 d-flex align-items-center justify-content-between">
            <h5 class="mb-0 fw-semibold" style="color:#2e2e7a;">Cafeteria Users</h5>
            <button type="button" class="btn fw-medium px-4"
                    style="background:#2e2e7a; border-color:#2e2e7a; color:#fff;"
                    data-bs-toggle="modal" data-bs-target="#addUserModal">
                Invite Cafeteria Users
            </button>
        </div>
    </div>

    {{-- Total entries --}}
    <div class="mb-2 fw-medium">{{ $data->total() }} Total Entries</div>

    {{-- Search + Filter --}}
    <form method="GET" action="{{ route('admin.cafeterias_user') }}">
        <div class="d-flex gap-3 mb-3 flex-wrap">
            <div style="max-width:360px; flex:1;">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="bx bx-search text-muted"></i>
                    </span>
                    <input type="text" name="email" class="form-control border-start-0"
                           placeholder="Email" value="{{ request('email') }}">
                </div>
            </div>
            <div style="min-width:260px;">
                <select name="cafeteria_id" class="form-select" onchange="this.form.submit()">
                    <option value="">Filter By Cafeteria</option>
                    @foreach($cafe as $c)
                        <option value="{{ $c->id }}" {{ request('cafeteria_id') == $c->id ? 'selected' : '' }}>
                            {{ $c->cafeteria_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary px-4"
                    style="background:#2e2e7a; border-color:#2e2e7a;">Search</button>
            <a href="{{ route('admin.cafeterias_user') }}" class="btn btn-secondary px-4">Clear</a>
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
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Cafeteria</th>
                            <th>Invite Code</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data as $row)
                            @php
                                $cafeteriaName = $cafe->firstWhere('id', $row->cafeteria_id)->cafeteria_name ?? 'N/A';
                            @endphp
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $row->name }}</td>
                                <td>{{ $row->email }}</td>
                                <td>{{ $row->role }}</td>
                                <td>{{ $cafeteriaName }}</td>
                                <td>{{ $row->invite_code }}</td>
                                <td>
                                    <form action="{{ route('admin.cafeteriaslist_userchangeStatus', $row->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Are you sure?')">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-sm {{ $row->status == 1 ? 'btn-warning' : 'btn-danger' }}">
                                            {{ $row->status == 1 ? 'Active' : 'Inactive' }}
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

{{-- Add Cafeteria User Modal --}}
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUserModalLabel">Add Cafeteria User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.cafeterias_user_store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Select Cafeteria</label>
                        <select name="cafe_id" class="form-control" required>
                            <option value="">-- Select Cafeteria --</option>
                            @foreach($cafe as $cafeteria)
                                <option value="{{ $cafeteria->id }}">{{ $cafeteria->cafeteria_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">User Email</label>
                        <input type="email" name="email"
                               class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email') }}" placeholder="Enter user email" required>
                        @error('email')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Send Invitation</button>
                </div>
            </form>
        </div>
    </div>
</div>

@if ($errors->any())
<script>
    document.addEventListener('DOMContentLoaded', function () {
        new bootstrap.Modal(document.getElementById('addUserModal')).show();
    });
</script>
@endif
@endsection