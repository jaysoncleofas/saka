@extends('layouts.app')

@section('style')
<style>
    .selectgroup-pills .selectgroup-item {
        height: 100%;
        padding-bottom: 1rem;
    }
    #msform {
        text-align: center;
        position: relative;
        margin-top: 20px
    }

    #msform fieldset .form-card {
        /* box-shadow: 0 4px 8px rgba(0, 0, 0, 0.03);
        background-color: #fff;
        border-radius: 3px;
        border: none;
        position: relative; */
        padding: 20px 40px 30px 40px;
        /* box-sizing: border-box; */
        width: 94%;
        margin: 0 3% 20px 3%;
    }

    #msform fieldset {
        background: white;
        border: 0 none;
        border-radius: 0.5rem;
        box-sizing: border-box;
        width: 100%;
        margin: 0;
        padding-bottom: 20px;
        position: relative
    }

    #msform fieldset:not(:first-of-type) {
        display: none
    }

    select.list-dt {
        border: none;
        outline: 0;
        border-bottom: 1px solid #ccc;
        padding: 2px 5px 3px 5px;
        margin: 2px
    }

    select.list-dt:focus {
        border-bottom: 2px solid #6777ef
    }

    .card {
        z-index: 0;
    }

    #progressbar {
        margin-bottom: 30px;
        overflow: hidden;
        color: lightgrey
    }

    #progressbar .active {
        color: #34395e
    }

    #progressbar li {
        list-style-type: none;
        width: 25%;
        float: left;
        position: relative
    }

    #progressbar #account:before {
        font-family: "Font Awesome 5 Free";
        font-weight: 900;
        content: "\f073";
    }

    #progressbar #personal:before {
        font-family: "Font Awesome 5 Free";
        font-weight: 900;
        content: "\f022";
    }

    #progressbar #payment:before {
        font-family: "Font Awesome 5 Free";
        font-weight: 900;
        content: "\f15c";
    }

    #progressbar #confirm:before {
        font-family: "Font Awesome 5 Free";
        font-weight: 900;
        content: "\f00c";
    }

    #progressbar li:before {
        width: 50px;
        height: 50px;
        line-height: 45px;
        display: block;
        font-size: 18px;
        color: #ffffff;
        background: lightgray;
        border-radius: 50%;
        margin: 0 auto 10px auto;
        padding: 2px
    }

    #progressbar li:after {
        content: '';
        width: 100%;
        height: 2px;
        background: lightgray;
        position: absolute;
        left: 0;
        top: 25px;
        z-index: -1
    }

    #progressbar li.active:before,
    #progressbar li.active:after {
        background: #6777ef
    }

    .fit-image {
        width: 100%;
        object-fit: cover
    }

    .selectgroup-button.selectgroup-button-icon.disabled {
        border-color: #fff !important;
        cursor: not-allowed;
    }
</style>
@endsection

@section('content')

<section class="section">
    <div class="section-header">
        <h1>Transactions</h1>
    </div>
    <div class="section-body">
        <!-- MultiStep Form -->
        <div class="container-fluid" id="grad1">
            {{-- justify-content-center --}}
            <div class="row mt-0">
                <div class="col-lg-8 col-md-10 col-sm-12 text-center p-0 mt-3 mb-2">
                    <div class="card px-0 pt-4 pb-0 mt-3 mb-3">
                        <div class="card-header">
                            <h4>Edit Transaction</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 mx-0">
                                    <form id="msform" autocomplete="off">
                                        <!-- progressbar -->
                                        <ul id="progressbar" class="pl-0">
                                            <li class="active" id="account"><strong>Date</strong></li>
                                            <li id="personal"><strong>Details</strong></li>
                                            <li id="payment"><strong>Confirm</strong></li>
                                            <li id="confirm"><strong>Finish</strong></li>
                                        </ul> <!-- fieldsets -->
                                        <fieldset>
                                            <div class="form-card text-left">
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <p class=""><strong>Control#:</strong> <span class="">{{ $transaction->id }}</span></p>
                                                        <p class=""><strong>Guest:</strong> <span class="">{{ $transaction->guest->fullname }}</span></p>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <p class=""><strong>Type:</strong> <span class="">{{ $transaction->is_reservation ? 'Reservation' : 'Walk in' }}</span></p>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="datepicker">Check-in Date:</label>
                                                    <input type="text" name="checkin"
                                                        class="form-control @error('checkin') is-invalid @enderror"
                                                        id="datepicker" value="{{ old('checkin') }}">
                                                    @error('checkin')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
    
                                                <div class="form-group">
                                                    <label class="form-label">Rental</label>
                                                    <div
                                                        class="selectgroup selectgroup-pills @error('rent_type') is-invalid @enderror">
                                                        <label class="selectgroup-item pb-0 mb-0">
                                                            <input type="radio" name="rent_type" value="cottage"
                                                                class="selectgroup-input" {{ $transaction->cottage_id ? 'checked' : '' }}>
                                                            <span
                                                                class="selectgroup-button selectgroup-button-icon">Cottage</span>
                                                        </label>
                                                        <label class="selectgroup-item pb-0 mb-0">
                                                            <input type="radio" name="rent_type" value="room"
                                                                class="selectgroup-input" {{ $transaction->room_id ? 'checked' : '' }}>
                                                            <span
                                                                class="selectgroup-button selectgroup-button-icon">Room</span>
                                                        </label>
                                                        <label class="selectgroup-item pb-0 mb-0">
                                                            <input type="radio" name="rent_type" value="exclusive_rental"
                                                                class="selectgroup-input" {{ $transaction->exclusive == 1 ? 'checked' : '' }}>
                                                            <span
                                                                class="selectgroup-button selectgroup-button-icon">Exclusive
                                                                Rental</span>
                                                        </label>
                                                    </div>
                                                    @error('rent_type')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
    
                                                <div class="cottage-use-container">
                                                    <div class="form-group">
                                                        <label class="form-label">Check-in & Check-out time</label>
                                                        <div
                                                            class="selectgroup selectgroup-pills @error('type') is-invalid @enderror">
                                                            <label class="selectgroup-item pb-0">
                                                                <input type="radio" name="type" value="day" data-sched="{{ config('yourconfig.resort')->day }}"
                                                                    class="selectgroup-input day"
                                                                    {{ old('type') == 'day' ? 'checked' : '' }}>
                                                                <span class="selectgroup-button selectgroup-button-icon"><i
                                                                        class="fas fa-sun"></i> Day
                                                                    {{ config('yourconfig.resort')->day }}</span>
                                                            </label>
                                                            <label class="selectgroup-item pb-0">
                                                                <input type="radio" name="type" value="night"
                                                                    class="selectgroup-input night" data-sched="{{ config('yourconfig.resort')->night }}"
                                                                    {{ old('type') == 'night' ? 'checked' : '' }}>
                                                                <span class="selectgroup-button selectgroup-button-icon"><i
                                                                        class="fas fa-moon"></i> Night
                                                                    {{ config('yourconfig.resort')->night }}</span>
                                                            </label>
                                                        </div>
                                                        @error('type')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                        @enderror
                                                    </div>
                                                </div>
    
                                                <div class="room-use-container d-none">
                                                    <div class="form-group">
                                                        <label class="form-label">Check-in & Check-out time</label>
                                                        <div
                                                            class="selectgroup selectgroup-pills @error('type') is-invalid @enderror">
                                                            <label class="selectgroup-item pb-0">
                                                                <input type="radio" name="type" value="night" data-sched="{{ config('yourconfig.resort')->night }}"
                                                                    class="selectgroup-input room-dn"
                                                                    {{ old('type') == 'night' ? 'checked' : '' }}>
                                                                <span class="selectgroup-button selectgroup-button-icon"><i
                                                                        class="fas fa-moon"></i> Night
                                                                    {{ config('yourconfig.resort')->night }}</span>
                                                            </label>
                                                            <label class="selectgroup-item pb-0">
                                                                <input type="radio" name="type" value="overnight" data-sched="{{ config('yourconfig.resort')->overnight }}"
                                                                    class="selectgroup-input room-dn"
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
                                                </div>
    
                                                <div class="exclusive-use-container d-none">
                                                    <div class="form-group">
                                                        <label class="form-label">Check-in & Check-out time</label>
                                                        <div
                                                            class="selectgroup selectgroup-pills @error('type') is-invalid @enderror">
                                                            <label class="selectgroup-item pb-0">
                                                                <input type="radio" name="type" value="day" data-sched="{{ config('yourconfig.resort')->day }}"
                                                                    class="selectgroup-input ex-day" disabled>
                                                                <span
                                                                    class="selectgroup-button selectgroup-button-icon ex-span-day disabled"><i
                                                                        class="fas fa-sun"></i> Day 9am - 5pm</span>
                                                            </label>
                                                            <label class="selectgroup-item pb-0">
                                                                <input type="radio" name="type" value="overnight" data-sched="9am - 11am"
                                                                    class="selectgroup-input ex-overnight" disabled>
                                                                <span
                                                                    class="selectgroup-button selectgroup-button-icon ex-span-overnight disabled"><i
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
                                            </div>
                                            <input type="button" name="next" class="first-next next action-button btn btn-primary"
                                                value="Next Step" />
                                        </fieldset>
                                        <fieldset>
                                            <div class="form-card text-left">
                                                <div class="rental-container-result"></div>
                                                <div class="breakfast-container d-none">
                                                    <div class="row">
                                                        @if (config('yourconfig.resort')->is_promo)
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
                                                                        value="{{ $breakfast->id }}" data-value="{{ $breakfast->title.' P'.number_format($breakfast->price, 0).' ('.$breakfast->notes.')' }}" class="selectgroup-input breakfastaddonscheckbox"
                                                                        {{ old('breakfast') ? (in_array($breakfast->id, old('breakfast')) ? 'checked' : '') : '' }}>
                                                                    <span
                                                                        class="selectgroup-button">{{ $breakfast->title.' P'.number_format($breakfast->price, 0).' ('.$breakfast->notes.')' }}</span>
                                                                </label>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                        @else
                                                        <div class="form-group col-lg-12 mb-0">
                                                            <label class="form-label">Breakfast
                                                                (P{{ number_format(config('yourconfig.resort')->breakfastPrice, 0) }})</label>
                                                            <div class="selectgroup selectgroup-pills">
                                                                <label class="selectgroup-item">
                                                                    <input type="radio" name="isbreakfast" value="1"
                                                                        class="selectgroup-input yes-breakfast"
                                                                        {{ old('isbreakfast') == 1 ? 'checked' : '' }}>
                                                                    <span
                                                                        class="selectgroup-button selectgroup-button-icon">Yes</span>
                                                                </label>
                                                                <label class="selectgroup-item">
                                                                    <input type="radio" name="isbreakfast" value="0"
                                                                        class="selectgroup-input"
                                                                        {{ old('isbreakfast') == 0 ? 'checked' : '' }}>
                                                                    <span
                                                                        class="selectgroup-button selectgroup-button-icon">No</span>
                                                                </label>
                                                            </div>
                                                        </div>
    
                                                        <div class="form-group col-lg-12 breakfastaddons-container d-none">
                                                            <label class="form-label">Breakfast Add ons:</label>
                                                            <div class="selectgroup selectgroup-pills">
                                                                @foreach ($breakfasts as $breakfast)
                                                                <label class="selectgroup-item mb-0">
                                                                    <input type="checkbox" name="breakfast[]"
                                                                        value="{{ $breakfast->id }}" data-value="{{ $breakfast->title.' P'.number_format($breakfast->price, 0).' ('.$breakfast->notes.')' }}" class="selectgroup-input breakfastaddonscheckbox"
                                                                        {{ old('breakfast') ? (in_array($breakfast->id, old('breakfast')) ? 'checked' : '') : '' }}>
                                                                    <span
                                                                        class="selectgroup-button">{{ $breakfast->title.' P'.number_format($breakfast->price, 0).' ('.$breakfast->notes.')' }}</span>
                                                                </label>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                        @endif
            
                                                    </div>
    
                                                </div>
    
                                                <div class="row">
                                                    @foreach ($entranceFees as $item)
                                                    <div class="form-group col-lg-4 col-md-4 col-sm-4">
                                                        <label for="{{ $item->title }}">{{ $item->title }}</label>
                                                        <input type="text"
                                                            class="form-control digit_only {{ $item->title == "Senior Citizen" ? 'sc' : '' }} @error($item->title) is-invalid @enderror"
                                                            name="{{ $item->title }}" id="{{ $item->title }}"
                                                            value="{{ old(str_replace(' ', '_', $item->title)) ?? 0 }}">
                                                        @error($item->title)
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                        @enderror
                                                    </div>
                                                    @endforeach
                                                </div>
    
                                                <div class="form-group">
                                                    <label for="notes">Notes</label>
                                                    <textarea class="form-control edited @error('notes') is-invalid @enderror"
                                                        name="notes" id="notes"></textarea>
                                                    @error('notes')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
    
                                                <div class="form-group">
                                                    <label>Select</label>
                                                    <div class="selectgroup selectgroup-pills">
                                                        <label class="selectgroup-item pb-0 mb-0">
                                                            <input type="radio" name="existing_guest" value="1"
                                                                class="selectgroup-input" checked>
                                                            <span
                                                                class="selectgroup-button selectgroup-button-icon">Existing
                                                                Guest</span>
                                                        </label>
                                                        <label class="selectgroup-item pb-0 mb-0">
                                                            <input type="radio" name="existing_guest" value="0"
                                                                class="selectgroup-input">
                                                            <span class="selectgroup-button selectgroup-button-icon">New
                                                                Guest</span>
                                                        </label>
                                                    </div>
                                                </div>
    
                                                <div class="existing-guest-container">
                                                    <div class="form-group">
                                                        <label>Guest</label>
                                                        <select class="form-control @error('guest') is-invalid @enderror"
                                                            name="guest" id="guest" style="width: 100%">
                                                        </select>
                                                        @error('guest')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                        @enderror
                                                    </div>
                                                </div>
    
                                                <div class="new-guest-container d-none">
                                                    <div class="row">
                                                        <div class="form-group col-md-6">
                                                            <label for="firstName">First Name</label>
                                                            <input type="text"
                                                                class="form-control @error('firstName') is-invalid @enderror"
                                                                name="firstName" id="firstName"
                                                                value="{{ old('firstName') }}">
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
                                                            <input type="text"
                                                                class="form-control @error('email') is-invalid @enderror"
                                                                name="email" id="email" value="{{ old('email') }}">
                                                            @error('email')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                            @enderror
                                                        </div>
    
                                                        <div class="form-group col-lg-12">
                                                            <label for="address">Address</label>
                                                            <textarea
                                                                class="form-control edited @error('address') is-invalid @enderror"
                                                                name="address" id="address"></textarea>
                                                            @error('address')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
    
                                            </div>
                                            <input type="button" name="previous"
                                                class="previous action-button-previous btn btn-secondary"
                                                value="Previous" />
                                            <input type="button" name="next" class="next second-next action-button btn btn-primary"
                                                value="Next Step" />
                                        </fieldset>
                                        <fieldset>
                                            <div class="form-card text-left">
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <p><strong>Is Reservation:</strong> <span class="reservation-text"></span></p>
                                                        <p><strong>Check-in Date:</strong> <span class="checkindate-text"></span></p>
                                                        <p><strong>Check-in & Check-out time:</strong> <span class="checkincheckouttime-text text-capitalize"></span></p>
                                                        <p><strong>Rental:</strong> <span class="rental-text text-capitalize"></span></p>
                                                        <p class="p-rent-text"><strong>Rent:</strong> <span class="rent-text"></span></p>
                                                        <p class="p-breakfast-text"><strong>Breakfast:</strong> <span class="breakfast-text"></span></p>
                                                        <p class="p-breakfastaddons-text"><strong>Breakfast add ons:</strong> <span class="breakfastaddons-text"></span></p>
                                                        <p><strong>Adults:</strong> <span class="adults-text"></span></p>
                                                        <p><strong>Kids:</strong> <span class="kids-text"></span></p>
                                                        <p><strong>Senior Citizen:</strong> <span class="seniorcitizen-text"></span></p>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <p class="p-existingguest-text"><strong>Existing Guest:</strong> <span class="existingguest-text"></span></p>
                                                        <p class="p-guestname-text"><strong>Guest Name:</strong> <span class="guestname-text"></span></p>
                                                        <p class="p-guestcontact-text"><strong>Contact:</strong> <span class="guestcontact-text"></span></p>
                                                        <p class="p-guestemail-text"><strong>Email:</strong> <span class="guestemail-text"></span></p>
                                                        <p class="p-guestaddress-text"><strong>Address:</strong> <span class="guestaddress-text"></span></p>
                                                        <p><strong>Notes:</strong> <span class="notes-text"></span></p>
                                                    </div>
                                                </div>
                                            </div>
                                            <input type="button" name="previous" class="previous action-button-previous btn btn-secondary"
                                                value="Previous" /> 
                                            <input type="button" name="make_payment"
                                                class="next action-button btn btn-primary third-next" value="Confirm" />
                                        </fieldset>
                                        <fieldset>
                                            <div class="form-card">
                                                <h2 class="fs-title text-center">Success !</h2> <br><br>
                                                <div class="row justify-content-center">
                                                    <div class="col-12">
                                                        <i class="fa fa-check-circle" style="font-size: 200px; color: #47c363;"></i>
                                                    </div>
                                                </div> <br><br>
                                                <div class="row justify-content-center">
                                                    <div class="col-7 text-center">
                                                        <h5>You Have Successfully Added a Transaction</h5>
                                                        <a href="" class="btn btn-primary view-transaction mr-2 mt-4">View Transaction</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </fieldset>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
    $(document).ready(function () {

        var current_fs, next_fs, previous_fs; //fieldsets
        var opacity;

        $(".first-next").click(function () {
            var checkin = $('#datepicker').val();
            var rent_type = $('input:radio[name="rent_type"]:checked').val();
            var type = $('input:radio[name="type"]:checked').val();
            if(checkin == "") {
                swal({
                    title: 'Error!',
                    text: 'Check-in Date must be filled out',
                    icon: "error",
                    button: true,
                });
                return false;
            } else if(rent_type == "") {
                swal({
                    title: 'Error!',
                    text: 'Rental must be filled out',
                    icon: "error",
                    button: true,
                });
                return false;
            } else if(type == undefined) {
                swal({
                    title: 'Error!',
                    text: 'Check-in & Check-out time must be filled out',
                    icon: "error",
                    button: true,
                });
                return false;
            } else {
                $.ajax({
                    type: 'post',
                    url: "{{ route('transaction.get_available_rooms_cottages') }}",
                    data: {
                        checkin: checkin,
                        rent_type: rent_type,
                        type: type,
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (result) {
                        console.log(result);
                        var _html = '';
                        if(result.data.length != 0) {
                            _html += '      <div class="form-group">';
                            _html += '         <label class="form-label">'+result.type+'</label>';
                            _html += '         <div class="selectgroup selectgroup-pills">';
                            _html += '            <div class="row">';
                            for (var i = 0; i < result.data.length; i++) {
                            _html += '                <div class="col-lg-4 col-md-6 col-sm-6">';
                            _html += '                    <label class="selectgroup-item mb-0" style="width: inherit;">';
                            _html += '                        <input type="radio" data-value="'+result.type+': '+result.data[i].name +'" name="'+result.type+'" value="'+ result.data[i].id +'" class="selectgroup-input radio-cottage">';
                            _html += '                        <span class="selectgroup-button" style="height: 100%; border-radius: 0.25rem !important;">';
                            _html += '                             <b>'+ result.data[i].name +'</b>';
                            _html += '                            <p style="white-space: pre-wrap;">'+ result.data[i].text+'</p>';
                            _html += '                        </span>';
                            _html += '                    </label>';
                            _html += '                </div>';
                            }
                            _html += '             </div>';
                            _html += '           </div>';
                            _html += '         </div>';
                        } else if(result.type == 'Exclusive Rental') {
                            _html += '<p>'+result.type+'</p>';
                        } else {
                            _html += '<p class="text-danger">No available '+result.type+'!</p>';
                        }
                        $('.rental-container-result').html(_html);
                    }
                });

                current_fs = $(this).parent();
                next_fs = $(this).parent().next();
                next_fieldset(current_fs, next_fs);
            }
        });

        $(".second-next").click(function () {
            var is_reservation = $('.is_reservation:checked').val();
            var checkin = $('#datepicker').val();
            var type = $('input:radio[name="type"]:checked').val();
            var type_sched = $('input:radio[name="type"]:checked').data('sched');
            var rent_type = $('input:radio[name="rent_type"]:checked').val();
            var roomcottageid = $('input:radio.radio-cottage:checked').val();
            var roomcottage_name = $('input:radio.radio-cottage:checked').data('value');
            var existing_guest = $('input:radio[name="existing_guest"]:checked').val();
            var existing_guest_id = $('#guest').val();
            var existing_guest_name = $('#select2-guest-container').attr('title');
            
            var adults = $('#Adults').val();
            var kids = $('#Kids').val();
            var senior = $('.sc').val();
            var notes = $('#notes').val();
            var firstName = $('#firstName').val();
            var lastName = $('#lastName').val();
            var contactNumber = $('#contactNumber').val();
            var email = $('#email').val();
            var address = $('#address').val();

            var isbreakfast = $('[name=isbreakfast]').val();
            var addons = [];
            $('.breakfastaddonscheckbox:checked').each(function() {
                var add = $(this).data('value');
                addons.push(add);
            });
            var guestname = firstName+' '+lastName;
            
            if(rent_type != 'exclusive_rental' && roomcottageid == undefined) {
                swal({
                    title: 'Error!',
                    text: 'Room or Cottage must be selected',
                    icon: "error",
                    button: true,
                });
                return false;
            } else if(existing_guest == 1 && existing_guest_id == "") {
                swal({
                    title: 'Error!',
                    text: 'Existing Guest must be filled out',
                    icon: "error",
                    button: true,
                });
                return false;
            } else if(existing_guest == 0 && firstName == "") {
                swal({
                    title: 'Error!',
                    text: "Guest's first name must be filled out",
                    icon: "error",
                    button: true,
                });
                return false;
            } else if(existing_guest == 0 && lastName == "") {
                swal({
                    title: 'Error!',
                    text: "Guest's last name must be filled out",
                    icon: "error",
                    button: true,
                });
                return false;
            } else if(existing_guest == 0 && contactNumber == "") {
                swal({
                    title: 'Error!',
                    text: "Guest's contact number must be filled out",
                    icon: "error",
                    button: true,
                });
                return false;
            } else if(existing_guest == 0 && email == "") {
                swal({
                    title: 'Error!',
                    text: "Guest's email must be filled out",
                    icon: "error",
                    button: true,
                });
                return false;
            } else if(existing_guest == 0 && address == "") {
                swal({
                    title: 'Error!',
                    text: "Guest's address must be filled out",
                    icon: "error",
                    button: true,
                });
                return false;
            } else {
                $('.reservation-text').text(is_reservation == 1 ? 'Yes' : 'No');
                $('.checkindate-text').text(checkin);
                $('.rental-text').text(rent_type == 'exclusive_rental' ? 'Exclusive Rental' : rent_type);
                $('.checkincheckouttime-text').text(type+' '+type_sched);
                $('.rent-text').text(roomcottage_name);
                $('.adults-text').text(adults);
                $('.kids-text').text(kids);
                $('.seniorcitizen-text').text(senior);
                $('.notes-text').text(notes);
                $('.existingguest-text').text(existing_guest_name);
                $('.breakfast-text').text(isbreakfast == 1 ? 'Yes' : 'No');
                $('.breakfastaddons-text').text(addons.join(", "));

                if(rent_type == 'room' && type == 'overnight') {
                    $('.p-breakfast-text').removeClass('d-none');
                    $('.p-breakfastaddons-text').removeClass('d-none');
                } else {
                    $('.p-breakfast-text').addClass('d-none');
                    $('.p-breakfastaddons-text').addClass('d-none');
                }

                if(existing_guest == 0) {
                    $('.p-guestname-text').removeClass('d-none');
                    $('.p-guestcontact-text').removeClass('d-none');
                    $('.p-guestemail-text').removeClass('d-none');
                    $('.p-guestaddress-text').removeClass('d-none');
                    $('.p-existingguest-text').addClass('d-none');
                } else {
                    $('.p-guestname-text').addClass('d-none');
                    $('.p-guestcontact-text').addClass('d-none');
                    $('.p-guestemail-text').addClass('d-none');
                    $('.p-guestaddress-text').addClass('d-none');
                    $('.p-existingguest-text').removeClass('d-none');
                }

                if(rent_type == 'exclusive_rental') {
                    $('.p-rent-text').addClass('d-none');
                } else {
                    $('.p-rent-text').removeClass('d-none');
                }

                $('.guestname-text').text(guestname);
                $('.guestcontact-text').text(contactNumber);
                $('.guestemail-text').text(email);
                $('.guestaddress-text').text(address);

                current_fs = $(this).parent();
                next_fs = $(this).parent().next();
                next_fieldset(current_fs, next_fs);
            }
        });

        $(".third-next").click(function () {
            var is_reservation = $('.is_reservation:checked').val();
            var checkin = $('#datepicker').val();
            var rent_type = $('input:radio[name="rent_type"]:checked').val();
            var type = $('input:radio[name="type"]:checked').val();
            var roomcottageid = $('input:radio.radio-cottage:checked').val();
            var isbreakfast = $('[name=isbreakfast]').val();
            var addons = [];
            $('.breakfastaddonscheckbox:checked').each(function() {
                var add = $(this).val();
                addons.push(add);
            });
            var adults = $('#Adults').val();
            var kids = $('#Kids').val();
            var senior = $('.sc').val();
            var notes = $('#notes').val();
            var existing_guest = $('input:radio[name="existing_guest"]:checked').val();
            var existing_guest_id = $('#guest').val();
            var firstName = $('#firstName').val();
            var lastName = $('#lastName').val();
            var contactNumber = $('#contactNumber').val();
            var email = $('#email').val();
            var address = $('#address').val();

            $.ajax({
                type: 'post',
                url: "{{ route('transaction.store') }}",
                data: {
                    is_reservation: is_reservation,
                    checkin: checkin,
                    rent_type: rent_type,
                    type: type,
                    roomcottageid: roomcottageid,
                    isbreakfast: isbreakfast,
                    addons: addons,
                    adults: adults,
                    kids: kids,
                    senior: senior,
                    notes: notes,
                    existing_guest: existing_guest,
                    existing_guest_id: existing_guest_id,
                    firstName: firstName,
                    lastName: lastName,
                    contactNumber: contactNumber,
                    email: email,
                    address: address,
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (result) {
                    console.log(result);
                    var _this = $(".third-next");
                    if(result.status == 'success') {
                        $('.view-transaction').attr('href', result.link);
                        current_fs = _this.parent();
                        next_fs = _this.parent().next();
                        next_fieldset(current_fs, next_fs);
                    } 
                    if(result.status == 'error') {
                        swal({
                            title: 'Error!',
                            text: result.text,
                            icon: "error",
                            button: true,
                        });
                    }
                }
            });

        });

        $(".previous").click(function () {

            current_fs = $(this).parent();
            previous_fs = $(this).parent().prev();

            //Remove class active
            $("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");

            //show the previous fieldset
            previous_fs.show();

            //hide the current fieldset with style
            current_fs.animate({
                opacity: 0
            }, {
                step: function (now) {
                    // for making fielset appear animation
                    opacity = 1 - now;

                    current_fs.css({
                        'display': 'none',
                        'position': 'relative'
                    });
                    previous_fs.css({
                        'opacity': opacity
                    });
                },
                duration: 600
            });
        });

        var next_fieldset = function (current_fs, next_fs) {
                //Add Class Active
                $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");

                //show the next fieldset
                next_fs.show();
                //hide the current fieldset with style
                current_fs.animate({
                    opacity: 0
                }, {
                    step: function (now) {
                        // for making fielset appear animation
                        opacity = 1 - now;

                        current_fs.css({
                            'display': 'none',
                            'position': 'relative'
                        });
                        next_fs.css({
                            'opacity': opacity
                        });
                    },
                    duration: 600
                });
        }

        $(".submit").click(function () {
            return false;
        })

    });

    $(document).ready(function () {
        var new_date = "{{ date('m-d-Y', strtotime($transaction->checkIn_at)) }}";
        // var new_date = moment().format('MM-DD-YYYY');
        $('#datepicker').datepicker({
            todayHighlight: true,
        });

        $('#datepicker').datepicker('setDate', new_date);

        $(document).on('change', 'input:radio[name="existing_guest"]', function () {
            if ($(this).is(':checked') && $(this).val() == '1') {
                $('.existing-guest-container').removeClass('d-none');
                $('.new-guest-container').addClass('d-none');
            } else {
                $('.new-guest-container').removeClass('d-none');
                $('.existing-guest-container').addClass('d-none');
            }
        });

        $(document).on('change', 'input:radio[name="rent_type"]', function () {
            if ($(this).is(':checked') && $(this).val() == 'cottage') {
                $('.cottage-use-container').removeClass('d-none');
                $('.room-use-container').addClass('d-none');
                $('.exclusive-use-container').addClass('d-none');
                $('.breakfast-container').addClass('d-none');
            } else if ($(this).is(':checked') && $(this).val() == 'room') {
                $('.cottage-use-container').addClass('d-none');
                $('.room-use-container').removeClass('d-none');
                $('.exclusive-use-container').addClass('d-none');
            } else if ($(this).is(':checked') && $(this).val() == 'exclusive_rental') {
                $('.cottage-use-container').addClass('d-none');
                $('.room-use-container').addClass('d-none');
                $('.exclusive-use-container').removeClass('d-none');
                $('.breakfast-container').addClass('d-none');
                check_exclusive_available();
            }
            $('input:radio[name="type"]').prop('checked', false);
        });

        var check_exclusive_available = function () {
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
                    console.log(result);
                    if (result.status.includes('overnight')) {
                        $('.ex-overnight').removeAttr('disabled');
                        $('.ex-span-overnight').removeClass('disabled');
                    } else {
                        $('.ex-overnight').attr('disabled', true);
                        $('.ex-span-overnight').addClass('disabled');
                    }
                    if (result.status.includes('day')) {
                        $('.ex-day').removeAttr('disabled');
                        $('.ex-span-day').removeClass('disabled');
                    } else {
                        $('.ex-day').attr('disabled', true);
                        $('.ex-span-day').addClass('disabled');
                    }
                    $('.ex-overnight').prop('checked', false);
                    $('.ex-day').prop('checked', false);
                }
            });
        }

        $(document).on('change', '#datepicker', function () {
            var _this = $(this);
            check_exclusive_available();
        });

        $(document).on('change', 'input:radio.room-dn[name="type"]', function () {
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
    });

</script>
@endsection
