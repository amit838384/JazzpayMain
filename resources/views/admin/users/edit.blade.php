@extends('layouts.app')

@section('content')


<style>
    select {
   -webkit-appearance:none;
   -moz-appearance:none;
   -ms-appearance:none;
   appearance:none;
   outline:0;
   box-shadow:none;
   border:1px solid grey!important;
   background: #405189;
   background-image: none;
   flex: 1;
   padding: 0 .5em;
   color:#fff;
   font-weight: bold;
   cursor:pointer;
   font-size: 1em;
   font-family: 'Open Sans', sans-serif;
}
select::-ms-expand {
   display: none;
}
.select {
   position: relative;
   display: flex;
   width: 20em;
   height: 3em;
   line-height: 3;
   background: #405189;
   overflow: hidden;
   border-radius: .25em;
}
.select::after {
   content: '\25BC';
   position: absolute;
   top: 0;
   right: 0;
   padding: 0 1em;
   background: #405189;
   cursor:pointer;
   pointer-events:none;
   transition:.25s all ease;
}
.select:hover::after {
   color: #fff;
}
</style>
<div class="row justify-content-center">
    <div class="col-md-12">

        <div class="card">
            <div class="card-header">
                <div class="float-start">
                    Edit Service
                </div>
                <div class="float-end">
                    <a href="{{ route('admin.subcategory') }}" class="btn btn-primary btn-sm">&larr; Back</a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('subcategory.update', $id) }}" method="post" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3 row">
                        <label for="categoryid" class="col-md-2 col-form-label text-md-end text-start">Select Category</label>
                        <div class="select" style="margin-left: 15px;">
                           
                            <select name="categoryid" id="categoryid" class="@error('categoryid') is-invalid @enderror">
                                @foreach($category as $catdata)

                                @if($catdata->id == $cat_id)
                               <option  value="{{ $catdata->id }}">{{ $catdata->name }}</option>

                                @endif
                               @endforeach

                                 @foreach($category as $profe)
                               <option  value="{{ $profe->id }}">{{ $profe->name }}</option>
                               @endforeach
                            </select>
                          
                         </div>
                    </div> 
                    <div class="mb-3 row">
                        <label for="title" class="col-md-2 col-form-label text-md-end text-start">Name</label>
                        <div class="col-md-8">
                          <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{$title;}}">
                            @if ($errors->has('title'))
                                <span class="text-danger">{{ $errors->first('title') }}</span>
                            @endif
                        </div>
                    </div>


                    <div class="mb-3 row">
                        <label for="title" class="col-md-2 col-form-label text-md-end text-start">Description</label>
                        <div class="col-md-8">
                            {{-- <label for="fullnameInput" class="form-label"> Description</label> --}}
                            <textarea type="text" class="form-control" id="description" placeholder="Enter your name" name="description" value="">{{ $description }}</textarea>
                            @if ($errors->has('description'))
                                <span class="text-danger">{{ $errors->first('description') }}</span>
                            @endif
                        </div>
                        
                    </div>


                    <!-- Display Old Image -->
                <div class="mb-3 mt-4 row">
                    <label for="old_service_icon" class="col-md-2 col-form-label text-md-end text-start">Old Image</label>
                    <div class="col-md-6">
                        @if (!empty($image))
                            <img src="{{ asset($image) }}" alt="Service Icon" style="width: 100px; height: 100px;">
                        @else
                            No image available
                        @endif
                    </div>
                </div>

    <!-- Upload New Image -->

                    <div class="mb-3 mt-4 row">
                        <label for="images" class="col-md-2 col-form-label text-md-end text-start">Upload Image</label>
                        <div class="col-md-6">
                            <input type="file" class="form-control" id="images" name="images">
                        </div>
                    </div>


                    
                    <div class="mb-3 row">
                        <input type="submit" class="col-md-3 offset-md-5 btn btn-primary" value="Update">
                    </div>
                    
                </form>
            </div>
        </div>
    </div>    
</div>
    
@endsection