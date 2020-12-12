@extends('layouts.resort')

@section('content')
@include('landing.nav1')

<div class="section about-page">
    <div class="container">
        <div data-w-id="48fad021-a822-6c74-c6f5-d4620a19fdcf"
            style="transform: translate3d(0px, 0px, 0px) scale3d(1, 1, 1) rotateX(0deg) rotateY(0deg) rotateZ(0deg) skew(0deg, 0deg); opacity: 1; transform-style: preserve-3d;"
            class="card about-page">
            <h1>About Us</h1>
            <p class="paragraph about-page">In the Tagalog vernacular, the word saka means "to farm". On the other hand, in the Ilocano vernacular, it means "foot". <br> <br>
                The reason why it is named SAKA is because this resort is unlike others. It is a genuine farm: with vegetable and fruit plants growing and a few animals too. It is located in one of the areas in Tarlac that's known for the rice fields: San Juan de Valdez.<br> <br>
                Also, the concept for the pool is equivalent to its meaning in Ilocano. It's shaped as a footprint. There are 5 fingers that serve as kiddie pools and the main foot would be a 16x32m lap pool, with a slope of 3-5 feet.
                </p>
        </div>
    </div>
    <div class="image-wrapper bg-about-page"><img
            src="{{ asset('/pics/pic2.jpeg') }}"
            {{-- srcset="https://assets.website-files.com/5f28567562c2bb7095a14f34/5f4710176c002e67cf9be4db_about-hero-p-500.jpeg 500w, https://assets.website-files.com/5f28567562c2bb7095a14f34/5f4710176c002e67cf9be4db_about-hero-p-800.jpeg 800w, https://assets.website-files.com/5f28567562c2bb7095a14f34/5f4710176c002e67cf9be4db_about-hero-p-1080.jpeg 1080w, https://assets.website-files.com/5f28567562c2bb7095a14f34/5f4710176c002e67cf9be4db_about-hero-p-1600.jpeg 1600w, https://assets.website-files.com/5f28567562c2bb7095a14f34/5f4710176c002e67cf9be4db_about-hero-p-2000.jpeg 2000w, https://assets.website-files.com/5f28567562c2bb7095a14f34/5f4710176c002e67cf9be4db_about-hero-p-2600.jpeg 2600w, https://assets.website-files.com/5f28567562c2bb7095a14f34/5f4710176c002e67cf9be4db_about-hero.jpg 2880w" --}}
            sizes="100vw"
            style="opacity: 1; transform: translate3d(0px, 0px, 0px) scale3d(1.1, 1.1, 1) rotateX(0deg) rotateY(0deg) rotateZ(0deg) skew(0deg, 0deg); transform-style: preserve-3d;"
            data-w-id="fb137d14-c118-c87a-4552-fbd4b2ec7ff7" alt="" class="image bg-about-page"></div>
</div>

<div class="section about-us">
    <div class="container">
        <div class="about-us-wrapper">
            <div data-w-id="cd32dcd8-a22d-9841-7db6-49adbbbe8fbf"
                style="transform: translate3d(0px, 0px, 0px) scale3d(1, 1, 1) rotateX(0deg) rotateY(0deg) rotateZ(0deg) skew(0deg, 0deg); opacity: 1; transform-style: preserve-3d;"
                class="split-content about-us-left">
                <div class="subtitle-wrapper left white">
                    <div>01</div>
                    <div class="dash white"></div>
                    <div>About the Resort</div>
                </div>
                <h2 class="title about-us">A place carefully designed to help you reconnect</h2>
                <p class="paragraph about-us">The concept for the pool is equivalent to its meaning in Ilocano. It's shaped as a footprint. There are 5 fingers that serve as kiddie pools and the main foot would be a 16x32m lap pool, with a slope of 3-5 feet. <br><br> Saka Resort is located at San Juan de Valdez, Tarlac, just 15 minutes away from the city proper, is 3 minutes away from the newly built Tarlac Recreational Park, and 30 minutes and on the way if you're going to visit the Tarlac Monastery. <br> <br>We are open from 9am-6pm for day swim, 6pm-10pm for night swim, and we also accept overnight stay.</p>
            </div>
            <div class="split-content about-us-right"><img
                    src="{{ asset('/pics/pic10.jpg') }}"
                    {{-- srcset="https://assets.website-files.com/5f28567562c2bb7095a14f34/5f2996cc7581e3e0fe413c5f_image-about-us-01-hotel-template-p-500.jpeg 500w, https://assets.website-files.com/5f28567562c2bb7095a14f34/5f2996cc7581e3e0fe413c5f_image-about-us-01-hotel-template.jpg 732w" --}}
                    sizes="(max-width: 479px) 78vw, (max-width: 767px) 53vw, (max-width: 991px) 66vw, (max-width: 1919px) 27vw, 329.2749938964844px"
                    style="opacity: 1; will-change: transform; transform: translate3d(0px, 1.887px, 0px) scale3d(1, 1, 1) rotateX(0deg) rotateY(0deg) rotateZ(0deg) skew(0deg, 0deg); transform-style: preserve-3d;"
                    data-w-id="dcdddd5d-2dd8-13bf-f1f9-3ab9771d6f1f" alt="" class="image about-us-1"><img
                    src="{{ asset('/pics/pic9.jpg') }}"
                    {{-- srcset="https://assets.website-files.com/5f28567562c2bb7095a14f34/5f2996cbb2a38464a48d0f76_image-about-us-02-hotel-template-p-500.jpeg 500w, https://assets.website-files.com/5f28567562c2bb7095a14f34/5f2996cbb2a38464a48d0f76_image-about-us-02-hotel-template.jpg 732w" --}}
                    sizes="(max-width: 479px) 78vw, (max-width: 767px) 53vw, (max-width: 991px) 66vw, (max-width: 1919px) 27vw, 329.2749938964844px"
                    style="opacity: 1; will-change: transform; transform: translate3d(0px, -0.093px, 0px) scale3d(1, 1, 1) rotateX(0deg) rotateY(0deg) rotateZ(0deg) skew(0deg, 0deg); transform-style: preserve-3d;"
                    data-w-id="92d8b3c3-6249-0dab-949e-9bf42fe7207b" alt="" class="image about-us-2"></div>
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
