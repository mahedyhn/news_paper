@extends('frontend.master')

@section('title')
    Home Page
@endsection

@section('content')
    <!-- Main News Slider Start -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 p-5">
                <div class="owl-carousel main-carousel position-relative">
                    @foreach ($news as $news)
                        <div class="position-relative overflow-hidden" style="height: 620px;">
                            <img class="img-fluid h-100" src="{{ asset('/') }}{{ $news->image }}"
                                style="object-fit: cover;">
                            <div class="overlay">
                                <div class="mb-2">
                                    <a class="badge badge-primary text-uppercase font-weight-semi-bold p-2 mr-2"
                                        href=""></a>
                                    <a class="text-white" href="">{{ $news->created_at }}</a>
                                </div>
                                <div class="mb-2">
                                    <a class="badge badge-primary text-uppercase font-weight-semi-bold p-2 mr-2"
                                        href=""></a>
                                    <a class="text-white" href="">{{ $news->category_id }}</a>
                                </div>
                                <a class="h2 m-0 text-white text-uppercase font-weight-bold"
                                    href="">{{ $news->title }}</a>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>

        </div>
    </div>
    <!-- Main News Slider End -->

    {{-- <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="row">
                    <div class="col-12">
                        <div class="section-title">
                            <h4 class="m-0 text-uppercase font-weight-bold">Latest News</h4>
                            <a class="text-secondary font-weight-medium text-decoration-none" href="">View All</a>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="position-relative mb-3">
                            @foreach ($news as $news)
                                <img class="img-fluid w-100" src=""
                                    style="object-fit: cover;">
                                <div class="bg-white border border-top-0 p-4">
                                    <div class="mb-2">
                                        <a class="badge badge-primary text-uppercase font-weight-semi-bold p-2 mr-2"
                                            href=""></a>
                                        <a class="text-body" href=""><small>Jan 01, 2045</small></a>
                                    </div>
                                    <a class="h4 d-block mb-3 text-secondary text-uppercase font-weight-bold"
                                        href=""></a>
                                    <p class="m-0"></p>
                                </div>
                                <div class="d-flex justify-content-between bg-white border border-top-0 p-4">
                                    <div class="d-flex align-items-center">
                                        <img class="rounded-circle mr-2" src="{{asset('/')}}{{$news->image}}" width="25" height="25"
                                            alt="">
                                        <small></small>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <small class="ml-3"><i class="far fa-eye mr-2"></i>12345</small>
                                        <small class="ml-3"><i class="far fa-comment mr-2"></i>123</small>
                                    </div>
                                </div>
                        </div>
                        @endforeach
                    </div>

                </div>
            </div>
        </div>
    </div> --}}
@endsection
