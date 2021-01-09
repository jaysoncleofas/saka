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

<div class="section request-info">
    <div class="container">
        <div class="request-info-wrapper">
            <div class="image-wrapper request-info"><img
                    src="{{ asset('/pics/pic10.jpg') }}"
                    {{-- srcset="https://assets.website-files.com/5f28567562c2bb7095a14f34/5f3406833d7865a717909c6e_image-request-info-hotel-template-p-500.jpeg 500w, https://assets.website-files.com/5f28567562c2bb7095a14f34/5f3406833d7865a717909c6e_image-request-info-hotel-template.jpg 1560w" --}}
                    sizes="(max-width: 767px) 100vw, (max-width: 991px) 654px, 50vw"
                    style="opacity: 1; transform: translate3d(0px, 0px, 0px) scale3d(1.1, 1.1, 1) rotateX(0deg) rotateY(0deg) rotateZ(0deg) skew(0deg, 0deg); transform-style: preserve-3d;"
                    data-w-id="a7bd4ca7-b754-97b7-fa57-9c69621a36e9" alt="" class="image request-info"></div>
            <div class="split-content request-info-left">
                <div data-w-id="cdcaa8df-bde4-2db1-76a0-5f9daae3a122"
                    style="transform: translate3d(0px, 0px, 0px) scale3d(1, 1, 1) rotateX(0deg) rotateY(0deg) rotateZ(0deg) skew(0deg, 0deg); opacity: 1; transform-style: preserve-3d;"
                    class="interaction-wrapper">
                    <div class="subtitle-wrapper left">
                        <div>01</div>
                        <div class="dash"></div>
                        <div>Reservation</div>
                    </div>
                    <h2 class="title request-info">Get a reservation today</h2>
                    <p class="paragraph request-info">Day use ({{ config('yourconfig.resort')->day }}), Night use ({{ config('yourconfig.resort')->night }}) <br> and Overnight ({{ config('yourconfig.resort')->overnight }})</p>
                </div>
                <div class="card request-info">
                    <div class="card-body">
                        
                    </div>
                </div>
            </div>
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