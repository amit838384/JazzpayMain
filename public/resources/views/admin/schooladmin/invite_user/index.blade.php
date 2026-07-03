@extends('layouts.app')
@section('content')
<div class="card">
    <div class="card-header">Invite School Users</div>
    <div class="card-body">      
  <a href="javascript:void(0)" class="btn btn-success btn-sm my-2" data-bs-toggle="modal" data-bs-target="#inviteModal">
    <i class="bi bi-plus-circle"></i> Invite School Users
</a>
<!-- Modal -->
<div class="modal fade" id="inviteModal" tabindex="-1" aria-labelledby="inviteModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" action="{{ route('admin.school_invite_users_store') }}">
        @csrf
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="inviteModalLabel">Invite School User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <div class="mb-3">
                    <label for="school_id" class="form-label">Select School</label>
					@if(Auth::user()->role == 'schooladmin')
						<select name="school_id" id="school_id" class="form-select" required>
							<option value="{{ $school->id }}">{{ $school->school_name }}</option>
						</select>
					@else
						<select name="school_id" id="school_id" class="form-select" required>
							@foreach($users as $user)
								@if($school->id == $user->school_id)
									<option value="{{ $school->id }}">{{ $school->school_name }}</option>
								@endif
							@endforeach
						</select>
					@endif
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">User Email</label>
                    <input type="email" name="email" id="email" class="form-control" placeholder="Enter user email" required>
                </div>

                <div class="mb-3">
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="Enter user name" required>
                </div>

            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Send Invite</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </form>
  </div>
</div>

        <table id="professions-table" class="table table-striped table-bordered">
            <!-- Invite Button -->




            <thead>
                <tr>
                    <th scope="col">S#</th>
                    <th scope="col">Email</th>
                    <th scope="col">Role</th>
                    <th scope="col">School</th>
                    <th scope="col">Invite Code</th>
                    <th scope="col">Status</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
           <tbody>
    @foreach ($users as $row)
        <tr>
            <th scope="row">{{ $loop->iteration }}</th>

            <td>{{ $row->email }}</td>
            <td>{{ $row->role }}</td>
            
      
                @if($row->school_id == $school->id)
            <td>{{ $school->school_name }}</td>
            @endif
            <td>{{ $row->invite_code }}</td>

            <td>
                <button type="submit" class="btn btn-sm {{ $row->status == 1 ? 'btn-warning' : 'btn-danger' }}">
                    {{ $row->status == 1 ? 'Active' : 'Inactive' }}
                </button>
            </td>

             <td>
                 <form method="POST" action="{{ route('admin.school_invite_users_store') }}"
                                onsubmit="return confirm('Are you sure you want to Resend invite?');">
                                @csrf
                                <button type="submit"
                                    style=" height: 34px; background: transparent">
                                    <i class="ri-arrow-right-s-fill" style="font-size: 20px; color: #333;"></i>
                                </button>
                            </form>
            </td>
           
        </tr>
    @endforeach
</tbody>

        </table>
    </div>
</div>
@endsection