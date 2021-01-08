@extends('layouts.resort')

@section('content')

@include('landing.nav1')
{{-- hero  --}}
<div class="section hero">
    <div class="mask d-flex justify-content-center align-items-center">
        <div class="container">
            <div data-w-id="a375f38e-cd39-316e-0b02-19c869f60288"
                style="transform: translate3d(0px, 0px, 0px) scale3d(1, 1, 1) rotateX(0deg) rotateY(0deg) rotateZ(0deg) skew(0deg, 0deg); transform-style: preserve-3d; opacity: 1;"
                class="split-content hero-left">
                <h1 class="size-1 home">Enjoy today at our farm resort</h1>
                <p class="paragraph hero">This farm resort offers a unique private pool shaped as a footprint and a natural farm. <br> Wander through the natural garden design.</p>
                <a href="rooms" class="btn btn-lg btn-dark button-primary large w-inline-block radius-zero mr-2">Browse Rooms</a>
                <a href="contact" class="btn btn-lg btn-outline-dark button-secondary large w-inline-block radius-zero">Reserve Now</a>
            </div>
        </div>
        
        <div class="image-wrapper hero">
            <img src="{{ asset('/pics/pic2.jpeg') }}" 
                {{-- srcset="https://assets.website-files.com/5f28567562c2bb7095a14f34/5f32c1ae95b617b18ca22d2d_image-hero-hotel-template-p-500.jpeg 500w, https://assets.website-files.com/5f28567562c2bb7095a14f34/5f32c1ae95b617b18ca22d2d_image-hero-hotel-template.jpg 1416w" --}}
                sizes="(max-width: 991px) 100vw, 46vw" class="image hero">
        </div>
    </div>
</div>

{{-- about  --}}
<div class="section about">
    <div class="container">
        <div class="about-wrapper">
            <div data-w-id="cfdbf57e-4be2-d5ad-cd01-9c0fa3d17082"
                style="transform: translate3d(0px, 0px, 0px) scale3d(1, 1, 1) rotateX(0deg) rotateY(0deg) rotateZ(0deg) skew(0deg, 0deg); transform-style: preserve-3d; opacity: 1;"
                class="image-wrapper about">
                <img src="{{ asset('/pics/pic4.jpeg') }}"
                    {{-- srcset="https://assets.website-files.com/5f28567562c2bb7095a14f34/5f32c12d2ffe390a4b9e01a2_image-about-section-hotel-template-p-500.jpeg 500w, https://assets.website-files.com/5f28567562c2bb7095a14f34/5f32c12d2ffe390a4b9e01a2_image-about-section-hotel-template-p-800.jpeg 800w, https://assets.website-files.com/5f28567562c2bb7095a14f34/5f32c12d2ffe390a4b9e01a2_image-about-section-hotel-template-p-1080.jpeg 1080w, https://assets.website-files.com/5f28567562c2bb7095a14f34/5f32c12d2ffe390a4b9e01a2_image-about-section-hotel-template.jpg 1552w" --}}
                    sizes="(max-width: 991px) 100vw, 776px" alt="" class="image about">
            </div>

            <div data-w-id="0d73eb2b-4111-f304-1a24-a97b189a0f50"
                style="transform: translate3d(0px, 0px, 0px) scale3d(1, 1, 1) rotateX(0deg) rotateY(0deg) rotateZ(0deg) skew(0deg, 0deg); transform-style: preserve-3d; opacity: 1;"
                class="card about">
                <div class="subtitle-wrapper about">
                    <div>01</div>
                    <div class="dash"></div>
                    <div>About</div>
                </div>

                <h2>Visit our farm resort and reconnect with yourself</h2>
                <p class="paragraph about">The concept for the pool is equivalent to its meaning in Ilocano. It's shaped as a footprint. There are 5 toes that serve as kiddie pools and the main foot would be a 16x32m lap pool, with a slope of 3-5 feet. <br><br> Saka Resort is located at San Juan de Valdez, Tarlac, just 15 minutes away from the city proper, 3 minutes away from the Tarlac Recreational Park, and 30 minutes and on the way if you're going to visit the Tarlac Monastery.</p>
                <a href="about" class="btn btn-lg btn-outline-dark button-secondary large w-inline-block radius-zero">Explore More</a>
            </div>
        </div>
    </div>
</div>

{{-- cta  --}}
<div class="section cta">
    <div class="container">
        <div data-w-id="8aab8902-0a32-a25f-f793-b5e182c451bf" class="card cta"
            style="transform: translate3d(0px, 0px, 0px) scale3d(1, 1, 1) rotateX(0deg) rotateY(0deg) rotateZ(0deg) skew(0deg, 0deg); transform-style: preserve-3d; opacity: 1;">
            <div class="dash-accent"></div>
            <h2 class="title cta">Visit our resort today</h2>
            <p class="paragraph cta">We are open from {{ config('yourconfig.resort')->day }} for day swim, {{ config('yourconfig.resort')->night }} for night swim, and we also accept overnight stay {{ config('yourconfig.resort')->overnight }}</p>
            <a href="cottages" class="btn btn-lg btn-dark button-primary large w-inline-block radius-zero mr-2">Browse Cottages</a>
        </div>
    </div>
    
    <img src="{{ asset('/pics/pic10.jpg') }}"
        {{-- srcset="https://assets.website-files.com/5f28567562c2bb7095a14f34/5f32c02377e6777dcecd3f45_image-cta-banner-hotel-template-p-500.jpeg 500w, https://assets.website-files.com/5f28567562c2bb7095a14f34/5f32c02377e6777dcecd3f45_image-cta-banner-hotel-template-p-1080.jpeg 1080w, https://assets.website-files.com/5f28567562c2bb7095a14f34/5f32c02377e6777dcecd3f45_image-cta-banner-hotel-template-p-1600.jpeg 1600w, https://assets.website-files.com/5f28567562c2bb7095a14f34/5f32c02377e6777dcecd3f45_image-cta-banner-hotel-template-p-2000.jpeg 2000w, https://assets.website-files.com/5f28567562c2bb7095a14f34/5f32c02377e6777dcecd3f45_image-cta-banner-hotel-template.jpg 2159w" --}}
        sizes="100vw" data-w-id="8aab8902-0a32-a25f-f793-b5e182c451c9" alt="" class="image cta-bg"
        style="opacity: 1; transform: translate3d(0px, 0px, 0px) scale3d(1.1, 1.1, 1) rotateX(0deg) rotateY(0deg) rotateZ(0deg) skew(0deg, 0deg); transform-style: preserve-3d;">
</div>

{{-- rooms  --}}
<div class="section rooms-section">
    <div data-w-id="baee32c1-2006-06b6-a6b9-1be1c6a56077"
        style="transform: translate3d(0px, 0px, 0px) scale3d(1, 1, 1) rotateX(0deg) rotateY(0deg) rotateZ(0deg) skew(0deg, 0deg); opacity: 1; transform-style: preserve-3d;"
        class="container-small-616px text-center w-container">

        <div class="subtitle-wrapper">
            <div>02</div>
            <div class="dash"></div>
            <div>Rooms</div>
        </div>
        <h2>Carefully designed rooms.</h2>
        <p class="paragraph experiences">Find and reserve your selected room and get the lowest prices.</p>
    </div>

    <div class="container">
        <div id="carouselExampleControls" class="carousel slide mt-5" data-ride="carousel">
            <ol class="carousel-indicators">
                @foreach ($rooms as $key => $room)
                <li data-target="#carouselExampleIndicators" data-slide-to="{{ $key }}" class="{{ $loop->first ? 'active' : ''}}"></li>
                @endforeach
              </ol>
            <div class="carousel-inner">
                @foreach ($rooms as $room)
                    <div class="carousel-item {{ $loop->first ? 'active' : ''}}">
                        <div class="room-section-slide w-slide">
                            <div class="w-dyn-list">
                                <div role="list" class="w-dyn-items">
                                    <div role="listitem" class="w-dyn-item">
                                        <div class="room-section-wrapper">
                                            <span class="image-wrapper w-inline-block">
                                                <img src="{{ $room->coverimage() ? asset('storage/rooms/'.$room->coverimage()->path) : asset('images/img07.jpg') }}"
                                                    alt=""
                                                    sizes="(max-width: 479px) 93vw, (max-width: 767px) 94vw, (max-width: 991px) 95vw, 100vw"
                                                    {{-- srcset="https://assets.website-files.com/5f28567562c2bb18b4a14f33/5f2a0675f3e8178256eb8a3f_image-room-thumbnail-02-hotel-template-p-500.jpeg 500w, https://assets.website-files.com/5f28567562c2bb18b4a14f33/5f2a0675f3e8178256eb8a3f_image-room-thumbnail-02-hotel-template-p-800.jpeg 800w, https://assets.website-files.com/5f28567562c2bb18b4a14f33/5f2a0675f3e8178256eb8a3f_image-room-thumbnail-02-hotel-template-p-1080.jpeg 1080w, https://assets.website-files.com/5f28567562c2bb18b4a14f33/5f2a0675f3e8178256eb8a3f_image-room-thumbnail-02-hotel-template.jpg 1152w" --}}
                                                    class="image room-section">
                                            </span>

                                            <div class="card room-section">
                                                <div class="dash-accent"></div>
                                                <a href="#" class="room-title-link w-inline-block">
                                                    <h3 class="title card-room">{{ $room->name }}</h3>
                                                </a>
                                                <p>{{ $room->descriptions }}</p>
                                                <div class="divider room-page"></div>

                                                <div class="about-room-page">
                                                    <div class="split-content card-about-room-left">
                                                        <div class="from-text">From</div>
                                                        <div class="card-price-wrapper">
                                                            <div data-wf-sku-bindings="%5B%7B%22from%22%3A%22f_price_%22%2C%22to%22%3A%22innerHTML%22%7D%5D" class="price">P&nbsp;{{ number_format($room->price, 0) }}</div>
                                                            <div>&nbsp;/night</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <a href="{{ route('room.show', $room->id) }}" class="btn btn-lg btn-dark button-primary large w-inline-block radius-zero mr-2">More Information</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>

            <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
    </div>
</div>

<div class="section restaurant-section">
    <div class="container">
        <div class="restaurant-wrapper">
            <div data-w-id="9926b0fe-073a-8977-b236-ad68c27024e0"
                style="transform: translate3d(0px, 0px, 0px) scale3d(1, 1, 1) rotateX(0deg) rotateY(0deg) rotateZ(0deg) skew(0deg, 0deg); opacity: 1; transform-style: preserve-3d;"
                class="split-content restaurant-left">
                <div class="subtitle-wrapper left">
                    <div>03</div>
                    <div class="dash"></div>
                    <div>Farm</div>
                </div>
                <h2>A genuine farm<br>inside our resort</h2>
                <p class="paragraph restaurant">SAKA is a genuine farm: with vegetable and fruit plants growing and a few animals too. It is located in one of the areas in Tarlac that's known for the rice fields: San Juan de Valdez.</p>
                    <a href="about" class="btn btn-lg btn-outline-dark button-secondary large w-inline-block radius-zero">Learn More</a>
            </div>
            <div class="split-content restaurant-right"><img
                    src="{{ asset('/cottages_pics/Toraja B.jpeg') }}"
                    {{-- srcset="https://assets.website-files.com/5f28567562c2bb7095a14f34/5f32be3ce649b09a7e937bcc_image-restaurant-01-hotel-template-p-500.jpeg 500w, https://assets.website-files.com/5f28567562c2bb7095a14f34/5f32be3ce649b09a7e937bcc_image-restaurant-01-hotel-template-p-800.jpeg 800w, https://assets.website-files.com/5f28567562c2bb7095a14f34/5f32be3ce649b09a7e937bcc_image-restaurant-01-hotel-template.jpg 880w" --}}
                    sizes="(max-width: 767px) 56vw, (max-width: 991px) 76vw, (max-width: 1919px) 37vw, 465.921875px"
                    style="opacity: 1; will-change: transform; transform: translate3d(0px, 2.0265px, 0px) scale3d(1, 1, 1) rotateX(0deg) rotateY(0deg) rotateZ(0deg) skew(0deg, 0deg); transform-style: preserve-3d;"
                    data-w-id="c82a33ef-8945-0299-c7ea-17e43f305080" alt="" class="image restaurant-1"><img
                    src="{{ asset('/cottages_pics/Toraja.jpg') }}"
                    {{-- srcset="https://assets.website-files.com/5f28567562c2bb7095a14f34/5f32be3b9c52ed0b138cba78_image-restaurant-02-hotel-template-p-500.jpeg 500w, https://assets.website-files.com/5f28567562c2bb7095a14f34/5f32be3b9c52ed0b138cba78_image-restaurant-02-hotel-template-p-800.jpeg 800w, https://assets.website-files.com/5f28567562c2bb7095a14f34/5f32be3b9c52ed0b138cba78_image-restaurant-02-hotel-template.jpg 838w" --}}
                    sizes="(max-width: 767px) 56vw, (max-width: 991px) 76vw, 416px"
                    style="opacity: 1; will-change: transform; transform: translate3d(0px, -2.6601px, 0px) scale3d(1, 1, 1) rotateX(0deg) rotateY(0deg) rotateZ(0deg) skew(0deg, 0deg); transform-style: preserve-3d;"
                    data-w-id="dbd4ec05-480a-7efb-ef2f-b9c2c370edad" alt="" class="image restaurant-2"></div>
        </div>
    </div>
</div>

{{-- cottages --}}
<div class="section rooms-section">
    <div data-w-id="baee32c1-2006-06b6-a6b9-1be1c6a56077"
        style="transform: translate3d(0px, 0px, 0px) scale3d(1, 1, 1) rotateX(0deg) rotateY(0deg) rotateZ(0deg) skew(0deg, 0deg); opacity: 1; transform-style: preserve-3d;"
        class="container-small-616px text-center w-container">
        <div class="subtitle-wrapper">
            <div>04</div>
            <div class="dash"></div>
            <div>Cottages</div>
        </div>

        <h2>Carefully designed cottages.</h2>
        <p class="paragraph experiences">Find and reserve your selected cottage and get the lowest prices.</p>
    </div>

    <div class="container">
        <div id="carouselExample2Controls" class="carousel slide mt-5" data-ride="carousel">
            <div class="carousel-inner">
                @foreach ($cottages as $cottage)
                <div class="carousel-item {{ $loop->first ? 'active' : ''}}">
                    <div class="room-section-slide w-slide">
                        <div class="w-dyn-list">
                            <div role="list" class="w-dyn-items">
                                <div role="listitem" class="w-dyn-item">
                                    <div class="room-section-wrapper">
                                        <span class="image-wrapper w-inline-block">
                                            <img src="{{ $cottage->coverimage() ? asset('storage/cottages/'.$cottage->coverimage()->path) : asset('images/img07.jpg') }}"
                                                alt=""
                                                sizes="(max-width: 479px) 93vw, (max-width: 767px) 94vw, (max-width: 991px) 95vw, 100vw"
                                                {{-- srcset="https://assets.website-files.com/5f28567562c2bb18b4a14f33/5f2a0675f3e8178256eb8a3f_image-room-thumbnail-02-hotel-template-p-500.jpeg 500w, https://assets.website-files.com/5f28567562c2bb18b4a14f33/5f2a0675f3e8178256eb8a3f_image-room-thumbnail-02-hotel-template-p-800.jpeg 800w, https://assets.website-files.com/5f28567562c2bb18b4a14f33/5f2a0675f3e8178256eb8a3f_image-room-thumbnail-02-hotel-template-p-1080.jpeg 1080w, https://assets.website-files.com/5f28567562c2bb18b4a14f33/5f2a0675f3e8178256eb8a3f_image-room-thumbnail-02-hotel-template.jpg 1152w" --}}
                                                class="image room-section">
                                        </span>
    
                                        <div class="card room-section">
                                            <div class="dash-accent"></div>
                                            <a href="#" class="room-title-link w-inline-block">
                                                <h3 class="title card-room">{{ $cottage->name }}</h3>
                                            </a>
                                            <p>{{ $cottage->descriptions }}</p>
                                            <div class="divider room-page"></div>
    
                                            <div class="about-room-page">
                                                <div class="split-content card-about-room-left">
                                                    <div class="from-text">From</div>
                                                    <div class="card-price-wrapper">
                                                        <div data-wf-sku-bindings="%5B%7B%22from%22%3A%22f_price_%22%2C%22to%22%3A%22innerHTML%22%7D%5D" class="price">P&nbsp;{{ number_format($cottage->price, 0) }}</div>
                                                        <div>&nbsp;/Day use</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <a href="{{ route('cottage.show', $cottage->id) }}" class="btn btn-lg btn-dark button-primary large w-inline-block radius-zero mr-2">More Information</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <a class="carousel-control-prev" href="#carouselExample2Controls" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>

            <a class="carousel-control-next" href="#carouselExample2Controls" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
    </div>
</div>

<div data-w-id="b8c00600-8c28-54b1-267c-8ea4a6ca2bab" style="opacity: 1;" class="bg-image-home"></div>

{{-- testimonials  --}}
<div class="section testimonials">
    <div data-w-id="a576343b-ebfate3d(0px, 0px, 0px) scale3d(1, 1, 1) rotateX(0deg) rotateY(0deg) rotateZ(0deg) skew(0deg, 0deg); opacity: 1; transform-style: preserve-3d;"
        class="container-small-658px text-center w-container">
        <div class="subtitle-wrapper">
            <div>05</div>
            <div class="dash"></div>
            <div>Testimonials</div>
        </div>
        <h2 class="title testimonials">Hear what our past<br>guests have to say</h2>
        {{-- <p class="paragraph testimonials">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Et sapien tempus duis facilisis pretium massa pellentesque vel feugiat nunc.</p> --}}
    </div>
    <div class="container">
        <div class="w-layout-grid testimonials-grid">
            <div data-w-id="17da022c-c325-1702-a0c3-727352939fdb"
                style="transform: translate3d(0px, 0px, 0px) scale3d(1, 1, 1) rotateX(0deg) rotateY(0deg) rotateZ(0deg) skew(0deg, 0deg); opacity: 1; transform-style: preserve-3d;"
                class="card testimonial">
                <p>“Extremely nice environment, the room was great, the service was awesome, really helpful and great service I'll visit them again in the near future.”</p>
                <div class="divider testimonial"></div>
                <div class="testimonial-about-wrapper">
                    <div class="testimonial-about">
                        <img src="https://assets.website-files.com/5f28567562c2bb7095a14f34/5f32bf40f0dd13d144ea2ad2_image-testimonial-01-hotel-template.jpg"
                            alt="" class="image testimonial">
                        <div class="testimonial-about-content">
                            <div class="testimonial-name">Robert Miller</div>
                            <div class="testimonial-from">Tarlac City</div>
                        </div>
                    </div>
                    <img src="https://assets.website-files.com/5f28567562c2bb7095a14f34/5f28a8e7449cb86f750623f2_stars-testimonial-hotel-template.svg" alt="">
                </div>
            </div>

            <div data-w-id="dd0eee54-9ef3-6545-6980-b57ecab364d6"
                style="transform: translate3d(0px, 0px, 0px) scale3d(1, 1, 1) rotateX(0deg) rotateY(0deg) rotateZ(0deg) skew(0deg, 0deg); opacity: 1; transform-style: preserve-3d;"
                class="card testimonial">
                <p>“Everything was absolutely great, staff were excellent and helpful. Room was spacious and clean. Breakfast was great.”</p>
                <div class="divider testimonial"></div>
                <div class="testimonial-about-wrapper">
                    <div class="testimonial-about">
                        <img src="https://assets.website-files.com/5f28567562c2bb7095a14f34/5f32bf401dd1709bc4284da1_image-testimonial-02-hotel-template.jpg"
                            alt="" class="image testimonial">
                        <div class="testimonial-about-content">
                            <div class="testimonial-name">Sophie Moore</div>
                            <div class="testimonial-from">Tarlac City</div>
                        </div>
                    </div>
                    <img src="https://assets.website-files.com/5f28567562c2bb7095a14f34/5f28a8e7449cb86f750623f2_stars-testimonial-hotel-template.svg" alt="">
                </div>
            </div>
        </div>
        <div data-w-id="55c0f8ac-c8ef-5e07-8454-99f3b872d6b3"
            style="transform: translate3d(0px, 0px, 0px) scale3d(1, 1, 1) rotateX(0deg) rotateY(0deg) rotateZ(0deg) skew(0deg, 0deg); opacity: 1; transform-style: preserve-3d;"
            class="flex-vc">
            <a href="contact" class="btn btn-lg btn-outline-dark">Reserve Now</a>
        </div>
    </div>
</div>

{{-- social  --}}
<div class="section instagram">
    <div data-w-id="826f6178-2f16-6e2c-de9c-95e5f3966b68" class="container-small-616px text-center w-container"
        style="transform: translate3d(0px, 0px, 0px) scale3d(1, 1, 1) rotateX(0deg) rotateY(0deg) rotateZ(0deg) skew(0deg, 0deg); transform-style: preserve-3d; opacity: 1;">
        <div class="subtitle-wrapper">
            <div>06</div>
            <div class="dash"></div>
            <div class="instagram-grid">Follow</div>
        </div>
        <h2 class="title instagram">Follow us to discover amazing stories</h2>
    </div>
    <div class="w-layout-grid grid">
        <a data-w-id="826f6178-2f16-6e2c-de9c-95e5f3966b72"
            href="#" class="image-wrapper instagram w-inline-block"
            style="transform: translate3d(0px, 0px, 0px) scale3d(1, 1, 1) rotateX(0deg) rotateY(0deg) rotateZ(0deg) skew(0deg, 0deg); transform-style: preserve-3d; opacity: 1;">
            <div class="image-instagram-hover"
                style="transform: translate3d(0px, 100%, 0px) scale3d(1, 1, 1) rotateX(0deg) rotateY(0deg) rotateZ(0deg) skew(0deg, 0deg); transform-style: preserve-3d; display: none;">
                <div class="instagram-icon-wrapper"
                    style="opacity: 0; transform: translate3d(0px, 0px, 0px) scale3d(1, 1, 1) rotateX(0deg) rotateY(0deg) rotateZ(0deg) skew(0deg, 0deg); transform-style: preserve-3d;">
                    <img src="{{ asset('/pics/pic12.jpg') }}" alt="" class="instagram-icon">
                </div>
            </div>

            <img src="{{ asset('/pics/pic19.jpg') }}" alt="" class="image instagram">
        </a>
        
        <a data-w-id="826f6178-2f16-6e2c-de9c-95e5f3966b77" href="#"
            class="image-wrapper instagram w-inline-block"
            style="transform: translate3d(0px, 0px, 0px) scale3d(1, 1, 1) rotateX(0deg) rotateY(0deg) rotateZ(0deg) skew(0deg, 0deg); transform-style: preserve-3d; opacity: 1;">
            <div class="image-instagram-hover"
                style="transform: translate3d(0px, 100%, 0px) scale3d(1, 1, 1) rotateX(0deg) rotateY(0deg) rotateZ(0deg) skew(0deg, 0deg); transform-style: preserve-3d; display: none;">
                <div class="instagram-icon-wrapper"
                    style="opacity: 0; transform: translate3d(0px, 0px, 0px) scale3d(1, 1, 1) rotateX(0deg) rotateY(0deg) rotateZ(0deg) skew(0deg, 0deg); transform-style: preserve-3d;">
                    <img src="https://assets.website-files.com/5f28567562c2bb7095a14f34/5f28b11486a831465affd83c_icon-instagram-section-hotel-template.svg" alt="" class="instagram-icon"></div>
            </div>
            
            <img src="{{ asset('/pics/pic10.jpg') }}"
                {{-- srcset="https://assets.website-files.com/5f28567562c2bb7095a14f34/5f32bc83e9e05bbbe8fbd5b5_image-instagram-02-hotel-template-p-500.jpeg 500w, https://assets.website-files.com/5f28567562c2bb7095a14f34/5f32bc83e9e05bbbe8fbd5b5_image-instagram-02-hotel-template.jpg 720w" --}}
                sizes="(max-width: 720px) 100vw, 720px" alt="" class="image instagram">
        </a>
        
        <a data-w-id="826f6178-2f16-6e2c-de9c-95e5f3966b7c" href="#"
            class="image-wrapper instagram w-inline-block"
            style="transform: translate3d(0px, 0px, 0px) scale3d(1, 1, 1) rotateX(0deg) rotateY(0deg) rotateZ(0deg) skew(0deg, 0deg); transform-style: preserve-3d; opacity: 1;">
            <div class="image-instagram-hover"
                style="transform: translate3d(0px, 100%, 0px) scale3d(1, 1, 1) rotateX(0deg) rotateY(0deg) rotateZ(0deg) skew(0deg, 0deg); transform-style: preserve-3d; display: none;">
                <div class="instagram-icon-wrapper"
                    style="opacity: 0; transform: translate3d(0px, 0px, 0px) scale3d(1, 1, 1) rotateX(0deg) rotateY(0deg) rotateZ(0deg) skew(0deg, 0deg); transform-style: preserve-3d;">
                    <img src="https://assets.website-files.com/5f28567562c2bb7095a14f34/5f28b11486a831465affd83c_icon-instagram-section-hotel-template.svg" alt="" class="instagram-icon"></div>
            </div>
            
            <img src="{{ asset('/pics/pic11.jpg') }}"
                {{-- srcset="https://assets.website-files.com/5f28567562c2bb7095a14f34/5f32bc84df3c3636649e8d16_image-instagram-03-hotel-template-p-500.jpeg 500w, https://assets.website-files.com/5f28567562c2bb7095a14f34/5f32bc84df3c3636649e8d16_image-instagram-03-hotel-template.jpg 720w" --}}
                sizes="(max-width: 720px) 100vw, 720px" alt="" class="image instagram">
        </a>
        
        <a data-w-id="826f6178-2f16-6e2c-de9c-95e5f3966b81" href="#"
            class="image-wrapper instagram w-inline-block"
            style="transform: translate3d(0px, 0px, 0px) scale3d(1, 1, 1) rotateX(0deg) rotateY(0deg) rotateZ(0deg) skew(0deg, 0deg); transform-style: preserve-3d; opacity: 1;">
            <div class="image-instagram-hover"
                style="transform: translate3d(0px, 100%, 0px) scale3d(1, 1, 1) rotateX(0deg) rotateY(0deg) rotateZ(0deg) skew(0deg, 0deg); transform-style: preserve-3d; display: none;">
                <div class="instagram-icon-wrapper"
                    style="opacity: 0; transform: translate3d(0px, 0px, 0px) scale3d(1, 1, 1) rotateX(0deg) rotateY(0deg) rotateZ(0deg) skew(0deg, 0deg); transform-style: preserve-3d;">
                    <img src="https://assets.website-files.com/5f28567562c2bb7095a14f34/5f28b11486a831465affd83c_icon-instagram-section-hotel-template.svg" alt="" class="instagram-icon"></div>
            </div>
            
            <img src="{{ asset('/pics/pic2.jpeg') }}"
                {{-- srcset="https://assets.website-files.com/5f28567562c2bb7095a14f34/5f32bc84f0dd13d143ea2337_image-instagram-04-hotel-template-p-500.jpeg 500w, https://assets.website-files.com/5f28567562c2bb7095a14f34/5f32bc84f0dd13d143ea2337_image-instagram-04-hotel-template.jpg 720w" --}}
                sizes="(max-width: 720px) 100vw, 720px" alt="" class="image instagram">
        </a>
    </div>

    <div class="container">
        <div data-w-id="826f6178-2f16-6e2c-de9c-95e5f3966b87" class="flex-vc"
            style="transform: translate3d(0px, 0px, 0px) scale3d(1, 1, 1) rotateX(0deg) rotateY(0deg) rotateZ(0deg) skew(0deg, 0deg); transform-style: preserve-3d; opacity: 1;">
            <a href="{{ config('yourconfig.resort')->facebook }}" target="_blank" class="btn btn-lg btn-outline-dark">Follow Us</a>
        </div>
    </div>
</div>

@include('landing.footer')

@endsection
