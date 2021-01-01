@extends('layouts.app')

@section('content')

<section class="section">
    <div class="section-header">
        <h1>Rooms</h1>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-12 col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Add Room</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('room.store') }}" enctype="multipart/form-data">
                            @csrf

                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="room">Room</label>
                                    <input type="text" class="form-control @error('room') is-invalid @enderror"
                                        name="room" id="room" value="{{ old('room') }}">
                                    @error('room')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="price">Price</label>
                                    <input type="text"
                                        class="form-control digit_only2 @error('price') is-invalid @enderror"
                                        name="price" id="price" value="{{ old('price') }}">
                                    @error('price')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="extraPerson">Extra Person Price</label>
                                    <input type="text"
                                        class="form-control digit_only2 @error('extraPerson') is-invalid @enderror"
                                        name="extraPerson" id="extraPerson" value="{{ old('extraPerson') }}">
                                    @error('extraPerson')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="maxExtraPerson">Max Extra Person</label>
                                    <input type="text"
                                        class="form-control digit_only2 @error('maxExtraPerson') is-invalid @enderror"
                                        name="maxExtraPerson" id="maxExtraPerson" value="{{ old('maxExtraPerson') }}">
                                    @error('maxExtraPerson')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label class="form-label">Entrance fee</label>
                                    <div class="selectgroup selectgroup-pills @error('entrancefee') is-invalid @enderror">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="entrancefee" value="Inclusive" class="selectgroup-input">
                                            <span class="selectgroup-button selectgroup-button-icon">Inclusive</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="radio" name="entrancefee" value="Exclusive" class="selectgroup-input">
                                            <span class="selectgroup-button selectgroup-button-icon">Exclusive</span>
                                        </label>
                                    </div>
                                    @error('entrancefee')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="descriptions">Descriptions</label>
                                <textarea class="form-control edited @error('descriptions') is-invalid @enderror"
                                    name="descriptions" id="descriptions"></textarea>
                                @error('descriptions')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group d-none" style="width:325px;">
                                <img class="img-fluid img-preview z-depth-1 profile-avatar mb-3">
                                <div class="file-field">
                                    <div class="btn btn-primary btn-sm">
                                        <span><i class="fa fa-image"></i> Choose</span>
                                        <input type="file" name="image" onchange="previewFile()">
                                    </div>
                                </div>

                                @if ($errors->has('image'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('image') }}</strong>
                                </span>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="images">Upload Images</label>
                                <input type="file" multiple class="form-control @error('images') is-invalid @enderror"
                                    name="images[]" id="images" value="{{ old('images') }}">
                                @error('images')
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

@section('scripts')
<script>
    function previewFile() {
        var preview = document.querySelector('.img-preview'); //selects the query named img
        var file = document.querySelector('input[type=file]').files[0]; //sames as here
        var reader = new FileReader();
        reader.onloadend = function () {
            preview.src = reader.result;
        }
        if (file) {
            reader.readAsDataURL(file); //reads the data as a URL
        } else {
            preview.src = "{{ asset('images/img07.jpg') }}";
        }
    }
    previewFile(); //calls the function named previewFile()

</script>
@endsection
