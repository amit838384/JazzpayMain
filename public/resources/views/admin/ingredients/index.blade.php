@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">Ingredients</div>
        <div class="card-body">
             <a href="javascript:void(0)" class="btn btn-success btn-sm my-2" data-bs-toggle="modal"
                data-bs-target="#inviteModal">
                <i class="bi bi-plus-circle"></i> Add Ingredients
            </a>
            <!-- Modal -->
            <div class="modal fade" id="inviteModal" tabindex="-1" aria-labelledby="inviteModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <form method="POST" action="{{ secure_url(route('admin.ingredients_category_store', [], false)) }}" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="inviteModalLabel">Ingredients</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">

                               <div class="mb-3">
                                    <label for="name" class="form-label"> Name</label>
                                    <input type="text" name="name" id="name" class="form-control"
                                        placeholder="Enter ingredients name" required>
                                </div>


                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Add</button>
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


                        <!-- <th scope="col">Status</th>
                        <th scope="col" style="width: 198.75px;">Action</th> -->
                    </tr>
                </thead>
                <tbody>
                    @foreach ($dish as $row)
                        <tr>
                            <th scope="row">{{ $loop->iteration }}</th>

                            <td>{{ $row->name }}</td>

                            <!-- <td>
                                <form action="{{ route('admin.ingredients_categorychangeStatus', $row->id) }}" method="POST"
                                    onsubmit="return confirm('Are you sure you want to {{ $row->status == 1 ? 'deactivate' : 'activate' }} this Ingredient?')">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit"
                                        class="btn btn-sm {{ $row->status == 1 ? 'btn-warning' : 'btn-danger' }}">
                                        {{ $row->status == 1 ? 'Active' : 'Inactive' }}
                                    </button>
                                </form>
                            </td> -->
                             <!-- <td> -->
                               <!-- Edit Button -->
                                <!-- <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#editMenuModal{{ $row->id }}">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </button> -->

                                <!-- <form action="{{ route('admin.menu_delete', $row->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this menu?')">
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </form> -->

<!-- Edit Modal -->
                                <!-- <div class="modal fade" id="editMenuModal{{ $row->id }}" tabindex="-1"
                                    aria-labelledby="editMenuModalLabel{{ $row->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <form action="{{ route('admin.ingredients_category_update', $row->id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            @method('POST')
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editMenuModalLabel{{ $row->id }}">Edit Menu</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">

                                                

                                                    <div class="mb-3">
                                                                    <label for="name" class="form-label"> Name</label>
                                                                    <input type="text" name="name" id="name" class="form-control"
                                                                        placeholder="Enter ingredients name" value="{{ $row->name }}">
                                                                </div>



                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-primary">Update</button>
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div> -->





                               

                            <!-- </td> -->



                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
    </div>
@endsection
