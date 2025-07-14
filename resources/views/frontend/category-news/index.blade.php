@extends('frontend.master')

@section('title')
    Home Page
@endsection

@section('content')


<!-- Featured News Slider Start -->
<div class="container-fluid  mb-3">

    <div class="container">
        @foreach ($news as $news)

        <div class="p-5 text-center">
        <div class="card">
            <div class="row ">
              <div class="col-md-12">
                <img src="{{asset('/')}}{{$news->image}}" class="img-fluid rounded-start" alt="...">
              </div>
              <div class="col-md-12">
                <div class="card-body">
                  <h5 class="card-title">{{$news->title}}</h5>
                  <p class="card-text">{{$news->description}}</p>
                  <p class="card-text"><small class="text-muted">{{$news->created_at}}</small></p>
                </div>
              </div>
            </div>
          </div>
        </div>
            @endforeach
        </div>

    </div>


@endsection
