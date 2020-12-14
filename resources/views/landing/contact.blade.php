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
                <div class="contact-address">Purok 2, San Juan De Valdez San Jose Tarlac</div>
            </div>
            <div data-w-id="90f3ca9d-5215-19f8-bd2f-3d46b8d12bcb"
                style="transform: translate3d(0px, 0px, 0px) scale3d(1, 1, 1) rotateX(0deg) rotateY(0deg) rotateZ(0deg) skew(0deg, 0deg); opacity: 1; transform-style: preserve-3d;"
                class="contact-wrapper"><img
                    src="https://assets.website-files.com/5f28567562c2bb7095a14f34/5f2b5416f856c13479427c88_icon-contact-02-hotel-template.svg"
                    alt="" class="contact-icon">
                <div class="contact-link-wrapper">
                    <a href="tel:0921 812 8099" class="contact-link">0921 812 8099</a>
                </div>
            </div>
            <div data-w-id="9eb1ef8b-c8b8-6a04-7bc0-145f4e9b3d76"
                style="transform: translate3d(0px, 0px, 0px) scale3d(1, 1, 1) rotateX(0deg) rotateY(0deg) rotateZ(0deg) skew(0deg, 0deg); opacity: 1; transform-style: preserve-3d;"
                class="contact-wrapper"><img
                    src="https://assets.website-files.com/5f28567562c2bb7095a14f34/5f2b54165a57dc778ea040db_icon-contact-03-hotel-template.svg"
                    alt="" class="contact-icon">
                <div class="contact-link-wrapper">
                    <a href="mailto:sakaresort@gmail.com"class="contact-link">sakaresort@gmail.com</a>
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
                    <p class="paragraph request-info">Day use (9am-5pm), Exclusive Rental (9am-5pm),<br> Entrance fee: 100PHP, 15,000PHP Good for 60 pax</p>
                </div>
                <div class="card request-info">
                    <div class="card-body">
                        <form method="POST" action="{{ route('reservation.store') }}">
                            @csrf

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="firstName">First Name</label>
                                    <input type="text" class="form-control @error('firstName') is-invalid @enderror"
                                        name="firstName" id="firstName" value="{{ old('firstName') }}">
                                    @error('firstName')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="lastName">Last Name</label>
                                    <input type="text" class="form-control @error('lastName') is-invalid @enderror"
                                        name="lastName" id="lastName" value="{{ old('lastName') }}">
                                    @error('lastName')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="contactNumber">Contact Number</label>
                                <input type="text" class="form-control @error('contactNumber') is-invalid @enderror"
                                        name="contactNumber" id="contactNumber" value="{{ old('contactNumber') }}">
                                @error('contactNumber')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="form-group col-lg-6">
                                    <label for="checkin">Check In</label>
                                    <input type="datetime-local"
                                        class="form-control @error('checkin') is-invalid @enderror" name="checkin"
                                        id="checkin" value="{{ old('checkin') }}">
                                    @error('checkin')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-group col-lg-6">
                                    <label for="checkout">Check Out</label>
                                    <input type="datetime-local"
                                        class="form-control @error('checkout') is-invalid @enderror" name="checkout"
                                        id="checkout" value="{{ old('checkout') }}">
                                    @error('checkout')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-lg-4">
                                    <label for="adult">Adult</label>
                                    <input type="text" class="form-control digit_only @error('adult') is-invalid @enderror"
                                        name="adult" id="adult" value="{{ old('adult') ?? 0 }}">
                                    @error('adult')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-group col-lg-4">
                                    <label for="kids">Kids</label>
                                    <input type="text" class="form-control digit_only @error('kids') is-invalid @enderror"
                                        name="kids" id="kids" value="{{ old('kids') ?? 0 }}">
                                    @error('kids')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-group col-lg-4">
                                    <label for="senior">Senior Citizen</label>
                                    <input type="text" class="form-control digit_only @error('senior') is-invalid @enderror"
                                        name="senior" id="senior" value="{{ old('senior') ?? 0 }}">
                                    @error('senior')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-group col-lg-6">
                                    <label class="form-label">Select</label>
                                    <div class="selectgroup selectgroup-pills">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="types" value="1" class="selectgroup-input"
                                                checked="">
                                            <span class="selectgroup-button selectgroup-button-icon"><i
                                                    class="fas fa-sun"></i> Day</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="radio" name="types" value="2" class="selectgroup-input">
                                            <span class="selectgroup-button selectgroup-button-icon"><i
                                                    class="fas fa-moon"></i> Night</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="radio" name="types" value="3" class="selectgroup-input">
                                            <span class="selectgroup-button selectgroup-button-icon"><i
                                                    class="fas fa-cloud-moon"></i> Overnight</span>
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group col-lg-6">
                                    <label class="form-label">Breakfast</label>
                                    <div class="selectgroup selectgroup-pills">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="breakfast" value="1" class="selectgroup-input">
                                            <span class="selectgroup-button selectgroup-button-icon">Yes</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="radio" name="breakfast" value="0" class="selectgroup-input" checked="">
                                            <span class="selectgroup-button selectgroup-button-icon">No</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Cottages</label>
                                <div class="selectgroup selectgroup-pills @error('cottages') is-invalid @enderror">
                                    <div class="row">
                                        @foreach ($cottages as $cottage)
                                        <div class="col-lg-4">
                                            <label class="selectgroup-item" style="width: inherit;">
                                                <input type="checkbox" name="cottages[]" value="{{ $cottage->id }}"
                                                    class="selectgroup-input">
                                                <span class="selectgroup-button"
                                                    style="height: 100%; border-radius: 0.25rem !important;">
                                                    <b>{{ $cottage->name }}</b>
                                                    <p style="white-space: pre-wrap;">P{{ number_format($cottage->price, 0) }}, {!! $cottage->descriptions !!}</p>
                                                </span>
                                            </label>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>

                                {{-- <div class="selectgroup selectgroup-pills @error('rooms') is-invalid @enderror">
                                    @foreach ($cottages as $cottage)
                                        <label class="selectgroup-item" style="width: inherit;">
                                            <input type="checkbox" name="cottages[]" value="{{ $cottage->id }}" class="selectgroup-input">
                                            <span class="selectgroup-button selectgroup-button-icon">{{ $cottage->name }}</span>
                                        </label>
                                    @endforeach
                                </div> --}}
                                @error('cottages')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">Rooms</label>
                                <div class="selectgroup selectgroup-pills @error('rooms') is-invalid @enderror">
                                    <div class="row">
                                        @foreach ($rooms as $room)
                                        <div class="col-lg-4">
                                            <label class="selectgroup-item" style="width: inherit;">
                                                <input type="checkbox" name="rooms[]" value="{{ $room->id }}"
                                                    class="selectgroup-input">
                                                <span class="selectgroup-button"
                                                    style="height: 100%; border-radius: 0.25rem !important;">
                                                    <b>{{ $room->name }}</b>
                                                    <p style="white-space: pre-wrap;">P{{ number_format($room->price, 0) }}, {!! $room->descriptions !!}</p>
                                                </span>
                                            </label>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>

                                {{-- <div class="selectgroup selectgroup-pills @error('rooms') is-invalid @enderror">
                                    @foreach ($rooms as $room)
                                        <label class="selectgroup-item" style="width: inherit;">
                                            <input type="checkbox" name="rooms[]" value="{{ $room->id }}" class="selectgroup-input">
                                            <span class="selectgroup-button selectgroup-button-icon">{{ $room->name }}</span>
                                        </label>
                                    @endforeach
                                </div> --}}
                                @error('rooms')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            
                            <button type="submit" class="text-right btn btn-lg btn-outline-dark button-secondary large w-inline-block radius-zero mt-3">Submit</button>
                        </form>
                    </div>
                </div>
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
@endsection