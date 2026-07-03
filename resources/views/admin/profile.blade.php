@extends('layouts.app')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-12">

        <div class="card">
            <div class="card-header">
                <div class="float-start">
                    Change password
                </div>
               
            </div>
            <div class="card-body">
                <form action="{{ route('admin.changepassword') }}" method="post">
                    @csrf

                    <div class="mb-3 row">
                        <label for="Password" class="col-md-4 col-form-label text-md-end text-start">Password</label>
                        <div class="col-md-6">
                          <input type="text" class="form-control @error('password') is-invalid @enderror" id="password" name="password" value="{{ old('password') }}">
                            @if ($errors->has('password'))
                                <span class="text-danger">{{ $errors->first('password') }}</span>
                            @endif
                        </div>
                    </div>


                    <div class="mb-3 row">
                        <label for="Confirm Password" class="col-md-4 col-form-label text-md-end text-start">Confirm Password</label>
                        <div class="col-md-6">
                          <input type="text" class="form-control @error('cpassword') is-invalid @enderror" id="cpassword" name="cpassword" value="{{ old('cpassword') }}">
                            @if ($errors->has('cpassword'))
                                <span class="text-danger">{{ $errors->first('cpassword') }}</span>
                            @endif
                        </div>
                    </div>

                    

                 
                    
                    <div class="mb-3 row">
                        <input type="submit" class="col-md-3 offset-md-5 btn btn-primary" value="Change Password">
                    </div>
                    
                </form>
            </div>
        </div>
    </div>    
</div>
    
@endsection