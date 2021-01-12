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
                                <div class="row">
                                    <div class="col-lg-6 col-md-6">
                                        <strong>Status:</strong> {{ ucfirst($transaction->status) }} <br>
                                        <strong>Payment Received by:</strong> 
                                        @if ($transaction->status == 'completed')
                                            {{ $transaction->receivedby->firstName .' '.$transaction->receivedby->lastName }}
                                        @endif <br>
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
                                        @if ($transaction->room_id)
                                        <strong>Entrance Fee:</strong> {{ $transaction->room->entrancefee }} <br>
                                        @endif
                                        <strong>Adults:</strong> {{ $transaction->adults }} <br>
                                        <strong>Kids:</strong> {{ $transaction->kids }} <br>
                                        <strong>Senior Citizens:</strong> {{ $transaction->senior }} <br>
                                        @if ($transaction->room_id && $transaction->type == 'overnight')
                                        <strong>Breakfast:</strong> Free <br>
                                        <span class="">
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
                                    <div class="col-lg-6 col-md-6">
                                        @if ($transaction->completed_at)
                                        <strong>Completed Date:</strong> {{ date('M d, Y h:i a', strtotime($transaction->completed_at)) }} <br>
                                        @endif
                                        @if ($transaction->cancelled_at)
                                        <strong>Cancelled Date:</strong> {{ date('M d, Y h:i a', strtotime($transaction->cancelled_at)) }} <br>
                                        @endif
                                        <strong>Guest:</strong> {{ $transaction->guest->fullname }} <br>
                                        <strong>Contact Number:</strong> {{ $transaction->guest->contact }} <br>
                                        <strong>Email:</strong> {{ $transaction->guest->email }} <br>
                                        <strong>Address:</strong> {{ $transaction->guest->address }} <br>
                                        <strong>Notes:</strong> {{ $transaction->notes }} <br>
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
