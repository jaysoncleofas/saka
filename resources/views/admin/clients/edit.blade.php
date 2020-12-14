@extends('layouts.app')

@section('content')

<section class="section">
    <div class="section-header">
        <h1>Clients</h1>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-6 col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Update Client</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('client.update', $client->id) }}">
                            @csrf @method('PUT')

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="firstName">First Name</label>
                                    <input type="text" class="form-control @error('firstName') is-invalid @enderror"
                                        name="firstName" id="firstName" value="{{ $client->firstName }}">
                                    @error('firstName')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="lastName">Last Name</label>
                                    <input type="text" class="form-control @error('lastName') is-invalid @enderror"
                                        name="lastName" id="lastName" value="{{ $client->lastName }}">
                                    @error('lastName')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="middleName">Middle Name</label>
                                <input type="text" class="form-control @error('middleName') is-invalid @enderror"
                                    name="middleName" id="middleName" value="{{ $client->middleName }}">
                                @error('middleName')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="contact">Contact</label>
                                <input type="text" class="form-control @error('contact') is-invalid @enderror"
                                        name="contact" id="contact" value="{{ $client->contact }}">
                                @error('contact')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="age">Age</label>
                                <input type="text" class="form-control @error('age') is-invalid @enderror"
                                        name="age" id="age" value="{{ $client->age }}">
                                @error('age')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="address">Address</label>
                                <textarea class="form-control edited @error('address') is-invalid @enderror" name="address" id="address">{{ $client->address }}</textarea>
                                @error('address')
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
