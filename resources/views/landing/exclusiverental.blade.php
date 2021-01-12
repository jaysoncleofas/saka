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

    .grecaptcha-badge { visibility: hidden; }
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
                </div>
                <div class="col-lg-8">
                    <div class="card reservate-room">
                        <div class="reservate-room-title-wrapper">
                            <h3>Reserve Cottage/Room</h3>
                        </div>
                        <div class="reservate-room-content">
                            <div>
                                <form class="room-reservation-form" action="{{ route('landing.exclusive_rental_store') }}"
                                    method="POST" autocomplete="off">
                                    @csrf
                                    <input type='hidden' name='recaptcha_token' id='recaptcha_token'
                                    @if($errors->has('recaptcha_token'))
                                        {{$errors->first('recaptcha_token')}}
                                    @endif>
                                    <div class="form-group">
                                        <label for="datepicker">Check-in Date:</label>
                                        <input type="text" name="checkin" required
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
                                                @for ($i = 2; $i <= 150; $i++) <option
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
                                                @for ($i = 1; $i <= 150; $i++) <option
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
                                                @for ($i = 1; $i <= 150; $i++) <option
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
                                            <input type="text" required
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
                                            <input type="text" required
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
                                            <input type="text" required
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
                                            <input type="email" required class="form-control @error('email') is-invalid @enderror"
                                                name="email" id="email" value="{{ old('email') }}">
                                            @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>

                                        <div class="form-group col-md-12">
                                            <label for="address">Address</label>
                                            <textarea required class="form-control @error('address') is-invalid @enderror" name="address" id="address"  rows="3">{{ old('address') }}</textarea>
                                            @error('address')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>

                                        <div class="col-lg-12">
                                            <button type="submit"
                                                class="btn btn-lg btn-dark button-primary large w-inline-block radius-zero mt-3 btn-submit">Book
                                                Now</button>
                                                {{-- <p class="mt-4">This site is protected by ReCaptcha and the Google
                                                    <a href="https://policies.google.com/privacy">Privacy Policy</a> and
                                                    <a href="https://policies.google.com/terms">Terms of Service</a> apply.</p> --}}
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

    @include('landing.footer')

    @endsection

    @section('script')
    {{-- <script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.sitekey') }}"></script>
    <script>
    grecaptcha.ready(function() {
    grecaptcha.execute('{{ config('services.recaptcha.sitekey') }}').then(function(token) {
    document.getElementById("recaptcha_token").value = token;
    }); });
    </script> --}}

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
        // $('#datepicker').datepicker('setDate', new_date);

        var getexclusive_available = function () {
            $.ajax({
                type: 'post',
                url: "{{ route('landing.getexclusive_available') }}",
                data: {
                    checkin: $('#datepicker').val(),
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (result) {
                    // console.log(result);
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
        }
        if($('#datepicker').val() != ''){
            getexclusive_available();
        }
        $(document).on('change', '#datepicker', function () {
            getexclusive_available();
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

        $(document).on('click', '.btn-submit', function () {
            var _this = $(this);
            if ($('#datepicker').val() == "") {
                swal({
                    title: 'Error!',
                    text: 'Check-in Date must be filled out',
                    icon: "error",
                    button: true,
                });
                return false;
            } else if(!$('input:radio[name="type"]:checked').val()) {
                swal({
                    title: 'Error!',
                    text: 'Select Day use or Nigth use!',
                    icon: "error",
                    button: true,
                });
                return false;
            } else if($('#firstName').val() == "") {
                swal({
                    title: 'Error!',
                    text: 'First Name field is required!',
                    icon: "error",
                    button: true,
                });
                return false;
            } else if($('#lastName').val() == "") {
                swal({
                    title: 'Error!',
                    text: 'Last Name field is required!',
                    icon: "error",
                    button: true,
                });
                return false;
            } else if($('#contactNumber').val() == "") {
                swal({
                    title: 'Error!',
                    text: 'Contact Number field is required!',
                    icon: "error",
                    button: true,
                });
                return false;
            } else if($('#email').val() == "") {
                swal({
                    title: 'Error!',
                    text: 'Email field is required!',
                    icon: "error",
                    button: true,
                });
                return false;
            } else if($('#address').val() == "") {
                swal({
                    title: 'Error!',
                    text: 'Address field is required!',
                    icon: "error",
                    button: true,
                });
                return false;
            } else {
                _this.attr("disabled", true);
                _this.append('<span class="spinner-border spinner-border-sm ml-2" role="status" aria-hidden="true"></span>');
                $('.room-reservation-form').submit();
            }
        });
    </script>
    @endsection
