@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">Assign Cafeteria</div>
        <div class="card-body">
            <a href="javascript:void(0)" class="btn btn-success btn-sm my-2" data-bs-toggle="modal"
                data-bs-target="#inviteModal">
                <i class="bi bi-plus-circle"></i> Invite Assign Cafeteria
            </a>
            <!-- Modal -->
            <div class="modal fade" id="inviteModal" tabindex="-1" aria-labelledby="inviteModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <form method="POST" action="{{ route('admin.assign_user_store') }}">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="inviteModalLabel">Assign Cafeteria</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">


                             <div class="mb-3">
                                    <label for="school_id" class="form-label">Select School</label>
                                    <select name="school_id" id="school_id" class="form-select" required>
                                        <option value="">-- Select School --</option>
                                        @foreach ($school as $s)
                                            <option value="{{ $s->id }}">{{ $s->school_name }}</option>
                                        @endforeach
                                    </select>
                                </div>


                                <!-- Select School -->
                                <div class="mb-3">
                                    <label for="school_id" class="form-label">Select Cafeteria</label>
                                    <select name="cafe_id" id="cafe_id" class="form-select" required>
                                        <option value="">-- Select Cafeteria --</option>
                                        @foreach ($cafe as $s)
                                            <option value="{{ $s->id }}">{{ $s->cafeteria_name }}</option>
                                        @endforeach
                                    </select>
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
                        <th scope="col">ID</th>
                        <th scope="col">Name</th>
                        <th scope="col">Address</th>
                        <th scope="col">Assign to</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cafe as $row)
                        <tr>
                            <th scope="row">{{ $loop->iteration }}</th>
                            <td>{{ $row->cafeteria_name }}</td>
                            <td>{{ $row->address }}</td>
                            <td>
                                @php
                                    $matchedSchool = $school->firstWhere('id', $row->school_id);
                                @endphp
                                {{ $matchedSchool ? $matchedSchool->school_name : 'N/A' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
