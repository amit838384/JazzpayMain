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
   border:0!important;
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
                    About Banner Detail Show
                </div>
                <div class="float-end">
                    <a href="{{ route('admin.aboutbanner') }}" class="btn btn-primary btn-sm">&larr; Back</a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.homebannerupdate', $id) }}" method="post" enctype="multipart/form-data">
                    @csrf

                    
                    <div class="mb-3 mt-4 row">
                        <label for="image" class="col-md-2 col-form-label text-md-end text-start">Banner Image</label>
                        <div class="col-md-6">
                            @if (!empty($image))
                                <img src="{{ asset($image) }}" alt="Service Icon" style="width: 100px; height: 100px;">
                               
                            @else
                       
                                No image available
                            @endif
                        </div>
                    </div>
                    

                    <div class="mb-3 row">
                        <label for="title_one" class="col-md-2 col-form-label text-md-end text-start">Title_one</label>
                        <div class="col-md-8">
                          <input type="text" class="form-control @error('title_one') is-invalid @enderror" id="title_one" name="title_one" value="{{$title_one;}}" readonly>
                            @if ($errors->has('title_one'))
                                <span class="text-danger">{{ $errors->first('title_one') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="title_two" class="col-md-2 col-form-label text-md-end text-start">Title Two</label>
                        <div class="col-md-8">
                          <input type="text" class="form-control @error('title_two') is-invalid @enderror" id="title_two" name="title_two" value="{{$title_two;}}" readonly>
                            @if ($errors->has('title_two'))
                                <span class="text-danger">{{ $errors->first('title_two') }}</span>
                            @endif
                        </div>
                    </div>
                    
                    
                    <div class="mb-3 row">
                        <label for="category" class="col-md-2 col-form-label text-md-end text-start">Description</label>
                        <div class="col-md-8">
                          <textarea type="text" class="form-control @error('description') is-invalid @enderror" id="description" name="description" readonly>{{$description}}</textarea>
                            @if ($errors->has('description'))
                                <span class="text-danger">{{ $errors->first('description') }}</span>
                            @endif
                        </div>
                    </div>
                 
                    
                </form>
            </div>
        </div>
    </div>    
</div>
    
@endsection