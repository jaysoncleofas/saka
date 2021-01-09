@extends('layouts.resort')

@section('style')
<style>
.selectgroup-pills {
    display: block;
    flex-wrap: wrap;
    align-items: flex-start;
}
.selectgroup-item {
    flex-grow: 1;
    position: relative;
}
.selectgroup-pills .selectgroup-item {
    margin-right: .5rem;
    flex-grow: 0;
}
.selectgroup-input {
    opacity: 0;
    position: absolute;
    z-index: -1;
    top: 0;
    left: 0;
}
.selectgroup-button {
    background-color: #fdfdff;
    border-color: #e4e6fc;
    border-width: 1px;
    border-style: solid;
    display: block;
    text-align: center;
    padding: 0 1rem;
    height: 35px;
    position: relative;
    cursor: pointer;
    border-radius: 3px;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    font-size: 13px;
    min-width: 2.375rem;
    line-height: 36px;
}
.selectgroup-button-icon {
    padding-left: .5rem;
    padding-right: .5rem;
}
.selectgroup-pills .selectgroup-button {
    border-radius: 50px !important;
}
.selectgroup-item:not(:last-child) .selectgroup-button {
    border-top-right-radius: 0;
    border-bottom-right-radius: 0;
}
.selectgroup-input:checked + .selectgroup-button {
    background-color: #333;
    color: #fff;
    z-index: 1;
}
.selectgroup-button-icon i {
    font-size: 14px;
}
.swal-footer,
.swal-text {
    text-align: center !important;
}
</style>
@endsection

@section('content')
@include('landing.nav2')

<div class="section contact">
    <div data-w-id="cef7360d-5f6a-7e81-a337-1d5b13101652"
        style="transform: translate3d(0px, 0px, 0px) scale3d(1, 1, 1) rotateX(0deg) rotateY(0deg) rotateZ(0deg) skew(0deg, 0deg); opacity: 1; transform-style: preserve-3d;"
        class="container-small-616px text-center w-container">
        <h1 class="title contact">Contact Us</h1>
        <p class="paragraph contact">For all inquiries, please email or call us using the contacts below.</p>
    </div>
    <div class="container">
        <div data-w-id="41bc547f-9f2d-a8af-6c63-6dad10ead714"
            style="transform: translate3d(0px, 0px, 0px) scale3d(1, 1, 1) rotateX(0deg) rotateY(0deg) rotateZ(0deg) skew(0deg, 0deg); opacity: 1; transform-style: preserve-3d;"
            class="divider contact"></div>
        <div class="w-layout-grid contact-grid">
            <div data-w-id="e021b36d-1d7f-b747-ab28-ad04c2090f31"
                style="transform: translate3d(0px, 0px, 0px) scale3d(1, 1, 1) rotateX(0deg) rotateY(0deg) rotateZ(0deg) skew(0deg, 0deg); opacity: 1; transform-style: preserve-3d;"
                class="contact-wrapper"><img
                    src="https://assets.website-files.com/5f28567562c2bb7095a14f34/5f2b54168519efa87caa868d_icon-contact-01-hotel-template.svg"
                    alt="" class="contact-icon">
                <div class="contact-address">{{ config('yourconfig.resort')->address }}</div>
            </div>
            <div data-w-id="90f3ca9d-5215-19f8-bd2f-3d46b8d12bcb"
                style="transform: translate3d(0px, 0px, 0px) scale3d(1, 1, 1) rotateX(0deg) rotateY(0deg) rotateZ(0deg) skew(0deg, 0deg); opacity: 1; transform-style: preserve-3d;"
                class="contact-wrapper"><img
                    src="https://assets.website-files.com/5f28567562c2bb7095a14f34/5f2b5416f856c13479427c88_icon-contact-02-hotel-template.svg"
                    alt="" class="contact-icon">
                <div class="contact-link-wrapper">
                    <a href="tel:0921 812 8099" class="contact-link">{{ config('yourconfig.resort')->phone }}</a>
                </div>
            </div>
            <div data-w-id="9eb1ef8b-c8b8-6a04-7bc0-145f4e9b3d76"
                style="transform: translate3d(0px, 0px, 0px) scale3d(1, 1, 1) rotateX(0deg) rotateY(0deg) rotateZ(0deg) skew(0deg, 0deg); opacity: 1; transform-style: preserve-3d;"
                class="contact-wrapper"><img
                    src="https://assets.website-files.com/5f28567562c2bb7095a14f34/5f2b54165a57dc778ea040db_icon-contact-03-hotel-template.svg"
                    alt="" class="contact-icon">
                <div class="contact-link-wrapper">
                    <a href="mailto:sakaresort@gmail.com"class="contact-link">{{ config('yourconfig.resort')->email }}</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="section instagram">
    <div data-w-id="826f6178-2f16-6e2c-de9c-95e5f3966b68" class="container-small-616px text-center w-container"
        style="transform: translate3d(0px, 0px, 0px) scale3d(1, 1, 1) rotateX(0deg) rotateY(0deg) rotateZ(0deg) skew(0deg, 0deg); transform-style: preserve-3d; opacity: 1;">
        <div class="subtitle-wrapper">
            <div>01</div>
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

@section('script')
    @if (session('notification'))
    <script>
    swal({
        title: 'Awesome!',
        text: '{{ session('notification') }}',
        icon: "success",
        button: true,
    });
    </script>
    @endif
    <script>
        $(document).on('click', '.uncheck-cottage', function () {
            $('.radio-cottage').each(function () {
                $(this).removeAttr('checked');
                $('.radio-cottage').prop('checked', false);
            });
            // console.log('encheck-cottage');
        });

        $(document).on('click', '.uncheck-room', function () {
            $('.radio-room').each(function () {
                $(this).removeAttr('checked');
                $('.radio-room').prop('checked', false);
            });
            // console.log('encheck-room');
        });
    </script>
@endsection