@extends('layouts.resort')

@section('style')
<style>
    html {
        scroll-behavior: smooth;
    }

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
        border-color: #ced4da;
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

    .selectgroup-input:checked+.selectgroup-button {
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

<div class="section room">
    <div class="image-wrapper room">
        <img style="opacity: 1; transform: translate3d(0px, 0px, 0px) scale3d(1.1, 1.1, 1) rotateX(0deg) rotateY(0deg) rotateZ(0deg) skew(0deg, 0deg); transform-style: preserve-3d;"
            src="{{ asset('pics/pic2.jpeg') }}"
            alt="" sizes="100vw" class="image room">
    </div>
    <div class="container">
        <div id="Book-Now" class="room-wrapper">
            <div class="row">
                <div class="col-lg-4">
                    <h1 class="title room">Exclusive Rental</h1>
                    <div class="price">15,000&nbsp;PHP - 25,000&nbsp;PHP</div>
                    <div class="divider room-page"></div>
                    <p class="paragraph room">Exclusive rental is 15,000php good for 60pax(all cottages for day tour included) <br>
                        200php per head for extra person <br>
                        Check in is 9am and Check out is 5pm<br> <br>
                        For overnight 25,000php for 30pax(all a/c rooms and non a/c rooms are included except A-house). <br>
                        250php per head for extra person  <br>
                        Check in is 9am and Check out is 11am</p>
                    {{-- <a href="#Gallery"
                        class="btn btn-lg btn-outline-dark button-secondary large w-inline-block radius-zero view-gallery">View
                        Gallery</a> --}}
                    {{-- <div class="about-room-page">
                        <div class="split-content card-about-room-left">
                            <div class="from-text">From</div>
                            <div class="card-price-wrapper">
                                <div>&nbsp;/night</div>
                            </div>
                        </div>
                    </div> --}}
                    {{-- <a href="{{ route('room.book', $cottage->id) }}"
                    class="btn btn-lg btn-outline-dark button-secondary large radius-zero">Book Now</a> --}}
                    {{-- <a href="{{ route('landing.contact') }}"
                    class="btn btn-lg btn-outline-dark button-secondary large radius-zero">Book Now</a> --}}
                </div>
                <div class="col-lg-8">
                    <div class="card reservate-room">
                        <div class="reservate-room-title-wrapper">
                            <h3>Reservate Cottage/Room</h3>
                        </div>
                        <div class="reservate-room-content">
                            <div>
                                <form action="{{ route('landing.exclusive_rental_store') }}"
                                    method="POST" autocomplete="off">
                                    @csrf

                                    <div class="form-group">
                                        <label for="datepicker">Check-in Date:</label>
                                        <input type="text" name="checkin"
                                            class="form-control @error('checkin') is-invalid @enderror" id="datepicker" value="{{ old('checkin') }}">
                                        @error('checkin')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-12 mb-2">
                                            <span class="day-tour">Good for 60pax, 200php for extra person</span>
                                            <span class="overnight-tour d-none">Good for 30pax, 250php for extra person</span>
                                        </div>

                                        <div class="col-lg-12 mb-0">
                                            <div class="form-group">
                                                <div
                                                    class="selectgroup selectgroup-pills @error('type') is-invalid @enderror">
                                                    <label class="selectgroup-item pb-0">
                                                        <input type="radio" name="type" value="day"
                                                            class="selectgroup-input day" disabled
                                                            {{ old('type') == 'day' ? 'checked' : '' }}>
                                                        <span class="selectgroup-button selectgroup-button-icon span-day disabled"><i
                                                                class="fas fa-sun"></i> Day 9am - 5pm</span>
                                                    </label>
                                                    <label class="selectgroup-item pb-0">
                                                        <input type="radio" name="type" value="overnight"
                                                            class="selectgroup-input overnight" disabled
                                                            {{ old('type') == 'overnight' ? 'checked' : '' }}>
                                                        <span class="selectgroup-button selectgroup-button-icon span-overnight disabled"><i
                                                                class="fas fa-moon"></i> Overnight 9am - 11am</span>
                                                    </label>
                                                </div>
                                                @error('type')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group col-lg-4">
                                            <select name="adults" id="adults" class="form-control">
                                                @for ($i = 2; $i <= 100; $i++) <option
                                                    {{ old('adults') == $i ? 'selected' : '' }} value="{{ $i }}">
                                                    {{ $i }} {{ $i > 1 ? 'Adults' : 'Adult' }}</option>
                                                    @endfor
                                            </select>
                                            @error('adults')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>

                                        <div class="form-group col-lg-4">
                                            <select name="kids" id="kids" class="form-control">
                                                <option value="0">No Kids</option>
                                                @for ($i = 1; $i <= 100; $i++) <option
                                                    {{ old('kids') == $i ? 'selected' : '' }} value="{{ $i }}">{{ $i }}
                                                    {{ $i > 1 ? 'Kids' : 'Kid' }}</option>
                                                    @endfor
                                            </select>
                                            @error('kids')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>

                                        <div class="form-group col-lg-4">
                                            <select name="senior_citizen" id="senior_citizen" class="form-control">
                                                <option value="0">No Senior Citizen</option>
                                                @for ($i = 1; $i <= 100; $i++) <option
                                                    {{ old('senior_citizen') == $i ? 'selected' : '' }}
                                                    value="{{ $i }}">{{ $i }}
                                                    {{ $i > 1 ? 'Senior Citizens' : 'Senior Citizen' }}</option>
                                                    @endfor
                                            </select>
                                            @error('senior_citizen')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>

                                        {{-- <div class="row"> --}}
                                        <div class="form-group col-md-6">
                                            <label for="firstName">First Name</label>
                                            <input type="text"
                                                class="form-control @error('firstName') is-invalid @enderror"
                                                name="firstName" id="firstName" value="{{ old('firstName') }}">
                                            @error('firstName')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="lastName">Last Name</label>
                                            <input type="text"
                                                class="form-control @error('lastName') is-invalid @enderror"
                                                name="lastName" id="lastName" value="{{ old('lastName') }}">
                                            @error('lastName')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        {{-- </div> --}}

                                        <div class="form-group col-md-6">
                                            <label for="contactNumber">Contact Number</label>
                                            <input type="text"
                                                class="form-control @error('contactNumber') is-invalid @enderror"
                                                name="contactNumber" id="contactNumber"
                                                value="{{ old('contactNumber') }}">
                                            @error('contactNumber')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="email">Email</label>
                                            <input type="text" class="form-control @error('email') is-invalid @enderror"
                                                name="email" id="email" value="{{ old('email') }}">
                                            @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>

                                        <div class="col-lg-12">
                                            <button type="submit"
                                                class="btn btn-lg btn-dark button-primary large w-inline-block radius-zero mt-3">Book
                                                Now</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    {{-- <div id="Gallery" class="section room-gallery">
        <div data-w-id="994faaf5-2600-70ed-ac03-5b5e6c485f48"
            style="transform: translate3d(0px, 0px, 0px) scale3d(1, 1, 1) rotateX(0deg) rotateY(0deg) rotateZ(0deg) skew(0deg, 0deg); opacity: 1; transform-style: preserve-3d;"
            class="container-small-658px text-center w-container">
            <div class="subtitle-wrapper">
                <div>01</div>
                <div class="dash"></div>
                <div>Gallery</div>
            </div>
            <h2 class="title room-gallery">A carefully designed room just for you</h2>
            <p class="paragraph room-gallery">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Et sapien tempus duis
            facilisis pretium massa pellentesque.</p>
        </div>
        <div class="container">
            <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                <ol class="carousel-indicators">
                    @foreach ($cottage->images as $key => $item)
                    <li data-target="#carouselExampleIndicators" data-slide-to="{{ $key }}"
                        class="{{ $loop->first ? 'active' : ''}}"></li>
                    @endforeach
                </ol>
                <div class="carousel-inner">
                    @foreach ($cottage->images as $item)
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
    </div> --}}

    @include('landing.footer')

    @endsection

    @section('script')
    @if (session('notification'))
    <script>
        swal({
            title: 'Error!',
            text: "Invalid date",
            icon: "error",
            button: true,
        });

    </script>
    @endif

    <script>
        $(document).ready(function () {
            // Add smooth scrolling to all links
            $(".view-gallery").on('click', function (event) {

                // Make sure this.hash has a value before overriding default behavior
                if (this.hash !== "") {
                    // Prevent default anchor click behavior
                    event.preventDefault();

                    // Store hash
                    var hash = this.hash;

                    // Using jQuery's animate() method to add smooth page scroll
                    // The optional number (800) specifies the number of milliseconds it takes to scroll to the specified area
                    $('html, body').animate({
                        scrollTop: $(hash).offset().top
                    }, 100, function () {

                        // Add hash (#) to URL when done scrolling (default click behavior)
                        window.location.hash = hash;
                    });
                } // End if
            });
        });

        var today = new Date().toISOString().split('T')[0];
        var new_date = moment().add(3, 'days').format('MM-DD-YYYY');

        $('#datepicker').datepicker({
            startDate: new_date
        });

        
        $(document).on('change', '#datepicker', function () {
            var _this = $(this);
            $.ajax({
                type: 'post',
                url: "{{ route('landing.getexclusive_available') }}",
                data: {
                    checkin: _this.val(),
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (result) {
                    console.log(result);
                    if(result.status.includes('overnight')) {
                        $('.overnight').removeAttr('disabled');
                        $('.span-overnight').removeClass('disabled');
                    } else {
                        $('.overnight').attr('disabled', true);
                        $('.span-overnight').addClass('disabled');
                    }
                    if(result.status.includes('day')) {
                        $('.day').removeAttr('disabled');
                        $('.span-day').removeClass('disabled');
                    } else {
                        $('.day').attr('disabled', true);
                        $('.span-day').addClass('disabled');
                    }
                    $('.overnight').prop('checked', false);
                    $('.day').prop('checked', false);
                }
            });
        });

        $(document).on('change', 'input:radio[name="type"]', function () {
            var _this = $(this);
            if (_this.is(':checked') && _this.val() == 'day') {
                $('.day-tour').removeClass('d-none');
                $('.overnight-tour').addClass('d-none');
            } else {
                $('.day-tour').addClass('d-none');
                $('.overnight-tour').removeClass('d-none');
            }
        });
    </script>
    @endsection