@extends('layouts.app')

@section('content')



<div class="card">
    <div class="card-header">Manage Users</div>
    <div class="card-body">      

        <table id="professions-table" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th scope="col">S#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Role</th>
                    <th scope="col">School</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
           <tbody>
    @foreach ($data as $row)
        <tr>
            <th scope="row">{{ $loop->iteration }}</th>
            <td>{{ $row->name }}</td>
            <td>{{ $row->email }}</td>
            <td>{{ $row->role }}</td>
            
                @if($row->school_id == $school->id)
            <td>{{ $school->school_name }}</td>
            @endif

          <td>
            <form action="{{ route('admin.school_schooluserschangeStatus', $row->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to {{ $row->status == 1 ? 'deactivate' : 'activate' }} this user?')">
                @csrf
                @method('PUT')
                <button type="submit" class="btn btn-sm {{ $row->status == 1 ? 'btn-warning' : 'btn-danger' }}">
                    {{ $row->status == 1 ? 'Active' : 'Inactive' }}
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