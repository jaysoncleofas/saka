@extends('layouts.app')

@section('style')
<style>
    .selectgroup-pills .selectgroup-item {
        height: 100%;
        padding-bottom: 1rem;
    }

</style>
@endsection

@section('content')

<section class="section">
    <div class="section-header">
        <h1>Transactions</h1>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h4>Edit Transaction</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('transaction.update', $transaction->id) }}">
                            @csrf @method('PUT')

                            <div class="row">
                                <div class="col-lg-6">
                                    <ul class="nav nav-pills mb-3" id="pills-tab">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="pills-existing-tab" data-toggle="pill"
                                                href="#pills-existing" role="tab" aria-controls="pills-existing"
                                                aria-selected="true">Existing Guest</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="pills-new-tab" href="{{ route('guest.create') }}">New
                                                Guest</a>
                                        </li>
                                    </ul>
                                </div>

                                <div class="col-lg-6 text-right">
                                    <div class="form-group">
                                        <label class="custom-switch mt-2">
                                          <input type="checkbox" name="is_reservation" id="is_reservation" value="1" class="custom-switch-input" {{ $transaction->is_reservation ? 'checked' : '' }}>
                                          <span class="custom-switch-indicator"></span>
                                          <span class="custom-switch-description">Is Reservation</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-content" id="pills-tabContent">
                                <div class="tab-pane fade show active" id="pills-existing" role="tabpanel"
                                    aria-labelledby="pills-existing-tab">
                                    <div class="row">
                                        <div class="col-lg-12">
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
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <div class="row">
                                <div class="form-group col-lg-6">
                                    <label for="checkin">Check In</label>
                                    <input type="datetime-local"
                                        class="form-control @error('checkin') is-invalid @enderror" name="checkin"
                                        id="checkin" value="{{ date('Y-m-d\TH:i:s', strtotime($transaction->checkIn_at)) }}">
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
                                        id="checkout" value="{{ $transaction->checkOut_at ? date('Y-m-d\TH:i:s', strtotime($transaction->checkOut_at)) : '' }}">
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

                                <div class="form-group col-lg-4">
                                    <label class="form-label">Select Type</label>
                                    <div class="selectgroup selectgroup-pills @error('type') is-invalid @enderror">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="type" value="day" class="selectgroup-input"
                                                {{ $transaction->type == 'day' ? 'checked' : '' }}>
                                            <span class="selectgroup-button selectgroup-button-icon"><i
                                                    class="fas fa-sun"></i> Day</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="radio" name="type" value="night" class="selectgroup-input"
                                                {{ $transaction->type == 'night' ? 'checked' : '' }}>
                                            <span class="selectgroup-button selectgroup-button-icon"><i
                                                    class="fas fa-moon"></i> Night</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="radio" name="type" value="overnight" class="selectgroup-input"
                                                {{ $transaction->type == 'overnight' ? 'checked' : '' }}>
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
                                                {{ $transaction->is_breakfast == 1 ? 'checked' : '' }}>
                                            <span class="selectgroup-button selectgroup-button-icon">Yes</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="radio" name="isbreakfast" value="0" class="selectgroup-input"
                                                {{ $transaction->is_breakfast == 0 ? 'checked' : '' }}>
                                            <span class="selectgroup-button selectgroup-button-icon">No</span>
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group col-lg-6">
                                    <label class="form-label">Breakfast Add ons</label>
                                    <div class="selectgroup selectgroup-pills">
                                        @php
                                            $tranbr = [];
                                            foreach($transaction->breakfasts as $br) {
                                                $tranbr[] = $br->id;
                                            }
                                        @endphp
                                        @foreach ($breakfasts as $breakfast)
                                        <label class="selectgroup-item">
                                            <input type="checkbox" name="breakfast[]" value="{{ $breakfast->id }}"
                                                class="selectgroup-input"
                                                {{ $tranbr ? (in_array($breakfast->id, $tranbr) ? 'checked' : '') : '' }}>
                                            <span
                                                class="selectgroup-button">{{ $breakfast->title.' P'.number_format($breakfast->price, 0) }}</span>
                                        </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div id="accordion">
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
                                                                {{ $transaction->cottage_id == $cottage->id ? 'checked' : '' }}>
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
                                                                {{ $transaction->room_id == $room->id ? 'checked' : '' }}>
                                                            <span class="selectgroup-button"
                                                                style="height: 100%; border-radius: 0.25rem !important;">
                                                                <b>{{ $room->name }}</b>
                                                                <p style="white-space: pre-wrap;">P{{ number_format($room->price, 0) }}, {!! $room->descriptions !!}</p>
                                                                @if ($room->extraPerson != 0)
                                                                <div class="form-group">
                                                                    {{-- <label for="extraPerson">Extra Person</label> --}}
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

                                    <div class="form-group mt-4">
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
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">
                                    Submit
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
    <script>
        $( document ).ready(function() {
            $.ajax({
                type: 'get',
                url: "{{ route('guest.get_guests') }}",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (result) {
                    var $element = $('#guest').select2({
                        minimumInputLength: 3 // only start searching when the user has input 3 or more characters
                    });
                    for (var d = 0; d < result.guests.length; d++) {
                        var item = result.guests[d];
                        // console.log(item);
                        // Create the DOM option that is pre-selected by default
                        var option = new Option(result.guests[d].text, result.guests[d].id, true, true);

                        // Append it to the select
                        $element.append(option);
                    }

                    var option = new Option('', '', true, true);
                    $element.append(option);
                    $element.val({{ $transaction->guest_id }}); // Select the option with a value of '1'
                    $element.trigger('change');
                }
            });

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
           
        });
    </script>
@endsection
