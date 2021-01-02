@extends('layouts.app')

@section('content')

<section class="section">
    <div class="section-header">
        <h1>Guests</h1>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-6 col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Show Guest</h4>
                        <div class="card-header-action">
                            <a href="{{ route('transaction.create', $guest->id) }}" class="btn btn-primary">Add Transaction</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <strong>Guest Information</strong>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><strong>Name:</strong> {{ $guest->firstName .' '.$guest->middleName.' '.$guest->lastName }}</li>
                            <li class="list-group-item"><strong>Contact:</strong> {{ $guest->contact }}</li>
                            <li class="list-group-item"><strong>Age:</strong> {{ $guest->age }}</li>
                            <li class="list-group-item"><strong>Address:</strong> {{ $guest->address }}</li>
                        </ul>
                        <hr class="mt-0">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
