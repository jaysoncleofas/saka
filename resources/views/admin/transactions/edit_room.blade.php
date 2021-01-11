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
        padding: 20px 40px 30px 40px;
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
        width: 33.3%;
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
        content: "\f00c";
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
        <div class="container-fluid" id="grad1">
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
                                        <ul id="progressbar" class="pl-0">
                                            <li class="active" id="account"><strong>Date</strong></li>
                                            <li id="personal"><strong>Details</strong></li>
                                            <li id="payment"><strong>Confirm</strong></li>
                                        </ul>
                                        <fieldset>
                                            <div class="form-card text-left">
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <p class=""><strong>Control#:</strong> <span
                                                                class="">{{ $transaction->id }}</span></p>
                                                        <p class=""><strong>Cottage:</strong> <span
                                                                class="">{{ $transaction->room->name }}</span></p>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <p class=""><strong>Type:</strong> <span
                                                                class="">{{ $transaction->is_reservation ? 'Reservation' : 'Walk in' }}</span>
                                                        </p>
                                                        <p class=""><strong>Guest:</strong> <span
                                                                class="">{{ $transaction->guest->fullname }}</span></p>
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
                                                            <input type="radio" name="rent_type" value="room"
                                                                class="selectgroup-input"
                                                                {{ $transaction->room_id ? 'checked' : '' }}>
                                                            <span
                                                                class="selectgroup-button selectgroup-button-icon">Room</span>
                                                        </label>
                                                    </div>
                                                    @error('rent_type')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>

                                                <div class="room-use-container">
                                                    <div class="form-group">
                                                        <label class="form-label">Check-in & Check-out time</label>
                                                        <div
                                                            class="selectgroup selectgroup-pills @error('type') is-invalid @enderror">
                                                            <label class="selectgroup-item pb-0">
                                                                <input type="radio" name="type" value="night"
                                                                    data-sched="{{ config('yourconfig.resort')->night }}"
                                                                    class="selectgroup-input room-dn"
                                                                    {{ $transaction->type == 'night' ? 'checked' : '' }}>
                                                                <span
                                                                    class="selectgroup-button selectgroup-button-icon"><i
                                                                        class="fas fa-moon"></i> Night
                                                                    {{ config('yourconfig.resort')->night }}</span>
                                                            </label>
                                                            <label class="selectgroup-item pb-0">
                                                                <input type="radio" name="type" value="overnight"
                                                                    data-sched="{{ config('yourconfig.resort')->overnight }}"
                                                                    class="selectgroup-input room-dn"
                                                                    {{ $transaction->type == 'overnight' ? 'checked' : '' }}>
                                                                <span
                                                                    class="selectgroup-button selectgroup-button-icon"><i
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
                                            </div>
                                            <button type="button" name="next" class="first-next next action-button btn btn-primary">Next Step</button>
                                        </fieldset>
                                        <fieldset>
                                            <div class="form-card text-left">
                                                <div class="rental-container-result"></div>
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
                                                                        value="{{ $breakfast->id }}"
                                                                        data-value="{{ $breakfast->title.' P'.number_format($breakfast->price, 0).' ('.$breakfast->notes.')' }}"
                                                                        class="selectgroup-input breakfastaddonscheckbox"
                                                                        {{ $transactionbreakfasts ? (in_array($breakfast->id, $transactionbreakfasts) ? 'checked' : '') : '' }}>
                                                                    <span
                                                                        class="selectgroup-button">{{ $breakfast->title.' P'.number_format($breakfast->price, 0).' ('.$breakfast->notes.')' }}</span>
                                                                </label>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="form-group col-lg-4">
                                                        <label for="Adults">Adults</label>
                                                        <input type="text"
                                                            class="form-control digit_only @error('Adults') is-invalid @enderror"
                                                            name="Adults" id="Adults"
                                                            value="{{ $transaction->adults }}">
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
                                                            value="{{ $transaction->kids }}">
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
                                                            value="{{ $transaction->senior }}">
                                                        @error('Senior_Citizen')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="notes">Notes</label>
                                                    <textarea
                                                        class="form-control edited @error('notes') is-invalid @enderror"
                                                        name="notes" id="notes"></textarea>
                                                    @error('notes')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>

                                            </div>
                                            <input type="button" name="previous"
                                                class="previous action-button-previous btn btn-secondary"
                                                value="Previous" />
                                            <input type="button" name="next"
                                                class="next second-next action-button btn btn-primary"
                                                value="Next Step" />
                                        </fieldset>
                                        <fieldset>
                                            <div class="form-card text-left">
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <p><strong>Check-in Date:</strong> <span
                                                                class="checkindate-text"></span></p>
                                                        <p><strong>Check-in & Check-out time:</strong> <span
                                                                class="checkincheckouttime-text text-capitalize"></span>
                                                        </p>
                                                        <p class="p-rent-text"><strong>Room:</strong> <span class="rent-text"></span></p>
                                                        <p><strong>Adults:</strong> <span class="adults-text"></span>
                                                        </p>
                                                        <p><strong>Kids:</strong> <span class="kids-text"></span></p>
                                                        <p><strong>Senior Citizen:</strong> <span
                                                                class="seniorcitizen-text"></span></p>
                                                        <p class="p-breakfast-text"><strong>Breakfast:</strong> <span
                                                                class="breakfast-text">Free</span></p>
                                                        <p class="p-breakfastaddons-text"><strong>Breakfast add
                                                                ons:</strong> <span class="breakfastaddons-text"></span>
                                                        </p>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <p class="p-guestname-text"><strong>Guest Name:</strong> <span class="guestname-text">{{ $transaction->guest->fullname }}</span></p>
                                                        <p class="p-guestcontact-text"><strong>Contact:</strong> <span class="guestcontact-text">{{ $transaction->guest->contact }}</span></p>
                                                        <p class="p-guestemail-text"><strong>Email:</strong> <span class="guestemail-text">{{ $transaction->guest->email }}</span></p>
                                                        <p class="p-guestaddress-text"><strong>Address:</strong> <span class="guestaddress-text">{{ $transaction->guest->address }}</span></p>
                                                        <p><strong>Notes:</strong> <span class="notes-text"></span></p>
                                                    </div>
                                                </div>
                                            </div>
                                            <input type="button" name="previous"
                                                class="previous action-button-previous btn btn-secondary"
                                                value="Previous" />
                                            <button type="button" name="make_payment"
                                                class="next action-button btn btn-primary third-next">Submit</button>
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
            if (checkin == "") {
                swal({
                    title: 'Error!',
                    text: 'Check-in Date must be filled out',
                    icon: "error",
                    button: true,
                });
                return false;
            } else if (rent_type == "") {
                swal({
                    title: 'Error!',
                    text: 'Rental must be filled out',
                    icon: "error",
                    button: true,
                });
                return false;
            } else if (type == undefined) {
                swal({
                    title: 'Error!',
                    text: 'Check-in & Check-out time must be filled out',
                    icon: "error",
                    button: true,
                });
                return false;
            } else {
                var _this = $(this);
                _this.attr("disabled", true);
                _this.append('<span class="spinner-border spinner-border-sm ml-2" role="status" aria-hidden="true"></span>');
                $.ajax({
                    type: 'post',
                    url: "{{ route('transaction.get_available_rooms_cottages') }}",
                    data: {
                        checkin: checkin,
                        rent_type: rent_type,
                        type: type,
                        edit: 1,
                        roomid: "{{ $transaction->room_id }}",
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (result) {
                        var _html = '';
                        if (result.data.length != 0) {
                            _html += '      <div class="form-group">';
                            _html += '         <label class="form-label">' + result.type +'</label>';
                            _html += '         <div class="selectgroup selectgroup-pills">';
                            _html += '            <div class="row">';
                            for (var i = 0; i < result.data.length; i++) {
                                _html +='                <div class="col-lg-4 col-md-6 col-sm-6">';
                                _html +='                    <label class="selectgroup-item mb-0" style="width: inherit;">';
                                _html += '                        <input type="radio" ' + (result.data[i].is_selected == 1 ? "checked" : "") +' data-value="' + result.data[i].name + '" name="' + result.type + '" value="' + result.data[i].id +'" class="selectgroup-input radio-cottage">';
                                _html +='                        <span class="selectgroup-button" style="height: 100%; border-radius: 0.25rem !important;">';
                                _html += '                             <b>' + result.data[i].name + '</b>';
                                _html +='                            <p style="white-space: pre-wrap;">' +result.data[i].text + '</p>';
                                _html += '                        </span>';
                                _html += '                    </label>';
                                _html += '                </div>';
                            }
                            _html += '             </div>';
                            _html += '           </div>';
                            _html += '         </div>';
                        } else {
                            _html += '<p class="text-danger">No available ' + result.type +'!</p>';
                        }
                        $('.rental-container-result').html(_html);
                        _this.removeAttr("disabled");
                        _this.find('.spinner-border').remove();

                        current_fs = _this.parent();
                        next_fs = _this.parent().next();
                        next_fieldset(current_fs, next_fs);
                    }
                });
            }
        });

        $(".second-next").click(function () {
            var checkin = $('#datepicker').val();
            var type = $('input:radio[name="type"]:checked').val();
            var type_sched = $('input:radio[name="type"]:checked').data('sched');
            var rent_type = $('input:radio[name="rent_type"]:checked').val();
            var roomcottageid = $('input:radio.radio-cottage:checked').val();
            var roomcottage_name = $('input:radio.radio-cottage:checked').data('value');
         
            var adults = $('#Adults').val();
            var kids = $('#Kids').val();
            var senior = $('#Senior_Citizen').val();
            var notes = $('#notes').val();
            var addons = [];
            $('.breakfastaddonscheckbox:checked').each(function () {
                var add = $(this).data('value');
                addons.push(add);
            });

            if (roomcottageid == undefined) {
                swal({
                    title: 'Error!',
                    text: 'Room or Cottage must be selected',
                    icon: "error",
                    button: true,
                });
                return false;
            } else {
                $('.checkindate-text').text(checkin);
                $('.rent-text').text(roomcottage_name);
                $('.checkincheckouttime-text').text(type+' '+type_sched);
                $('.adults-text').text(adults);
                $('.kids-text').text(kids);
                $('.seniorcitizen-text').text(senior);
                $('.notes-text').text(notes);
                
                $('.breakfast-text').text('Free');
                $('.breakfastaddons-text').text(addons.join(", "));

                if (rent_type == 'room' && type == 'overnight') {
                    $('.p-breakfast-text').removeClass('d-none');
                    $('.p-breakfastaddons-text').removeClass('d-none');
                } else {
                    $('.p-breakfast-text').addClass('d-none');
                    $('.p-breakfastaddons-text').addClass('d-none');
                }
                $('.p-rent-text').removeClass('d-none');

                current_fs = $(this).parent();
                next_fs = $(this).parent().next();
                next_fieldset(current_fs, next_fs);
            }
        });

        $(".third-next").click(function () {
            var checkin = $('#datepicker').val();
            var rent_type = $('input:radio[name="rent_type"]:checked').val();
            var type = $('input:radio[name="type"]:checked').val();
            var roomcottageid = $('input:radio.radio-cottage:checked').val();
            var addons = [];
            $('.breakfastaddonscheckbox:checked').each(function () {
                var add = $(this).val();
                addons.push(add);
            });
            var adults = $('#Adults').val();
            var kids = $('#Kids').val();
            var senior = $('#Senior_Citizen').val();
            var notes = $('#notes').val();

            var _this = $(this);
            _this.attr("disabled", true);
            _this.append('<span class="spinner-border spinner-border-sm ml-2" role="status" aria-hidden="true"></span>');
            $.ajax({
                type: 'put',
                url: "{{ route('transaction.update_room', $transaction->id) }}",
                data: {
                    checkin: checkin,
                    rent_type: rent_type,
                    type: type,
                    roomcottageid: roomcottageid,
                    addons: addons,
                    adults: adults,
                    kids: kids,
                    senior: senior,
                    notes: notes,
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (result) {
                    var _this = $(".third-next");
                    if(result.status == 'success') {
                        iziToast.success({
                            title: '',
                            message: 'Success!',
                            position: 'topRight'
                        });
                        setTimeout(function(){ 
                            window.location.href = result.link;
                         }, 1000);
                    } 
                    if(result.status == 'error') {
                        swal({
                            title: 'Error!',
                            text: result.text,
                            icon: "error",
                            button: true,
                        });
                        
                        _this.removeAttr("disabled");
                        _this.find('.spinner-border').remove();
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

        $(document).on('change', 'input:radio.room-dn[name="type"]', function () {
            if ($(this).is(':checked') && $(this).val() == 'overnight') {
                $('.breakfast-container').removeClass('d-none');
            } else {
                $('.breakfast-container').addClass('d-none');
            }
        });

        if ($('input:radio.room-dn[name="type"]:checked').val() == 'overnight') {
            $('.breakfast-container').removeClass('d-none');
        }

        $(document).on('change', 'input:radio[name="isbreakfast"]', function () {
            if ($(this).is(':checked') && $(this).val() == '1') {
                $('.breakfastaddons-container').removeClass('d-none');
            } else {
                $('.breakfastaddons-container').addClass('d-none');
            }
            $('breakfastaddonscheckbox:checked').prop('checked', false);
        });

        if ($('input:radio[name="isbreakfast"]:checked').val() == '1') {
            $('.breakfastaddons-container').removeClass('d-none');
        }
    });

</script>
@endsection
