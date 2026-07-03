@extends('layouts.app')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-12">
  
        <div class="card">
            <div class="card-header">
                <div class="float-start">
                    <h5>Sub Category Information </h5>
                </div>

                <div class="float-end">
                    <a href="{{ route('admin.subcategory') }}" class="btn btn-primary btn-sm">&larr; Back</a>
                </div>

            </div>
            <div class="card-body">
        

                <!-- /////////////////////service///////////////// -->
                <div class="row justify-content-center">
                    <div class="col-md-12">
                
                        <div class="card">

                          
                                <div class="row">
                                    <label for="name" class="col-md-4 col-form-label text-md-end text-start"><strong>Category:</strong></label>
                                    <div class="col-md-6" style="line-height: 35px;">
                                     @foreach ($category as $catdata)
                                     @if($catdata->id == $cat_id)
                                       <strong style="font-size:15px;">{{  $catdata->name }}</strong> 
                                        @endif
                                     @endforeach
                                    </div>
                           
                        
                           
                            <div class="card-body">
                                <div class="row">
                                    <label for="name" class="col-md-4 col-form-label text-md-end text-start"><strong>Title:</strong></label>
                                    <div class="col-md-6" style="line-height: 35px;">
                                       <span style="font-size:15px;"> {{ $title }} </span>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="row">
                                    <label for="name" class="col-md-4 col-form-label text-md-end text-start"><strong>Description:</strong></label>
                                    <div class="col-md-6" style="line-height: 35px;">
                                      <textarea type="text" class="form-control" id="description" placeholder="Enter your name" name="description" value="" disabled> {{ $description }} </textarea>
                                    </div>
                           
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="row">
                                    <label for="name" class="col-md-4 col-form-label text-md-end text-start"><strong>Image:</strong></label>
                                    <div class="col-md-6" style="line-height: 35px;">
                                        <img src="{{ asset($image) }}" alt="" style="width: 100px; height: auto;">
                                    </div>
                                </div>
                            </div>

                           
                        </div>
                    </div>    
                </div>


        
            </div>
        </div>
    </div>    
</div>
    
@endsection