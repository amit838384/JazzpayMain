@extends('layouts.app')
@section('content')
<div class="container-fluid">

    {{-- Page header --}}
    <div class="card mb-3">
        <div class="card-body py-3 px-4">
            <h5 class="mb-0 fw-semibold" style="color:#2e2e7a;">Cards</h5>
        </div>
    </div>

    {{-- Total entries --}}
    <div class="mb-2 fw-medium">{{ $data->total() }} Total Entries</div>

    {{-- Search + Filter --}}
    <form method="GET" action="{{ route('admin.cards') }}">
        <div class="row g-2 mb-3">
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="bx bx-search text-muted"></i>
                    </span>
                    <input type="text" name="card_number" class="form-control border-start-0"
                           placeholder="Card number" value="{{ request('card_number') }}">
                </div>
            </div>
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="bx bx-search text-muted"></i>
                    </span>
                    <input type="text" name="student_name" class="form-control border-start-0"
                           placeholder="Student name" value="{{ request('student_name') }}">
                </div>
            </div>
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="bx bx-search text-muted"></i>
                    </span>
                    <input type="text" name="parent_name" class="form-control border-start-0"
                           placeholder="Parent name" value="{{ request('parent_name') }}">
                </div>
            </div>
            <div class="col-md-4">
                <select name="school_id" class="form-select" onchange="this.form.submit()">
                    <option value="">Select School</option>
                    @foreach($school as $s)
                        <option value="{{ $s->id }}" {{ request('school_id') == $s->id ? 'selected' : '' }}>
                            {{ $s->school_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <select name="card_type" class="form-select" onchange="this.form.submit()">
                    <option value="">Select Card Number Type</option>
                    <option value="all"      {{ request('card_type') === 'all'      ? 'selected' : '' }}>All</option>
                    <option value="written"  {{ request('card_type') === 'written'  ? 'selected' : '' }}>Written</option>
                    <option value="unwritten"{{ request('card_type') === 'unwritten'? 'selected' : '' }}>Unwritten</option>
                </select>
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4 w-100"
                        style="background:#2e2e7a; border-color:#2e2e7a;">Search</button>
                <a href="{{ route('admin.cards') }}" class="btn btn-secondary px-4 w-100">Clear</a>
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
                            <th>Card Number</th>
                            <th>Student Name</th>
                            <th>Parent Name</th>
                            <th>School</th>
                            <th>Card Status</th>
                            <th>Written</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data as $row)
                            @php
                                $par = $parent->firstWhere('id', $row->parent_id);
                                $sch = $school->firstWhere('id', $row->school_id);
                            @endphp
                            <tr>
                                <td>{{ $row->id }}</td>
                                <td>{{ $row->card_no ?: '-' }}</td>
                                <td>{{ $row->student_name }}</td>
                                <td>{{ $par->name ?? '-' }}</td>
                                <td>{{ $sch->school_name ?? '-' }}</td>
                                <td>
                                    <span class="{{ $row->card_no ? 'text-success' : 'text-danger' }}">
                                        {{ $row->card_no ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>
                                    <button type="button"
                                            class="btn btn-sm fw-medium"
                                            style="color:#2e2e7a; border:none; background:none; text-decoration:underline;"
                                            data-bs-toggle="modal"
                                            data-bs-target="#showModal{{ $row->id }}">
                                        WRITE ON CARD
                                    </button>

                                    <div class="modal fade" id="showModal{{ $row->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <form action="{{ route('admin.cafeteriacard_add', $row->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Tap card or Enter Card Number to assign</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <input type="hidden" value="{{ $row->id }}" name="id">
                                                        <input type="text" class="form-control" name="number"
                                                               value="{{ $row->card_no }}" placeholder="Enter Number">
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button class="btn btn-primary" type="submit">Update</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
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
@endsection