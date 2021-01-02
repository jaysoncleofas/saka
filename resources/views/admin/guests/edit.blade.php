@extends('layouts.app')

@section('content')

<section class="section">
    <div class="section-header">
        <h1>Guests</h1>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-6 col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Update Guest</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('guest.update', $guest->id) }}">
                            @csrf @method('PUT')

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="firstName">First Name</label>
                                    <input type="text" class="form-control @error('firstName') is-invalid @enderror"
                                        name="firstName" id="firstName" value="{{ $guest->firstName }}">
                                    @error('firstName')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="lastName">Last Name</label>
                                    <input type="text" class="form-control @error('lastName') is-invalid @enderror"
                                        name="lastName" id="lastName" value="{{ $guest->lastName }}">
                                    @error('lastName')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="middleInitial">Middle Initial</label>
                                <input type="text" class="form-control @error('middleInitial') is-invalid @enderror"
                                    name="middleInitial" id="middleInitial" value="{{ $guest->middleInitial }}">
                                @error('middleInitial')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="contact">Contact</label>
                                <input type="text" class="form-control @error('contact') is-invalid @enderror"
                                        name="contact" id="contact" value="{{ $guest->contact }}">
                                @error('contact')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="age">Age</label>
                                <input type="text" class="form-control @error('age') is-invalid @enderror"
                                        name="age" id="age" value="{{ $guest->age }}">
                                @error('age')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="address">Address</label>
                                <textarea class="form-control edited @error('address') is-invalid @enderror" name="address" id="address">{{ $guest->address }}</textarea>
                                @error('address')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
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
