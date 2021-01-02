@extends('layouts.guest')

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
    <div class="section-body">
        <div class="row">
            <div class="col-lg-12">
                <div class="card mt-5">
                    <div class="card-header">
                        <h4>Add Guest Transaction</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('landing.reservation-store') }}">
                            @csrf
                            <input type="hidden" name="is_reservation" value="0">
                            <input type="hidden" name="is_guest" id="is_guest" value="1">
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
                                <div class="form-group col-lg-6">
                                    <label for="checkin">Check In</label>
                                    <input type="datetime-local"
                                        class="form-control @error('checkin') is-invalid @enderror" name="checkin"
                                        id="checkin" value="{{ old('checkin') ?? date('Y-m-d\TH:i:s') }}">
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
                                                class="selectgroup-button">{{ $breakfast->title.' P'.number_format($breakfast->price, 0).' ('.$breakfast->notes.')' }}</span>
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

                                    {{-- <div class="form-group mt-4">
                                        <label for="notes">Notes</label>
                                        <textarea class="form-control edited @error('notes') is-invalid @enderror"
                                            name="notes" id="notes"></textarea>
                                        @error('notes')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div> --}}
                                </div>
                            </div>
                            
                            <div class="form-group mt-4">
                                <button type="submit" class="btn btn-primary">
                                    Submit
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
</section>
@endsection

@section('scripts')
<script>
    $(document).ready(function () {
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
