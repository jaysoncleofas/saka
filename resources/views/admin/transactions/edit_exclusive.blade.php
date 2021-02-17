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
        <!-- MultiStep Form -->
        <div class="container-fluid" id="grad1">
            {{-- justify-content-center --}}
            <div class="row mt-0">
                <div class="col-lg-12 text-center p-0 mt-3 mb-2">
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
                                            {{-- <li id="confirm"><strong>Finish</strong></li> --}}
                                        </ul> <!-- fieldsets -->
                                        <fieldset>
                                            <div class="form-card text-left">
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <p class=""><strong>Control#:</strong> <span class="">{{ $transaction->id }}</span></p>
                                                        <p class=""><strong>Rent:</strong> <span class="">Exclusive Rental</span></p>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <p class=""><strong>Type:</strong> <span class="">{{ $transaction->is_reservation ? 'Reservation' : 'Walk in' }}</span></p>
                                                        <p class=""><strong>Guest:</strong> <span class="">{{ $transaction->guest->fullname }}</span></p>
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
                                                            <input type="radio" name="rent_type"
                                                                value="exclusive_rental" class="selectgroup-input" checked>
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
    
                                                <div class="exclusive-use-container">
                                                    <div class="form-group">
                                                        <label class="form-label">Check-in & Check-out time</label>
                                                        <div
                                                            class="selectgroup selectgroup-pills @error('type') is-invalid @enderror">
                                                            <label class="selectgroup-item pb-0">
                                                                <input type="radio" name="type" value="day"
                                                                    data-sched="{{ config('yourconfig.resort')->day }}"
                                                                    class="selectgroup-input ex-day" {{ $transaction->type == 'day' ? 'checked' : '' }}>
                                                                <span
                                                                    class="selectgroup-button selectgroup-button-icon ex-span-day"><i
                                                                        class="fas fa-sun"></i> Day 9am - 5pm</span>
                                                            </label>
                                                            <label class="selectgroup-item pb-0">
                                                                <input type="radio" name="type" value="overnight"
                                                                    data-sched="9am - 11am"
                                                                    class="selectgroup-input ex-overnight" {{ $transaction->type == 'overnight' ? 'checked' : '' }}>
                                                                <span
                                                                    class="selectgroup-button selectgroup-button-icon ex-span-overnight"><i
                                                                        class="fas fa-moon"></i> Overnight 9am -
                                                                    11am</span>
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
                                                    <textarea class="form-control edited @error('notes') is-invalid @enderror"
                                                        name="notes" id="notes">{{ $transaction->notes }}</textarea>
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
                                            <input type="button" name="next" class="next second-next action-button btn btn-primary"
                                                value="Next Step" />
                                        </fieldset>
                                        <fieldset>
                                            <div class="form-card text-left">
                                                <div class="row">
                                                    <div class="col-lg-12 reservation-summary"></div>
                                                </div>
                                            </div>
                                            <input type="button" name="previous" class="previous action-button-previous btn btn-secondary"
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
                var _this = $(this);
                _this.attr("disabled", true);
                _this.append('<span class="spinner-border spinner-border-sm ml-2" role="status" aria-hidden="true"></span>');
                
                setTimeout(function(){ 
                    var _html = '';
                    _html += '<p>Exclusive Rental</p>';
                    $('.rental-container-result').html(_html);
                    _this.removeAttr("disabled");
                    _this.find('.spinner-border').remove();
    
                    current_fs = _this.parent();
                    next_fs = _this.parent().next();
                    next_fieldset(current_fs, next_fs);
                }, 1000);
            }
        });

        $(".second-next").click(function () {
            var checkin = $('#datepicker').val();
            var type = $('input:radio[name="type"]:checked').val();
            var type_sched = $('input:radio[name="type"]:checked').data('sched');
            var rent_type = $('input:radio[name="rent_type"]:checked').val();
            
            var adults = $('#Adults').val();
            var kids = $('#Kids').val();
            var senior = $('#Senior_Citizen').val();
            var notes = $('#notes').val();
            
            var _this = $(this);
                _this.attr("disabled", true);
                _this.append('<span class="spinner-border spinner-border-sm ml-2" role="status" aria-hidden="true"></span>');
                    $.ajax({
                        type: 'post',
                        url: "{{ route('transaction.summary') }}",
                        data: {
                            is_reservation: "{{ $transaction->is_reservation }}",
                            checkin: checkin,
                            rent_type: rent_type,
                            type: type,
                            adults: adults,
                            kids: kids,
                            senior_citizen: senior,
                            notes: notes,
                            existing_guest: 1,
                            existing_guest_id: "{{ $transaction->guest_id }}",
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (result) {
                            $('.reservation-summary').html(result.data);
                            _this.removeAttr("disabled");
                            _this.find('.spinner-border').remove();
                            current_fs = _this.parent();
                            next_fs = _this.parent().next();
                            next_fieldset(current_fs, next_fs);
                        }
                    });
        });

        $(".third-next").click(function () {
            var checkin = $('#datepicker').val();
            var type = $('input:radio[name="type"]:checked').val();
            var adults = $('#Adults').val();
            var kids = $('#Kids').val();
            var senior = $('#Senior_Citizen').val();
            var notes = $('#notes').val();

            var _this = $(this);
            _this.attr("disabled", true);
            _this.append('<span class="spinner-border spinner-border-sm ml-2" role="status" aria-hidden="true"></span>');
            
            $.ajax({
                type: 'put',
                url: "{{ route('transaction.update_exclusive', $transaction->id) }}",
                data: {
                    checkin: checkin,
                    type: type,
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

        var check_exclusive_available = function () {
            $.ajax({
                type: 'post',
                url: "{{ route('landing.getexclusive_available') }}",
                data: {
                    checkin: $('#datepicker').val(),
                    tranid: '{{ $transaction->id }}'
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (result) {
                    if (result.status.includes('overnight')) {
                        $('.ex-overnight').removeAttr('disabled');
                        $('.ex-span-overnight').removeClass('disabled');
                    } else {
                        $('.ex-overnight').attr('disabled', true);
                        $('.ex-span-overnight').addClass('disabled');
                        $('.ex-overnight').prop('checked', false);
                        // $('.ex-day').prop('checked', false);
                    }
                    if (result.status.includes('day')) {
                        $('.ex-day').removeAttr('disabled');
                        $('.ex-span-day').removeClass('disabled');
                    } else {
                        $('.ex-day').attr('disabled', true);
                        $('.ex-span-day').addClass('disabled');
                        // $('.ex-overnight').prop('checked', false);
                        $('.ex-day').prop('checked', false);
                    }
                }
            });
        }

        $(document).on('change', '#datepicker', function () {
            var _this = $(this);
            check_exclusive_available();
        });
        check_exclusive_available();
    });

</script>
@endsection
