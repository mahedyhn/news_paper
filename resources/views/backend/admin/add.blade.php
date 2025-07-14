@extends('backend.master')

@section('content')
    <div id="layoutSidenav_content">
        <section class="my-5 bg-light">
            <div class="container">
                <div class="row">
                    <div class="col-md-8 offset-2">
                        <h1 class="text-center">Add News</h1>
                        @if (Session::get('msg'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>{{Session::get('msg')}}</strong>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                          </div>
                        @endif

                        <form class="p-5 bg-warning rounded" method="POST" action="{{route('store')}}" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-3">
                                <label for="exampleInputEmail1" class="form-label">Category</label>
                                <select name="category_id">
                                  <option value="" disabled>--- Select a Category ---</option>
                                  @foreach ($categories as $category)
                                    <option value="{{$category->id}}">{{$category->name}}</option>
                                  @endforeach

                                </select>
                                @error('title')
                                  <span class="text-danger">{{$message}}</span>
                                @enderror

                            <div class="mb-3">
                              <label class="form-label">News Title</label>
                              <input type="text" class="form-control" name="title" placeholder="news title">
                            </div>


                            <div class="mb-3">
                              <label class="form-label">News Description</label>
                              <textarea placeholder="description" name="description"  class="form-control" id="summernote" ></textarea>
                            </div>

                            <div class="mb-3">
                              <label class="form-label">News Image</label>
                              <input type="file" class="form-control" name="image" accept="image/*">
                            </div>
                            <button type="submit" class="btn btn-primary">Add News</button>
                          </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('scripts')
<script>
    $('#summernote').summernote({
      placeholder: 'Hello stand alone ui',
      tabsize: 2,
      height: 120,
      toolbar: [
        ['style', ['style']],
        ['font', ['bold', 'underline', 'clear']],
        ['color', ['color']],
        ['para', ['ul', 'ol', 'paragraph']],
        ['table', ['table']],
        ['insert', ['link', 'picture', 'video']],
        ['view', ['fullscreen', 'codeview', 'help']]
      ]
    });
  </script>
@endsection


