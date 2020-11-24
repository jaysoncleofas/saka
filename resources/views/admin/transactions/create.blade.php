@extends('layouts.app')

@section('content')

<section class="section">
    <div class="section-header">
        <h1>Transactions</h1>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Add Transaction</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('transaction.store') }}">
                            @csrf

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
                                    <label for="middleName">Middle Name</label>
                                    <input type="text" class="form-control @error('middleName') is-invalid @enderror"
                                        name="middleName" id="middleName" value="{{ old('middleName') }}">
                                    @error('middleName')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="contact">Contacts</label>
                                    <input type="text" class="form-control @error('contact') is-invalid @enderror"
                                        name="contact" id="contact" value="{{ old('contact') }}">
                                    @error('contact')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="age">Age</label>
                                    <input type="text" class="form-control @error('age') is-invalid @enderror"
                                        name="age" id="age" value="{{ old('age') }}">
                                    @error('age')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="address">Address</label>
                                    <textarea class="form-control @error('address') is-invalid @enderror" name="address"
                                        id="address"></textarea>
                                    @error('address')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="firstName">Check In</label>
                                    <input type="datetime-local"
                                        class="form-control @error('firstName') is-invalid @enderror" name="firstName"
                                        id="firstName" value="{{ old('firstName') }}">
                                    @error('firstName')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="lastName">Check Out</label>
                                    <input type="datetime-local"
                                        class="form-control @error('lastName') is-invalid @enderror" name="lastName"
                                        id="lastName" value="{{ old('lastName') }}">
                                    @error('lastName')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="firstName">Adult</label>
                                    <input type="number" class="form-control @error('firstName') is-invalid @enderror"
                                        name="firstName" id="firstName" value="{{ old('firstName') }}">
                                    @error('firstName')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="lastName">Kids</label>
                                    <input type="number" class="form-control @error('lastName') is-invalid @enderror"
                                        name="lastName" id="lastName" value="{{ old('lastName') }}">
                                    @error('lastName')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="lastName">Senior Citizen</label>
                                    <input type="number" class="form-control @error('lastName') is-invalid @enderror"
                                        name="lastName" id="lastName" value="{{ old('lastName') }}">
                                    @error('lastName')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-3">
                                    <label class="form-label">Select</label>
                                    <div class="selectgroup selectgroup-pills">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="check" value="1" class="selectgroup-input"
                                                checked="">
                                            <span class="selectgroup-button selectgroup-button-icon"><i
                                                    class="fas fa-sun"></i> Day</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="radio" name="check" value="2" class="selectgroup-input">
                                            <span class="selectgroup-button selectgroup-button-icon"><i
                                                    class="fas fa-moon"></i> Night</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="radio" name="check" value="3" class="selectgroup-input">
                                            <span class="selectgroup-button selectgroup-button-icon"><i
                                                    class="fas fa-cloud-moon"></i> Overnight</span>
                                        </label>
                                    </div>
                                </div>
    
                                <div class="form-group col-md-3">
                                    <label class="form-label">Breakfast</label>
                                    <div class="selectgroup selectgroup-pills">
                                      <label class="selectgroup-item">
                                        <input type="radio" name="breakfast" value="1" class="selectgroup-input">
                                        <span class="selectgroup-button selectgroup-button-icon">Yes</span>
                                      </label>
                                      <label class="selectgroup-item">
                                        <input type="radio" name="breakfast" value="2" class="selectgroup-input">
                                        <span class="selectgroup-button selectgroup-button-icon">No</span>
                                      </label>
                                    </div>
                                  </div>
                            </div>
                            

                            <div class="form-group">
                                <label class="form-label">Cottages</label>
                                <div class="selectgroup selectgroup-pills">
                                    <div class="row">
                                        @foreach ($cottages as $cottage)
                                        <div class="col-md-3">
                                            <label class="selectgroup-item" style="width: inherit;">
                                                <input type="checkbox" name="cottage[]" value="{{ $cottage->id }}"
                                                    class="selectgroup-input">
                                                <span class="selectgroup-button"
                                                    style="height: 100%; border-radius: 0.25rem !important;">
                                                    <b>{{ $cottage->name }}</b>
                                                    <p style="white-space: pre-wrap;">{!! $cottage->descriptions !!}</p>
                                                    {{-- <input type="number" class="form-control mb-2" value="1" placeholder="Quantity"> --}}
                                                </span>
                                            </label>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Rooms</label>
                                <div class="selectgroup selectgroup-pills">
                                    <div class="row">
                                        @foreach ($rooms as $room)
                                        <div class="col-md-3">
                                            <label class="selectgroup-item" style="width: inherit;">
                                                <input type="checkbox" name="room[]" value="{{ $room->id }}"
                                                    class="selectgroup-input">
                                                <span class="selectgroup-button"
                                                    style="height: 100%; border-radius: 0.25rem !important;">
                                                    <b>{{ $room->name }}</b>
                                                    <p style="white-space: pre-wrap;">{!! $room->descriptions !!}</p>
                                                </span>
                                            </label>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
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
