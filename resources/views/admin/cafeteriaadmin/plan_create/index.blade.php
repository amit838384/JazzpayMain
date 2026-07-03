@extends('layouts.app')

@section('content')
<div class="container-fluid mt-4">
    <div class="card shadow p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">Cafeteria Plans</h4>
            <a href="{{ route('admin.cafeteria_createPlan') }}" class="btn btn-sm btn-primary">
                + Add New Plan
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($plans->count() > 0)
        <div class="table-responsive">
            <table id="professions-table" class="table table-striped table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Plan Name</th>
                        <th>Duration</th>
                        <th>Meals</th>
                        <th>Price (₹)</th>
                        <th>Auto Renew</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Plan Details</th>
                        {{-- <th>Action</th> --}}
                    </tr>
                </thead>
                <tbody>
                    @foreach($plans as $index => $plan)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $plan->name }}</td>
                        <td>{{ $plan->duration_days }} Days</td>
                        <td>
                            @foreach(explode(',', $plan->meals) as $meal)
                                <span class="badge bg-info text-dark">{{ ucfirst($meal) }}</span>
                            @endforeach
                        </td>
                        
                        <td><strong>₹{{ number_format($plan->price, 2) }}</strong></td>
                        <td>
                            @if($plan->auto_renew)
                                <span class="badge bg-success">Yes</span>
                            @else
                                <span class="badge bg-danger">No</span>
                            @endif
                        </td>
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

                        <td>{{ $plan->created_at ? $plan->created_at->format('d M Y') : '-' }}</td>
                        {{-- <td>
                            <a href="{{ route('admin.cafeteria.plans.edit', $plan->id) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('admin.cafeteria.plans.delete', $plan->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Are you sure you want to delete this plan?')" class="btn btn-sm btn-danger">
                                    Delete
                                </button>
                            </form>
                        </td> --}}
                        <td>
                              <a href="{{ route('admin.plan.subscriptions', $plan->id) }}" class="btn btn-sm btn-warning">Plan Details</a>

                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
            <p class="text-muted mb-0">No plans found. Click "Add New Plan" to create one.</p>
        @endif
    </div>
</div>
@endsection
