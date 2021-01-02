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
                        <form method="POST" action="{{ route('landing.reservation-store') }}">
                            @csrf
                            <input type="hidden" name="is_reservation" value="1">
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="firstName">First Name</label>
                                    <input type="text" class="form-control @error('firstName') is-invalid @enderror"
                                        name="firstName" id="firstName" value="{{ old('firstName') }}">
                                    @error('firstName')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="lastName">Last Name</label>
                                    <input type="text" class="form-control @error('lastName') is-invalid @enderror"
                                        name="lastName" id="lastName" value="{{ old('lastName') }}">
                                    @error('lastName')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="contactNumber">Contact Number</label>
                                    <input type="text" class="form-control @error('contactNumber') is-invalid @enderror"
                                            name="contactNumber" id="contactNumber" value="{{ old('contactNumber') }}">
                                    @error('contactNumber')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-lg-4">
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

                                <div class="form-group col-lg-4">
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
                                    <label for="Adults">Adults</label>
                                    <input type="text"
                                        class="form-control digit_only @error('Adults') is-invalid @enderror"
                                        name="Adults" id="Adults"
                                        value="{{ old('adults') ?? 0 }}">
                                    @error('Adults')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-group col-lg-4">
                                    <label for="Kids">Kids</label>
                                    <input type="text"
                                        class="form-control digit_only @error('Kids') is-invalid @enderror"
                                        name="Kids" id="Kids"
                                        value="{{ old('kids') ?? 0 }}">
                                    @error('Kids')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-group col-lg-4">
                                    <label for="Senior_Citizen">Senior Citizen</label>
                                    <input type="text"
                                        class="form-control digit_only @error('Senior_Citizen') is-invalid @enderror"
                                        name="Senior_Citizen" id="Senior_Citizen"
                                        value="{{ old('Senior_Citizen') ?? 0 }}">
                                    @error('Senior_Citizen')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-group col-lg-4">
                                    <label class="form-label">Select Type</label>
                                    <div class="selectgroup selectgroup-pills @error('type') is-invalid @enderror">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="type" value="day" class="selectgroup-input"
                                                {{ old('type') == 'day' ? 'checked' : '' }}>
                                            <span class="selectgroup-button selectgroup-button-icon"><i
                                                    class="fas fa-sun"></i> Day</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="radio" name="type" value="night" class="selectgroup-input"
                                                {{ old('type') == 'night' ? 'checked' : '' }}>
                                            <span class="selectgroup-button selectgroup-button-icon"><i
                                                    class="fas fa-moon"></i> Night</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="radio" name="type" value="overnight" class="selectgroup-input"
                                                {{ old('type') == 'overnight' ? 'checked' : '' }}>
                                            <span class="selectgroup-button selectgroup-button-icon"><i
                                                    class="fas fa-cloud-moon"></i> Overnight</span>
                                        </label>
                                    </div>
                                    @error('type')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-group col-lg-2">
                                    <label class="form-label">Breakfast
                                        ({{ config('yourconfig.resort')->breakfastPrice == 0 ? 'Free' : 'P'.number_format(config('yourconfig.resort')->breakfastPrice, 0) }})</label>
                                    <div class="selectgroup selectgroup-pills">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="isbreakfast" value="1" class="selectgroup-input"
                                                {{ old('isbreakfast') == 1 ? 'checked' : '' }}>
                                            <span class="selectgroup-button selectgroup-button-icon">Yes</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="radio" name="isbreakfast" value="0" class="selectgroup-input"
                                                {{ old('isbreakfast') == 0 ? 'checked' : '' }}>
                                            <span class="selectgroup-button selectgroup-button-icon">No</span>
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group col-lg-6">
                                    <label class="form-label">Breakfast Add ons</label>
                                    <div class="selectgroup selectgroup-pills">
                                        @foreach ($breakfasts as $breakfast)
                                        <label class="selectgroup-item">
                                            <input type="checkbox" name="breakfast[]" value="{{ $breakfast->id }}"
                                                class="selectgroup-input"
                                                {{ old('breakfast') ? (in_array($breakfast->id, old('breakfast')) ? 'checked' : '') : '' }}>
                                            <span
                                                class="selectgroup-button">{{ $breakfast->title.' P'.number_format($breakfast->price, 0) }}</span>
                                        </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group mb-0">
                                        <label class="form-label">Cottages</label>
                                        <div class="selectgroup selectgroup-pills">
                                            <div class="row cottage-result">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="form-group mb-0">
                                        <label class="form-label">Rooms</label>
                                        <div class="selectgroup selectgroup-pills">
                                            <div class="row room-result">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- <div id="accordion">
                                <div class="accordion">
                                    <div class="accordion-header" role="button" data-toggle="collapse"
                                        data-target="#panel-body-1" aria-expanded="true">
                                        <h4>Cottages</h4>
                                    </div>
                                    <div class="accordion-body collapse show" id="panel-body-1" data-parent="#accordion"
                                        style="">
                                        <div class="form-group mb-0 mt-4">
                                            <div
                                                class="selectgroup selectgroup-pills @error('cottage') is-invalid @enderror">
                                                <div class="row">
                                                    @foreach ($cottages as $cottage)
                                                    <div class="col-lg-4">
                                                        <label class="selectgroup-item mb-0 uncheck-room" style="width: inherit;">
                                                            <input type="radio" name="cottage"
                                                                value="{{ $cottage->id }}"
                                                                class="selectgroup-input radio-cottage"
                                                                {{ old('cottage') == $cottage->id ? 'checked' : '' }}>
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
                                            @error('cottage')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion">
                                    <div class="accordion-header collapsed" role="button"
                                        data-toggle="collapse" data-target="#panel-body-2" aria-expanded="false">
                                        <h4>Rooms</h4>
                                    </div>
                                    <div class="accordion-body collapse" id="panel-body-2" data-parent="#accordion"
                                        style="">
                                        <div class="form-group mb-0 mt-4">
                                            <div
                                                class="selectgroup selectgroup-pills @error('room') is-invalid @enderror">
                                                <div class="row">
                                                    @foreach ($rooms as $room)
                                                    <div class="col-lg-4">
                                                        <label class="selectgroup-item mb-0 uncheck-cottage" style="width: inherit;">
                                                            <input type="radio" name="room" value="{{ $room->id }}"
                                                                class="selectgroup-input radio-room"
                                                                {{ old('room') == $room->id ? 'checked' : '' }}>
                                                            <span class="selectgroup-button"
                                                                style="height: 100%; border-radius: 0.25rem !important;">
                                                                <b>{{ $room->name }}</b>
                                                                <p style="white-space: pre-wrap;">P{{ number_format($room->price, 0) }}, {!! $room->descriptions !!}</p>
                                                                @if ($room->extraPerson != 0)
                                                                <div class="form-group">
                                                                    <input type="text"
                                                                        placeholder="Max Extra Person({{ $room->extraPersonAvailable }})"
                                                                        class="form-control digit_only" id="extraPerson"
                                                                        name="extraPerson{{ $room->id }}"
                                                                        value="{{ old('extraPerson'.$room->id) }}">
                                                                </div>
                                                                @endif
                                                            </span>
                                                        </label>
                                                    </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                            @error('room')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                             --}}
                            <button type="submit" class="text-right btn btn-lg btn-outline-dark button-secondary large w-inline-block radius-zero mt-3">Submit</button>
                        </form>
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

        $(document).on('change', '#checkin, #checkout', function () {
        // $(document).on('click', '.show-cottages-rooms', function () {
            var checkin = $('#checkin').val();
            var checkout = $('#checkout').val();
            $.ajax({
                type: 'post',
                url: "{{ route('landing.cottage_available') }}",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    checkin: checkin,
                    checkout: checkout,
                },
                success: function (e) {
                    var _html = '';
 
                    for (var i = 0; i < e.cottages.length; i++) {
            
                    _html += '                <div class="col-lg-4">';
                    _html += '                    <label class="selectgroup-item mb-0 uncheck-cottage" style="width: inherit;">';
                    _html += '                        <input type="radio" name="cottage" value="'+ e.cottages[i].id +'" class="selectgroup-input radio-cottage">';
                    _html += '                        <span class="selectgroup-button" style="height: 100%; border-radius: 0.25rem !important;">';
                    _html += '                             <b>'+ e.cottages[i].name +'</b>';
                    _html += '                            <p style="white-space: pre-wrap;">P'+ e.cottages[i].price +', '+ e.cottages[i].descriptions +'</p>';
                    _html += '                        </span>';
                    _html += '                    </label>';
                    _html += '                </div>';
                    }
                    $('.cottage-result').html(_html);
                }
            });

            $.ajax({
                type: 'post',
                url: "{{ route('landing.room_available') }}",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    checkin: checkin,
                    checkout: checkout,
                },
                success: function (e) {
                    var _html = '';
 
                    for (var i = 0; i < e.rooms.length; i++) {
            
                    _html += '                <div class="col-lg-4">';
                    _html += '                    <label class="selectgroup-item mb-0 uncheck-room" style="width: inherit;">';
                    _html += '                        <input type="radio" name="cottage" value="'+ e.rooms[i].id +'" class="selectgroup-input radio-cottage">';
                    _html += '                        <span class="selectgroup-button" style="height: 100%; border-radius: 0.25rem !important;">';
                    _html += '                             <b>'+ e.rooms[i].name +'</b>';
                    _html += '                            <p style="white-space: pre-wrap;">P'+ e.rooms[i].price +', '+ e.rooms[i].descriptions +'</p>';
                    if(e.rooms[i].extraPerson != '0.00') {
                    _html += '    <div class="form-group">';
                    _html += '        <input type="text" placeholder="Max Extra Person('+ e.rooms[i].extraPersonAvailable +')" class="form-control digit_only" id="extraPerson" name="extraPerson'+ e.rooms[i].id +'">';
                    _html += '    </div>';
                    }
                    _html += '                        </span>';
                    _html += '                    </label>';
                    _html += '                </div>';
                    }
                    $('.room-result').html(_html);
                }
            });
        });

    </script>
@endsection