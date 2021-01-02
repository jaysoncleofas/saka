@extends('layouts.resort')

@section('content')
@include('landing.nav2')

<div class="section rooms">
    <div data-w-id="bdd05fbd-ef66-976b-a0f3-8eee1cbe19fb"
        style="transform: translate3d(0px, 0px, 0px) scale3d(1, 1, 1) rotateX(0deg) rotateY(0deg) rotateZ(0deg) skew(0deg, 0deg); opacity: 1; transform-style: preserve-3d;"
        class="container-small-616px text-center w-container">
        <h1 class="title rooms">Rooms</h1>
        <p>Find and reserve your selected room and get the lowest prices.</p>
    </div>
    <div class="container">
        <div class="w-dyn-list">
            <div role="list" class="rooms-grid w-dyn-items">
                @foreach ($rooms as $room)
                <div data-w-id="ec7088c0-5648-cb50-7d44-82b7954e22d6"
                    style="transform: translate3d(0px, 0px, 0px) scale3d(1, 1, 1) rotateX(0deg) rotateY(0deg) rotateZ(0deg) skew(0deg, 0deg); opacity: 1; transform-style: preserve-3d;"
                    role="listitem" class="w-dyn-item">
                    <div data-w-id="a401d7a1-981e-8de5-af70-01e85569058d" class="room-page-wrapper room-page">
                        <span class="image-wrapper w-inline-block"><img
                                src="{{ $room->coverimage() ? asset('storage/rooms/'.$room->coverimage()->path) : asset('images/img07.jpg') }}"
                                alt=""
                                sizes="(max-width: 479px) 100vw, (max-width: 767px) 90vw, (max-width: 991px) 530px, (max-width: 1919px) 46vw, 576px"
                                {{-- srcset="https://assets.website-files.com/5f28567562c2bb18b4a14f33/5f32cfd10e90f5949c551038_image-room-thumbnail-01-hotel-template-p-500.jpeg 500w, https://assets.website-files.com/5f28567562c2bb18b4a14f33/5f32cfd10e90f5949c551038_image-room-thumbnail-01-hotel-template-p-800.jpeg 800w, https://assets.website-files.com/5f28567562c2bb18b4a14f33/5f32cfd10e90f5949c551038_image-room-thumbnail-01-hotel-template-p-1080.jpeg 1080w, https://assets.website-files.com/5f28567562c2bb18b4a14f33/5f32cfd10e90f5949c551038_image-room-thumbnail-01-hotel-template.jpg 1152w" --}}
                                class="image room-page"
                                style="transform: translate3d(0px, 0px, 0px) scale3d(1, 1, 1) rotateX(0deg) rotateY(0deg) rotateZ(0deg) skew(0deg, 0deg); transform-style: preserve-3d;">
                            </span>
                        <div class="card room-page"
                            style="transform: translate3d(0px, 0px, 0px) scale3d(1, 1, 1) rotateX(0deg) rotateY(0deg) rotateZ(0deg) skew(0deg, 0deg); transform-style: preserve-3d;">
                            <div class="dash-accent"></div><span
                                class="room-title-link w-inline-block">
                                <h3 class="title card-room">{{ $room->name }}</h3>
                            </span>
                            <p>{{ $room->descriptions }}</p>
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
                            <div class="w-inline-block">
                                <a href="room/{{ $room->id }}" class="btn btn-lg btn-outline-dark button-secondary large radius-zero">More Information</a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

@include('landing.footer')

@endsection
