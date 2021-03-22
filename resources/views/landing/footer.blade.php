<footer class="footer">
    <div class="container">
        <div class="footer-top">
            <div data-w-id="3d154036-488d-0b14-8c77-ba597ddd1fc5" class="split-content footer-top-left"
                style="transform: translate3d(0px, 0px, 0px) scale3d(1, 1, 1) rotateX(0deg) rotateY(0deg) rotateZ(0deg) skew(0deg, 0deg); transform-style: preserve-3d; opacity: 1;">
                <a href="/" aria-current="page" class="footer-logo-container w-inline-block w--current">
                    <img src="{{ asset('/pics/A.png') }}" alt="" class="footer-logo mr-3">
                    <span class="h3">{{ config('yourconfig.resort')->name }}</span>
                </a>
                <p class="paragraph footer-paragraph mt-3">In the Tagalog word saka means "to farm". In Ilocano, it means "foot".</p>
            </div>

            <div data-w-id="a849da38-4a0a-3a01-70a3-2e66b13808e5" class="split-content footer-top-right"
                style="transform: translate3d(0px, 0px, 0px) scale3d(1, 1, 1) rotateX(0deg) rotateY(0deg) rotateZ(0deg) skew(0deg, 0deg); transform-style: preserve-3d; opacity: 1;">
                <div class="title footer-follow-us">Follow Us</div>
                <div class="w-layout-grid footer-follow-us-grid">
                    <a href="{{ config('yourconfig.resort')->facebook }}" target="_blank"
                        class="footer-social-media-wrapper w-inline-block">
                        <div> <i class="fab fa-facebook-f"></i> </div>
                    </a>

                    <a href="{{ config('yourconfig.resort')->instagram }}" target="_blank" class="footer-social-media-wrapper w-inline-block">
                        <div><i class="fab fa-instagram"></i></div>
                    </a>

                    {{-- <a href="{{ config('yourconfig.resort')->twitter }}" target="_blank"
                        class="footer-social-media-wrapper w-inline-block">
                        <div> <i class="fab fa-twitter"></i> </div>
                    </a> --}}
                </div>
            </div>
        </div>

        <div data-w-id="fff77d10-89fc-c679-2009-4633885b94fd" class="divider footer-divider"
            style="background-color:white; transform: translate3d(0px, 0px, 0px) scale3d(1, 1, 1) rotateX(0deg) rotateY(0deg) rotateZ(0deg) skew(0deg, 0deg); transform-style: preserve-3d; opacity: 1;">
        </div>
    </div>

    <div class="small-print-wrapper">
        <div data-w-id="ecfd8b3f-1721-7e98-2cdb-a93160490cd2" class="copyright" style="opacity: 1;">Copyright 2020 ©
            {{ config('yourconfig.resort')->name }}</div>
    </div>
</footer>