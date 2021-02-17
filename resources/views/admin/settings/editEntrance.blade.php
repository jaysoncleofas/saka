@extends('layouts.app')

@section('content')

<section class="section">
    <div class="section-header">
        <h1>Settings</h1>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-6 col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Edit Entrance Fee</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('entrancefee.update', $entranceFee->id) }}">
                            @csrf @method('PUT')

                            <div class="form-group">
                                <label for="title">Title</label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror"
                                    name="title" id="title" value="{{ $entranceFee->title }}">
                                @error('title')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="price">Day</label>
                                <input type="text" class="form-control digit_only2 @error('price') is-invalid @enderror"
                                    name="price" id="price" value="{{ number_format($entranceFee->price, 0) }}">
                                @error('price')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="nightPrice">Night</label>
                                <input type="text" class="form-control digit_only2 @error('nightPrice') is-invalid @enderror"
                                    name="nightPrice" id="nightPrice" value="{{ number_format($entranceFee->nightPrice, 0) }}">
                                @error('nightPrice')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="overnightPrice">Overnight</label>
                                <input type="text" class="form-control digit_only2 @error('overnightPrice') is-invalid @enderror"
                                    name="overnightPrice" id="overnightPrice" value="{{ number_format($entranceFee->overnightPrice, 0) }}">
                                @error('overnightPrice')
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