@extends('layouts.app')

@section('content')



<div class="card">
<div class="card-header">School List</div>
   <div class="card p-3">
    <h4>Cafe Pos Report</h4>
    <form method="GET" action="{{ route('admin.cafe_pos_report') }}">
        <div class="row mb-3">
            <div class="col-md-3">
                <label>From Date</label>
                <input type="datetime-local" name="from_date" value="{{ $fromDate->format('Y-m-d\TH:i') }}" class="form-control">
            </div>
            <div class="col-md-3">
                <label>To Date</label>
                <input type="datetime-local" name="to_date" value="{{ $toDate->format('Y-m-d\TH:i') }}" class="form-control">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-dark w-100">Search</button>
            </div>
        </div>
    </form>

    <table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>Sr No</th>
            <th>Cafe User</th>
            <th>Cafeteria</th>
            <th>Sale</th>
        </tr>
    </thead>
    <tbody>
        @foreach($sales as $index => $row)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $row->cafe_user ?? 'N/A' }}</td>
                <td>{{ $row->cafeteria_name ?? 'Unknown' }}</td>
                <td>{{ number_format($row->total_sale, 2) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
</div>
</div>
@endsection