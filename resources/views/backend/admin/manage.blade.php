@extends('backend.master')
@section('content')
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <h1 class="mt-4">Manage News</h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item active">{{Session::get('msg')}}</li>
                </ol>
                <div class="row">
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Showing All News
                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple">
                                <thead>
                                <tr>
                                    <th class="p-2">Category</th>
                                    <th>Titile</th>
                                    <th>Description</th>
                                    <th>Image</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($news as $news)

                                <tr>
                                    <td>{{$news->category_id}}</td>
                                    <td>{{$news->title}}</td>
                                    <td>{{$news->description}}</td>
                                    <td><img src="{{asset('/')}}{{$news->image}}" height="70" width="100"></td>
                                    <td>{{$news->status}}</td>
                                    <td>
                                        <a href="{{route('edit',$news->id)}}" class="btn btn-sm btn-primary">Edit</a>
                                        <a href="{{route('news.delete',$news->id)}}" class="btn btn-sm btn-danger">Delete</a>
                                    </td>
                                </tr>

                                @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <footer class="py-4 bg-light mt-auto">
            <div class="container-fluid px-4">
                <div class="d-flex align-items-center justify-content-between small">
                    <div class="text-muted">Copyright &copy; Your Website 2022</div>
                    <div>
                        <a href="#">Privacy Policy</a>
                        &middot;
                        <a href="#">Terms &amp; Conditions</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>
@endsection

