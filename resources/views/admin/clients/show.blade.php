@extends('layouts.app')

@section('content')

<section class="section">
    <div class="section-header">
        <h1>Clients</h1>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-6 col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Show Client</h4>
                        <div class="card-header-action">
                            <a href="{{ route('transaction.create', $client->id) }}" class="btn btn-primary">Add Transaction</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <ul class="list-group">
                            <li class="list-group-item"><strong>Name:</strong> {{ $client->firstName .' '.$client->middleName.' '.$client->lastName }}</li>
                            <li class="list-group-item"><strong>Contact:</strong> {{ $client->contact }}</li>
                            <li class="list-group-item"><strong>Age:</strong> {{ $client->age }}</li>
                            <li class="list-group-item"><strong>Address:</strong> {{ $client->address }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
