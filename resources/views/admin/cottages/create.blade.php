@extends('layouts.app')

@section('content')

<section class="section">
    <div class="section-header">
        <h1>Cottages</h1>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-12 col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Add Cottage</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('cottage.store') }}" enctype="multipart/form-data">
                            @csrf

                            <div class="form-group">
                                <label for="cottage">Cottage</label>
                                <input type="text" class="form-control @error('cottage') is-invalid @enderror"
                                    name="cottage" id="cottage" value="{{ old('cottage') }}">
                                @error('cottage')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="price">Price</label>
                                    <input type="text" class="form-control digit_only2 @error('price') is-invalid @enderror"
                                        name="price" id="price" value="{{ old('price') }}">
                                    @error('price')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                {{-- <div class="form-group col-md-6">
                                    <label for="nightPrice">Overnight Price</label>
                                    <input type="text" class="form-control @error('nightPrice') is-invalid @enderror"
                                        name="nightPrice" id="nightPrice" value="{{ old('nightPrice') }}">
                                    @error('nightPrice')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div> --}}
                            </div>

                            <div class="form-group">
                                <label for="descriptions">Descriptions</label>
                                <textarea class="form-control edited @error('descriptions') is-invalid @enderror" name="descriptions" id="descriptions"></textarea>
                                @error('descriptions')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group" style="width:325px;">
                                <img class="img-fluid img-preview z-depth-1 profile-avatar mb-3">
                                {{-- style="object-fit: cover;height:325px; width:325px;" --}}
                                <div class="file-field">
                                    <div class="btn btn-primary btn-sm">
                                        <span><i class="fa fa-image"></i> Choose</span>
                                        <input type="file" name="image" onchange="previewFile()">
                                    </div>
                                    {{-- <a href="javascript:void(0);" data-href="{{ route('profile.picture.remove') }}" class="btn btn-danger btn-sm remove_avatar" data-method="put" data-value="{{ $user->id }}">
                                        <span><i class="fa fa-times"></i> Remove</span>
                                    </a> --}}
                                </div>
        
                                @if ($errors->has('image'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('image') }}</strong>
                                    </span>
                                @endif
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
        function previewFile(){
            var preview = document.querySelector('.img-preview'); //selects the query named img
            var file    = document.querySelector('input[type=file]').files[0]; //sames as here
            var reader  = new FileReader();
            reader.onloadend = function () {
                preview.src = reader.result;
            }
            if (file) {
                reader.readAsDataURL(file); //reads the data as a URL
            } else {
                preview.src = "{{ asset('images/img07.jpg') }}";
            }
        }
        previewFile();  //calls the function named previewFile()
    </script>
@endsection