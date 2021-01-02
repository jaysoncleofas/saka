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
                        <h4>Edit Breakfast Add ons</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('breakfast.update', $breakfast->id) }}">
                            @csrf @method('PUT')

                            <div class="form-group">
                                <label for="title">Title</label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror"
                                    name="title" id="title" value="{{ $breakfast->title }}">
                                @error('title')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="price">Price</label>
                                <input type="text" class="form-control digit_only2 @error('price') is-invalid @enderror"
                                    name="price" id="price" value="{{ number_format($breakfast->price, 0) }}">
                                @error('price')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <div class="control-label">Status</div>
                                <label for="status" class="custom-switch mt-2 pl-0">
                                    <input type="checkbox" name="status" id="status" value="1" class="custom-switch-input" {{ $breakfast->is_active ? 'checked' : '' }}>
                                    <span class="custom-switch-indicator"></span>
                                    <span class="custom-switch-description">Active</span>
                                </label>
                            </div>

                            <div class="form-group mt-4">
                                <label for="notes">Notes</label>
                                <textarea class="form-control edited @error('notes') is-invalid @enderror"
                                    name="notes" id="notes">{{ $breakfast->notes }}</textarea>
                                @error('notes')
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