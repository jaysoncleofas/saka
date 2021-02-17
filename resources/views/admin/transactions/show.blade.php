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
                            <a href="@php
                                if($transaction->cottage_id){
                                    echo route('transaction.edit_cottage', $transaction->id);
                                  } elseif($transaction->room_id){
                                    echo route('transaction.edit_room', $transaction->id);
                                  }if($transaction->is_exclusive){
                                    echo route('transaction.edit_exclusive', $transaction->id);
                                  }
                            @endphp" class="mr-2">Edit <i class="fa fa-edit"></i></a>
                            <a href="{{ route('transaction.invoice', $transaction->id) }}" class="btn btn-primary">Show Invoice</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row is-size-15">
                            <div class="col-lg-12">
                                <strong>Control#{{ $transaction->id }}</strong>
                                <div class="row mt-2">
                                    <div class="col-lg-4 col-md-4">
                                        <ul class="list-group list-group-flush border-top border-bottom">
                                            <li class="list-group-item py-1"><strong>Status:</strong> <span class="float-right">{{ ucfirst($transaction->status) }}</span></li>
                                            @if ($transaction->completed_at)
                                                <li class="list-group-item py-1"><strong>Completed Date:</strong> <span class="float-right">{{ date('M d, Y h:i a', strtotime($transaction->completed_at)) }}</span></li>
                                            @endif
                                            @if ($transaction->cancelled_at)
                                                <li class="list-group-item py-1"><strong>Completed Date:</strong> <span class="float-right">{{ date('M d, Y h:i a', strtotime($transaction->cancelled_at)) }}</span></li>
                                            @endif
                                            <li class="list-group-item py-1"><strong>Payment Received by:</strong> 
                                                <span class="float-right">
                                                    @if ($transaction->status == 'completed')
                                                        {{ $transaction->receivedby->firstName .' '.$transaction->receivedby->lastName }}
                                                    @endif
                                                </span>
                                            </li>
                                            <li class="list-group-item py-1"><strong>Type:</strong> <span class="float-right">{{ $transaction->is_reservation ? 'Reservation' : 'Walk in' }}</span></li>
                                            @if ($transaction->payment_id)
                                                <li class="list-group-item py-1"><strong>Payment Method:</strong> <span class="float-right">{{ $transaction->payment->name }}</span></li>
                                            @endif
                                        </ul>
                                    </div>

                                    <div class="col-lg-4 col-md-4">
                                        <ul class="list-group list-group-flush border-top border-bottom">
                                            @if ($transaction->cottage_id)
                                                <li class="list-group-item py-1"><strong>Cottage:</strong> <span class="float-right">{{ $transaction->cottage->name }}</span></li>
                                            @endif
                                            @if ($transaction->room_id)
                                                <li class="list-group-item py-1"><strong>Room:</strong> <span class="float-right">{{ $transaction->room->name }}</span></li>
                                            @endif
                                            @if ($transaction->is_exclusive)
                                                <li class="list-group-item py-1"><strong>Exclusive Rental:</strong> <span class="float-right">P{{ number_format($transaction->rentBill, 0) }}</span></li>
                                            @endif
                                            <li class="list-group-item py-1"><strong>Use:</strong> <span class="float-right">{{ ucfirst($transaction->type) }} Use</span></li>
                                            <li class="list-group-item py-1"><strong>Check In:</strong> <span class="float-right">{{ $transaction->checkIn_at->format('M d, Y h:i a') }}</span></li>
                                            <li class="list-group-item py-1"><strong>Check Out:</strong> <span class="float-right">{{ $transaction->checkOut_at ? $transaction->checkOut_at->format('M d, Y h:i a') : '-' }}</span></li>
                                            @if ($transaction->room_id)
                                                <li class="list-group-item py-1"><strong>Entrance Fee:</strong> <span class="float-right">{{ $transaction->room->entrancefee }}</span></li>
                                            @endif
                                            <li class="list-group-item py-1"><strong>Adults:</strong> <span class="float-right">{{ $transaction->adults }}</span></li>
                                            <li class="list-group-item py-1"><strong>Kids:</strong> <span class="float-right">{{ $transaction->kids }}</span></li>
                                            <li class="list-group-item py-1"><strong>Senior Citizens:</strong> <span class="float-right">{{ $transaction->senior }}</span></li>
                                            @if ($transaction->room_id && $transaction->type == 'overnight')
                                                <li class="list-group-item py-1"><strong>Breakfast:</strong> <span class="float-right">Free</span></li>
                                                <li class="list-group-item py-1"><strong>Add ons:</strong> 
                                                    <span class="float-right">
                                                        @php
                                                            $breakfasts_array = [];
                                                            foreach ($transaction->breakfasts as $breakfast) {
                                                                array_push($breakfasts_array, $breakfast->title);
                                                            }
                                                            echo implode(', ', $breakfasts_array);
                                                        @endphp
                                                    </span>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>

                                    <div class="col-lg-4 col-md-4">
                                        <ul class="list-group list-group-flush border-top border-bottom">
                                            <li class="list-group-item py-1"><strong>Guest:</strong> <span class="float-right"><a href="{{ route('guest.show', $transaction->guest_id) }}">{{ $transaction->guest->fullname }}</a></span></li>
                                            <li class="list-group-item py-1"><strong>Contact Number:</strong> <span class="float-right">{{ $transaction->guest->contact }}</span></li>
                                            <li class="list-group-item py-1"><strong>Email:</strong> <span class="float-right">{{ $transaction->guest->email }}</span></li>
                                            <li class="list-group-item py-1"><strong>Address:</strong> <span class="float-right">{{ $transaction->guest->address }}</span></li>
                                            <li class="list-group-item py-1"><strong>Notes:</strong> <span class="float-right">{{ $transaction->notes }}</span></li>
                                        </ul>
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
                                                <th>Items</th>
                                                <th>Quantity</th>
                                                <th>Unit Price</th>
                                                <th>Total price</th>
                                            </tr>
                                        </thead>
                                        <tbody>
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
                                                                @php
                                                                    $fees = 0;
                                                                    if($transaction->type == 'day') {
                                                                       $fees =  $entrancefee->price;
                                                                    } elseif ($transaction->type == 'night') {
                                                                        $fees =  $entrancefee->nightPrice;
                                                                    } else {
                                                                        $fees =  $entrancefee->overnightPrice;
                                                                    }     
                                                                @endphp 
                                                            @if ($entrancefee->title == 'Adults')
                                                                <td>P {{ number_format($fees, 2) }}</td>
                                                                <td>P<span class="totalprice">{{ number_format($transaction->adults * ($fees), 2) }}</span></td>
                                                            @endif
                                                        @endforeach
                                                    </tr>
                                                    @endif

                                                    @if ($transaction->kids)
                                                    <tr>
                                                        <td>Kids</td>
                                                        <td>{{ $transaction->kids }}</td>
                                                        @foreach ($entranceFees as $entrancefee)
                                                            @php
                                                                $fees = 0;
                                                                if($transaction->type == 'day') {
                                                                    $fees =  $entrancefee->price;
                                                                } elseif ($transaction->type == 'night') {
                                                                    $fees =  $entrancefee->nightPrice;
                                                                } else {
                                                                    $fees =  $entrancefee->overnightPrice;
                                                                }     
                                                            @endphp 
                                                            @if ($entrancefee->title == 'Kids')
                                                                <td>P{{ number_format($fees, 2) }}</td>
                                                                <td>P<span class="totalprice">{{ number_format($transaction->kids * ($fees), 2) }}</span></td>
                                                            @endif
                                                        @endforeach
                                                    </tr>
                                                    @endif

                                                    @if ($transaction->senior)
                                                    <tr>
                                                        <td>Senior Citizen</td>
                                                        <td>{{ $transaction->senior }}</td>
                                                        @foreach ($entranceFees as $entrancefee)
                                                            @php
                                                                $fees = 0;
                                                                if($transaction->type == 'day') {
                                                                    $fees =  $entrancefee->price;
                                                                } elseif ($transaction->type == 'night') {
                                                                    $fees =  $entrancefee->nightPrice;
                                                                } else {
                                                                    $fees =  $entrancefee->overnightPrice;
                                                                }     
                                                            @endphp 
                                                            @if ($entrancefee->title == 'Senior Citizen')
                                                                <td>P{{ number_format($fees, 2) }}</td>
                                                                <td>P<span class="totalprice">{{ number_format($transaction->senior * ($fees), 2) }}</span></td>
                                                            @endif
                                                        @endforeach
                                                    </tr>
                                                    @endif
                                                @endif
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

                                            @if ($transaction->room_id && $transaction->type == 'overnight')
                                                <tr>
                                                    <td>Breakfast Add ons:</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
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
                                <ul class="striped list-unstyled float-right">
                                    <li><strong>TOTAL:</strong><span class="ml-2">P<span id="total_invoice">{{ number_format($transaction->totalBill, 2) }}</span></span></li>
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
