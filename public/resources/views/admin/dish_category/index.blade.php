@extends('layouts.app')
@section('content')
<div class="container-fluid">

    {{-- Page header --}}
    <div class="card mb-3">
        <div class="card-body py-3 px-4 d-flex align-items-center justify-content-between">
            <h5 class="mb-0 fw-semibold" style="color:#2e2e7a;">Dishes Category</h5>
            <button type="button" class="btn fw-medium px-4"
                    style="background:#2e2e7a; border-color:#2e2e7a; color:#fff;"
                    data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                Add Dishes Category
            </button>
        </div>
    </div>

    {{-- Total entries --}}
    <div class="mb-2 fw-medium">{{ $dish->total() }} Total Entries</div>

    {{-- Filters --}}
    <form method="GET" action="{{ route('admin.dish_category') }}">
        <div class="d-flex gap-3 mb-3 flex-wrap">
            <div style="max-width:360px; flex:1;">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="bx bx-search text-muted"></i>
                    </span>
                    <input type="text" name="name" class="form-control border-start-0"
                           placeholder="Name" value="{{ request('name') }}">
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
            <a href="{{ route('admin.dish_category') }}" class="btn btn-secondary px-4">Clear</a>
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
                            <th>Name</th>
                            <th>Meal Type</th>
                            <th>Cafeteria</th>
                            <th>Active</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($dish as $row)
                            @php
                                $cafeName = $cafe->firstWhere('id', $row->cafeteria_id)->cafeteria_name ?? '-';
                            @endphp
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $row->name }}</td>
                                <td>{{ $row->meal_type ?? '-' }}</td>
                                <td>{{ $cafeName }}</td>
                                <td>
                                    <span class="text-{{ $row->status == 1 ? 'success' : 'danger' }}">
                                        {{ $row->status == 1 ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    {{-- Edit pencil --}}
                                    <button type="button" class="btn btn-sm btn-link text-dark p-1"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editModal{{ $row->id }}"
                                            title="Edit">
                                        <i class="bx bx-pencil fs-18"></i>
                                    </button>

                                    {{-- Three-dot kebab --}}
                                    <div class="dropdown d-inline-block">
                                        <button class="btn btn-sm btn-link text-dark p-1" data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded fs-18"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <form action="{{ secure_url(route('admin.dish_categorychangeStatus', $row->id, [], false)) }}"
                                                      method="POST"
                                                      onsubmit="return confirm('Are you sure?')">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="dropdown-item">
                                                        <i class="bx {{ $row->status == 1 ? 'bx-x-circle' : 'bx-check-circle' }} me-1"></i>
                                                        {{ $row->status == 1 ? 'Deactivate' : 'Activate' }}
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>

                                    {{-- Edit Modal --}}
                                    <div class="modal fade" id="editModal{{ $row->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <form action="{{ route('admin.dish_category_update', $row->id) }}" method="POST">
                                                @csrf
                                                @method('POST')
                                                <div class="modal-content">
                                                    <div class="modal-header border-0">
                                                        <h5 class="modal-title fw-semibold">Edit Category</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label">Name</label>
                                                            <input type="text" name="name" class="form-control"
                                                                   value="{{ $row->name }}" placeholder="Enter name" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Meal Type</label>
                                                            <input type="text" name="meal_type" class="form-control"
                                                                   value="{{ $row->meal_type }}" placeholder="Enter meal type">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Select Cafeteria</label>
                                                            <select name="cafeteria_id" class="form-select" required>
                                                                <option value="">-- Select Cafeteria --</option>
                                                                @foreach($cafe as $s)
                                                                    <option value="{{ $s->id }}" {{ $row->cafeteria_id == $s->id ? 'selected' : '' }}>
                                                                        {{ $s->cafeteria_name }}
                                                                    </option>
                                                                @endforeach
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
                                <td colspan="6" class="text-center text-muted py-4">No records found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{-- Pagination --}}
            @if($dish->hasPages())
                <div class="d-flex justify-content-end px-3 py-2">
                    {{ $dish->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>

</div>

{{-- Add Category Modal --}}
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" action="{{ secure_url(route('admin.dish_category_store', [], false)) }}" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-semibold">Add Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" placeholder="Enter name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Meal Type</label>
                        <input type="text" name="meal_type" class="form-control" placeholder="Enter meal type">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Select Cafeteria</label>
                        <select name="cafeteria_id" class="form-select" required>
                            <option value="">-- Select Cafeteria --</option>
                            @foreach($cafe as $s)
                                <option value="{{ $s->id }}">{{ $s->cafeteria_name }}</option>
                            @endforeach
                        </select>
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
@endsection