@extends('layouts.app')

@section('content')

<section class="section">
    <div class="section-header">
        <h1>Transactions</h1>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h4>Show Transaction</h4>
                        <div class="card-header-action">
                            <a href="{{ route('transaction.edit', $transaction->id) }}" class="mr-2">Edit <i class="fa fa-edit"></i></a>
                            <a href="{{ route('transaction.invoice', $transaction->id) }}" class="btn btn-primary">Show Invoice</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <strong>Client Information</strong>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item"><strong>Name:</strong> {{ $transaction->client->firstName .' '.$transaction->client->middleName.' '.$transaction->client->lastName }}</li>
                                    <li class="list-group-item"><strong>Contact:</strong> {{ $transaction->client->contact }}</li>
                                    <li class="list-group-item"><strong>Age:</strong> {{ $transaction->client->age }}</li>
                                    <li class="list-group-item"><strong>Address:</strong> {{ $transaction->client->address }}</li>
                                    {{-- <li class="list-group-item"><strong>Check In:</strong> {{ $transaction->checkIn_at->format('M d, Y h:i a') }}</li>
                                    <li class="list-group-item"><strong>Check Out:</strong> {{ $transaction->checkOut_at ? $transaction->checkOut_at->format('M d, Y h:i a') : '-' }}</li> --}}
                                </ul>
                                <hr class="mt-0 mb-5">
                            {{-- </div>
                            <div class="col-lg-6"> --}}
                                <strong>Transaction Information</strong>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item"><strong>Check In:</strong> {{ $transaction->checkIn_at->format('M d, Y h:i a') }}</li>
                                    <li class="list-group-item"><strong>Check Out:</strong> {{ $transaction->checkOut_at ? $transaction->checkOut_at->format('M d, Y h:i a') : '-' }}</li>
                                    <li class="list-group-item"><strong>Adults:</strong> {{ $transaction->adults }}</li>
                                    <li class="list-group-item"><strong>Kids:</strong> {{ $transaction->kids }}</li>
                                    <li class="list-group-item"><strong>Senior Citizens:</strong> {{ $transaction->senior }}</li>
                                    <li class="list-group-item"><strong>Use:</strong> {{ ucfirst($transaction->type) }}</li>
                                    <li class="list-group-item"><strong>Breakfast:</strong> {{ $transaction->is_breakfast == 1 ? 'Yes' : 'No' }}
                                        <span class="ml-5">
                                            <strong>Add ons:</strong>
                                            @php
                                                $breakfasts_array = [];
                                                foreach ($transaction->breakfasts as $breakfast) {
                                                    array_push($breakfasts_array, $breakfast->title);
                                                }
                                                echo implode(', ', $breakfasts_array);
                                            @endphp
                                        </span>
                                    </li>
                                    @if ($transaction->cottage_id)
                                    <li class="list-group-item">
                                        <strong>Cottage:</strong> 
                                        {{ $transaction->cottage->name }}
                                    </li>
                                    @endif
                                    @if ($transaction->room_id)
                                    <li class="list-group-item">
                                        <strong>Room:</strong> 
                                        {{ $transaction->room->name }}
                                    </li>
                                    @endif
                                    <li class="list-group-item"><strong>Notes:</strong> {{ $transaction->notes }}</li>
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
