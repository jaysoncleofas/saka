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
                src="{{ asset('pics/pic10.jpg') }}"
                alt="" sizes="100vw" class="image room">
        </div>
        <div class="container">
            <div id="Book-Now" class="room-wrapper">
                <div class="row">
                    <div class="col-lg-4">
                        <h1 class="title room">{{ $room->name }}</h1>
                        <div class="price">{{ number_format($room->price) }}&nbsp;PHP</div>
                        <div class="divider room-page"></div>
                        <p class="paragraph room">{{ $room->descriptions }}</p>
                        <a href="#Gallery" class="btn btn-lg btn-outline-dark button-secondary large w-inline-block radius-zero view-gallery">View Gallery</a>
                    </div>
                    <div class="col-lg-8">
                        <div class="card reservate-room">
                            <div class="reservate-room-title-wrapper">
                                <h3>Reserve Room</h3>
                            </div>
                            <div class="reservate-room-content">
                                <div>
                                    <form class="room-reservation-form" action="{{ route('landing.room_reservation_store', $room->id) }}" method="POST" autocomplete="off">
                                        @csrf
                                        <input type='hidden' name='recaptcha_token' id='recaptcha_token'
                                        @if($errors->has('recaptcha_token'))
                                            {{$errors->first('recaptcha_token')}}
                                        @endif>
                                        <div class="form-group">
                                            <label for="datepicker">Check-in Date:</label>
                                            <input type="text" name="checkin" required class="form-control @error('checkin') is-invalid @enderror" id="datepicker" value="{{ old('checkin') }}">
                                            @error('checkin')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>

                                        <div class="form-group mb-0">
                                            {{-- <label class="form-label">Select Type</label> --}}
                                            <div
                                                class="selectgroup selectgroup-pills @error('type') is-invalid @enderror">
                                                <label class="selectgroup-item pb-0">
                                                    <input type="radio" name="type" value="day"
                                                        class="selectgroup-input"
                                                        {{ old('type') == 'day' ? 'checked' : '' }}>
                                                    <span class="selectgroup-button selectgroup-button-icon"><i
                                                            class="fas fa-sun"></i> Day
                                                        {{ config('yourconfig.resort')->day }}</span>
                                                </label>
                                                <label class="selectgroup-item pb-0">
                                                    <input type="radio" name="type" value="night"
                                                        class="selectgroup-input"
                                                        {{ old('type') == 'night' ? 'checked' : '' }}>
                                                    <span class="selectgroup-button selectgroup-button-icon"><i
                                                            class="fas fa-moon"></i> Night
                                                        {{ config('yourconfig.resort')->night }}</span>
                                                </label>
                                                <label class="selectgroup-item pb-0">
                                                    <input type="radio" name="type" value="overnight"
                                                        class="selectgroup-input overnight"
                                                        {{ old('type') == 'overnight' ? 'checked' : '' }}>
                                                    <span class="selectgroup-button selectgroup-button-icon"><i
                                                            class="fas fa-cloud-moon"></i> Overnight
                                                        {{ config('yourconfig.resort')->overnight }}</span>
                                                </label>
                                            </div>
                                            @error('type')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>

                                        <div class="breakfast-container d-none">
                                            <div class="row">
                                                <div class="form-group col-lg-12 mb-0">
                                                    <label class="form-label">Free Breakfast</label>
                                                    <input type="hidden" name="isbreakfast" value="1">
                                                </div>

                                                <div class="form-group col-lg-12 breakfastaddons-container">
                                                    <label class="form-label">Breakfast Add ons:</label>
                                                    <div class="selectgroup selectgroup-pills">
                                                        @foreach ($breakfasts as $breakfast)
                                                        <label class="selectgroup-item mb-0">
                                                            <input type="checkbox" name="breakfast[]"
                                                                value="{{ $breakfast->id }}" class="selectgroup-input"
                                                                {{ old('breakfast') ? (in_array($breakfast->id, old('breakfast')) ? 'checked' : '') : '' }}>
                                                            <span
                                                                class="selectgroup-button">{{ $breakfast->title.' P'.number_format($breakfast->price, 0).' ('.$breakfast->notes.')' }}</span>
                                                        </label>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-12 mb-2">
                                                <span>{{ $room->entrancefee }} Entrance fee</span>
                                                @if ($room->entrancefee == 'Exclusive')
                                                :
                                                @foreach ($entranceFees as $item)
                                                {{ $item->title }} P{{ number_format($item->nightPrice, 0) }}
                                                @if(!($loop->last))
                                                ,
                                                @endif
                                                @endforeach
                                                @endif
                                                <br>
                                                @if ($room->extraPerson != 0)
                                                <span>Good for {{ $room->min }}pax, {{ number_format($room->extraPerson, 0) }}php for extra person(max of {{ $room->max - $room->min }})</span>
                                                @else
                                                <span>Room max capacity is {{ $room->max }}pax</span>
                                                @endif
                                            </div>
                                            
                                            <div class="form-group col-lg-4">
                                                <select name="adults" id="adults" class="form-control">
                                                    @for ($i = 1; $i <= $room->max; $i++) 
                                                    <option {{ old('adults') == $i ? 'selected' : '' }} value="{{ $i }}">{{ $i }} {{ $i > 1 ? 'Adults' : 'Adult' }}</option>
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
                                                    @for ($i = 1; $i <= $room->max; $i++) 
                                                    <option {{ old('kids') == $i ? 'selected' : '' }} value="{{ $i }}">{{ $i }} {{ $i > 1 ? 'Kids' : 'Kid' }}</option>
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
                                                    @for ($i = 1; $i <= $room->max; $i++) 
                                                    <option {{ old('senior_citizen') == $i ? 'selected' : '' }} value="{{ $i }}">{{ $i }} {{ $i > 1 ? 'Senior Citizens' : 'Senior Citizen' }}</option>
                                                    @endfor
                                                </select>
                                                @error('senior_citizen')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>

                                            <div class="form-group col-lg-12 mt-2 mb-0">
                                                <label class="form-label">Payment Method:</label>
                                                <div class="selectgroup selectgroup-pills @error('payment') is-invalid @enderror">
                                                    @foreach ($payments as $payment)
                                                    <label class="selectgroup-item mb-0">
                                                        <input type="radio" name="payment"
                                                            value="{{ $payment->id }}" class="selectgroup-input"
                                                            {{ old('payment') ? (in_array($payment->id, old('payment')) ? 'checked' : '') : '' }}>
                                                        <span
                                                            class="selectgroup-button">{{ $payment->name }}</span>
                                                    </label>
                                                    @endforeach
                                                </div>
                                                @error('payment')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>

                                            {{-- <div class="row"> --}}
                                                <div class="form-group col-md-6">
                                                    <label for="firstName">First Name</label>
                                                    <input type="text" required class="form-control @error('firstName') is-invalid @enderror"
                                                        name="firstName" id="firstName" value="{{ old('firstName') }}">
                                                    @error('firstName')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                
                                                <div class="form-group col-md-6">
                                                    <label for="lastName">Last Name</label>
                                                    <input type="text" required class="form-control @error('lastName') is-invalid @enderror"
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
                                                <input type="text" required class="form-control phone-number @error('contactNumber') is-invalid @enderror"
                                                        name="contactNumber" id="contactNumber" value="{{ old('contactNumber') }}">
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

    <div id="Gallery" class="section room-gallery mt-0">
        <div data-w-id="994faaf5-2600-70ed-ac03-5b5e6c485f48"
            style="transform: translate3d(0px, 0px, 0px) scale3d(1, 1, 1) rotateX(0deg) rotateY(0deg) rotateZ(0deg) skew(0deg, 0deg); opacity: 1; transform-style: preserve-3d;"
            class="container-small-658px text-center w-container">
            <div class="subtitle-wrapper">
                <div>01</div>
                <div class="dash"></div>
                <div>Gallery</div>
            </div>
            <h2 class="title room-gallery">A carefully designed room just for you</h2>
            {{-- <p class="paragraph room-gallery">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Et sapien tempus duis
            facilisis pretium massa pellentesque.</p> --}}
        </div>
        <div class="container">
            <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                <ol class="carousel-indicators">
                    @foreach ($room->images as $key => $item)
                    <li data-target="#carouselExampleIndicators" data-slide-to="{{ $key }}"
                        class="{{ $loop->first ? 'active' : ''}}"></li>
                    @endforeach
                </ol>
                <div class="carousel-inner">
                    @foreach ($room->images as $item)
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
        text: '{{ session('notification') }}',
        icon: "error",
        button: true,
    });
    </script>
    @endif

    <script>
        $(document).ready(function(){
            // Add smooth scrolling to all links
            $(".view-gallery").on('click', function(event) {

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
                    }, 100, function(){
                
                        // Add hash (#) to URL when done scrolling (default click behavior)
                        window.location.hash = hash;
                    });
                } // End if
            });
        });
        var new_date = moment().add(1, 'days').format('MM-DD-YYYY');

        $(document).on('change', 'input:radio[name="type"]', function () {
            if ($(this).is(':checked') && $(this).val() == 'overnight') {
                $('.breakfast-container').removeClass('d-none');
            } else {
                $('.breakfast-container').addClass('d-none');
            }
        });

        $(document).on('change', 'input:radio[name="isbreakfast"]', function () {
            if ($(this).is(':checked') && $(this).val() == '1') {
                $('.breakfastaddons-container').removeClass('d-none');
            } else {
                $('.breakfastaddons-container').addClass('d-none');
            }
        });

        var disabledDates = [];
        $.ajax({
            type: 'post',
            url: "{{ route('landing.getrooms_available', $room->id) }}",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (result) {
                for (var i = 0; i < result.dates.length; i++) {
                    var formatted_date = moment(result.dates[i]).format('MM/DD/YYYY');
                    disabledDates.push(formatted_date);
                }
                $('#datepicker').datepicker({
                    datesDisabled: disabledDates,
                    startDate: new_date
                });
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
                    text: 'Select Day use or Night use!',
                    icon: "error",
                    button: true,
                });
                return false;
            } else if(!$('input:radio[name="payment"]:checked').val()) {
                swal({
                    title: 'Error!',
                    text: 'Select payment method!',
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
