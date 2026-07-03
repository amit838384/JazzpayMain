@extends('layouts.app')
@section('content')
<div class="container-fluid">

    {{-- Page header --}}
    <div class="card mb-3">
        <div class="card-body py-3 px-4 d-flex align-items-center justify-content-between">
            <h5 class="mb-0 fw-semibold" style="color:#2e2e7a;">School</h5>
            <a href="{{ route('admin.school_create') }}" class="btn fw-medium px-4"
               style="background:#2e2e7a; border-color:#2e2e7a; color:#fff;">
                Add School
            </a>
        </div>
    </div>

    {{-- Total entries + search --}}
    <div class="mb-2 fw-medium">{{ $data->count() }} Total Entries</div>

    <div class="mb-3" style="max-width:360px;">
        <div class="input-group">
            <span class="input-group-text bg-white border-end-0">
                <i class="bx bx-search text-muted"></i>
            </span>
            <input type="text" id="schoolSearch" class="form-control border-start-0"
                   placeholder="Name" value="">
        </div>
    </div>

    {{-- Table --}}
    <div class="card">
        <div class="card-body">
            <table id="professions-table" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th scope="col">S#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Address</th>
                        <th scope="col">Created Date</th>
                        <th scope="col">Status</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $row)
                        <tr>
                            <th scope="row">{{ $loop->iteration }}</th>
                            <td>{{ $row->school_name }}</td>
                            <td>{{ $row->address }}</td>
                            <td>{{ $row->created_at }}</td>
                            <td>
                                <form action="{{ route('admin.schoolchangeStatus', $row->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to {{ $row->status == 1 ? 'deactivate' : 'activate' }} this school?')">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-sm {{ $row->status == 1 ? 'btn-warning' : 'btn-danger' }}">
                                        {{ $row->status == 1 ? 'Active' : 'Inactive' }}
                                    </button>
                                </form>
                            </td>
                            <td>
                                {{-- Show Modal --}}
                                <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#showModal{{ $row->id }}">
                                    <i class="bi bi-pencil-square"></i> Show
                                </button>

                                <div class="modal fade" id="showModal{{ $row->id }}" tabindex="-1" aria-labelledby="showModalLabel{{ $row->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <form action="{{ route('admin.school_update', $row->id) }}" method="POST">
                                            @csrf
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="showModalLabel{{ $row->id }}">Edit School</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">School Name</label>
                                                        <input type="text" class="form-control" name="schoolname" value="{{ $row->school_name }}" disabled>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Address</label>
                                                        <input type="text" class="form-control" name="address" value="{{ $row->address }}" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                {{-- Edit Modal --}}
                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editModal{{ $row->id }}">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </button>

                                <div class="modal fade" id="editModal{{ $row->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $row->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <form action="{{ route('admin.school_update', $row->id) }}" method="POST">
                                            @csrf
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editModalLabel{{ $row->id }}">Edit School</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">School Name</label>
                                                        <input type="text" class="form-control" name="schoolname" value="{{ $row->school_name }}">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Address</label>
                                                        <input type="text" class="form-control" name="address" value="{{ $row->address }}">
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-success">Update</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>

<script>
window.addEventListener('load', function () {
    // Wire custom search input to DataTables (already init'd on #professions-table by script.blade.php)
    var table = $('#professions-table').DataTable();
    document.getElementById('schoolSearch').addEventListener('keyup', function () {
        table.search(this.value).draw();
    });
});
</script>
@endsection