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
                    Add Sub Category
                </div>
                <div class="float-end">
                    <a href="{{ route('admin.category') }}" class="btn btn-primary btn-sm">&larr; Back</a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.subcategoryshowstore') }}" method="post" enctype="multipart/form-data" class="category-form">
                    @csrf
                
                    <!-- Profession Select -->
                    <div class="mb-4 row">
                        <label for="professionselect" class="col-md-3 col-form-label text-md-end text-start">Select Category</label>
                        <div class="col-md-4">
                            <select name="cat_ids" id="cat_ids" class="form-select @error('cat_id') is-invalid @enderror">
                                <option value="">Select Category</option>
                                @foreach($profession as $profe)
                                    <option value="{{ $profe->id }}">{{ $profe->name }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('cat_id'))
                                <span class="text-danger">{{ $errors->first('cat_id') }}</span>
                            @endif
                        </div>
                    </div>
                
                    <!-- Category Name -->
                    <div class="mb-4 row">
                        <label for="category" class="col-md-3 col-form-label text-md-end text-start">Sub Category Name</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control @error('category') is-invalid @enderror" id="category" name="category" value="{{ old('category') }}">
                            @if ($errors->has('category'))
                                <span class="text-danger">{{ $errors->first('category') }}</span>
                            @endif
                        </div>
                    </div>
                
                    <!-- Category Image -->
                    <div class="mb-4 row">
                        <label for="category_image" class="col-md-3 col-form-label text-md-end text-start">Sub Category Image</label>
                        <div class="col-md-9">
                            <input type="file" class="form-control @error('category_image') is-invalid @enderror" id="category_image" name="category_image">
                            @if ($errors->has('category_image'))
                                <span class="text-danger">{{ $errors->first('category_image') }}</span>
                            @endif
                        </div>
                        
                    </div>

                    <div class="mb-4 row">
                        <label for="category_image" class="col-md-3 col-form-label text-md-end text-start">Description</label>
                        <div class="col-md-9">
                            {{-- <label for="fullnameInput" class="form-label"> Description</label> --}}
                            <textarea type="text" class="form-control" id="description" placeholder="Enter your name" name="description" value=""></textarea>
                            @if ($errors->has('category_image'))
                                <span class="text-danger">{{ $errors->first('category_image') }}</span>
                            @endif
                        </div>
                        
                    </div>

                  
                
                    <!-- Submit Button -->
                    <div class="row">
                        <div class="col-md-7 offset-md-3">
                            <button type="submit" class="btn btn-primary btn-lg w-100">Add Sub Category</button>
                        </div>
                    </div>
                </form>
                
            </div>
        </div>
    </div>    
</div>
    
@endsection