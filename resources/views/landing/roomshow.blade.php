@extends('layouts.resort')

@section('content')
@include('landing.nav2')

<div class="section room">
    <div class="image-wrapper room">
        <img style="opacity: 1; transform: translate3d(0px, 0px, 0px) scale3d(1.1, 1.1, 1) rotateX(0deg) rotateY(0deg) rotateZ(0deg) skew(0deg, 0deg); transform-style: preserve-3d;"
            data-w-id="7413b28b-5dab-db33-11d3-4d756c53e821"
            data-wf-sku-bindings="%5B%7B%22from%22%3A%22f_main_image_4dr%22%2C%22to%22%3A%22src%22%7D%5D"
            src="{{ $room->coverimage() ? asset('storage/rooms/'.$room->coverimage()->path) : asset('images/img07.jpg') }}"
            alt="" sizes="100vw" class="image room"></div>
    <div class="container">
        <div id="Book-Now" class="room-wrapper">
            <div class="row">
                <div class="col-lg-6">
                    <h1 class="title room">{{ $room->name }}</h1>
                    <p class="paragraph room">{{ $room->descriptions }}</p>
                    <div class="divider room-page"></div>
                            <div class="about-room-page">
                                <div class="split-content card-about-room-left">
                                    <div class="from-text">From</div>
                                    <div class="card-price-wrapper">
                                        <div class="price">{{ number_format($room->price) }}&nbsp;PHP</div>
                                        {{-- <div>&nbsp;/night</div> --}}
                                    </div>
                                </div>
                            </div>
                    {{-- <a href="{{ route('room.book', $room->id) }}"
                        class="btn btn-lg btn-outline-dark button-secondary large radius-zero">Book Now</a> --}}
                    <a href="{{ route('landing.contact') }}"
                        class="btn btn-lg btn-outline-dark button-secondary large radius-zero">Book Now</a>
                </div>
                <div class="col-lg-6">
                    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                        <ol class="carousel-indicators">
                            @foreach ($room->images as $key => $item)
                            <li data-target="#carouselExampleIndicators" data-slide-to="{{ $key }}"
                                class="{{ $loop->first ? 'active' : ''}}"></li>
                            @endforeach
                        </ol>
                        <div class="carousel-inner">
                            @foreach ($room->images as $item)
                            <div class="carousel-item {{ $loop->first ? 'active' : ''}}">
                                <img class="d-block w-100 room-images" src="{{ asset('storage/rooms/'.$item->path) }}"
                                    alt="First slide">
                            </div>
                            @endforeach
                        </div>
                        <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('landing.footer')

@endsection
