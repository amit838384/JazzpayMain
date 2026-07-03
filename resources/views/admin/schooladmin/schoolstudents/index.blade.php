@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">Students</div>
        <div class="card-body">
            <a href="javascript:void(0)" class="btn btn-success btn-sm my-2" data-bs-toggle="modal"
                data-bs-target="#inviteModal">
                <i class="bi bi-plus-circle"></i> Students
            </a>
            <!-- Modal -->
            <div class="modal fade" id="inviteModal" tabindex="-1" aria-labelledby="inviteModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <form method="POST" action="{{ route('admin.school_students_store') }}">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="inviteModalLabel">Add Student</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">


                                <div class="mb-3">
                                    <label for="school_id" class="form-label">Select School</label>
                                    <select name="school_id" id="school_id" class="form-select" required>
                                        <!-- <option value="">-- Select School --</option> -->
                                            @foreach($data as $user)
                                            @foreach($school as $sch)
                                              @if($sch->id == $user->school_id)
                                            <option value="{{ $sch->id }}">{{ $sch->school_name }}</option>
                                            @endif
                                            @endforeach
                                          
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="parent_id" class="form-label">Select Parent</label>
                                    <select name="parent_id" id="parent_id" class="form-select" required>
                                        <option value="">-- Select Parent --</option>
                                        @foreach ($parent as $p)
                                            <option value="{{ $p->id }}">{{ $p->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="student_name" class="form-label">Student Name</label>
                                    <input type="text" name="student_name" id="student_name" class="form-control"
                                        placeholder="Enter student name" required>
                                </div>

                                 <div class="mb-3">
                                    <label for="admission_no" class="form-label">Admission No</label>
                                    <input type="text" name="admission_no" id="admission_no" class="form-control"
                                        placeholder="Enter admission_no" required>
                                </div>

                                <div class="mb-3">
                                    <label for="grade" class="form-label">Grade</label>
                                    <input type="text" name="grade" id="grade" class="form-control"
                                        placeholder="Enter grade" required>
                                </div>

                                <div class="mb-3">
                                    <label for="gender" class="form-label">Gender</label>
                                    <select name="gender" id="gender" class="form-select" required>
                                        <option value="">-- Select Gender --</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="dob" class="form-label">Date of Birth</label>
                                    <input type="date" name="dob" id="dob" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label for="wallet_balance" class="form-label">Wallet Balance</label>
                                    <input type="number" name="wallet_balance" id="wallet_balance" class="form-control"
                                        placeholder="Enter wallet balance" required>
                                </div>

                                <div class="mb-3">
                                    <label for="spend_limit" class="form-label">Spend Limit</label>
                                    <input type="number" name="spend_limit" id="spend_limit" class="form-control"
                                        placeholder="Enter spend limit" required>
                                </div>

                                <div class="mb-3">
                                    <label for="verified" class="form-label">Verified</label>
                                    <select name="verified" id="verified" class="form-select" required>
                                        <option value="">-- Select Option --</option>
                                        <option value="1">Yes</option>
                                        <option value="0">No</option>
                                    </select>
                                </div>



                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Save</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <table id="professions-table" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th scope="col">Admission No</th>
                        <th scope="col">Name</th>
                        <th scope="col">Parent</th>
                        <th scope="col">Mobile</th>
                        <th scope="col">Parent Email</th>
                        <th scope="col">School</th>
                        <th scope="col">Credits</th>
                        <th scope="col">Daily Limit</th>
                        <th scope="col">verified</th>
                        <th scope="col">Status</th>
                        <th scope="col" style="width: 198.75px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $row)
                        <tr>
                            <th scope="row">{{ $row->id }}</th>

                            <td>{{ $row->student_name }}</td>

                            @foreach ($parent as $par)
                                @if ($row->parent_id == $par->id)
                                    <td>{{ $par->name }}</td>
                                @endif
                            @endforeach

                            @foreach ($parent as $par)
                                @if ($row->parent_id == $par->id)
                                    <td>{{ $par->mobile }}</td>
                                @endif
                            @endforeach
                            
                            @foreach ($parent as $par)
                                @if ($row->parent_id == $par->id)
                                    <td>{{ $par->email }}</td>
                                @endif
                            @endforeach

                            @foreach ($school as $sch)
                                @if ($row->school_id == $sch->id)
                                    <td>{{ $sch->school_name }}</td>
                                @endif
                            @endforeach

                            <td>{{ $row->wallet_balance }}</td>
                            <td>{{ $row->spend_limit }}</td>
                            <td>{{ $row->verified }}</td>

                            <td>
                                <form action="{{ route('admin.studentschangeStatus', $row->id) }}" method="POST"
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
                                    <i class="bi bi-pencil-square"></i> More Details
                                </button>


                                <div class="modal fade" id="showModal{{ $row->id }}" tabindex="-1"
                                    aria-labelledby="showModalLabel{{ $row->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <form action="{{ route('admin.school_update', $row->id) }}" method="POST">
                                            @csrf
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="showModalLabel{{ $row->id }}">Student</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">

                                                    <Span class="fw-bold">Full Name</Span> : {{ $row->student_name }}<br>

                                                    <Span class="fw-bold">Parent Name</Span> :  
                                                    @foreach ($parent as $par)
                                                        @if ($row->parent_id == $par->id)
                                                            {{ $par->name }}
                                                        @endif
                                                    @endforeach<br>

                                                    <Span class="fw-bold">Parent Mobile </Span> : 
                                                     @foreach ($parent as $par)
                                                        @if ($row->parent_id == $par->id)
                                                            {{ $par->mobile }}
                                                        @endif
                                                    @endforeach<br>

                                                    <Span class="fw-bold">Parent Email </Span> : 
                                                     @foreach ($parent as $par)
                                                        @if ($row->parent_id == $par->id)
                                                            {{ $par->email }}
                                                        @endif
                                                    @endforeach<br>

                                                    <Span class="fw-bold">School Name </Span> :
                                                    {{ $sch->school_name }}<br>

                                                    <Span class="fw-bold">Grade </Span> :
                                                    {{ $row->grade }}<br>

                                                    <Span class="fw-bold">Gender </Span> :
                                                    {{ $row->gender }}<br>

                                                    <Span class="fw-bold">Date of Birth </Span> :
                                                    {{ $row->dob }}<br>

                                                    <Span class="fw-bold">Spend Limit </Span> : {{ $row->spend_limit}}<br>


                                                    <Span class="fw-bold">Wallet Balance </Span> : {{ $row->wallet_balance }}<br>

                                                    <Span class="fw-bold">Verified </Span> : {{ $row->verified }}<br>


                                                    <span class="fw-bold">Status</span> :
                                                    <span
                                                        class="btn btn-sm {{ $row->status == 1 ? 'text-primary' : 'text-danger' }}">
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
                                    <i class="bi bi-pencil-square"></i> Verify
                                </button>



                            </td>



                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
    </div>
@endsection
