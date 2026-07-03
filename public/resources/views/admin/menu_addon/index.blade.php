@extends('layouts.app')
@section('content')
<div class="container-fluid">

    {{-- Page header --}}
    <div class="card mb-3">
        <div class="card-body py-3 px-4 d-flex align-items-center justify-content-between">
            <h5 class="mb-0 fw-semibold" style="color:#2e2e7a;">Menu Addon</h5>
            <button type="button" class="btn fw-medium px-4"
                    style="background:#2e2e7a; border-color:#2e2e7a; color:#fff;"
                    data-bs-toggle="modal" data-bs-target="#addAddonModal">
                Add Addon
            </button>
        </div>
    </div>

    {{-- Total entries --}}
    <div class="mb-2 fw-medium">{{ $addons->total() }} Total Entries</div>

    {{-- Filters --}}
    <form method="GET" action="{{ route('admin.menu_addon') }}">
        <div class="row g-2 mb-3">
            <div class="col-md-3">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 small text-muted">From Date</span>
                    <input type="date" name="from_date" class="form-control border-start-0"
                           value="{{ request('from_date') }}">
                </div>
            </div>
            <div class="col-md-3">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 small text-muted">To Date</span>
                    <input type="date" name="to_date" class="form-control border-start-0"
                           value="{{ request('to_date') }}">
                </div>
            </div>
            <div class="col-md-3">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="bx bx-search text-muted"></i>
                    </span>
                    <input type="text" name="name" class="form-control border-start-0"
                           placeholder="Name" value="{{ request('name') }}">
                </div>
            </div>
            <div class="col-md-3">
                <select name="cafeteria_id" class="form-select" onchange="this.form.submit()">
                    <option value="">Filter By Cafeteria</option>
                    @foreach($cafe as $c)
                        <option value="{{ $c->id }}" {{ request('cafeteria_id') == $c->id ? 'selected' : '' }}>
                            {{ $c->cafeteria_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="dish_id" class="form-select" onchange="this.form.submit()">
                    <option value="">Select Dish</option>
                    @foreach($dishes as $d)
                        <option value="{{ $d->id }}" {{ request('dish_id') == $d->id ? 'selected' : '' }}>
                            {{ $d->dish_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100"
                        style="background:#2e2e7a; border-color:#2e2e7a;">Search</button>
                <a href="{{ route('admin.menu_addon') }}" class="btn btn-secondary w-100">Clear</a>
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
                            <th>Name</th>
                            <th>Dish Name</th>
                            <th>Cafeteria</th>
                            <th>Available Dates</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($addons as $row)
                            @php
                                $dishName = $row->dish->dish_name ?? '-';
                                $cafeName = $row->cafeteria->cafeteria_name ?? '-';
                                $dateList = $row->dates->pluck('available_date')
                                    ->map(fn($d) => \Carbon\Carbon::parse($d)->format('Y-m-d'))
                                    ->implode(', ');
                            @endphp
                            <tr>
                                <td>{{ $row->id }}</td>
                                <td>{{ $row->name }}</td>
                                <td>{{ $dishName }}</td>
                                <td>{{ $cafeName }}</td>
                                <td>{{ $dateList ?: '-' }}</td>
                                <td class="text-center">
                                    <div class="dropdown d-inline-block">
                                        <button class="btn btn-sm btn-link text-dark p-1" data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded fs-18"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <button type="button" class="dropdown-item"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#editAddonModal{{ $row->id }}">
                                                    <i class="bx bx-pencil me-1"></i> Edit
                                                </button>
                                            </li>
                                            <li>
                                                <form action="{{ route('admin.menu_addon.change_status', $row->id) }}" method="POST"
                                                      onsubmit="return confirm('Are you sure?')">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="dropdown-item">
                                                        <i class="bx {{ $row->status == 1 ? 'bx-x-circle' : 'bx-check-circle' }} me-1"></i>
                                                        {{ $row->status == 1 ? 'Deactivate' : 'Activate' }}
                                                    </button>
                                                </form>
                                            </li>
                                            <li>
                                                <form action="{{ route('admin.menu_addon.delete', $row->id) }}" method="POST"
                                                      onsubmit="return confirm('Are you sure you want to delete this addon?')">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item text-danger">
                                                        <i class="bx bx-trash me-1"></i> Delete
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>

                                    {{-- Edit Addon Modal --}}
                                    <div class="modal fade" id="editAddonModal{{ $row->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <form action="{{ route('admin.menu_addon.update', $row->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-content">
                                                    <div class="modal-header border-0">
                                                        <h5 class="modal-title fw-semibold">Edit Addon</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label">Select Cafeteria</label>
                                                            <select name="cafeteria_id" class="form-select" required>
                                                                <option value="">-- Select Cafeteria --</option>
                                                                @foreach($cafe as $c)
                                                                    <option value="{{ $c->id }}" {{ $row->cafeteria_id == $c->id ? 'selected' : '' }}>
                                                                        {{ $c->cafeteria_name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Select Dish</label>
                                                            <select name="dish_id" class="form-select" required>
                                                                <option value="">-- Select Dish --</option>
                                                                @foreach($dishes as $d)
                                                                    <option value="{{ $d->id }}" {{ $row->dish_id == $d->id ? 'selected' : '' }}>
                                                                        {{ $d->dish_name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Addon Name</label>
                                                            <input type="text" name="name" class="form-control"
                                                                   value="{{ $row->name }}" placeholder="Enter addon name" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Available Dates</label>

                                                            {{-- Existing dates: locked from editing, but can be removed --}}
                                                            @foreach($row->dates as $dt)
                                                                <div class="d-flex gap-2 mb-2" id="existingDateRow{{ $row->id }}_{{ $loop->index }}">
                                                                    <input type="date" class="form-control" disabled
                                                                           value="{{ \Carbon\Carbon::parse($dt->available_date)->format('Y-m-d') }}">
                                                                    <input type="hidden" name="existing_dates[]"
                                                                           value="{{ \Carbon\Carbon::parse($dt->available_date)->format('Y-m-d') }}">
                                                                    <button type="button" class="btn btn-sm btn-outline-danger"
                                                                            title="Remove this date"
                                                                            onclick="document.getElementById('existingDateRow{{ $row->id }}_{{ $loop->index }}').remove()">
                                                                        <i class="bx bx-trash"></i>
                                                                    </button>
                                                                </div>
                                                            @endforeach

                                                            {{-- New dates can be added here --}}
                                                            <div id="editDatesWrapper{{ $row->id }}"></div>
                                                            <button type="button" class="btn btn-sm btn-outline-primary mt-1"
                                                                    onclick="addDateField('editDatesWrapper{{ $row->id }}')">
                                                                <i class="bx bx-plus"></i> Add Another Date
                                                            </button>
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
                                <td colspan="6" class="text-center text-muted py-4">No records found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{-- Pagination --}}
            @if($addons->hasPages())
                <div class="d-flex justify-content-end px-3 py-2">
                    {{ $addons->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>


    {{-- Add Addon Modal --}}
    <div class="modal fade" id="addAddonModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form method="POST" action="{{ route('admin.menu_addon.store') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header border-0">
                        <h5 class="modal-title fw-semibold">Add Addon</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Select Cafeteria</label>
                            <select name="cafeteria_id" class="form-select" required>
                                <option value="">-- Select Cafeteria --</option>
                                @foreach($cafe as $c)
                                    <option value="{{ $c->id }}">{{ $c->cafeteria_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Select Dish</label>
                            <select name="dish_id" class="form-select" required>
                                <option value="">-- Select Dish --</option>
                                @foreach($dishes as $d)
                                    <option value="{{ $d->id }}">{{ $d->dish_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Addon Name</label>
                            <input type="text" name="name" class="form-control" placeholder="Enter addon name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Available Dates</label>
                            <input type="date" name="dates[]" class="form-control mb-2" required>
                            <div id="addDatesWrapper"></div>
                            <button type="button" class="btn btn-sm btn-outline-primary mt-1"
                                    onclick="addDateField('addDatesWrapper')">
                                <i class="bx bx-plus"></i> Add Another Date
                            </button>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary"
                                style="background:#2e2e7a; border-color:#2e2e7a;">Add</button>
                    </div>
                </div>
            </form>
        </div>
    </div>


</div>



<script>
function addDateField(wrapperId) {
    const wrapper = document.getElementById(wrapperId);
    const div = document.createElement('div');
    div.className = 'd-flex gap-2 mb-2';
    div.innerHTML = `
        <input type="date" name="dates[]" class="form-control" required>
        <button type="button" class="btn btn-sm btn-outline-danger" onclick="this.parentElement.remove()">
            <i class="bx bx-x"></i>
        </button>
    `;
    wrapper.appendChild(div);
}
</script>
@endsection