@extends('layouts.app')

@section('content')

<section class="section">
    <div class="section-header">
        <h1>Change Password</h1>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-6 col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Update Password</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('changepassword.update') }}">
                            @csrf @method('PUT')

                            <div class="form-group">
                                <label for="currentPassword">Current Password</label>
                                <input type="password" class="form-control @error('currentPassword') is-invalid @enderror" name="currentPassword" id="currentPassword" value="{{ old('currentPassword') }}">
                                @error('currentPassword')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="newPassword">New Password</label>
                                <input type="password" class="form-control @error('newPassword') is-invalid @enderror" name="newPassword" id="newPassword" value="{{ old('newPassword') }}">
                                @error('newPassword')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="newPassword_confirmation">Confirm Password</label>
                                <input type="password" class="form-control" name="newPassword_confirmation" id="newPassword_confirmation">
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
