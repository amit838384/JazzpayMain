@extends('layouts.app')

@section('content')



<div class="card">
    <div class="card-header">Users</div>
    <div class="card-body">
        
     
        <table id="professions-table" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th scope="col">S#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">createddate</th>
                    <th scope="col">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($profession as $categoryget)
                <tr>
                    <th scope="row">{{ $loop->iteration }}</th>
                    <td>{{ $categoryget->f_name }} {{ $categoryget->l_name }}</td>
                    <td>{{ $categoryget->email }}</td>
                    <td>{{ $categoryget->created_at }}</td>
                    <td>
                        <form action="{{ route('userdata.update-status', $categoryget->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="status" value="{{ $categoryget->status == 1 ? 0 : 1 }}">
                            <button type="submit" class="btn btn-sm {{ $categoryget->status == 1 ? 'btn-warning' : 'btn-danger' }}">
                                {{ $categoryget->status == 1 ? 'Active' : 'Inactive' }}
                            </button>
                        </form>
                    </td>
                   
                    </td> 
                    
                   
                </tr>
                @empty
               
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection