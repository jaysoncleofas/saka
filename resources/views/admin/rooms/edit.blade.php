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
                        <h4>Update Room</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('room.update', $room->id) }}" enctype="multipart/form-data">
                            @csrf @method('PUT')

                            <div class="row">
                                <div class="form-group col-lg-12">
                                    <label for="room">Room</label>
                                    <input type="text" class="form-control @error('room') is-invalid @enderror"
                                        name="room" id="room" value="{{ $room->name }}">
                                    @error('room')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-group col-lg-6">
                                    <label for="price">Price</label>
                                    <input type="text" class="form-control digit_only2 @error('price') is-invalid @enderror"
                                        name="price" id="price" value="{{ number_format($room->price, 0) }}">
                                    @error('price')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-group col-lg-6">
                                    <label for="extraPerson">Extra Person</label>
                                    <input type="text" class="form-control @error('extraPerson') is-invalid @enderror"
                                        name="extraPerson" id="extraPerson" value="{{ number_format($room->extraPerson, 0) }}">
                                    @error('extraPerson')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="min">Min Capacity</label>
                                    <input type="text"
                                        class="form-control digit_only2 @error('min') is-invalid @enderror"
                                        name="min" id="min" value="{{ $room->min }}">
                                    @error('min')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="max">Max Capacity</label>
                                    <input type="text"
                                        class="form-control digit_only2 @error('max') is-invalid @enderror"
                                        name="max" id="max" value="{{ $room->max }}">
                                    @error('max')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label class="form-label">Entrance fee</label>
                                    <div class="selectgroup selectgroup-pills">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="entrancefee" value="Inclusive" class="selectgroup-input" {{ $room->entrancefee == 'Inclusive' ? 'checked' : '' }}>
                                            <span class="selectgroup-button selectgroup-button-icon">Inclusive</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="radio" name="entrancefee" value="Exclusive" class="selectgroup-input" {{ $room->entrancefee == 'Exclusive' ? 'checked' : '' }}>
                                            <span class="selectgroup-button selectgroup-button-icon">Exclusive</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="descriptions">Descriptions</label>
                                <textarea class="form-control edited @error('descriptions') is-invalid @enderror" name="descriptions" id="descriptions">{{ $room->descriptions }}</textarea>
                                @error('descriptions')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="images">Upload New Images</label>
                                <input type="file" multiple class="form-control @error('images') is-invalid @enderror"
                                    name="images[]" id="images" value="{{ old('images') }}">
                                @error('images')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            
                            <div class="form-group d-none" style="width:325px;">
                                <img class="img-fluid img-preview z-depth-1 room-image mb-3">
                                <div class="file-field">
                                    <div class="btn btn-primary btn-sm">
                                        <span><i class="fa fa-image"></i> Choose</span>
                                        <input type="file" name="image" onchange="previewFile()">
                                    </div>
                                </div>
                                
                                @if ($room->image)
                                <a href="javascript:void(0);" data-href="{{ route('room.image.remove') }}" class="btn btn-danger btn-sm remove_image mt-3" data-method="put" data-value="{{ $room->id }}">
                                    <span><i class="fa fa-times"></i> Remove</span>
                                </a>
                                @endif
        
                                @if ($errors->has('image'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('image') }}</strong>
                                    </span>
                                @endif
                            </div>

                            @if ($room->images()->count() > 0)
                                <div class="form-group">
                                    <label class="form-label">Images (Check the cover image)</label>
                                    <div class="row gutters-sm">
                                        @foreach ($room->images as $image)
                                            <div class="col-6 col-sm-4 mb-3">
                                                <label class="imagecheck">
                                                    <input name="coverimage" type="radio" value="{{ $image->id }}" class="imagecheck-input" {{ $image->is_cover ? 'checked' : '' }}>
                                                    <figure class="imagecheck-figure">
                                                        <img src="{{ asset('storage/rooms/'.$image->path) }}" alt="}" class="imagecheck-image">
                                                    </figure>
                                                </label>
                                                <a href="javascript:void(0);" data-href="{{ route('room.coverimage.remove') }}"
                                                    class="btn btn-danger btn-sm remove_coverimage mt-3" data-method="put"
                                                    data-value="{{ $image->id }}">
                                                    <span><i class="fa fa-times"></i> Remove</span>
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

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
                preview.src = "{{$room->image ? asset('storage/rooms/'.$room->image) : asset('images/img07.jpg')}}";
            }
        }
        previewFile();  //calls the function named previewFile()

        $(document).on('click', '.remove_image', function(e) {
            e.preventDefault();
            var $this = $(this);
            swal({
                title: 'Are you sure?',
                text: 'Once deleted, you will not be able to recover!',
                icon: 'warning',
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        type: $this.data('method'),
                        url: $this.data('href'),
                        data: {id: $this.data('value')},
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (result) {
                            if (result == 'success') {
                                location.reload();
                            }
                        }
                    });
                } else {
                    swal('The image is safe!');
                }
            });
        });

        $(document).on('click', '.remove_coverimage', function (e) {
            e.preventDefault();
            var $this = $(this);
            swal({
                    title: 'Are you sure?',
                    text: 'Once deleted, you will not be able to recover!',
                    icon: 'warning',
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            type: $this.data('method'),
                            url: $this.data('href'),
                            data: {
                                id: $this.data('value')
                            },
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function (result) {
                                if (result == 'success') {
                                    location.reload();
                                }
                            }
                        });
                    } else {
                        swal('The image is safe!');
                    }
                });
        });
    </script>
@endsection
