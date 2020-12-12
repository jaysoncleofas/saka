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
                        <h4>Add Transaction</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('transaction.store') }}">
                            @csrf

                            <ul class="nav nav-pills mb-3" id="pills-tab">
                                <li class="nav-item">
                                    <a class="nav-link active" id="pills-existing-tab" data-toggle="pill" href="#pills-existing"
                                        role="tab" aria-controls="pills-existing" aria-selected="true">Existing Client</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="pills-new-tab" href="{{ route('client.create') }}" >New Client</a>
                                </li>
                            </ul>
                            <div class="tab-content" id="pills-tabContent">
                                <div class="tab-pane fade show active" id="pills-existing" role="tabpanel" aria-labelledby="pills-existing-tab">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label>Client</label>
                                                <select class="form-control @error('client') is-invalid @enderror" name="client" id="client" style="width: 100%">
                                                </select>
                                                @error('client')
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
                                    <label for="adult">Adult</label>
                                    <input type="text" class="form-control digit_only @error('adult') is-invalid @enderror"
                                        name="adult" id="adult" value="{{ old('adult') ?? 0 }}">
                                    @error('adult')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-group col-lg-4">
                                    <label for="kids">Kids</label>
                                    <input type="text" class="form-control digit_only @error('kids') is-invalid @enderror"
                                        name="kids" id="kids" value="{{ old('kids') ?? 0 }}">
                                    @error('kids')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-group col-lg-4">
                                    <label for="senior">Senior Citizen</label>
                                    <input type="text" class="form-control digit_only @error('senior') is-invalid @enderror"
                                        name="senior" id="senior" value="{{ old('senior') ?? 0 }}">
                                    @error('senior')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-group col-lg-6">
                                    <label class="form-label">Select</label>
                                    <div class="selectgroup selectgroup-pills">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="types" value="1" class="selectgroup-input"
                                                checked="">
                                            <span class="selectgroup-button selectgroup-button-icon"><i
                                                    class="fas fa-sun"></i> Day</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="radio" name="types" value="2" class="selectgroup-input">
                                            <span class="selectgroup-button selectgroup-button-icon"><i
                                                    class="fas fa-moon"></i> Night</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="radio" name="types" value="3" class="selectgroup-input">
                                            <span class="selectgroup-button selectgroup-button-icon"><i
                                                    class="fas fa-cloud-moon"></i> Overnight</span>
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group col-lg-6">
                                    <label class="form-label">Breakfast</label>
                                    <div class="selectgroup selectgroup-pills">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="breakfast" value="1" class="selectgroup-input">
                                            <span class="selectgroup-button selectgroup-button-icon">Yes</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="radio" name="breakfast" value="0" class="selectgroup-input" checked="">
                                            <span class="selectgroup-button selectgroup-button-icon">No</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Cottages</label>
                                {{-- <div class="selectgroup selectgroup-pills @error('cottages') is-invalid @enderror">
                                    @foreach ($cottages as $cottage)
                                        <label class="selectgroup-item" style="width: inherit;">
                                            <input type="checkbox" name="cottages[]" value="{{ $cottage->id }}" class="selectgroup-input">
                                            <span class="selectgroup-button selectgroup-button-icon">{{ $cottage->name }}</span>
                                        </label>
                                    @endforeach
                                </div> --}}
                                <div class="selectgroup selectgroup-pills @error('cottages') is-invalid @enderror">
                                    <div class="row">
                                        @foreach ($cottages as $cottage)
                                        <div class="col-lg-4">
                                            <label class="selectgroup-item" style="width: inherit;">
                                                <input type="checkbox" name="cottages[]" value="{{ $cottage->id }}"
                                                    class="selectgroup-input">
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
                                @error('cottages')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">Rooms</label>
                                {{-- <div class="selectgroup selectgroup-pills @error('rooms') is-invalid @enderror">
                                    @foreach ($rooms as $room)
                                        <label class="selectgroup-item" style="width: inherit;">
                                            <input type="checkbox" name="rooms[]" value="{{ $room->id }}" class="selectgroup-input">
                                            <span class="selectgroup-button selectgroup-button-icon">{{ $room->name }}</span>
                                        </label>
                                    @endforeach
                                </div> --}}
                                <div class="selectgroup selectgroup-pills @error('rooms') is-invalid @enderror">
                                    <div class="row">
                                        @foreach ($rooms as $room)
                                        <div class="col-lg-4">
                                            <label class="selectgroup-item" style="width: inherit;">
                                                <input type="checkbox" name="rooms[]" value="{{ $room->id }}"
                                                    class="selectgroup-input">
                                                <span class="selectgroup-button"
                                                    style="height: 100%; border-radius: 0.25rem !important;">
                                                    <b>{{ $room->name }}</b>
                                                    <p style="white-space: pre-wrap;">P{{ number_format($room->price, 0) }}, {!! $room->descriptions !!}</p>
                                                </span>
                                            </label>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @error('rooms')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group text-right">
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
                url: "{{ route('client.get_clients') }}",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (result) {
                    var $element = $('#client').select2();
                    for (var d = 0; d < result.clients.length; d++) {
                        var item = result.clients[d];
                        console.log(item);
                        // Create the DOM option that is pre-selected by default
                        var option = new Option(result.clients[d].text, result.clients[d].id, true, true);

                        // Append it to the select
                        $element.append(option);
                    }

                    var option = new Option('', '', true, true);
                    $element.append(option);
                    $element.val(window.location.search.slice(1)); // Select the option with a value of '1'
                    $element.trigger('change');
                }
            });
           
        });
    </script>
@endsection
