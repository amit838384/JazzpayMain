@extends('layouts.app')

@section('content')



<div class="card">
    <div class="card-header">Message List</div>
    <div class="card-body">      
 
        <table id="professions-table" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th scope="col">S#</th>
                    <th scope="col">Message</th>
                    <th scope="col">Created Date</th>
                    <th scope="col">Status</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
           <tbody>
    @foreach ($message as $row)
        <tr>
            <th scope="row">{{ $loop->iteration }}</th>
            <td>{{ $row->message }}</td>
            <td>{{ $row->created_at }}</td>
           <td>
                <form action="{{ route('admin.alert_messagestatuschange', $row->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to {{ $row->status == 1 ? 'deactivate' : 'activate' }} this message?')">
                    @csrf
                    <button type="submit" class="btn btn-sm {{ $row->status == 1 ? 'btn-warning' : 'btn-danger' }}">
                        {{ $row->status == 1 ? 'Active' : 'Inactive' }}
                    </button>
                </form>
            </td>

            
            <td>
                <div class="modal fade" id="showModal{{ $row->id }}" tabindex="-1" aria-labelledby="showModalLabel{{ $row->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <form action="{{ route('admin.school_update', $row->id) }}" method="POST">
                            @csrf
                            <div class="modal-content">
                               
                                    <div class="mb-3">
                                        <label for="address{{ $row->id }}" class="form-label">Message</label>
                                        <input type="text" class="form-control" name="message" id="message{{ $row->id }}" value="{{ $row->message }}" disabled>
                                    </div>
                                </div>
                              
                            </div>
                        </form>
                    </div>
                </div>

              

                {{-- ////////////////////////////////////--Edit--////////////////////////////////////////// --}}
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editModal{{ $row->id }}">
                    <i class="bi bi-pencil-square"></i> Edit
                </button>

                <!-- Modal -->
                <div class="modal fade" id="editModal{{ $row->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $row->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <form action="{{ route('admin.alert_message_update', $row->id) }}" method="POST">
                            @csrf
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editModalLabel{{ $row->id }}">Edit Message</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    
                                    <div class="mb-3">
                                        <label for="message{{ $row->id }}" class="form-label">Message</label>
                                        <textarea type="text" class="form-control" name="message" id="message{{ $row->id }}">{{ $row->message }}</textarea>
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
@endsection