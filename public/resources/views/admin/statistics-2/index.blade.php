@extends('layouts.app')

@section('content')



<div class="card">
    <div class="card-header">School List</div>
    <div class="card-body">      
  <a href="{{ route('admin.school_create') }}" class="btn btn-success btn-sm my-2"><i class="bi bi-plus-circle"></i> Add New School </a>
        <table id="professions-table" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th scope="col">S#</th>
                    <th scope="col">Dish Name</th>
                    <th scope="col">Student Name</th>
                    <th scope="col">School</th>
                    <th scope="col">Cafeteria</th>
                    <th scope="col">Qty</th>
                    <th scope="col">Payment Mode</th>
                    <th scope="col">Payment Status</th>
                    <th scope="col">Date</th>
                    <th scope="col">Credits</th>
                    <!-- <th scope="col">Card</th>
                    <th scope="col">Action</th> -->

                </tr>
            </thead>
           <tbody>
    @foreach ($data as $row)
        <tr>
            <th scope="row">{{ $loop->iteration }}</th>

            <td>
                @foreach($dish as $di)
                @if($di->id == $row->dish_id)
                {{ $di->dish_name }}
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

            <td>{{ $row->qty }}</td>
           <td>{{ !empty($row->payment_type) ? $row->payment_type : '--' }}</td>

          <td>{{ $row->payment_status == 1 ? 'Yes' : 'No' }}</td>


            <td>{{ $row->date }}</td>


          <td>{{ $row->total_price }}</td>
            <!-- <td>{{ $row->pos_type }}</td> -->



            
            <!-- <td> -->
              

                <!-- {{-- ////////////////////////////////////--Edit--////////////////////////////////////////// --}} -->
            <!-- <div class="btn-group" role="group">
              
                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#invalidModal{{ $row->id }}">
                    <i class="ri-close-circle-line"></i> Invalid
                </button>

                
                <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#refundModal{{ $row->id }}">
                    <i class="ri-refund-2-line"></i> Refund
                </button>

                
                <a href="#" class="btn btn-info btn-sm">
                    <i class="ri-eye-line pt-4"></i>
                </a>

                
                <button type="button" class="btn btn-secondary btn-sm" onclick="window.print()">
                    <i class="ri-printer-line"></i>
                </button>
            </div> -->


                <!-- Modal -->
                <div class="modal fade" id="editModal{{ $row->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $row->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <form action="{{ route('admin.school_update', $row->id) }}" method="POST">
                            @csrf
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editModalLabel{{ $row->id }}">Edit School</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="schoolname{{ $row->id }}" class="form-label">School Name</label>
                                        <input type="text" class="form-control" name="schoolname" id="schoolname{{ $row->id }}" value="{{ $row->school_name }}">
                                    </div>
                                    <div class="mb-3">
                                        <label for="address{{ $row->id }}" class="form-label">Address</label>
                                        <input type="text" class="form-control" name="address" id="address{{ $row->id }}" value="{{ $row->address }}">
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

            <!-- </td> -->
        </tr>
    @endforeach
</tbody>

        </table>
    </div>
</div>
@endsection