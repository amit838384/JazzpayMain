@extends('layouts.app')

@section('content')



<div class="card">
    <div class="card-header">Onsite Sales</div>
    <div class="card-body">  
       <table id="professions-table" class="table table-striped table-bordered">
    <thead>
        <tr>
            <th scope="col">S#</th>
            <th scope="col">Invoice No</th>
            <th scope="col">Date</th>
            <th scope="col">Dish Name</th>
            <th scope="col">Student Name</th>
            <th scope="col">School</th>
            <th scope="col">Cafeteria</th>
            <th scope="col">Payment Mode</th>
            <!-- <th scope="col">Transaction Number</th> -->
            <th scope="col">Payment Status</th>
            <th scope="col">Quantity</th>
            <th scope="col">Credits</th>
        </tr>
    </thead>

    <tbody>
        @php 
            $totalQty = 0;
            $totalCredits = 0;
        @endphp

        @foreach ($data as $row)
            @php
                $totalQty += $row->qty ?? 0;
                $totalCredits += $row->total_price ?? 0;
            @endphp

            <tr>
                <th scope="row">{{ $loop->iteration }}</th>
                <th scope="row">{{ $row->transaction_no }}</th>
                <td>{{ $row->date }}</td>


                <td>
                    @foreach($dish as $di)
                        @if($di->id == $row->dish_id)
                            {{ $di->dish_name  }} x <strong>{{ $row->qty }}</strong> 
                        @endif
                    @endforeach
                </td>

                <td>
                    @foreach($student as $di)
                        @if($di->id == $row->student_id)
                            {{ $di->student_name }}
                        @endif
                    @endforeach
                </td>

                <td>
                    @foreach($school as $di)
                        @if($di->id == $row->school_id)
                            {{ $di->school_name }}
                        @endif
                    @endforeach
                </td>

                <td>
                    @foreach($cafeteria as $caf)
                        @if($caf->school_id == $row->school_id)
                            {{ $caf->cafeteria_name }}
                        @endif
                    @endforeach
                </td>

                <td>{{ $row->payment_type ?? '--' }}</td>
                <!-- <td>{{ $row->transaction_no ?? '--' }}</td> -->
                <td>{{ $row->payment_status == 1 ? 'Success' : 'Pending' }}</td>
                <td>{{ $row->qty ?? 0 }}</td>
                <td>{{ $row->total_price ?? 0 }}</td>
            </tr>
        @endforeach
    </tbody>

    <tfoot>
        <tr>
            <th colspan="9" class="text-end">Total:</th>
            <th>{{ $totalQty }}</th>
            <th>{{ $totalCredits }}</th>
        </tr>
    </tfoot>
</table>

    </div>
</div>
@endsection