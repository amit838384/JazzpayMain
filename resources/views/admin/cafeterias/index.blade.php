@extends('layouts.app')

@section('content')



<div class="card">
    <div class="card-header">Cafeteria</div>
    <div class="card-body">      
        {{-- ////////////////////////////////////////////////////////////////////////////////////////////////////////////
        ///////////////////////////////////////-Add Cafeteria/////////////////////////////////////////////////////////////////////
        //////////////////////////////////////////////////////////////////////////////////////////////////////////// --}}
        <button type="button" class="btn btn-success btn-sm my-2" data-bs-toggle="modal" data-bs-target="#addCafeteriaModal">
            <i class="bi bi-plus-circle"></i> Add New Cafeteria
        </button>

        <!-- Add Cafeteria Modal -->
        <div class="modal fade" id="addCafeteriaModal" tabindex="-1" aria-labelledby="addCafeteriaModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addCafeteriaModalLabel">Add New Cafeteria</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <form action="{{ route('admin.cafeterias_store') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="cafeteria_name" class="form-label">Cafeteria Name</label>
                                <input type="text" class="form-control" id="cafeteria_name" name="cafeteria_name" placeholder="Enter cafeteria name" required>
                            </div>

                            <div class="mb-3">
                                <label for="address" class="form-label">Address</label>
                                <input type="text" class="form-control" id="address" name="address" placeholder="Enter address" required>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Save Cafeteria</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
                {{-- ////////////////////////////////////////////////////////////////////////////////////////////////////////////
        ///////////////////////////////////////-Add Cafeteria/////////////////////////////////////////////////////////////////////
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////--}}
        <table id="professions-table" class="table table-striped table-bordered">
    <thead>
        <tr>
            <th scope="col">S#</th>
            <th scope="col">Name</th>
            <th scope="col">Address</th>
            <th scope="col">Status</th>
            <th scope="col">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $row)
            <tr id="row-{{ $row->id }}">
                <th scope="row">{{ $loop->iteration }}</th>
                <td>{{ $row->cafeteria_name }}</td>
                <td>{{ $row->address }}</td>

                <td>
                    @if ($row->status == 1)
                        <button class="btn btn-success btn-sm toggle-status" 
                                data-id="{{ $row->id }}" 
                                data-status="1">
                            Active
                        </button>
                    @else
                        <button class="btn btn-danger btn-sm toggle-status" 
                                data-id="{{ $row->id }}" 
                                data-status="0">
                            Inactive
                        </button>
                    @endif
                </td>

                <td>
                    <!-- Edit Button -->
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editModal{{ $row->id }}">
                        <i class="bi bi-pencil-square"></i> Edit
                    </button>
                </td>
            </tr>

            <!-- 🔹 Edit Modal -->
            <div class="modal fade" id="editModal{{ $row->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $row->id }}" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editModalLabel{{ $row->id }}">Edit Cafeteria</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <form action="{{ route('admin.cafeterias_update', $row->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="cafeteria_name_{{ $row->id }}" class="form-label">Name</label>
                                    <input type="text" class="form-control" id="cafeteria_name_{{ $row->id }}" name="cafeteria_name" value="{{ $row->cafeteria_name }}" required>
                                </div>

                                <div class="mb-3">
                                    <label for="address_{{ $row->id }}" class="form-label">Address</label>
                                    <input type="text" class="form-control" id="address_{{ $row->id }}" name="address" value="{{ $row->address }}" required>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- End Modal -->
        @endforeach
    </tbody>
</table>


    </div>
</div>

@endsection


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).on('click', '.toggle-status', function() {
    var button = $(this);
    var id = button.data('id');
    var currentStatus = button.data('status');
    var newStatus = currentStatus == 1 ? 0 : 1;

    $.ajax({
        url: 'cafeterias/change-status/' + id,
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            status: newStatus
        },
        success: function(response) {
            if (response.success) {
                if (newStatus == 1) {
                    button.removeClass('btn-danger').addClass('btn-success').text('Active');
                } else {
                    button.removeClass('btn-success').addClass('btn-danger').text('Inactive');
                }
                button.data('status', newStatus);
            } else {
                alert('Failed to update status.');
            }
        },
        error: function() {
            alert('Error updating status.');
        }
    });
});
</script>