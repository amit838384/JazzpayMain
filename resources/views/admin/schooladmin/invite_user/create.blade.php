@extends('layouts.app')

@section('content')
    <style>
        .card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background: linear-gradient(90deg, #007bff, #00c6ff);
            color: #fff;
            font-weight: 600;
            font-size: 1.2rem;
            border-top-left-radius: 1rem;
            border-top-right-radius: 1rem;
            padding: 1rem 1.5rem;
        }

        .card-body {
            padding: 2rem;
        }

        .form-label {
            font-weight: 500;
            color: #333;
        }

        .form-control {
            border-radius: 0.5rem;
            border: 1px solid #ced4da;
            transition: all 0.3s;
        }

        .form-control:focus {
            box-shadow: 0 0 0 0.25rem rgba(0, 123, 255, 0.25);
            border-color: #007bff;
        }

        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
            padding: 0.6rem 2rem;
            font-weight: 500;
            font-size: 1rem;
            border-radius: 0.5rem;
            transition: all 0.3s;
        }

        .btn-success:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }

        .btn-back {
            background-color: #ffffff;
            border: 1px solid #ffffff;
            color: #333;
            font-weight: 500;
            padding: 0.4rem 1rem;
            border-radius: 0.4rem;
            transition: all 0.3s;
        }

        .btn-back:hover {
            background-color: #f0f0f0;
        }

        .text-danger {
            font-size: 0.9rem;
        }
    </style>

    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Add New Category</span>
                    <a href="{{ route('admin.school') }}" class="btn btn-back btn-sm">&larr; Back</a>
                </div>
                <div class="card-body">

                    <form action="{{ route('admin.school_store') }}" method="post" enctype="multipart/form-data">
                        @csrf

                        <!-- Category Name -->
                        <div class="mb-4">
                            <label for="schoolname" class="form-label">school Name</label>
                            <input type="text" class="form-control @error('schoolname') is-invalid @enderror"
                                id="schoolname" name="schoolname" value="{{ old('schoolname') }}"
                                placeholder="Enter School name">
                            @error('schoolname')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                         <div class="mb-4">
                            <label for="address" class="form-label">Address</label>
                            <textarea type="text" class="form-control @error('address') is-invalid @enderror"
                                id="address" name="address"placeholder="Enter address name">{{ old('address') }}</textarea>
                            @error('address')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="text-center">
                            <button type="submit" class="btn btn-success">
                                + Add 
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection
