@extends('layouts.app')

@section('content')

<section class="section">
    <div class="section-header">
        <h1>Transactions</h1>
    </div>
    <div class="section-body">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Show Transaction 
                            @if ($transaction->status == 'paid')
                                <span class="badge badge-success">Paid</span>
                            @endif
                        </h4>
                        <div class="card-header-action">
                            <a href="{{ route('transaction.edit', $transaction->id) }}" class="mr-2">Edit <i class="fa fa-edit"></i></a>
                            <a href="{{ route('transaction.invoice', $transaction->id) }}" class="btn btn-primary">Show Invoice</a>
                        </div>
                    </div>
                    <div class="card-body">
                        {{-- <div class="row">
                            <div class="col-lg-4">
                                <strong>Transaction</strong>
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
                            <div class="col-lg-4">
                                <strong>Guest</strong>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item"><strong>Name:</strong> {{ $transaction->guest->firstName .' '.$transaction->guest->middleName.' '.$transaction->guest->lastName }}</li>
                                    <li class="list-group-item"><strong>Contact:</strong> {{ $transaction->guest->contact }}</li>
                                    <li class="list-group-item"><strong>Age:</strong> {{ $transaction->guest->age }}</li>
                                    <li class="list-group-item"><strong>Address:</strong> {{ $transaction->guest->address }}</li>
                                </ul>
                            </div>

                            <div class="col-lg-4">
                                <strong>Bills</strong>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item"><strong>Total Bills:</strong> P{{ number_format($transaction->totalBill, 2) }}</li>                                    
                                    <li class="list-group-item"><strong>Payment Received by:</strong> 
                                        @if ($transaction->status == 'paid')
                                            {{ $transaction->receivedby->firstName .' '.$transaction->receivedby->lastName }}
                                        @endif
                                    </li>
                                </ul>
                            </div>
                        </div> --}}
                        <div class="row">
                            <div class="col-lg-12">
                                <strong>Transaction# {{ $transaction->id }}</strong>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <strong>Status:</strong> {{ ucfirst($transaction->status) }} <br>
                                        @if ($transaction->cottage_id)
                                       <strong>Cottage:</strong> {{ $transaction->cottage->name }} <br>
                                        @endif
                                        @if ($transaction->room_id)
                                        <strong>Room:</strong> {{ $transaction->room->name }} <br>
                                        {{-- <strong>Entrance Fee:</strong> {{ $transaction->room->entrancefee }} <br> --}}
                                        @endif
                                        @if ($transaction->is_exclusive)
                                       <strong>Exclusive Rental:</strong> P{{ number_format($transaction->rentBill, 0) }} <br>
                                        @endif
                                        <strong>Check In:</strong> {{ $transaction->checkIn_at->format('M d, Y h:i a') }} <br>
                                        <strong>Check Out:</strong> {{ $transaction->checkOut_at ? $transaction->checkOut_at->format('M d, Y h:i a') : '-' }} <br>
                                        <strong>Type:</strong> {{ ucfirst($transaction->type) }} Use <br>
                                    </div>


                                    <div class="col-lg-6">
                                        @if ($transaction->room_id)
                                        <strong>Entrance Fee:</strong> {{ $transaction->room->entrancefee }} <br>
                                        @endif
                                        <strong>Adults:</strong> {{ $transaction->adults }} <br>
                                        <strong>Kids:</strong> {{ $transaction->kids }} <br>
                                        <strong>Senior Citizens:</strong> {{ $transaction->senior }} <br>
                                        @if ($transaction->is_breakfast)
                                        <strong>Breakfast:</strong> {{ $transaction->is_breakfast == 1 ? 'Yes' : 'No' }}
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
                                        @endif
                                    </div>
                                    
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12 mt-5">
                                <strong>Bills Summary</strong>
                                <div class="table-responsive mt-2">
                                    <!-- Item list -->
                                    <table class="table table-bordered table-md">
                                        <thead>
                                            <tr>
                                                <th>Item list</th>
                                                <th>Quantity</th>
                                                <th>Unit Price</th>
                                                <th>Total price</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if ($transaction->is_exclusive == 1)
                                                <tr>
                                                    <td>Exclusive Rental</td>
                                                    <td>1</td>
                                                    <td>P{{ number_format($transaction->rentBill, 2) }}</td>
                                                    <td>P<span class="totalprice">{{ number_format($transaction->rentBill, 2) }}</span></td>
                                                </tr>
                                            @else
                                                @if ($transaction->cottage || $transaction->room->entrancefee == 'Exclusive')
                                                    @if ($transaction->adults)
                                                    <tr>
                                                        <td>Adults</td>
                                                        <td>{{ $transaction->adults }}</td>
                                                        @foreach ($entranceFees as $entrancefee)
                                                            @if ($entrancefee->title == 'Adults')
                                                                <td>P{{ number_format($transaction->type != 'day' ? $entrancefee->nightPrice : $entrancefee->price, 2) }}</td>
                                                                <td>P<span class="totalprice">{{ number_format($transaction->adults * ($transaction->type != 'day' ? $entrancefee->nightPrice : $entrancefee->price), 2) }}</span></td>
                                                            @endif
                                                        @endforeach
                                                    </tr>
                                                    @endif

                                                    @if ($transaction->kids)
                                                    <tr>
                                                        <td>Kids</td>
                                                        <td>{{ $transaction->kids }}</td>
                                                        @foreach ($entranceFees as $entrancefee)
                                                            @if ($entrancefee->title == 'Kids')
                                                                <td>P{{ number_format($transaction->type != 'day' ? $entrancefee->nightPrice : $entrancefee->price, 2) }}</td>
                                                                <td>P<span class="totalprice">{{ number_format($transaction->kids * ($transaction->type != 'day' ? $entrancefee->nightPrice : $entrancefee->price), 2) }}</span></td>
                                                            @endif
                                                        @endforeach
                                                    </tr>
                                                    @endif

                                                    @if ($transaction->senior)
                                                    <tr>
                                                        <td>Senior Citizen</td>
                                                        <td>{{ $transaction->senior }}</td>
                                                        @foreach ($entranceFees as $entrancefee)
                                                            @if ($entrancefee->title == 'Senior Citizen')
                                                                <td>P{{ number_format($transaction->type != 'day' ? $entrancefee->nightPrice : $entrancefee->price, 2) }}</td>
                                                                <td>P<span class="totalprice">{{ number_format($transaction->senior * ($transaction->type != 'day' ? $entrancefee->nightPrice : $entrancefee->price), 2) }}</span></td>
                                                            @endif
                                                        @endforeach
                                                    </tr>
                                                    @endif
                                                @endif
                                            @endif

                                            @if ($transaction->cottage)
                                            <tr>
                                                <td>Cottage: {{ $transaction->cottage->name }}</td>
                                                <td>1</td>
                                                <td>P{{ number_format($transaction->type != 'day' ? $transaction->cottage->nightPrice : $transaction->cottage->price, 2) }}</td>
                                                <td>P<span class="totalprice">{{  number_format($transaction->type != 'day' ? $transaction->cottage->nightPrice : $transaction->cottage->price, 2) }}</span></td>
                                            </tr>
                                            @endif

                                            @if ($transaction->room)
                                                <tr>
                                                    <td>Room: {{ $transaction->room->name }}</td>
                                                    <td>1</td>
                                                    <td>P{{ number_format($transaction->room->price, 2) }}</td>
                                                    <td>P<span class="totalprice">{{  number_format($transaction->room->price, 2) }}</span></td>
                                                </tr>
                                            @endif

                                            @if ($transaction->extraPerson && $transaction->room)
                                                <tr>
                                                    <td>Extra Person</td>
                                                    <td>{{ $transaction->extraPerson }}</td>
                                                    <td>P{{ number_format($transaction->room->extraPerson, 2) }}</td>
                                                    <td>P<span class="totalprice">{{  number_format($transaction->room->extraPerson*$transaction->extraPerson, 2) }}</span></td>
                                                </tr>
                                            @elseif($transaction->extraPerson && $transaction->is_exclusive)
                                                <tr>
                                                    <td>Extra Person</td>
                                                    <td>{{ $transaction->extraPerson }}</td>
                                                    <td>P{{ number_format(($transaction->type == 'day' ? 200 : 250), 2) }}</td>
                                                    <td>P<span class="totalprice">{{  number_format($transaction->extraPersonTotal, 2) }}</span></td>
                                                </tr>
                                            @endif

                                            @if ($transaction->is_breakfast)
                                                <tr>
                                                    <td>Breakfast</td>
                                                    <td>1</td>
                                                    <td>P{{ ($transaction->is_freebreakfast == 0 ) ? number_format(config('yourconfig.resort')->breakfastPrice, 2) : 0 }}</td>
                                                    <td>P<span class="totalprice">{{ ($transaction->is_freebreakfast == 0 ) ? number_format(config('yourconfig.resort')->breakfastPrice, 2) : 0 }}</span></td>
                                                </tr>

                                                @foreach ($transaction->breakfasts as $breakfast)
                                                <tr>
                                                    <td>{{ $breakfast->title }}</td>
                                                    <td>1</td>
                                                    <td>P{{ number_format($breakfast->price, 2) }}</td>
                                                    <td>P<span class="totalprice">{{ number_format($breakfast->price, 2) }}</span></td>
                                                </tr>
                                                @endforeach
                                            @endif
    
                                        </tbody>
                                    </table>
                                    <!-- /.Item list -->
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-3 float-md-right ml-auto">
                                <ul class="striped list-unstyled">
                                    <li><strong>TOTAL:</strong><span class="float-right">P<span id="total_invoice">{{ number_format($transaction->totalBill, 2) }}</span></span></li>
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
