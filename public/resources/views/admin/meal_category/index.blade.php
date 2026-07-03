@extends('layouts.app')
@section('content')
<div class="container-fluid">

    {{-- Page header --}}
    <div class="card mb-3">
        <div class="card-body py-3 px-4 d-flex align-items-center justify-content-between">
            <h5 class="mb-0 fw-semibold" style="color:#2e2e7a;">Meals</h5>
            <button type="button" class="btn fw-medium px-4"
                    style="background:#2e2e7a; border-color:#2e2e7a; color:#fff;"
                    data-bs-toggle="modal" data-bs-target="#addMealModal">
                Add Meals
            </button>
        </div>
    </div>

    {{-- Total entries --}}
    <div class="mb-2 fw-medium">{{ $dish->total() }} Total Entries</div>

    {{-- Search --}}
    <form method="GET" action="{{ route('admin.meal_category') }}">
        <div class="d-flex gap-3 mb-3 flex-wrap">
            <div style="max-width:360px; flex:1;">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="bx bx-search text-muted"></i>
                    </span>
                    <input type="text" name="name" class="form-control border-start-0"
                           placeholder="Meal Name" value="{{ request('name') }}">
                </div>
            </div>
            <button type="submit" class="btn btn-primary px-4"
                    style="background:#2e2e7a; border-color:#2e2e7a;">Search</button>
            <a href="{{ route('admin.meal_category') }}" class="btn btn-secondary px-4">Clear</a>
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
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($dish as $row)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $row->name }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="bx bx-error-circle fs-24 d-block mb-1"></i>
                                        No Data Available
                                    </div>
                                </td>
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
	{{-- Add Meal Modal --}}
<div class="modal fade" id="addMealModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" action="{{ secure_url(route('admin.meal_category_store', [], false)) }}" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-semibold">Add Meal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" placeholder="Enter name" required>
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


@endsection