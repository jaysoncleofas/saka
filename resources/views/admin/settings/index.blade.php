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
                                <div class="control-label">SMS Notification ({{ number_format($resort->points, 0) }} Credits)</div>
                                <label for="sms_notification" class="custom-switch mt-2 pl-0">
                                    <input type="checkbox" name="sms_notification" id="sms_notification" value="1" class="custom-switch-input" {{ $resort->sms_on ? 'checked' : '' }}>
                                    <span class="custom-switch-indicator"></span>
                                    <span class="custom-switch-description">On</span>
                                </label>
                            </div>

                            <span>Exclusive Day Use:</span>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="exclusive_dayprice">Price</label>
                                        <input type="text" class="form-control digit_only2 @error('exclusive_dayprice') is-invalid @enderror"
                                            name="exclusive_dayprice" id="exclusive_dayprice" value="{{ number_format($resort->exclusive_dayprice, 0) }}">
                                        @error('exclusive_dayprice')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="exclusive_daycapacity">Capacity</label>
                                        <input type="text"
                                            class="form-control digit_only2 @error('exclusive_daycapacity') is-invalid @enderror"
                                            name="exclusive_daycapacity" id="exclusive_daycapacity" value="{{ $resort->exclusive_daycapacity }}">
                                        @error('exclusive_daycapacity')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <span>Exclusive Overnight Use:</span>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="exclusive_overnightprice">Price</label>
                                        <input type="text" class="form-control digit_only2 @error('exclusive_overnightprice') is-invalid @enderror"
                                            name="exclusive_overnightprice" id="exclusive_overnightprice" value="{{ number_format($resort->exclusive_overnightprice, 0) }}">
                                        @error('exclusive_overnightprice')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="exclusive_overnightcapacity">Capacity</label>
                                        <input type="text"
                                            class="form-control digit_only2 @error('exclusive_overnightcapacity') is-invalid @enderror"
                                            name="exclusive_overnightcapacity" id="exclusive_overnightcapacity" value="{{ $resort->exclusive_overnightcapacity }}">
                                        @error('exclusive_overnightcapacity')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <span>Bank Account Details:</span>
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="bank_account">Account Name</label>
                                        <input type="text" class="form-control @error('bank_account') is-invalid @enderror"
                                            name="bank_account" id="bank_account" value="{{ $resort->bank_account }}">
                                        @error('bank_account')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="bank">Bank</label>
                                        <input type="text"
                                            class="form-control @error('bank') is-invalid @enderror"
                                            name="bank" id="bank" value="{{ $resort->bank }}">
                                        @error('bank')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="bank_accountnumber">Account Number</label>
                                        <input type="text"
                                            class="form-control @error('bank_accountnumber') is-invalid @enderror"
                                            name="bank_accountnumber" id="bank_accountnumber" value="{{ $resort->bank_accountnumber }}">
                                        @error('bank_accountnumber')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <span>GCash Details:</span>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="gcash_account">GCash Account</label>
                                        <input type="text" class="form-control @error('gcash_account') is-invalid @enderror"
                                            name="gcash_account" id="gcash_account" value="{{ $resort->gcash_account }}">
                                        @error('gcash_account')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="gcash_number">Number</label>
                                        <input type="text"
                                            class="form-control @error('gcash_number') is-invalid @enderror"
                                            name="gcash_number" id="gcash_number" value="{{ $resort->gcash_number }}">
                                        @error('gcash_number')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
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
                                                <th>Day</th>
                                                <th>Night</th>
                                                <th>Overnight</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($entranceFees as $entrance)
                                            <tr>
                                                <td>{{ $entrance->title }}</td>
                                                <td>P{{ number_format($entrance->price, 0) }}</td>
                                                <td>P{{ number_format($entrance->nightPrice, 0) }}</td>
                                                <td>P{{ number_format($entrance->overnightPrice, 0) }}</td>
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
                                {{-- <div class="card-header-action">
                                    <a href="{{ route('breakfast.create') }}" class="btn btn-primary">Add</a>
                                </div> --}}
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
                                                <td><a href="{{ route('breakfast.edit', $breakfast->id) }}" class="btn btn-primary btn-action mr-1 mb-1" title="Edit"><i class="fas fa-pencil-alt"></i></a></td>
                                                {{-- <a class="btn btn-danger btn-action trigger-delete mb-1" title="Delete" data-action="{{ route('breakfast.destroy', $breakfast->id) }}" data-model="breakfast"><i class="fas fa-trash"></i></a> --}}
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
