@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h4>Subscriptions for Plan</h4>
    <table class="table table-bordered table-striped mt-3">
        <thead>
            <tr>
                <th>#</th>
                <th>Student Name</th>
                <th>Parent Name</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Status</th>
                <th>Days Left</th>
                <th>Paused Days</th>
                <th>Paused Details</th>
            </tr>
        </thead>
        <tbody>
            @foreach($subscriptions as $key => $sub)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $sub->student->student_name ?? '-' }}</td>
                    <td>{{ $sub->parent->name ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($sub->start_date)->format('d M Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($sub->end_date)->format('d M Y') }}</td>
                    <td>
                        @if($sub->status_dynamic == 'active')
                            <span class="badge bg-success">Active</span>
                        @elseif($sub->status_dynamic == 'paused')
                            <span class="badge bg-warning text-dark">Paused</span>
                        @else
                            <span class="badge bg-secondary">Completed</span>
                        @endif
                    </td>
                    <td>{{ $sub->remaining_days_dynamic }}</td>
                    <td>{{ $sub->paused_days_count }}</td>
                    <td>
                      <a href="{{ route('admin.plan.subscriptions.paused', $sub->id) }}" class="btn btn-sm btn-warning">Pause Details</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
