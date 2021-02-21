@component('mail::message')
# Hi {{ $transaction->guest->fullname }}, 
@if ($transaction->payment_id != 1)
Thank you for booking. It's on-hold until we confirm that payment has been received. In the meantime, here's a reminder of what you booked:<br> 

Send the deposit slip through our Facebook Page's Messenger. <br> 
Account: SAKA RESORT<br>
@else 
Thank you for booking. Here's a reminder of what you booked:<br> 
@endif

@if ($transaction->payment_id == 2)
# Our gcash details:
GCASH Account: <strong>John Doe</strong><br>
Number: <strong>004358012183</strong><br>
@endif
@if ($transaction->payment_id == 3)
# Our bank details:
Account Name: <strong>John Doe</strong><br>
Bank: <strong>BDO</strong><br>
Account number: <strong>004358012183</strong><br>
@endif

# Reservation Details:
Control Number: <strong>{{ $transaction->id }}</strong><br>
Check in date: <strong>{{ date('M d, Y h:i a', strtotime($transaction->checkIn_at)) }}</strong> <br>
Check out date: <strong>{{ date('M d, Y h:i a', strtotime($transaction->checkOut_at)) }}</strong>

@php
$type = '';
if($transaction->cottage) {
    $type = 'Cottage: '.$transaction->cottage->name;
} elseif($transaction->room) {
    $type = 'Room: '.$transaction->room->name;
} else {
    $type = 'Exclusive Rental';
}   
@endphp

@component('mail::table')
| Item          | Quantity      | Unit Price | Total Price |
| :------------- |:-------------:|:-------------:| --------:|
@if ($transaction->cottage)
| Cottage: {{ $transaction->cottage->name }} | 1 | P{{ number_format($transaction->type != 'day' ? $transaction->cottage->nightPrice : $transaction->cottage->price, 2) }} | P<span class="totalprice">{{  number_format($transaction->type != 'day' ? $transaction->cottage->nightPrice : $transaction->cottage->price, 2) }}</span> |
@endif
@if ($transaction->room)
| Room: {{ $transaction->room->name }} | 1 |P{{ number_format($transaction->room->price, 2) }} | P<span class="totalprice">{{  number_format($transaction->room->price, 2) }}</span>|
@endif
@if ($transaction->is_exclusive == 1)
| Exclusive Rental | 1 | P{{ number_format($transaction->rentBill, 2) }} | P<span class="totalprice">{{ number_format($transaction->rentBill, 2) }}</span>|
@else
@if ($transaction->cottage || $transaction->room->entrancefee == 'Exclusive')
@if ($transaction->adults)
| Adults | {{ $transaction->adults }} | @foreach ($entranceFees as $entrancefee) @if ($entrancefee->title == 'Adults') P{{ number_format($transaction->type != 'day' ? $entrancefee->nightPrice : $entrancefee->price, 2) }} | P<span class="totalprice">{{ number_format($transaction->adults * ($transaction->type != 'day' ? $entrancefee->nightPrice : $entrancefee->price), 2) }}</span> @endif @endforeach |
@endif
@if ($transaction->kids)
| Kids | {{ $transaction->kids }} | @foreach ($entranceFees as $entrancefee) @if ($entrancefee->title == 'Kids') P{{ number_format($transaction->type != 'day' ? $entrancefee->nightPrice : $entrancefee->price, 2) }} | P<span class="totalprice">{{ number_format($transaction->kids * ($transaction->type != 'day' ? $entrancefee->nightPrice : $entrancefee->price), 2) }}</span> @endif @endforeach |
@endif
@if ($transaction->senior)
| Senior Citizen | {{ $transaction->senior }} | @foreach ($entranceFees as $entrancefee) @if ($entrancefee->title == 'Senior Citizen') P{{ number_format($transaction->type != 'day' ? $entrancefee->nightPrice : $entrancefee->price, 2) }} | P<span class="totalprice">{{ number_format($transaction->senior * ($transaction->type != 'day' ? $entrancefee->nightPrice : $entrancefee->price), 2) }}</span> @endif @endforeach |
@endif
@endif
@endif
@if ($transaction->extraPerson && $transaction->room)
| Extra Person | {{ $transaction->extraPerson }} | P{{ number_format($transaction->room->extraPerson, 2) }} | P<span class="totalprice">{{  number_format($transaction->room->extraPerson*$transaction->extraPerson, 2) }}</span>|
@elseif($transaction->extraPerson && $transaction->is_exclusive)
| Extra Person | {{ $transaction->extraPerson }} | P{{ number_format(($transaction->type == 'day' ? 200 : 250), 2) }} | P<span class="totalprice">{{  number_format($transaction->extraPersonTotal, 2) }}</span>|
@endif
@if ($transaction->room_id && $transaction->type == 'overnight')
| Breakfast Add ons: |  |  | |
@foreach ($transaction->breakfasts as $breakfast)
| {{ $breakfast->title }} | 1 | P{{ number_format($breakfast->price, 2) }} | P<span class="totalprice">{{ number_format($breakfast->price, 2) }}</span>|
@endforeach
@endif
|     |  | Total Bill:   | P{{ number_format($transaction->totalBill, 2) }} |
@endcomponent
@component('mail::button', ['url' => url('/guest-transaction/'.$transaction->controlCode)])
View Reservation
@endcomponent

If you need to make changes or require assistance please call {{ $resort->phone }} or email us at {{ $resort->email }}. <br>

We look forward to welcoming you in Saka Resort soon!

Thanks,<br>
{{ config('app.name') }}

@component('mail::subcopy')
If you’re having trouble clicking the "View Reservation" button, copy and paste the URL below into your web browser: {{ url('/guest-transaction/'.$transaction->controlCode) }}
@endcomponent
@endcomponent
