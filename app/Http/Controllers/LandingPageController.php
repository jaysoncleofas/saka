<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cottage;
use App\Models\Room;
use App\Models\Entrancefee;
use App\Models\Breakfast;
use App\Models\Guest;
use App\Models\Transaction;
use App\Models\Resort;
use DB;
use Carbon\Carbon;
use App\Notifications\ReservationSent;
use App\Rules\ReCaptchaRule;

class LandingPageController extends Controller
{
    public function index()
    {
        $data['rooms'] = Room::all();
        $data['cottages'] = Cottage::all();
        return view('landing.index', $data);
    }

    public function about()
    {
        return view('landing.about');
    }

    public function rooms()
    {
        $data['rooms'] = Room::all();
        return view('landing.rooms', $data);
    }

    public function cottages()
    {
        $data['cottages'] = Cottage::all();
        return view('landing.cottages', $data);
    }

    public function contact()
    {
        $data['cottages'] = Cottage::all();
        $data['rooms'] = Room::all();
        $data['entranceFees'] = Entrancefee::all();
        $data['breakfasts'] = Breakfast::all();
        return view('landing.contact', $data);
    }

    public function room_show($id)
    {
        $data['entranceFees'] = Entrancefee::all();
        $data['breakfasts'] = Breakfast::all();
        $data['room'] = Room::findOrFail($id);
        return view('landing.roomshow', $data);
    }

    public function room_reservation_store(Request $request, $id)
    {
        $request->validate([
            'firstName' => 'required',
            'lastName' => 'required',
            'contactNumber' => 'required',
            'email' => 'required',
            'address' => 'required',
            'checkin' => 'required',
            'adults' => 'required|numeric',
            'kids' => 'required|numeric',
            'senior_citizen' => 'required|numeric',
            'type' => 'required'
        ]);

        $room = Room::findOrFail($id);
        $totalpax = $request->adults + $request->kids + $request->senior_citizen;
        if($room->max < $totalpax) {
            session()->flash('notification', 'The Maximum capacity for this room is '.$room->max.'pax');
            session()->flash('type', 'error');
            return redirect()->back()->withInput($request->input());
        }
        $checkIn_at = Carbon::parse($request->checkin);
        if($request->type == 'night') {
            $checkin = Carbon::parse($request->checkin)->setHour(17);  
            $checkout = Carbon::parse($request->checkin)->setHour(21);  
        } else {
            $checkin = Carbon::parse($request->checkin)->setHour(14);  
            $checkout = Carbon::parse($request->checkin)->addDay(1)->setHour(11);  
        }

        $is_reserved = Transaction::where('room_id', $room->id)->whereDate('checkIn_at', '=', $checkIn_at)->first();
        if($is_reserved) {
            session()->flash('notification', 'Sorry, the room was already reserved.');
            session()->flash('type', 'error');
            return redirect()->back()->withInput($request->input());
        }

        $guest = Guest::whereContact($request->contactNumber)->first();
        if(empty($guest)) {
            $guest = Guest::create([
                'firstName' => $request->firstName,
                'lastName' => $request->lastName,
                'contact' => $request->contactNumber,
                'email' => $request->email,
                'address' => $request->address,
            ]);
        } else {
            $guest->update([
                'firstName' => $request->firstName,
                'lastName' => $request->lastName,
                'email' => $request->email,
                'address' => $request->address,
            ]);
        }

        $extraPerson = null;
        $entranceFees = Entrancefee::all();
        $adultfees = 0;
        $kidfees = 0;
        $seniorfees = 0;
        foreach($entranceFees as $fee) {
            if($fee->title == 'Adults') {
                $adultfees = $request->adults * $fee->nightPrice;
            } elseif ($fee->title == 'Kids') {
                $kidfees = $request->kids * $fee->nightPrice;
            } elseif ($fee->title == 'Senior Citizen') {
                $seniorfees = $request->senior_citizen * $fee->nightPrice;
            }
        }
        $totalEntranceFee = $adultfees + $kidfees + $seniorfees;

        $rentBill = 0;
        $extraPersonTotal = 0;
        if(!empty($room->min) && $totalpax > $room->min) {
            $extraPerson = $totalpax - $room->min;
            $extraPersonTotal = $extraPerson * $room->extraPerson;
        }
        if($room->entrancefee == 'Inclusive') {
            $totalEntranceFee = 0;
        }
        $rentBill = $room->price;

        $resort = Resort::findOrFail(1);
        $breakfastfees = 0;
        $isbreakfast = $request->type == 'night' ? 0 : $request->isbreakfast;
        if($request->isbreakfast) {
            $breakfastfees = $resort->is_promo ? 0 : $resort->breakfastPrice;
            if($isbreakfast) {
                foreach($request->breakfast as $breakfast_id) {
                    $breakfastP = Breakfast::findOrfail($breakfast_id);
                    $breakfastfees = $breakfastfees + $breakfastP->price;
                }
            }
        }

        $totalBill = $totalEntranceFee + $extraPersonTotal + $breakfastfees + $rentBill;

        do {
            $length = 64;
            $keyspace = '123456789abcdefghijkmnopqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ';
            $controlCode = '';
            $max = mb_strlen($keyspace, '8bit') - 1;
            for ($i = 0; $i < $length; ++$i) {
                $controlCode .= $keyspace[random_int(0, $max)];
            }
            $check_controlCode = Transaction::where('controlCode', $controlCode)->first();
        } while($check_controlCode = false);

        $transaction = new Transaction();
        $transaction->guest_id = $guest->id;
        $transaction->room_id = $room->id;
        $transaction->cottage_id = null;
        $transaction->checkIn_at = $checkin;
        $transaction->checkOut_at = $checkout;
        $transaction->adults = $request->adults;
        $transaction->kids = $request->kids;
        $transaction->senior = $request->senior_citizen;
        $transaction->type = $request->type;
        $transaction->is_breakfast = $isbreakfast;
        $transaction->is_freebreakfast = $resort->is_promo == 1 ? 1 : 0;
        $transaction->status = 'pending';
        $transaction->notes = null;
        $transaction->is_reservation = 1;
        $transaction->extraPerson = $extraPerson;
        $transaction->extraPersonTotal = $extraPersonTotal;
        $transaction->totalEntranceFee = $totalEntranceFee;
        $transaction->breakfastfees = $breakfastfees;
        $transaction->rentBill = $rentBill;
        $transaction->totalBill = $totalBill;
        $transaction->controlCode = $controlCode;
        $transaction->save();

        if($isbreakfast == 1) {
            $transaction->breakfasts()->sync($request->breakfast);
        }
    
        // $guest->notify(new ReservationSent($transaction));
        $msg = "Thank you for checking us out. We are reviewing your reservation: control#".$transaction->id.". We sent the reservation link to your email.";
        // $smsResult = \App\Helpers\CustomSMS::send($guest->contact, $msg);
        session()->flash('type', 'success');
        session()->flash('notification', 'Resevation was sent successfully. Please wait for the approval of your reservation.');
        return redirect()->route('landing.transaction_show', $controlCode);    
    }

    public function cottage_reservation_store(Request $request, $id)
    {
        $request->validate([
            'firstName' => 'required',
            'lastName' => 'required',
            'contactNumber' => 'required',
            'email' => 'required',
            'address' => 'required',
            'checkin' => 'required',
            'adults' => 'required|numeric',
            'kids' => 'required|numeric',
            'senior_citizen' => 'required|numeric',
            'type' => 'required'
        ]);

        $cottage = Cottage::findOrFail($id);
        $checkIn_at = Carbon::parse($request->checkin);
        if($request->type == 'night') {
            $checkin = Carbon::parse($request->checkin)->setHour(17);  
            $checkout = Carbon::parse($request->checkin)->setHour(21);  
        } else {
            $checkin = Carbon::parse($request->checkin)->setHour(9);  
            $checkout = Carbon::parse($request->checkin)->setHour(17);  
        }

        $is_reserved = Transaction::where('cottage_id', $cottage->id)->whereDate('checkIn_at', '=', $checkIn_at)->where('type', $request->type)->count();
        if($is_reserved >= $cottage->units) {
            session()->flash('notification', 'Sorry, the cottage was already reserved.');
            session()->flash('type', 'error');
            return redirect()->back()->withInput($request->input());
        }

        $guest = Guest::whereContact($request->contactNumber)->first();
        if(empty($guest)) {
            $guest = Guest::create([
                'firstName' => $request->firstName,
                'lastName' => $request->lastName,
                'contact' => $request->contactNumber,
                'email' => $request->email,
                'address' => $request->address,
            ]);
        } else {
            $guest->update([
                'firstName' => $request->firstName,
                'lastName' => $request->lastName,
                'email' => $request->email,
                'address' => $request->address,
            ]);
        }

        $entranceFees = Entrancefee::all();
        $adultfees = 0;
        $kidfees = 0;
        $seniorfees = 0;
        foreach($entranceFees as $fee) {
            if($fee->title == 'Adults') {
                $adultfees = $request->adults * $fee->price;
            } elseif ($fee->title == 'Kids') {
                $kidfees = $request->kids * $fee->price;
            } elseif ($fee->title == 'Senior Citizen') {
                $seniorfees = $request->senior_citizen * $fee->price;
            }
        }
        $totalEntranceFee = $adultfees + $kidfees + $seniorfees;
        $rentBill = $request->type == 'day' ? $cottage->price : $cottage->nightPrice;
        $totalBill = $totalEntranceFee + $rentBill;

        do {
            $length = 64;
            $keyspace = '123456789abcdefghijkmnopqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ';
            $controlCode = '';
            $max = mb_strlen($keyspace, '8bit') - 1;
            for ($i = 0; $i < $length; ++$i) {
                $controlCode .= $keyspace[random_int(0, $max)];
            }
            $check_controlCode = Transaction::where('controlCode', $controlCode)->first();
        } while($check_controlCode = false);

        $transaction = new Transaction();
        $transaction->guest_id = $guest->id;
        $transaction->room_id = null;
        $transaction->cottage_id = $cottage->id;
        $transaction->checkIn_at = $checkin;
        $transaction->checkOut_at = $checkout;
        $transaction->adults = $request->adults;
        $transaction->kids = $request->kids;
        $transaction->senior = $request->senior_citizen;
        $transaction->type = $request->type;
        $transaction->is_breakfast = 0;
        $transaction->is_freebreakfast = 0;
        $transaction->status = 'pending';
        $transaction->notes = null;
        $transaction->is_reservation = 1;
        $transaction->extraPerson = null;
        $transaction->extraPersonTotal = 0;
        $transaction->totalEntranceFee = $totalEntranceFee;
        $transaction->breakfastfees = 0;
        $transaction->rentBill = $rentBill;
        $transaction->totalBill = $totalBill;
        $transaction->controlCode = $controlCode;
        $transaction->save();

        // $guest->notify(new ReservationSent($transaction));
        $msg = "Thank you for checking us out. We are reviewing your reservation: control#".$transaction->id.". We sent the reservation link to your email.";
        // $smsResult = \App\Helpers\CustomSMS::send($guest->contact, $msg);
        session()->flash('type', 'success');
        session()->flash('notification', 'Resevation was sent successfully. Please wait for the approval of your reservation.');
        return redirect()->route('landing.transaction_show', $controlCode);    
    }

    public function exclusive_rental_store(Request $request)
    {
        // return $request->all();
        $request->validate([
            'firstName' => 'required',
            'lastName' => 'required',
            'contactNumber' => 'required',
            'email' => 'required',
            'address' => 'required',
            'checkin' => 'required',
            'adults' => 'required|numeric',
            'kids' => 'required|numeric',
            'senior_citizen' => 'required|numeric',
            'type' => 'required'
        ]);

        $checkIn_at = Carbon::parse($request->checkin);
        if($request->type == 'overnight') {
            $checkin = Carbon::parse($request->checkin)->setHour(9);  
            $checkout = Carbon::parse($request->checkin)->addDays(1)->setHour(11);   
            $is_reserved = Transaction::whereDate('checkIn_at', $checkIn_at)->whereIn('type', [$request->type, 'night'])->first();
        } else {
            $checkin = Carbon::parse($request->checkin)->setHour(9);  
            $checkout = Carbon::parse($request->checkin)->setHour(17);
            $is_reserved = Transaction::whereDate('checkIn_at', $checkIn_at)->where('type', $request->type)->first();
        }

        if($is_reserved) {
            session()->flash('notification', 'Sorry, the date is not available.');
            session()->flash('type', 'error');
            return redirect()->back()->withInput($request->input());
        }

        $guest = Guest::whereContact($request->contactNumber)->first();
        if(empty($guest)) {
            $guest = Guest::create([
                'firstName' => $request->firstName,
                'lastName' => $request->lastName,
                'contact' => $request->contactNumber,
                'email' => $request->email,
                'address' => $request->address,
            ]);
        } else {
            $guest->update([
                'firstName' => $request->firstName,
                'lastName' => $request->lastName,
                'email' => $request->email,
                'address' => $request->address,
            ]);
        }
        $extraPerson = null;
        $totalpax = $request->adults + $request->kids + $request->senior_citizen;
        $maxpax = $request->type == 'day' ? 60 : 30;
        $extraPersonTotal = 0;
        if($totalpax > $maxpax) {
            $extraPerson = $totalpax - $maxpax;
            $extraPersonTotal = $extraPerson * ($request->type == 'day' ? 200 : 250);
        }
        $rentBill = $request->type == 'day' ? 15000 : 25000;
        $totalBill = $extraPersonTotal + $rentBill;

        do {
            $length = 64;
            $keyspace = '123456789abcdefghijkmnopqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ';
            $controlCode = '';
            $max = mb_strlen($keyspace, '8bit') - 1;
            for ($i = 0; $i < $length; ++$i) {
                $controlCode .= $keyspace[random_int(0, $max)];
            }
            $check_controlCode = Transaction::where('controlCode', $controlCode)->first();
        } while($check_controlCode = false);

        $transaction = new Transaction();
        $transaction->guest_id = $guest->id;
        $transaction->room_id = null;
        $transaction->cottage_id = null;
        $transaction->is_exclusive = 1;
        $transaction->checkIn_at = $checkin;
        $transaction->checkOut_at = $checkout;
        $transaction->adults = $request->adults;
        $transaction->kids = $request->kids;
        $transaction->senior = $request->senior_citizen;
        $transaction->type = $request->type;
        $transaction->is_breakfast = 0;
        $transaction->is_freebreakfast = 0;
        $transaction->status = 'pending';
        $transaction->notes = null;
        $transaction->is_reservation = 1;
        $transaction->extraPerson = $extraPerson;
        $transaction->extraPersonTotal = $extraPersonTotal;
        $transaction->totalEntranceFee = $extraPersonTotal;
        $transaction->breakfastfees = 0;
        $transaction->rentBill = $rentBill;
        $transaction->totalBill = $totalBill;
        $transaction->controlCode = $controlCode;
        $transaction->save();

        // $guest->notify(new ReservationSent($transaction));
        $msg = "Thank you for checking us out. We are reviewing your reservation: control#".$transaction->id.". We sent the reservation link to your email.";
        // $smsResult = \App\Helpers\CustomSMS::send($guest->contact, $msg);
        session()->flash('type', 'success');
        session()->flash('notification', 'Resevation was sent successfully. Please wait for the approval of your reservation.');
        return redirect()->route('landing.transaction_show', $controlCode);    
    }

    public function transaction_show($code) 
    {
        $data['transaction'] = Transaction::where('controlCode', $code)->first();
        if($data['transaction']) {
            $data['entranceFees'] = Entrancefee::all();
            return view('landing.transaction_show', $data);
        }
        return abort(404);
    }

    public function cottage_show($id)
    {
        $data['cottage'] = Cottage::findOrFail($id);
        $data['entranceFees'] = Entrancefee::all();
        $data['breakfasts'] = Breakfast::all();
        return view('landing.cottageshow', $data);
    }

    public function room_book($id)
    {
        $data['room'] = Room::findOrFail($id);
        $data['cottages'] = Cottage::all();
        $data['rooms'] = Room::all();
        $data['entranceFees'] = Entrancefee::all();
        $data['breakfasts'] = Breakfast::where('is_active', 1)->get();
        return view('landing.roombook', $data);
    }

    public function room_available(Request $request)
    {
  
        $checkin = Carbon::parse($request->checkin); 
        $checkout = Carbon::parse($request->checkout); 
        $slot = Transaction::where('cottage_id', null)->whereBetween('checkIn_at', [$checkin, $checkout])->orWhereBetween('checkOut_at', [$checkin, $checkout])->whereNot('status', 'cancelled')->pluck('id')->toArray();

        if(!empty($slot)) {
            $rooms = Room::whereNotIn('id', $slot)->get();
            return response()->json(['rooms' => $rooms], 200);
        }
        $rooms = Room::all();
        return response()->json(['rooms' => $rooms ], 200);
    }

    public function getrooms_available($id)
    {
        $room = Room::findOrFail($id);
        $day3 = Carbon::now()->addDays(3);
        $slot = Transaction::where('room_id', $room->id)->whereDate('checkIn_at', '>=', $day3)->whereNot('status', 'cancelled')->pluck('checkIn_at')->toArray();
        return response()->json(['dates' => $slot ], 200);
    }

    public function getcottages_available(Request $request, $id)
    {
        $cottage = Cottage::findOrFail($id);
        $checkin = Carbon::parse($request->checkin); 

        $slot = Transaction::where('cottage_id', $cottage->id)->whereDate('checkIn_at', $checkin)->whereNot('status', 'cancelled')->pluck('checkIn_at')->toArray();

        $maxreservation = $cottage->units * 2;
        if(count($slot) >= $maxreservation) {
            return response()->json(['status' => 'not available' ], 200);
        } else {
            return response()->json(['status' => 'available' ], 200);
        }
    }

    public function check_cottage_available(Request $request, $id)
    {
        $cottage = Cottage::findOrFail($id);
        $checkin = Carbon::parse($request->checkin);         

        $slot = Transaction::where('cottage_id', $cottage->id)->whereDate('checkIn_at', $checkin)->where('type', $request->usetype)->whereNot('status', 'cancelled')->count();

        if($slot >= $cottage->units) {
            $test = $cottage->units;
            return response()->json(['status' => 'not available', 'unit' => $test], 200);
        } else {
            $test = $cottage->units - $slot;
            return response()->json(['status' => $test.' units available', 'unit' => $test], 200);
        }
    }
    

    public function cottage_available(Request $request)
    {
  
        $checkin = Carbon::parse($request->checkin); 
        $checkout = Carbon::parse($request->checkout); 
        $slot = Transaction::where('room_id', null)->whereBetween('checkIn_at', [$checkin, $checkout])->orWhereBetween('checkOut_at', [$checkin, $checkout])->whereNot('status', 'cancelled')->pluck('id')->toArray();

        if(!empty($slot)) {
            $cottages = Cottage::whereNotIn('id', $slot)->get();
            return response()->json(['cottages' => $cottages], 200);
        }
        $cottages = Cottage::all();
        return response()->json(['cottages' => $cottages ], 200);
    }

    public function available_rooms(Request $request, $id)
    {
        $room = Room::findOrFail($id);

        $checkin = Carbon::parse($request->checkin)->startOfDay(); 
        $checkout = Carbon::parse($request->checkin)->endOfDay();

        $checkout = Carbon::parse($request->checkout); 
        $slot = Transaction::where('cottage_id', null)->whereBetween('checkIn_at', [$checkin, $checkout])->orWhereBetween('checkOut_at', [$checkin, $checkout])->whereNot('status', 'cancelled')->pluck('id')->toArray();

        if(!empty($slot)) {
            $rooms = Room::whereNotIn('id', $slot)->get();
            return response()->json(['rooms' => $rooms], 200);
        }
        $rooms = Room::all();
        return response()->json(['rooms' => $rooms ], 200);
    }

    public function exclusive_rental()
    {
        return view('landing.exclusiverental');
    }

    public function getexclusive_available(Request $request)
    {
        $checkin = Carbon::parse($request->checkin); 
        if($request->tranid){
            $slot = Transaction::whereDate('checkIn_at', $checkin)->where('id', '!=',$request->tranid)->whereNot('status', 'cancelled')->get();
        } else {
            $slot = Transaction::whereDate('checkIn_at', $checkin)->whereNot('status', 'cancelled')->get();
        }
        $day_available = true;
        $overnight_available = true;
        foreach($slot as $item) {
            if($item->type == 'day') {
                $day_available = false;
            } elseif ($item->type == 'night' || $item->type == 'overnight') {
                $overnight_available = false;
            }
        }
        $available = [];
        if($day_available) {
            $available[] = 'day';
        } 
        if($overnight_available) {
            $available[] = 'overnight';
        }
 
        return response()->json(['status' => $available ], 200);
    }
}
