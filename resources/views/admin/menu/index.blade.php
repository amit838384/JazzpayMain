@extends('layouts.app')
@section('content')
<div class="container-fluid">

    {{-- Page header --}}
    <div class="card mb-3">
        <div class="card-body py-3 px-4 d-flex align-items-center justify-content-between">
            <h5 class="mb-0 fw-semibold" style="color:#2e2e7a;">Menu</h5>
            <button type="button" class="btn fw-medium px-4"
                    style="background:#2e2e7a; border-color:#2e2e7a; color:#fff;"
                    data-bs-toggle="modal" data-bs-target="#addMenuModal">
                Add Menu
            </button>
        </div>
    </div>

    {{-- Total entries --}}
    <div class="mb-2 fw-medium">{{ $menu->total() }} Total Entries</div>

    {{-- Filters --}}
    <form method="GET" action="{{ route('admin.menu') }}">
        <div class="d-flex gap-3 mb-3 flex-wrap">
            <div style="min-width:260px;">
                <select name="school_id" class="form-select" onchange="this.form.submit()">
                    <option value="">Select School</option>
                    @foreach($school as $s)
                        <option value="{{ $s->id }}" {{ request('school_id') == $s->id ? 'selected' : '' }}>
                            {{ $s->school_name }}
                        </option>
                    @endforeach
                </select>
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
            <a href="{{ route('admin.menu') }}" class="btn btn-secondary px-4">Clear</a>
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
                            <th>School Name</th>
                            <th>Cafeteria Name</th>
                            <th>Month</th>
                            <th>Year</th>
                            <th>Menu</th>
                            <th>Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($menu as $row)
                            @php
                                $sch = $school->firstWhere('id', $row->school_id);
                                $c   = $cafe->firstWhere('id', $row->cafeteria_id);
                                $monthName = $row->month ? date('F', mktime(0,0,0,(int)$row->month,10)) : '-';
                            @endphp
                            <tr>
                                <td>{{ $row->id }}</td>
                                <td>{{ $sch->school_name ?? '-' }}</td>
                                <td>{{ $c->cafeteria_name ?? '-' }}</td>
                                <td>{{ $monthName }}</td>
                                <td>{{ $row->year }}</td>
                                <td>
                                    @if($row->menu_upload)
                                        <a href="{{ asset($row->menu_upload) }}" target="_blank"
                                           class="text-primary text-decoration-none" style="font-size:.8rem; word-break:break-all;">
                                            {{ Str::limit($row->menu_upload, 50) }}
                                        </a>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    <form action="{{ route('admin.menuchangeStatus', $row->id) }}" method="POST"
                                          onsubmit="return confirm('Are you sure?')">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-sm {{ $row->status == 1 ? 'btn-warning' : 'btn-danger' }}">
                                            {{ $row->status == 1 ? 'Active' : 'Inactive' }}
                                        </button>
                                    </form>
                                </td>
                                <td class="text-center">
                                    {{-- Edit --}}
                                    <button type="button" class="btn btn-sm btn-link text-primary p-1"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editMenuModal{{ $row->id }}"
                                            title="Edit">
                                        <i class="bx bx-pencil fs-18"></i>
                                    </button>

                                    {{-- Delete --}}
                                    <form action="{{ route('admin.menu_delete', $row->id) }}" method="POST"
                                          class="d-inline"
                                          onsubmit="return confirm('Are you sure you want to delete this menu?')">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-link text-danger p-1" title="Delete">
                                            <i class="bx bx-trash fs-18"></i>
                                        </button>
                                    </form>

                                    {{-- Edit Modal --}}
                                    <div class="modal fade" id="editMenuModal{{ $row->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <form action="{{ route('admin.menu_update', $row->id) }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                @method('POST')
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Edit Menu</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label">Select School</label>
                                                            <select name="school_id" class="form-select" required>
                                                                <option value="">-- Select School --</option>
                                                                @foreach($school as $s)
                                                                    <option value="{{ $s->id }}" {{ $row->school_id == $s->id ? 'selected' : '' }}>
                                                                        {{ $s->school_name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Select Cafeteria</label>
                                                            <select name="cafeteria_id" class="form-select" required>
                                                                <option value="">-- Select Cafeteria --</option>
                                                                @foreach($cafe as $cs)
                                                                    <option value="{{ $cs->id }}" {{ $row->cafeteria_id == $cs->id ? 'selected' : '' }}>
                                                                        {{ $cs->cafeteria_name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Month</label>
                                                            <select name="month" class="form-control" required>
                                                                <option value="">-- Select Month --</option>
                                                                @for($m = 1; $m <= 12; $m++)
                                                                    <option value="{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}"
                                                                        {{ $row->month == str_pad($m, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>
                                                                        {{ date('F', mktime(0,0,0,$m,10)) }}
                                                                    </option>
                                                                @endfor
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Year</label>
                                                            <select name="year" class="form-control" required>
                                                                <option value="">-- Select Year --</option>
                                                                @for($i = date('Y'); $i >= 2000; $i--)
                                                                    <option value="{{ $i }}" {{ $row->year == $i ? 'selected' : '' }}>{{ $i }}</option>
                                                                @endfor
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label fw-semibold">Upload Menu</label>
                                                            <input type="file" name="menu" class="form-control">
                                                            @if($row->menu_upload)
                                                                <small class="text-muted">Current:
                                                                    <a href="{{ asset($row->menu_upload) }}" target="_blank">View</a>
                                                                </small>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-primary">Update</button>
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
                                <td colspan="8" class="text-center text-muted py-4">No records found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{-- Pagination --}}
            @if($menu->hasPages())
                <div class="d-flex justify-content-end px-3 py-2">
                    {{ $menu->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>

	
	
{{-- Add Menu Modal --}}
<div class="modal fade" id="addMenuModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ secure_url(route('admin.menu_store', [], false)) }}" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Menu</h5>
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
                        <label class="form-label">Select Cafeteria</label>
                        <select name="cafeteria_id" class="form-select" required>
                            <option value="">-- Select Cafeteria --</option>
                            @foreach($cafe as $cs)
                                <option value="{{ $cs->id }}">{{ $cs->cafeteria_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Month</label>
                        <select name="month" class="form-control" required>
                            <option value="">-- Select Month --</option>
                            <option value="01">January</option>
                            <option value="02">February</option>
                            <option value="03">March</option>
                            <option value="04">April</option>
                            <option value="05">May</option>
                            <option value="06">June</option>
                            <option value="07">July</option>
                            <option value="08">August</option>
                            <option value="09">September</option>
                            <option value="10">October</option>
                            <option value="11">November</option>
                            <option value="12">December</option>
                        </select>
                    </div>
                    <div class="mb-3">
						<label class="form-label">Year</label>
						<select name="year" class="form-control" required>
							<option value="">-- Select Year --</option>
							@for($i = date('Y'); $i >= 2000; $i--)
								<option value="{{ $i }}" {{ $i == date('Y') ? 'selected' : '' }}>{{ $i }}</option>
							@endfor
						</select>
					</div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Upload Menu <span class="text-danger">*</span></label>
                        <input type="file" name="menu" class="form-control" required>
                        <small class="text-muted">Accepted: PDF, JPG, PNG. Max: 2MB.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Add</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>



</div>

@endsection