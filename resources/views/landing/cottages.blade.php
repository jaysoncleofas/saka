@extends('layouts.resort')

@section('content')
@include('landing.nav1')

<div class="section rooms">
    <div data-w-id="bdd05fbd-ef66-976b-a0f3-8eee1cbe19fb"
        style="transform: translate3d(0px, 0px, 0px) scale3d(1, 1, 1) rotateX(0deg) rotateY(0deg) rotateZ(0deg) skew(0deg, 0deg); opacity: 1; transform-style: preserve-3d;"
        class="container-small-616px text-center w-container">
        <h1 class="title rooms">Cottages</h1>
        <p>Find and reserve your selected cottage and get the lowest prices.</p>
    </div>
    <div class="container">
        <div class="w-dyn-list">
            <div role="list" class="rooms-grid w-dyn-items">
                @foreach ($cottages as $cottage)
                <div data-w-id="ec7088c0-5648-cb50-7d44-82b7954e22d6"
                    style="transform: translate3d(0px, 0px, 0px) scale3d(1, 1, 1) rotateX(0deg) rotateY(0deg) rotateZ(0deg) skew(0deg, 0deg); opacity: 1; transform-style: preserve-3d;"
                    role="listitem" class="w-dyn-item">
                    <div data-w-id="a401d7a1-981e-8de5-af70-01e85569058d" class="room-page-wrapper room-page">
                        <span class="image-wrapper w-inline-block"><img
                                src="{{ $cottage->image ? asset('storage/cottages/'.$cottage->image) : asset('images/img07.jpg') }}"
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
                                <h3 class="title card-room">{{ $cottage->name }}</h3>
                            </span>
                            <p>{{ $cottage->descriptions }}</p>
                            <div class="divider room-page"></div>
                            <div class="about-room-page">
                                <div class="split-content card-about-room-left">
                                    <div class="from-text">From</div>
                                    <div class="card-price-wrapper">
                                        <div data-wf-sku-bindings="%5B%7B%22from%22%3A%22f_price_%22%2C%22to%22%3A%22innerHTML%22%7D%5D"
                                            class="price">{{ number_format($cottage->price) }}&nbsp;PHP</div>
                                        {{-- <div>&nbsp;/use</div> --}}
                                    </div>
                                </div>
                            </div>
                            
                            <div class="w-inline-block">
                                <a href="contact" class="btn btn-lg btn-outline-dark button-secondary large radius-zero">Reserve Now</a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <div class="footer-top">
            <div data-w-id="3d154036-488d-0b14-8c77-ba597ddd1fc5" class="split-content footer-top-left"
                style="transform: translate3d(0px, 0px, 0px) scale3d(1, 1, 1) rotateX(0deg) rotateY(0deg) rotateZ(0deg) skew(0deg, 0deg); transform-style: preserve-3d; opacity: 1;">
                <a href="/" aria-current="page" class="footer-logo-container w-inline-block w--current">
                    <img src="{{ asset('/pics/A.png') }}" alt="" class="footer-logo mr-3">
                    <span class="h3">Saka Resort</span>
                </a>
                <p class="paragraph footer-paragraph mt-3">In the Tagalog vernacular, the word saka means "to farm". On the other hand, in the Ilocano vernacular, it means "foot".</p>
            </div>

            <div data-w-id="a849da38-4a0a-3a01-70a3-2e66b13808e5" class="split-content footer-top-right"
                style="transform: translate3d(0px, 0px, 0px) scale3d(1, 1, 1) rotateX(0deg) rotateY(0deg) rotateZ(0deg) skew(0deg, 0deg); transform-style: preserve-3d; opacity: 1;">
                <div class="title footer-follow-us">Follow Us</div>
                <div class="w-layout-grid footer-follow-us-grid">
                    <a href="https://www.facebook.com/" target="_blank"
                        class="footer-social-media-wrapper w-inline-block">
                        <div> <i class="fab fa-facebook-f"></i> </div>
                    </a>

                    <a href="https://twitter.com/" target="_blank" class="footer-social-media-wrapper w-inline-block">
                        <div><i class="fab fa-instagram"></i></div>
                    </a>

                    <a href="https://www.instagram.com/" target="_blank"
                        class="footer-social-media-wrapper w-inline-block">
                        <div> <i class="fab fa-twitter"></i> </div>
                    </a>
                </div>
            </div>
        </div>

        <div data-w-id="fff77d10-89fc-c679-2009-4633885b94fd" class="divider footer-divider"
            style="background-color:white; transform: translate3d(0px, 0px, 0px) scale3d(1, 1, 1) rotateX(0deg) rotateY(0deg) rotateZ(0deg) skew(0deg, 0deg); transform-style: preserve-3d; opacity: 1;">
        </div>
    </div>

    <div class="small-print-wrapper">
        <div data-w-id="ecfd8b3f-1721-7e98-2cdb-a93160490cd2" class="copyright" style="opacity: 1;">Copyright 2020 ©
            Saka Resort</div>
    </div>
</footer>

@endsection
