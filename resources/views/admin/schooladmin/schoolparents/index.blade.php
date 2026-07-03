@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">Parent</div>
        <div class="card-body">
            <a href="javascript:void(0)" class="btn btn-success btn-sm my-2" data-bs-toggle="modal"
                data-bs-target="#inviteModal">
                <i class="bi bi-plus-circle"></i> Invite Parent
            </a>
            <!-- Modal -->
            <div class="modal fade" id="inviteModal" tabindex="-1" aria-labelledby="inviteModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <form method="POST" action="{{ route('admin.school_parents_store') }}">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="inviteModalLabel">Invite Parent</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">

                                <!-- Select School -->
                                <div class="mb-3">
                                    <label for="school_id" class="form-label">Select School</label>
                                    <select name="school_id" id="school_id" class="form-select" required>
                                        <option value="">-- Select School --</option>
                                        @foreach ($school as $s)
                                            <option value="{{ $s->id }}">{{ $s->school_name }}</option>
                                        @endforeach
                                    </select>
                                </div>


                                 

                                 <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="text" name="email" id="email" class="form-control"
                                        placeholder="Enter user email" required>
                                </div>

                                 <div class="mb-3">
                                    <label for="mobile" class="form-label">Mobile No</label>
                                    <input type="number" name="mobile" id="mobile" class="form-control"
                                        placeholder="Enter user mobile no">
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
                <thead>
                    <tr>
                        <th scope="col">S#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Mobile</th>
                        <th scope="col">Role</th>
                        <th scope="col">School</th>
                        <th scope="col">Invite Code</th>
                        <th scope="col">Sent Date</th>
                        <th scope="col">Accepted Date</th>
                        <th scope="col" style="width: 10px !Important;">Balance<br>(QAR)</th>
                        <th scope="col">Status</th>
                        <th scope="col" style="width: 198.75px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $row)
                        <tr>
                            <th scope="row">{{ $loop->iteration }}</th>
                            <td>{{ $row->name }}</td>
                            <td>{{ $row->mobile }}</td>
                            <td>{{ $row->role }}</td>
                            @foreach ($school as $sch)
                                @if ($row->school_id == $sch->id)
                                    <td>{{ $sch->school_name }}</td>
                                @endif
                            @endforeach

                            <td>{{ $row->invite_code }}</td>
                            <td>{{ $row->sent_date }}</td>
                            @if(!empty($row->accepted_date))
                            <td>{{ $row->accepted_date }}</td>
                            @else
                            <td class="text-center">--</td>
                            @endif
                            <td>{{ $row->balance }}</td>

                            <td>
                                <form action="{{ route('admin.school_parentschangeStatus', $row->id) }}" method="POST"
                                    onsubmit="return confirm('Are you sure you want to {{ $row->status == 1 ? 'deactivate' : 'activate' }} this user?')">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit"
                                        class="btn btn-sm {{ $row->status == 1 ? 'btn-warning' : 'btn-danger' }}">
                                        {{ $row->status == 1 ? 'Active' : 'Inactive' }}
                                    </button>
                                </form>
                            </td>
                             <td>
                                <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#showModal{{ $row->id }}">
                                    <i class="bi bi-pencil-square"></i> Show
                                </button>


                                <div class="modal fade" id="showModal{{ $row->id }}" tabindex="-1"
                                    aria-labelledby="showModalLabel{{ $row->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <form action="{{ route('admin.school_update', $row->id) }}" method="POST">
                                            @csrf
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="showModalLabel{{ $row->id }}">Parent Details</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    
                                                        <Span class="fw-bold">Full Name</Span> : {{$row->name}}<br><br>

                                                        <Span class="fw-bold">Mobile </Span> : {{$row->mobile}}<br><br>
                                                        
                                                        <Span class="fw-bold">Email </Span> : {{$row->email}}<br><br>

                                                        <Span class="fw-bold">School Name </Span> : {{$sch->school_name}}<br><br>

                                                        <Span class="fw-bold">Invite Code </Span> : {{$row->invite_code}}<br><br>

                                                        <Span class="fw-bold">Sent Date </Span> : {{$row->sent_date}}<br><br>

                                                        <Span class="fw-bold">Accepted Date </Span> : {{$row->accepted_date}}<br><br>

                                                        <Span class="fw-bold">Balance </Span> : {{$row->balance}}<br><br>

                                                         <span class="fw-bold">Status</span> :
                                                            <span class="btn btn-sm {{ $row->status == 1 ? 'text-warning' : 'text-danger' }}">
                                                                {{ $row->status == 1 ? 'Active' : 'Inactive' }}
                                                            </span>
                                                            <br><br>   
                                                </div>

                                            </div>
                                        </form>
                                    </div>
                                </div>



                                {{-- ////////////////////////////////////--Edit--////////////////////////////////////////// --}}
                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#editModal{{ $row->id }}">
                                    <i class="bi bi-pencil-square"></i> Reset Password
                                </button>

                               

                            </td>



                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
    </div>
@endsection
