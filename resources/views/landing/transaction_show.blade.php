@extends('layouts.resort')

@section('style')
<style>
    .swal-footer,
    .swal-text {
        text-align: center !important;
    }
</style>
@endsection

@section('content')
@include('landing.nav2')

<div class="section rooms">
    <div class="container-small-616px text-center w-container">
        <h2 class="title request-info mb-5">Reservation</h2>
        {{-- <p>Find and reserve your selected room and get the lowest prices.</p> --}}
    </div>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row is-size-15">
                            <div class="col-lg-12">
                                <strong>Control# {{ $transaction->id }}</strong>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <strong>Status:</strong> {{ ucfirst($transaction->status) }} <br>
                                        @if ($transaction->is_exclusive)
                                       <strong>Rent:</strong> Exclusive Rental <br>
                                        @endif
                                        @if ($transaction->cottage_id)
                                       <strong>Cottage:</strong> {{ $transaction->cottage->name }} <br>
                                        @endif
                                        @if ($transaction->room_id)
                                        <strong>Room:</strong> {{ $transaction->room->name }} <br>
                                        {{-- <strong>Entrance Fee:</strong> {{ $transaction->room->entrancefee }} <br> --}}
                                        @endif
                                        <strong>Type:</strong> {{ ucfirst($transaction->type) }} Use <br>
                                        <strong>Check In:</strong> {{ $transaction->checkIn_at->format('M d, Y h:i a') }} <br>
                                        <strong>Check Out:</strong> {{ $transaction->checkOut_at ? $transaction->checkOut_at->format('M d, Y h:i a') : '-' }} <br>
                                        @if ($transaction->room_id)
                                        <strong>Entrance Fee:</strong> {{ $transaction->room->entrancefee }} <br>
                                        @endif
                                        <strong>Payment Method:</strong> {{ $transaction->payment->name }} <br>
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

                                    <div class="col-lg-6">
                                        <strong>Guest:</strong> {{ $transaction->guest->fullname }} <br>
                                        <strong>Contact Number:</strong> {{ $transaction->guest->contact }} <br>
                                        <strong>Email:</strong> {{ $transaction->guest->email }} <br>
                                        <strong>Address:</strong> {{ $transaction->guest->address }} <br>
                                        {{-- <strong>Notes:</strong> {{ $transaction->notes }} <br> --}}
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
</div>

@include('landing.footer')

@endsection

@section('script')
@if (session('notification'))
<script>
swal({
    title: 'Success!',
    text: '{{ session('notification') }}',
    icon: "success",
    button: true,
});
</script>
@endif
@endsection