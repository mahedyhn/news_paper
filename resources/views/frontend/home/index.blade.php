@extends('frontend.master')

@section('title')
    Home Page
@endsection

@section('content')
    <!-- Modern News Carousel Start -->
    <div class="container-fluid carousel-container">
        <div class="carousel-inner-wrapper">
            <div class="owl-carousel main-carousel">
                @foreach ($news->take(5) as $item)
                    <div class="carousel-slide">
                        <div class="slide-image-wrapper">
                            <img class="slide-image" src="{{ asset('/') }}{{ $item->image }}" alt="{{ $item->title }}">
                            <div class="slide-overlay"></div>
                        </div>
                        <div class="slide-content">
                            <div class="slide-content-inner">
                                <div class="slide-meta">
                                    <span class="badge-category">{{ $item->category->name ?? 'News' }}</span>
                                    <span class="meta-separator">•</span>
                                    <span class="slide-date">{{ $item->created_at->format('M d, Y') }}</span>
                                </div>
                                <h1 class="slide-title">{{ $item->title }}</h1>
                                <p class="slide-excerpt">{{ Str::limit($item->description, 120) }}</p>
                                <div class="slide-footer">
                                    <div class="author-info">
                                        <i class="fas fa-user-circle"></i>
                                        <span>{{ $item->author }}</span>
                                    </div>
                                    <a href="#newsModal{{ $item->id }}" class="read-more-btn" data-toggle="modal" data-target="#newsModal{{ $item->id }}">
                                        Read Full Story
                                        <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Carousel Controls -->
            <div class="carousel-controls">
                <button class="control-btn prev-btn" aria-label="Previous slide">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="control-btn next-btn" aria-label="Next slide">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
    </div>
    <!-- Modern News Carousel End -->


    <!-- Category Statistics Section -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="section-title">
                        <h4 class="m-0 text-uppercase font-weight-bold">Categories Overview</h4>
                    </div>
                </div>
                @forelse($categories as $category)
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="card border-0 shadow">
                            <div class="card-body text-center">
                                <h5 class="card-title text-primary font-weight-bold">{{ $category->name }}</h5>
                                <p class="card-text text-muted">{{ $category->desc ?? 'Category news' }}</p>
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <span class="badge badge-primary badge-lg">{{ $category->newspapers->count() }} News</span>
                                    <a href="{{ route('blogsByCategory', $category->id) }}"
                                        class="btn btn-sm btn-primary">View</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <p class="text-center text-muted">No categories available</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
    <!-- Category Statistics Section End -->

    <!-- Latest News Section with Table -->
    <div class="container-fluid py-5 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="section-title">
                        <h4 class="m-0 text-uppercase font-weight-bold">Latest News</h4>
                    </div>
                </div>
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th>Image</th>
                                    <th>Title</th>
                                    <th>Category</th>
                                    <th>Author</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($news as $item)
                                    <tr>
                                        <td>
                                            <img src="{{ asset('/') }}{{ $item->image }}" alt="{{ $item->title }}"
                                                style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                                        </td>
                                        <td>
                                            <strong>{{ Str::limit($item->title, 50) }}</strong>
                                        </td>
                                        <td>
                                            <span class="badge badge-info">{{ $item->category->name ?? 'N/A' }}</span>
                                        </td>
                                        <td>{{ $item->author }}</td>
                                        <td>
                                            <small class="text-muted">{{ $item->created_at->format('M d, Y') }}</small>
                                        </td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-primary" data-toggle="modal"
                                                data-target="#newsModal{{ $item->id }}">
                                                <i class="far fa-eye"></i> View
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">No news articles available</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Latest News Section End -->

    <!-- Breaking News Section -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="section-title">
                        <h4 class="m-0 text-uppercase font-weight-bold">Breaking News</h4>
                    </div>
                </div>
                @forelse($news->take(6) as $item)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card border-0 shadow h-100 overflow-hidden hover-effect">
                            <img class="card-img-top" src="{{ asset('/') }}{{ $item->image }}" alt="{{ $item->title }}"
                                style="height: 200px; object-fit: cover;">
                            <div class="card-body d-flex flex-column">
                                <div class="mb-2">
                                    <span
                                        class="badge badge-danger font-weight-semi-bold">{{ $item->category->name ?? 'News' }}</span>
                                    <small class="text-muted ml-2">{{ $item->created_at->format('M d, Y') }}</small>
                                </div>
                                <h5 class="card-title font-weight-bold">{{ Str::limit($item->title, 60) }}</h5>
                                <p class="card-text text-muted flex-grow-1">{{ Str::limit($item->description, 100) }}</p>
                                <div class="d-flex justify-content-between align-items-center mt-auto pt-3 border-top">
                                    <small><i class="fas fa-user"></i> {{ $item->author }}</small>
                                    <a href="#" class="text-primary font-weight-bold" data-toggle="modal"
                                        data-target="#newsModal{{ $item->id }}">
                                        Read More →
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <p class="text-center text-muted">No breaking news available</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
    <!-- Breaking News Section End -->

    <!-- News Statistics Section -->
    <div class="container-fluid py-5 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="section-title">
                        <h4 class="m-0 text-uppercase font-weight-bold">Portal Statistics</h4>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card border-0 shadow text-center">
                        <div class="card-body">
                            <i class="fas fa-newspaper fa-3x text-primary mb-3"></i>
                            <h5 class="card-title font-weight-bold">Total Articles</h5>
                            <h2 class="text-primary font-weight-bold">{{ $news->count() }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card border-0 shadow text-center">
                        <div class="card-body">
                            <i class="fas fa-list fa-3x text-success mb-3"></i>
                            <h5 class="card-title font-weight-bold">Total Categories</h5>
                            <h2 class="text-success font-weight-bold">{{ $categories->count() }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card border-0 shadow text-center">
                        <div class="card-body">
                            <i class="fas fa-calendar-alt fa-3x text-warning mb-3"></i>
                            <h5 class="card-title font-weight-bold">Today's Articles</h5>
                            <h2 class="text-warning font-weight-bold">
                                {{ $news->where('created_at', '>=', now()->startOfDay())->count() }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card border-0 shadow text-center">
                        <div class="card-body">
                            <i class="fas fa-fire fa-3x text-danger mb-3"></i>
                            <h5 class="card-title font-weight-bold">Popular This Week</h5>
                            <h2 class="text-danger font-weight-bold">
                                {{ $news->where('created_at', '>=', now()->subWeek())->count() }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- News Statistics Section End -->

    <!-- Article Details Modals -->
    @foreach($news as $item)
        <div class="modal fade" id="newsModal{{ $item->id }}" tabindex="-1" role="dialog"
            aria-labelledby="newsModalLabel{{ $item->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="newsModalLabel{{ $item->id }}">{{ $item->title }}</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <img src="{{ asset('/') }}{{ $item->image }}" alt="{{ $item->title }}" class="img-fluid mb-3"
                            style="max-height: 400px; object-fit: cover; width: 100%; border-radius: 6px;">
                        <div class="mb-3">
                            <span class="badge badge-primary">{{ $item->category->name ?? 'News' }}</span>
                            <small class="text-muted ml-2"><i class="far fa-calendar"></i>
                                {{ $item->created_at->format('M d, Y H:i') }}</small>
                            <small class="text-muted ml-3"><i class="fas fa-user"></i> {{ $item->author }}</small>
                        </div>
                        <p class="text-justify">{{ $item->description }}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    <!-- Article Details Modals End -->

    <style>
        /* Modern Carousel Styles */
        .carousel-container {
            width: 100%;
            padding: 0 !important;
            margin: 0 !important;
            display: block;
        }

        .carousel-inner-wrapper {
            position: relative;
            width: 100%;
            height: 500px;
            overflow: hidden;
            background: #000;
            display: block;
        }

        .main-carousel {
            height: 100%;
            width: 100%;
            display: block;
        }

        .carousel-slide {
            position: relative;
            height: 100% !important;
            width: 100% !important;
            display: flex !important;
            align-items: center;
            overflow: hidden;
        }

        .slide-image-wrapper {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
        }

        .slide-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.8s ease-out;
            display: block;
        }

        .carousel-slide:hover .slide-image {
            transform: scale(1.05);
        }

        .slide-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, 
                rgba(0, 0, 0, 0.8) 0%,
                rgba(0, 0, 0, 0.6) 30%,
                rgba(0, 0, 0, 0.4) 60%,
                transparent 100%);
            z-index: 2;
        }

        .slide-content {
            position: relative;
            z-index: 3;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            color: white;
            padding: 60px 5%;
            background: transparent;
        }

        .slide-content-inner {
            width: 100%;
            max-width: 600px;
            animation: slideInUp 0.9s cubic-bezier(0.4, 0, 0.2, 1) forwards;
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(60px);
                filter: blur(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
                filter: blur(0);
            }
        }

        .slide-meta {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 2px;
            flex-wrap: wrap;
        }

        .badge-category {
            background: linear-gradient(135deg, #ff6b6b 0%, #ff4757 100%);
            color: white;
            padding: 8px 18px;
            border-radius: 30px;
            font-weight: 700;
            font-size: 11px;
            box-shadow: 0 8px 25px rgba(255, 71, 87, 0.35);
            letter-spacing: 0.5px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .meta-separator {
            opacity: 0.7;
            font-weight: bold;
        }

        .slide-date {
            opacity: 0.9;
            font-weight: 500;
        }

        .slide-title {
            font-size: 48px;
            font-weight: 900;
            line-height: 1.1;
            margin: 15px 0 25px 0;
            text-shadow: 3px 3px 15px rgba(0, 0, 0, 0.7);
            letter-spacing: -0.5px;
            word-spacing: 3px;
        }

        .slide-excerpt {
            font-size: 16px;
            line-height: 1.7;
            margin-bottom: 30px;
            opacity: 1;
            max-width: 100%;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.5);
            color: rgba(255, 255, 255, 0.98);
        }

        .slide-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            padding-top: 25px;
            margin-top: 20px;
            border-top: 2px solid rgba(255, 255, 255, 0.15);
            flex-wrap: wrap;
        }

        .author-info {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 14px;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.95);
        }

        .author-info i {
            font-size: 22px;
            color: #ff6b6b;
        }

        .read-more-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 14px 32px;
            background: linear-gradient(135deg, #ff4757 0%, #ff6b6b 100%);
            color: white;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 700;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 10px 35px rgba(255, 71, 87, 0.45);
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            white-space: nowrap;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .read-more-btn:hover {
            transform: translateY(-5px);
            box-shadow: 0 18px 45px rgba(255, 71, 87, 0.55);
            color: white;
            text-decoration: none;
        }

        .read-more-btn:active,
        .read-more-btn:focus {
            text-decoration: none;
            color: white;
            outline: none;
        }

        .read-more-btn i {
            transition: transform 0.3s ease;
        }

        .read-more-btn:hover i {
            transform: translateX(3px);
        }

        /* Carousel Dots */
        /* Carousel Controls */
        .carousel-controls {
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            transform: translateY(-50%);
            display: flex;
            justify-content: space-between;
            padding: 0;
            z-index: 9;
            pointer-events: none;
        }

        .control-btn {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.12);
            border: 2px solid rgba(255, 255, 255, 0.25);
            color: white;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            pointer-events: auto;
            font-size: 18px;
            backdrop-filter: blur(15px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
            padding: 0;
            margin: 0;
        }

        .control-btn:hover {
            background: rgba(255, 71, 87, 0.9);
            border-color: rgba(255, 255, 255, 0.4);
            transform: scale(1.15);
            box-shadow: 0 15px 40px rgba(255, 71, 87, 0.5);
        }

        .control-btn:active {
            transform: scale(0.95);
        }

        .control-btn:focus {
            outline: 2px solid rgba(255, 255, 255, 0.5);
            outline-offset: 2px;
        }

        .prev-btn:hover i,
        .next-btn:hover i {
            filter: brightness(1.2);
        }

        .prev-btn i,
        .next-btn i {
            transition: filter 0.3s ease;
        }

        .section-title {
            border-bottom: 3px solid #dc3545;
            padding-bottom: 15px;
            margin-bottom: 30px;
        }

        .hover-effect {
            transition: all 0.3s ease-in-out;
        }

        .hover-effect:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2) !important;
        }

        .table-hover tbody tr:hover {
            background-color: #f5f5f5 !important;
        }

        .badge-lg {
            padding: 10px 15px !important;
            font-size: 14px !important;
        }

        /* Fix Owl Carousel spacing */
        .owl-carousel .owl-stage,
        .owl-carousel .owl-stage-outer {
            margin: 0;
            padding: 0;
        }

        /* Responsive Design - Extra Large Screens */
        @media (min-width: 1400px) {
            .carousel-inner-wrapper {
                height: 600px;
            }

            .slide-title {
                font-size: 56px;
            }
        }

        /* Responsive Design - Large Screens */
        @media (max-width: 1200px) {
            .carousel-inner-wrapper {
                height: 550px;
            }

            .slide-content {
                padding: 50px 4%;
            }

            .slide-title {
                font-size: 42px;
                margin: 12px 0 20px 0;
            }

            .slide-excerpt {
                font-size: 15px;
                margin-bottom: 20px;
            }
        }

        /* Responsive Design - Tablets */
        @media (max-width: 1024px) {
            .carousel-inner-wrapper {
                height: 450px;
            }

            .slide-content {
                padding: 45px 3%;
            }

            .slide-title {
                font-size: 36px;
                margin: 10px 0 18px 0;
            }

            .slide-excerpt {
                font-size: 14px;
                margin-bottom: 18px;
            }

            .read-more-btn {
                padding: 12px 26px;
                font-size: 12px;
            }

            .control-btn {
                width: 45px;
                height: 45px;
                font-size: 16px;
            }
        }

        @media (max-width: 768px) {
            .carousel-inner-wrapper {
                height: 420px;
            }

            .slide-content {
                padding: 40px 3%;
            }

            .slide-title {
                font-size: 30px;
                margin: 10px 0 15px 0;
                font-weight: 800;
            }

            .slide-excerpt {
                font-size: 13px;
                margin-bottom: 15px;
                line-height: 1.5;
            }

            .read-more-btn {
                padding: 11px 24px;
                font-size: 11px;
            }

            .control-btn {
                width: 40px;
                height: 40px;
                font-size: 15px;
            }

            .badge-category {
                padding: 6px 14px;
                font-size: 10px;
            }
        }

        @media (max-width: 600px) {
            .carousel-inner-wrapper {
                height: 360px;
            }

            .slide-content {
                padding: 35px 2.5%;
            }

            .slide-title {
                font-size: 26px;
                line-height: 1.3;
                margin: 10px 0 12px 0;
                font-weight: 800;
            }

            .slide-excerpt {
                font-size: 13px;
                margin-bottom: 12px;
                line-height: 1.5;
            }

            .slide-meta {
                font-size: 11px;
                margin-bottom: 10px;
                gap: 6px;
            }

            .badge-category {
                padding: 5px 12px;
                font-size: 10px;
            }

            .carousel-controls {
                padding: 0 12px;
            }

            .control-btn {
                width: 36px;
                height: 36px;
                font-size: 15px;
            }

            .slide-footer {
                gap: 12px;
                padding-top: 12px;
            }

            .read-more-btn {
                padding: 9px 18px;
                font-size: 12px;
                gap: 6px;
            }

            .author-info {
                font-size: 12px;
            }

            .author-info i {
                font-size: 18px;
            }
        }

        @media (max-width: 480px) {
            .carousel-inner-wrapper {
                height: 300px;
            }

            .slide-content {
                padding: 30px 2%;
                align-items: center;
            }

            .slide-content-inner {
                width: 100%;
            }

            .slide-title {
                font-size: 22px;
                line-height: 1.3;
                margin: 8px 0 10px 0;
                font-weight: 800;
            }

            .slide-excerpt {
                display: none;
            }

            .slide-meta {
                font-size: 10px;
                margin-bottom: 8px;
                gap: 5px;
            }

            .badge-category {
                padding: 4px 10px;
                font-size: 9px;
            }

            .meta-separator {
                display: none;
            }

            .carousel-controls {
                padding: 0 10px;
            }

            .control-btn {
                width: 32px;
                height: 32px;
                font-size: 14px;
            }

            .slide-footer {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
                padding-top: 10px;
                width: 100%;
            }

            .read-more-btn {
                padding: 8px 16px;
                font-size: 11px;
                gap: 5px;
                width: 100%;
                justify-content: center;
            }

            .author-info {
                font-size: 11px;
                gap: 6px;
            }

            .author-info i {
                font-size: 16px;
            }
        }

        @media (max-width: 340px) {
            .carousel-inner-wrapper {
                height: 280px;
            }

            .slide-title {
                font-size: 18px;
            }

            .control-btn {
                width: 28px;
                height: 28px;
                font-size: 12px;
            }

            .read-more-btn {
                padding: 6px 12px;
                font-size: 10px;
            }
        }
    </style>

    <script>
        $(document).ready(function () {
            // Initialize Owl Carousel
            var $carousel = $(".main-carousel");
            var carouselInstance = $carousel.owlCarousel({
                items: 1,
                loop: true,
                margin: 0,

    .slide-meta {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 15px;
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 1px;
    flex-wrap: wrap;
    }

    .badge-category {
    background: linear-gradient(135deg, #dc3545, #ff6b6b);
    color: white;
    padding: 8px 16px;
    border-radius: 25px;
    font-weight: 600;
    font-size: 12px;
    box-shadow: 0 4px 15px rgba(220, 53, 69, 0.4);
    }

    .meta-separator {
    opacity: 0.7;
    font-weight: bold;
    }

    .slide-date {
    opacity: 0.9;
    font-weight: 500;
    }

    .slide-title {
    font-size: 48px;
    font-weight: 800;
    line-height: 1.2;
    margin: 15px 0 20px 0;
    text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.5);
    letter-spacing: -0.5px;
    }

    .slide-excerpt {
    font-size: 16px;
    line-height: 1.6;
    margin-bottom: 25px;
    opacity: 0.95;
    max-width: 100%;
    }

    .slide-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
    padding-top: 20px;
    border-top: 1px solid rgba(255, 255, 255, 0.2);
    flex-wrap: wrap;
    }

    .author-info {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 14px;
    font-weight: 500;
    }

    .author-info i {
    font-size: 24px;
    }

    .read-more-btn {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 12px 28px;
    background: linear-gradient(135deg, #dc3545, #ff6b6b);
    color: white;
    text-decoration: none;
    border-radius: 50px;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: 0 6px 20px rgba(220, 53, 69, 0.4);
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    white-space: nowrap;
    }

    .read-more-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 30px rgba(220, 53, 69, 0.6);
    color: white;
    text-decoration: none;
    }

    .read-more-btn i {
    transition: transform 0.3s ease;
    }

    .read-more-btn:hover i {
    transform: translateX(3px);
    }

    /* Carousel Controls */
    .carousel-controls {
    position: absolute;
    top: 50%;
    left: 0;
    right: 0;
    transform: translateY(-50%);
    display: flex;
    justify-content: space-between;
    padding: 0 30px;
    z-index: 9;
    pointer-events: none;
    }

    .control-btn {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.15);
    border: 2px solid rgba(255, 255, 255, 0.3);
    color: white;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    pointer-events: auto;
    font-size: 18px;
    backdrop-filter: blur(10px);
    }

    .control-btn:hover {
    background: rgba(220, 53, 69, 0.8);
    border-color: #dc3545;
    transform: scale(1.1);
    box-shadow: 0 6px 20px rgba(220, 53, 69, 0.4);
    }

    .control-btn:active {
    transform: scale(0.95);
    }

    .section-title {
    border-bottom: 3px solid #dc3545;
    padding-bottom: 15px;
    margin-bottom: 30px;
    }

    .hover-effect {
    transition: all 0.3s ease-in-out;
    }

    .hover-effect:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2) !important;
    }

    .table-hover tbody tr:hover {
    background-color: #f5f5f5 !important;
    }

    .badge-lg {
    padding: 10px 15px !important;
    font-size: 14px !important;
    }

    /* Responsive Design - Extra Large Screens */
    @media (min-width: 1400px) {
    .carousel-inner-wrapper {
    height: 700px;
    }

    .slide-title {
    font-size: 56px;
    }
    }

    /* Responsive Design - Large Screens */
    @media (max-width: 1200px) {
    .carousel-inner-wrapper {
    height: 600px;
    }

    .slide-content {
    padding: 50px 4%;
    }

    .slide-title {
    font-size: 44px;
    }

    .slide-excerpt {
    font-size: 15px;
    }
    }

    /* Responsive Design - Tablets */
    @media (max-width: 1024px) {
    .carousel-inner-wrapper {
    height: 520px;
    }

    .slide-content {
    padding: 45px 3%;
    }

    .slide-title {
    font-size: 38px;
    }

    .carousel-controls {
    padding: 0 20px;
    }

    .control-btn {
    width: 45px;
    height: 45px;
    font-size: 17px;
    }

    .slide-excerpt {
    max-width: 100%;
    font-size: 14px;
    }
    }

    @media (max-width: 768px) {
    .carousel-inner-wrapper {
    height: 450px;
    }

    .slide-content {
    padding: 40px 3%;
    }

    .slide-content-inner {
    max-width: 100%;
    }

    .slide-title {
    font-size: 32px;
    margin: 12px 0 16px 0;
    }

    .slide-excerpt {
    font-size: 14px;
    margin-bottom: 15px;
    line-height: 1.4;
    }

    .carousel-controls {
    padding: 0 15px;
    }

    .control-btn {
    width: 40px;
    height: 40px;
    font-size: 16px;
    }

    .slide-footer {
    flex-direction: column;
    align-items: flex-start;
    gap: 15px;
    padding-top: 15px;
    border-top: 1px solid rgba(255, 255, 255, 0.15);
    }

    .slide-meta {
    font-size: 12px;
    gap: 8px;
    margin-bottom: 12px;
    }

    .badge-category {
    padding: 6px 14px;
    font-size: 11px;
    }

    .read-more-btn {
    padding: 10px 22px;
    font-size: 13px;
    gap: 8px;
    }

    .author-info {
    font-size: 13px;
    gap: 8px;
    }

    .author-info i {
    font-size: 20px;
    }
    }

    @media (max-width: 600px) {
    .carousel-inner-wrapper {
    height: 380px;
    }

    .slide-content {
    padding: 35px 2.5%;
    }

    .slide-title {
    font-size: 26px;
    line-height: 1.3;
    margin: 10px 0 12px 0;
    }

    .slide-excerpt {
    font-size: 13px;
    margin-bottom: 12px;
    }

    .slide-meta {
    font-size: 11px;
    margin-bottom: 10px;
    gap: 6px;
    }

    .badge-category {
    padding: 5px 12px;
    font-size: 10px;
    }

    .carousel-controls {
    padding: 0 12px;
    }

    .control-btn {
    width: 36px;
    height: 36px;
    font-size: 15px;
    }

    .slide-footer {
    gap: 12px;
    padding-top: 12px;
    }

    .read-more-btn {
    padding: 9px 18px;
    font-size: 12px;
    gap: 6px;
    }

    .author-info {
    font-size: 12px;
    }

    .author-info i {
    font-size: 18px;
    }
    }

    @media (max-width: 480px) {
    .carousel-inner-wrapper {
    height: 320px;
    }

    .slide-content {
    padding: 30px 2%;
    align-items: flex-end;
    }

    .slide-content-inner {
    width: 100%;
    }

    .slide-title {
    font-size: 22px;
    line-height: 1.3;
    margin: 8px 0 10px 0;
    }

    .slide-excerpt {
    display: none;
    }

    .slide-meta {
    font-size: 10px;
    margin-bottom: 8px;
    gap: 5px;
    }

    .badge-category {
    padding: 4px 10px;
    font-size: 9px;
    }

    .meta-separator {
    display: none;
    }

    .carousel-controls {
    padding: 0 10px;
    }

    .control-btn {
    width: 32px;
    height: 32px;
    font-size: 14px;
    }

    .slide-footer {
    flex-direction: column;
    align-items: flex-start;
    gap: 10px;
    padding-top: 10px;
    width: 100%;
    }

    .read-more-btn {
    padding: 8px 16px;
    font-size: 11px;
    gap: 5px;
    width: 100%;
    justify-content: center;
    }

    .author-info {
    font-size: 11px;
    gap: 6px;
    }

    .author-info i {
    font-size: 16px;
    }
    }

    @media (max-width: 340px) {
    .carousel-inner-wrapper {
    height: 280px;
    }

    .slide-title {
    font-size: 18px;
    }

    .control-btn {
    width: 28px;
    height: 28px;
    font-size: 12px;
    }

    .read-more-btn {
    padding: 6px 12px;
    font-size: 10px;
    }
    }
    </style>

    .main-carousel {
    height: 100%;
    width: 100%;
    }

    .carousel-slide {
    position: relative;
    height: 100% !important;
    width: 100% !important;
    display: flex !important;
    align-items: center;
    overflow: hidden;
    }

    .slide-image-wrapper {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    overflow: hidden;
    }

    .slide-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.8s ease-out;
    display: block;
    }

    .carousel-slide:hover .slide-image {
    transform: scale(1.05);
    }

    .slide-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, rgba(0, 0, 0, 0.4) 0%, rgba(0, 0, 0, 0.6) 50%, rgba(220, 53, 69, 0.3) 100%);
    z-index: 2;
    }

    .slide-content {
    position: relative;
    z-index: 3;
    width: 100%;
    height: 100%;
    display: flex;
    align-items: flex-end;
    color: white;
    padding: 60px 40px;
    background: linear-gradient(to top, rgba(0, 0, 0, 0.8) 0%, transparent 40%);
    }

    .slide-content-inner {
    width: 100%;
    max-width: 700px;
    animation: slideInUp 0.8s ease-out;
    }

    @keyframes slideInUp {
    from {
    opacity: 0;
    transform: translateY(40px);
    }
    to {
    opacity: 1;
    transform: translateY(0);
    }
    }

    .slide-meta {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 15px;
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 1px;
    }

    .badge-category {
    background: linear-gradient(135deg, #dc3545, #ff6b6b);
    color: white;
    padding: 8px 16px;
    border-radius: 25px;
    font-weight: 600;
    font-size: 12px;
    box-shadow: 0 4px 15px rgba(220, 53, 69, 0.4);
    }

    .meta-separator {
    opacity: 0.7;
    font-weight: bold;
    }

    .slide-date {
    opacity: 0.9;
    font-weight: 500;
    }

    .slide-title {
    font-size: 48px;
    font-weight: 800;
    line-height: 1.2;
    margin: 15px 0 20px 0;
    text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.5);
    letter-spacing: -0.5px;
    }

    .slide-excerpt {
    font-size: 16px;
    line-height: 1.6;
    margin-bottom: 25px;
    opacity: 0.95;
    max-width: 600px;
    }

    .slide-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
    padding-top: 20px;
    border-top: 1px solid rgba(255, 255, 255, 0.2);
    }

    .author-info {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 14px;
    font-weight: 500;
    }

    .author-info i {
    font-size: 24px;
    }

    .read-more-btn {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 12px 28px;
    background: linear-gradient(135deg, #dc3545, #ff6b6b);
    color: white;
    text-decoration: none;
    border-radius: 50px;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: 0 6px 20px rgba(220, 53, 69, 0.4);
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    }

    .read-more-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 30px rgba(220, 53, 69, 0.6);
    color: white;
    }

    .read-more-btn i {
    transition: transform 0.3s ease;
    }

    .read-more-btn:hover i {
    transform: translateX(3px);
    }

    /* Carousel Controls */
    .carousel-controls {
    position: absolute;
    top: 50%;
    left: 0;
    right: 0;
    transform: translateY(-50%);
    display: flex;
    justify-content: space-between;
    padding: 0 30px;
    z-index: 9;
    pointer-events: none;
    }

    .control-btn {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.15);
    border: 2px solid rgba(255, 255, 255, 0.3);
    color: white;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    pointer-events: auto;
    font-size: 18px;
    backdrop-filter: blur(10px);
    }

    .control-btn:hover {
    background: rgba(220, 53, 69, 0.8);
    border-color: #dc3545;
    transform: scale(1.1);
    box-shadow: 0 6px 20px rgba(220, 53, 69, 0.4);
    }

    .control-btn:active {
    transform: scale(0.95);
    }

    .section-title {
    border-bottom: 3px solid #dc3545;
    padding-bottom: 15px;
    margin-bottom: 30px;
    }

    .hover-effect {
    transition: all 0.3s ease-in-out;
    }

    .hover-effect:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2) !important;
    }

    .table-hover tbody tr:hover {
    background-color: #f5f5f5 !important;
    }

    .badge-lg {
    padding: 10px 15px !important;
    font-size: 14px !important;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
    .modern-carousel-wrapper {
    height: 450px;
    }

    .slide-content {
    padding: 40px 25px;
    }

    .slide-title {
    font-size: 32px;
    }

    .slide-excerpt {
    font-size: 14px;
    }

    .carousel-controls {
    padding: 0 15px;
    }

    .control-btn {
    width: 40px;
    height: 40px;
    font-size: 16px;
    }

    .slide-footer {
    flex-direction: column;
    align-items: flex-start;
    gap: 15px;
    }
    }

    @media (max-width: 480px) {
    .modern-carousel-wrapper {
    height: 350px;
    }

    .slide-content {
    padding: 30px 20px;
    }

    .slide-title {
    font-size: 24px;
    }

    .slide-excerpt {
    display: none;
    }

    .slide-meta {
    font-size: 12px;
    gap: 8px;
    }

    .badge-category {
    padding: 6px 12px;
    font-size: 10px;
    }

    }
    </style>

    <script>
        $(document).ready(function () {
            // Initialize Owl Carousel
            var $carousel = $(".main-carousel");
            var carouselInstance = $carousel.owlCarousel({
                items: 1,
                loop: true,
                margin: 0,
                dots: false,
                nav: false,
                autoplay: true,
                autoplayTimeout: 6000,
                autoplayHoverPause: true,
                animateOut: 'fadeOut',
                animateIn: 'fadeIn',
                dragEndSpeed: 500,
                smartSpeed: 800,
                responsive: {
                    0: { items: 1 },
                    480: { items: 1 },
                    768: { items: 1 },
                    992: { items: 1 },
                    1200: { items: 1 }
                }
            });

            // Get carousel data
            var owl = carouselInstance.data('owl.carousel');
            var totalSlides = owl._items.length;
            var isNavigating = false;

            // Handle previous button navigation
            $('.prev-btn').click(function (e) {
                e.preventDefault();
                e.stopPropagation();
                if (!isNavigating) {
                    isNavigating = true;
                    $carousel.trigger('prev.owl.carousel');
                    setTimeout(function () { isNavigating = false; }, 100);
                }
            });

            // Handle next button navigation
            $('.next-btn').click(function (e) {
                e.preventDefault();
                e.stopPropagation();
                if (!isNavigating) {
                    isNavigating = true;
                    $carousel.trigger('next.owl.carousel');
                    setTimeout(function () { isNavigating = false; }, 100);
                }
            });

            // Add focus states for accessibility
            $('.prev-btn, .next-btn').on('focus', function () {
                $(this).css('outline', 'none');
            });

            // Keyboard navigation
            $(document).keydown(function (e) {
                if (e.keyCode === 37) { // Left arrow
                    if (!isNavigating) {
                        isNavigating = true;
                        $carousel.trigger('prev.owl.carousel');
                        setTimeout(function () { isNavigating = false; }, 100);
                    }
                } else if (e.keyCode === 39) { // Right arrow
                    if (!isNavigating) {
                        isNavigating = true;
                        $carousel.trigger('next.owl.carousel');
                        setTimeout(function () { isNavigating = false; }, 100);
                    }
                }
            });

            // Better title truncation
            function truncateTitle() {
                var screenWidth = $(window).width();
                if (screenWidth < 768) {
                    $('.slide-title').each(function () {
                        var text = $(this).text();
                        if (text.length > 40) {
                            $(this).text(text.substring(0, 40) + '...');
                        }
                    });
                }
            }

            truncateTitle();
            $(window).resize(truncateTitle);
        });
    </script>
@endsection