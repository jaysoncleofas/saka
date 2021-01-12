@extends('layouts.app')

@section('content')

<section class="section">
    <div class="section-header">
        <h1>Settings</h1>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Resort Settings</h4>
                        <div class="card-header-action">
                            SMS Credits: <strong>{{ number_format($resort->points, 0) }}</strong>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('resort.update', $resort->id) }}">
                            @csrf @method('PUT')

                            <div class="form-group">
                                <label for="resortName">Resort Name</label>
                                <input type="text" class="form-control @error('resortName') is-invalid @enderror" name="resortName" id="resortName" value="{{ $resort->name }}">
                                @error('resortName')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" id="email"value="{{ $resort->email }}">
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="phone">Phone</label>
                                        <input type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" id="phone"value="{{ $resort->phone }}">
                                        @error('phone')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>



                            <div class="form-group">
                                <label for="address">Address</label>
                                <input type="text" class="form-control @error('address') is-invalid @enderror" name="address" id="address"value="{{ $resort->address }}">
                                @error('address')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <span>Operational time:</span>
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="day">Day</label>
                                        <input type="text" class="form-control @error('day') is-invalid @enderror" name="day" id="day"value="{{ $resort->day }}">
                                        @error('day')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="night">Night</label>
                                        <input type="text" class="form-control @error('night') is-invalid @enderror" name="night" id="night"value="{{ $resort->night }}">
                                        @error('night')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="overnight">Overnight</label>
                                        <input type="text" class="form-control @error('overnight') is-invalid @enderror" name="overnight" id="overnight"value="{{ $resort->overnight }}">
                                        @error('overnight')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="facebook">Facebook link</label>
                                <input type="text" class="form-control @error('facebook') is-invalid @enderror" name="facebook" id="facebook"value="{{ $resort->facebook }}">
                                @error('facebook')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="instagram">Instagram link</label>
                                <input type="text" class="form-control @error('instagram') is-invalid @enderror" name="instagram" id="instagram"value="{{ $resort->instagram }}">
                                @error('instagram')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="twitter">Twitter link</label>
                                <input type="text" class="form-control @error('twitter') is-invalid @enderror" name="twitter" id="twitter"value="{{ $resort->twitter }}">
                                @error('twitter')
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

            <div class="col-lg-6">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Entrance Fees</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped" width="100%">
                                        <thead>
                                            <tr>
                                                <th>Title</th>
                                                <th>Price</th>
                                                <th>Night Price</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($entranceFees as $entrance)
                                            <tr>
                                                <td>{{ $entrance->title }}</td>
                                                <td>P{{ number_format($entrance->price, 0) }}</td>
                                                <td>P{{ number_format($entrance->nightPrice, 0) }}</td>
                                                <td><a href="{{ route('entrancefee.edit', $entrance->id) }}" class="btn btn-primary btn-action mr-1" title="Edit"><i class="fas fa-pencil-alt"></i></a></td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Breakfast Add ons</h4>
                                <div class="card-header-action">
                                    <a href="{{ route('breakfast.create') }}" class="btn btn-primary">Add</a>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped" width="100%">
                                        <thead>
                                            <tr>
                                                <th>Title</th>
                                                <th>Price</th>
                                                <th>Notes</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($breakfasts as $breakfast)
                                            <tr>
                                                <td>{{ $breakfast->title }}</td>
                                                <td>P{{ number_format($breakfast->price, 0) }}</td>
                                                <td>{{ $breakfast->notes }}</td>
                                                <td>{{ $breakfast->is_active ? 'Active' : 'Inactive' }}</td>
                                                <td><a href="{{ route('breakfast.edit', $breakfast->id) }}" class="btn btn-primary btn-action mr-1 mb-1" title="Edit"><i class="fas fa-pencil-alt"></i></a> <a class="btn btn-danger btn-action trigger-delete mb-1" title="Delete" data-action="{{ route('breakfast.destroy', $breakfast->id) }}" data-model="breakfast"><i class="fas fa-trash"></i></a></td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

@endsection

@section('scripts')
<script>
    $(function () {

    });
</script>
@endsection
