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

<div class="section rooms">
    <div class="container-small-616px text-center w-container">
        <h2 class="title request-info mb-5">Reservation</h2>
        {{-- <p>Find and reserve your selected room and get the lowest prices.</p> --}}
    </div>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{ route('landing.reservation-store') }}">
                            @csrf
                            <input type="hidden" name="is_reservation" value="1">

                            <div class="row mb-5">
                                <div class="col-lg-4">
                                    <img src="{{ $room->coverimage() ? asset('storage/rooms/'.$room->coverimage()->path) : asset('images/img07.jpg') }}"
                                        class="img-fluid" alt="">
                                </div>
                                <div class="col-lg-8">
                                    <h3 class="title room">{{ $room->name }}</h3>
                                    <h3 class="title room">P{{ number_format($room->price, 2) }}</h3>
                                    <p class="paragraph room">{{ $room->descriptions }}</p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-4">
                                    <label>Select Your Booking Date: </label>
                                    <div id="datepicker"></div>
                                    <input type="hidden" id="my_hidden_input">
                                </div>

                                <div class="form-group col-lg-4">
                                    {{-- <div class="card"> --}}
                                    {{-- </div> --}}
                                    {{-- <label for="checkin">Select Date</label>
                                    <input type="date" class="form-control @error('checkin') is-invalid @enderror"
                                        name="checkin" id="checkin"
                                        value="{{ old('checkin') ?? date('Y-m-d\TH:i:s') }}">
                                    @error('checkin')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror --}}
                                </div>
                            </div>

                            <div class="room-select d-none">
                                <div class="row">
                                    <div class="form-group col-lg-4">
                                        <label class="form-label">Select</label>

                                        <div class="selectgroup selectgroup-pills">
                                            <label class="selectgroup-item">
                                                <input type="radio" name="slots" value="1" class="selectgroup-input"
                                                    {{ old('slots') == 1 ? 'checked' : '' }}>
                                                <span class="selectgroup-button selectgroup-button-icon">Night(5pm -
                                                    9pm)</span>
                                            </label>
                                            <label class="selectgroup-item">
                                                <input type="radio" name="slots" value="0" class="selectgroup-input"
                                                    {{ old('slots') == 0 ? 'checked' : '' }}>
                                                <span class="selectgroup-button selectgroup-button-icon">Overnight(2pm -
                                                    11am)</span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="form-group col-lg-2">
                                        <label class="form-label">Breakfast
                                            ({{ config('yourconfig.resort')->is_promo ? 'Free' : 'P'.number_format(config('yourconfig.resort')->breakfastPrice, 0) }})</label>
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
                                                    class="selectgroup-button">{{ $breakfast->title.' P'.number_format($breakfast->price, 0).' ('.$breakfast->notes.')' }}</span>
                                            </label>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-lg-4">
                                        <label for="Adults">Adults</label>
                                        <input type="text"
                                            class="form-control digit_only @error('Adults') is-invalid @enderror"
                                            name="Adults" id="Adults" value="{{ old('adults') ?? 0 }}">
                                        @error('Adults')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-lg-4">
                                        <label for="Kids">Kids</label>
                                        <input type="text"
                                            class="form-control digit_only @error('Kids') is-invalid @enderror" name="Kids"
                                            id="Kids" value="{{ old('kids') ?? 0 }}">
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


                                </div>

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

                                <button type="submit"
                                    class="text-right btn btn-lg btn-outline-dark button-secondary large w-inline-block radius-zero mt-3">Submit</button>
                            </div>

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
        text: '{{ session('
        notification ') }}',
        icon: "success",
        button: true,
    });

</script>
@endif
<script>
    var today = new Date().toISOString().split('T')[0];
    // console.log(today);
    var new_date = moment().add(3, 'days').format('MM-DD-YYYY');
    console.log(new_date);
    // $('#checkin').attr("min", new_date);

    // $(document).on('change', '#checkin', function () {
    //     var _this = $(this);
        $.ajax({
            type: 'get',
            url: "{{ route('landing.getrooms_available', $room->id) }}",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (result) {
                console.log(result);
            }
        });
    // });

    var disabledDates = ['01/11/2021','01/13/2021'];
    $('#datepicker').datepicker({
        datesDisabled: disabledDates,
        startDate: new_date
    });
    // $('#datepicker').on('changeDate', function() {
    //     $('#my_hidden_input').val(
    //         $('#datepicker').datepicker('getFormattedDate')
    //     );
    // });

</script>
@endsection
