@extends('layouts.app')

@section('content')

<section class="section">
    <div class="section-header">
        <h1>Cottages</h1>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-6 col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Update Cottage</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('cottage.update', $cottage->id) }}">
                            @csrf @method('PUT')

                            <div class="form-group">
                                <label for="cottage">Cottage</label>
                                <input type="text" class="form-control @error('cottage') is-invalid @enderror"
                                    name="cottage" id="cottage" value="{{ $cottage->name }}">
                                @error('cottage')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="price">Price</label>
                                    <input type="text" class="form-control @error('price') is-invalid @enderror"
                                        name="price" id="price" value="{{ $cottage->price }}">
                                    @error('price')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="overnightPrice">Overnight Price</label>
                                    <input type="text" class="form-control @error('overnightPrice') is-invalid @enderror"
                                        name="overnightPrice" id="overnightPrice" value="{{ $cottage->overnightPrice }}">
                                    @error('overnightPrice')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                            </div>

                            <div class="form-group">
                                <label for="descriptions">Descriptions</label>
                                <textarea class="form-control @error('descriptions') is-invalid @enderror" name="descriptions" id="descriptions">{{ $cottage->descriptions }}</textarea>
                                @error('descriptions')
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
