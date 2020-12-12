@extends('layouts.app')

@section('content')

<section class="section">
    <div class="section-header">
        <h1>Transactions</h1>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-12 col-md-12 col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Show Transaction</h4>
                        <div class="card-header-action">
                            <a href="{{ route('transaction.invoice', $transaction->id) }}" class="btn btn-primary">Show Invoice</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <p><a href="{{ route('transaction.edit', $transaction->id) }}">Edit Transaction<i class="fa fa-edit"></i></a></p>
                            </div>
                            <div class="col-lg-6">
                                <ul class="list-group">
                                    <li class="list-group-item"><strong>Name:</strong> {{ $transaction->client->firstName .' '.$transaction->client->middleName.' '.$transaction->client->lastName }}</li>
                                    <li class="list-group-item"><strong>Contact:</strong> {{ $transaction->client->contact }}</li>
                                    <li class="list-group-item"><strong>Age:</strong> {{ $transaction->client->age }}</li>
                                    <li class="list-group-item"><strong>Address:</strong> {{ $transaction->client->address }}</li>
                                    <li class="list-group-item"><strong>Check In:</strong> {{ $transaction->checkIn_at->format('M d, Y, h:i a') }}</li>
                                    <li class="list-group-item"><strong>Check Out:</strong> {{ $transaction->checkOut_at ? $transaction->checkOut_at->format('M d, Y, h:i a') : '-' }}</li>
                                </ul>
                            </div>
                            <div class="col-lg-6">
                                <ul class="list-group">
                                    <li class="list-group-item"><strong>Adult:</strong> {{ $transaction->adult }}</li>
                                    <li class="list-group-item"><strong>Kids:</strong> {{ $transaction->kids }}</li>
                                    <li class="list-group-item"><strong>Senior Citizen:</strong> {{ $transaction->senior }}</li>
                                    <li class="list-group-item"><strong>Day/Night Use:</strong> {{ $transaction->type->name }}</li>
                                    <li class="list-group-item"><strong>Breakfast:</strong> {{ $transaction->is_breakfast == 1 ? 'Yes' : 'No' }}</li>
                                    <li class="list-group-item">
                                        <strong>Cottages:</strong> 
                                        @php
                                            $cottages_array = [];
                                            foreach ($transaction->cottages as $cottage) {
                                                array_push($cottages_array, $cottage->name);
                                            }
                                            echo implode(', ', $cottages_array);
                                        @endphp
                                    </li>
                                    <li class="list-group-item">
                                        <strong>Rooms:</strong> 
                                        @php
                                            $rooms_array = [];
                                            foreach ($transaction->rooms as $room) {
                                                array_push($rooms_array, $room->name);
                                            }
                                            echo implode(', ', $rooms_array);
                                        @endphp
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
