
@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">Cards</div>
        <div class="card-body">
           
            <table id="professions-table" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Card Number</th>
                        <th scope="col">Parent Name</th>
                        <th scope="col">Student Name</th>
                        <th scope="col">School</th>
                        <th scope="col" style="width: 198.75px;">Written</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $row)
                        <tr>
                            <th scope="row">{{ $row->id }}</th>
                            <th scope="row">{{ $row->card_no }}</th>
                            @foreach ($parent as $par)
                                @if ($row->parent_id == $par->id)
                                    <td>{{ $par->name }}</td>
                                @endif
                            @endforeach
                            <td>{{ $row->student_name }}</td>
                            @foreach ($school as $sch)
                                @if ($row->school_id == $sch->id)
                                    <td>{{ $sch->school_name }}</td>
                                @endif
                            @endforeach


                        
                            <td>
                                <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#showModal{{ $row->id }}">
                                    <i class="bi bi-pencil-square"></i> WRITE ON CARD
                                </button>


                                <div class="modal fade" id="showModal{{ $row->id }}" tabindex="-1"
                                    aria-labelledby="showModalLabel{{ $row->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <form action="{{ route('admin.cafeteriacard_add', $row->id) }}" method="POST">
                                            @csrf
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="showModalLabel{{ $row->id }}">Tap card or Enter Card Number to assign</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">

                                                   <div class="mb-4">
                                                    <input type="hidden" value="{{ $row->id }}" id="id" name="id">
                                                        <input type="text" class="form-control " id="number" name="number" value="" placeholder="Enter Number">
                                                    </div>
                                                </div>
                                                <button class="btn btn-primary" type="submit">update</button>
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
