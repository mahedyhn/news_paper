@extends('backend.master')
@section('content')
<div id="layoutSidenav_content">
    <div class="row py-5">
        <h1 class="text-center">Add Category</h1>
        <h3 class="text-center text-success">{{Session::get('msg')}}</h3>
        <form class="p-5 " action="{{route('store-category')}}" method="POST">
            @csrf
            <div class="mb-3">
              <label for="exampleInputEmail1" class="form-label">Category Name</label>
              <input type="text" class="form-control" name="name" value="{{old('name')}}">
              @error('name')
                <span class="text-danger">{{$message}}</span>
              @enderror
            </div>
            <div class="mb-3">
              <label class="form-label">Category Description</label>
              <input type="text" class="form-control" name="desc"  value="{{old('desc')}}" >
              @error('desc')
                <span class="text-danger">{{$message}}</span>
              @enderror
            </div>
            <button type="submit" class="btn btn-warning">Add New Category</button>
          </form>
    </div>
</div>
@endsection


