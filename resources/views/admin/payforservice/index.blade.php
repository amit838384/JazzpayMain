@extends('layouts.app')
@section('content')
<div class="container-fluid">

    {{-- Header card --}}
    <div class="card mb-3">
        <div class="card-body d-flex justify-content-between align-items-center">
            <h4 class="mb-0 text-primary fw-bold">Pay For Service List</h4>
            <a href="{{ route('admin.cafeteria_createPlan') }}" class="btn btn-primary">Add Service</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">{{ $plans->total() }} Total Entries</h5>
        </div>
        <div class="card-body">

            {{-- Filter by cafeteria --}}
            <form method="GET" action="{{ route('admin.admin_payforservice') }}" class="row g-3 mb-3">
                <div class="col-xl-3 col-md-6">
                    <select name="cafeteria_id" class="form-select" onchange="this.form.submit()">
                        <option value="">Filter By Cafeteria</option>
                        @foreach($cafeterias as $cafe)
                            <option value="{{ $cafe->id }}" {{ request('cafeteria_id') == $cafe->id ? 'selected' : '' }}>
                                {{ $cafe->cafeteria_name ?? ('Cafeteria #'.$cafe->id) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @if(request('cafeteria_id'))
                    <div class="col-xl-2 col-md-6">
                        <a href="{{ route('admin.admin_payforservice') }}" class="btn btn-soft-secondary w-100">Clear</a>
                    </div>
                @endif
            </form>

            <div class="table-responsive">
                <table class="table table-bordered table-nowrap align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Cafeteria</th>
                            <th>Grade</th>
                            <th>Price</th>
                            <th>Date</th>
                            <th>Terms</th>
                            <th>Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($plans as $plan)
                            <tr>
                                <td>{{ $plan->id }}</td>
                                <td>{{ $plan->name }}</td>
                                <td>{{ $plan->description ?? '-' }}</td>
                                <td>{{ $plan->cafeteria_name ?? '-' }}</td>
                                <td>{{ $plan->grade ?? '-' }}</td>
                                <td>{{ number_format($plan->price, 2) }}</td>
                                <td>
                                    @if(!empty($plan->start_date) && !empty($plan->end_date))
                                        {{ \Carbon\Carbon::parse($plan->start_date)->format('d-M-Y') }}
                                        - {{ \Carbon\Carbon::parse($plan->end_date)->format('d-M-Y') }}
                                    @else
                                        {{ $plan->duration_days }} Days
                                    @endif
                                </td>
                                <td>{{ $plan->terms ?? '-' }}</td>
                                <td>
                                    <a href="{{ route('admin.plan.toggleStatus', $plan->id) }}"
                                       onclick="return confirm('Are you sure you want to change status?')">
                                        @if($plan->active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Inactive</span>
                                        @endif
                                    </a>
                                </td>
                                <td class="text-center">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="bx bx-dots-vertical-rounded fs-18"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a class="dropdown-item" href="{{ route('admin.adminplanSubscriptions', $plan->id) }}">
                                                    <i class="bx bx-list-ul me-1"></i> Plan Details
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="{{ route('admin.plan.toggleStatus', $plan->id) }}"
                                                   onclick="return confirm('Change status?')">
                                                    <i class="bx bx-toggle-left me-1"></i> Toggle Status
                                                </a>
                                            </li>
                                            {{-- Add Edit/Delete here if you have those routes --}}
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="10" class="text-center text-muted py-3">No data available</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">{{ $plans->links() }}</div>

        </div>
    </div>

</div>
@endsection
