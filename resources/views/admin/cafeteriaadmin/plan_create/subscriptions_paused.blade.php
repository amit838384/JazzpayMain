@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h4>Pause Date for Student : {{ $student->student_name }}</h4>
    <table id="professions-table" class="table table-bordered table-striped mt-3">
        <thead>
            <tr>
                <th>#</th>
                <th>pause_date</th>
                <th>requested_by (Parent)</th>
                <th>status</th>
                <th>reason</th>

            </tr>
        </thead>
        <tbody>
            @foreach($paused as $key => $sub)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $sub->pause_date }}</td>
                    <td>{{ $parent->name }}</td>
                    <td>{{ $sub->status }}</td>
                    <td>{{ $sub->reason }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
