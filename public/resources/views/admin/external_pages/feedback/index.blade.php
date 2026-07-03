@extends('layouts.app')

@section('content')



<div class="card">
    <div class="card-header">Feedback List</div>
    <div class="card-body">      
        <table id="professions-table" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th scope="col">S#</th>
                    <th scope="col">Parent</th>
                    <th scope="col">Message</th>
                    <th scope="col">Created Date</th>
                    <th scope="col">Status</th
                </tr>
            </thead>
           <tbody>
        @foreach ($feedback as $row)
        <tr>
            <th scope="row">{{ $loop->iteration }}</th>
            <td>
            @foreach($parent as $par)
            @if($row->parent_id == $par->id)
            {{ $par->name }}
            @endif
            @endforeach
            </td>
            <td>{{ $row->message }}</td>
            <td>{{ $row->created_at->format('d-m-Y h:i A') }}</td>

           <td>
                <form action="{{ route('admin.feedbackstatuschange', $row->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to {{ $row->status == 1 ? 'deactivate' : 'activate' }} this Feedback?')">
                    @csrf
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