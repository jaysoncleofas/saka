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

    public function reservation_store(Request $request)
    {
        $request->validate([
            'firstName' => 'required',
            'lastName' => 'required',
            'contactNumber' => 'required',
            'checkin' => 'required',
            'checkout' => 'nullable',
            'Adults' => 'required|numeric',
            'Kids' => 'required|numeric',
            'Senior_Citizen' => 'required|numeric',
            'type' => 'required',
            'isbreakfast' => 'required',
            'cottage' => 'required_without:room',
            'room' => 'required_without:cottage',
            'notes' => 'nullable',
            'is_reservation' => 'nullable'
        ]);

        $guest = Guest::whereContact($request->contactNumber)->first();
        if(empty($guest)) {
            $guest = Guest::create([
                'firstName' => $request->firstName,
                'lastName' => $request->lastName,
                'contact' => $request->contactNumber,
            ]);
        }

        $extraPerson = null;
        $entranceFees = Entrancefee::all();
        $adultfees = 0;
        $kidfees = 0;
        $seniorfees = 0;
        foreach($entranceFees as $fee) {
            if($fee->title == 'Adults') {
                $adultfees = $request->Adults*($request->type != 'day' ? $fee->nightPrice : $fee->price);
            } elseif ($fee->title == 'Kids') {
                $kidfees = $request->Kids*($request->type != 'day' ? $fee->nightPrice : $fee->price);
            } elseif ($fee->title == 'Senior Citizen') {
                $seniorfees = $request->Senior_Citizen*($request->type != 'day' ? $fee->nightPrice : $fee->price);
            }
        }
        $totalEntranceFee = $adultfees + $kidfees + $seniorfees;

        $rentBill = 0;
        $extraPersonTotal = 0;
        if($request->room) {
            $room = Room::findOrFail($request->room);
            $extraPerson = $request->input('extraPerson'.$room->id);
            $extraPersonTotal = $extraPerson*$room->extraPerson;
            if($room->entrancefee == 'Inclusive') {
                $totalEntranceFee = 0;
            }
            $rentBill = $room->price;
        }

        if($request->cottage) {
            $cottage = Cottage::findOrFail($request->cottage);
            $rentBill = ($request->type != 'day' ? $cottage->nightPrice : $cottage->price);
        }

        $breakfastfees = 0;
        if($request->isbreakfast) {
            $resort = Resort::findOrFail(1);
            $breakfastfees = $resort->breakfastPrice;
            foreach($request->breakfast as $breakfast_id) {
                $breakfastP = Breakfast::findOrfail($breakfast_id);
                $breakfastfees = $breakfastfees + $breakfastP->price;
            }
        }

        $totalBill = $totalEntranceFee + $extraPersonTotal + $breakfastfees + $rentBill;

        $transaction = new Transaction();
        $transaction->guest_id = $guest->id;
        $transaction->room_id = $request->room;
        $transaction->cottage_id = $request->cottage;
        $transaction->checkIn_at = $request->checkin;
        $transaction->checkOut_at = $request->checkout;
        $transaction->adults = $request->Adults;
        $transaction->kids = $request->Kids;
        $transaction->senior = $request->Senior_Citizen;
        $transaction->type = $request->type;
        $transaction->is_breakfast = $request->isbreakfast;
        $transaction->status = $request->is_reservation ? 'pending' : 'active';
        $transaction->notes = $request->notes;
        $transaction->is_reservation = $request->is_reservation ?? 0;
        $transaction->extraPerson = $extraPerson;
        $transaction->extraPersonTotal = $extraPersonTotal;
        $transaction->totalEntranceFee = $totalEntranceFee;
        $transaction->breakfastfees = $breakfastfees;
        $transaction->rentBill = $rentBill;
        $transaction->totalBill = $totalBill;
        $transaction->save();

        $transaction->breakfasts()->sync($request->breakfast);
        
        session()->flash('notification', 'Your reservation has been sent, expect a call to confirm your reservation.');
        session()->flash('type', 'success');

        if($request->is_guest) {
            session()->flash('notification', 'Successfully added!');
            return redirect()->route('transaction.guest_show', $transaction->id);    
        }
        return redirect()->back();
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
            'checkin' => 'required',
            'adults' => 'required|numeric',
            'kids' => 'required|numeric',
            'senior_citizen' => 'required|numeric',
            'type' => 'required',
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

        session()->flash('type', 'success');
        session()->flash('notification', 'Resevation was sent successfully. Please wait for the approval of your reservation.');
        return redirect()->route('landing.transaction_show', $controlCode);    
        // }
        // return redirect()->back();
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
        $slot = Transaction::where('cottage_id', null)->whereBetween('checkIn_at', [$checkin, $checkout])->orWhereBetween('checkOut_at', [$checkin, $checkout])->pluck('id')->toArray();

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
        // $checkin = Carbon::parse($request->checkin); 
        // $checkout = Carbon::parse($request->checkout); 
        // ->whereDate('date', '>=', Carbon::now('Europe/Stockholm'))
        $day3 = Carbon::now()->addDays(3);
        $slot = Transaction::where('room_id', $room->id)->whereDate('checkIn_at', '>=', $day3)->pluck('checkIn_at')->toArray();

        // if(!empty($slot)) {
        //     $rooms = Room::whereNotIn('id', $slot)->get();
        //     return response()->json(['rooms' => $rooms], 200);
        // }
        // $rooms = Room::all();
        return response()->json(['dates' => $slot ], 200);
    }
    

    public function cottage_available(Request $request)
    {
  
        $checkin = Carbon::parse($request->checkin); 
        $checkout = Carbon::parse($request->checkout); 
        $slot = Transaction::where('room_id', null)->whereBetween('checkIn_at', [$checkin, $checkout])->orWhereBetween('checkOut_at', [$checkin, $checkout])->pluck('id')->toArray();

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
        $slot = Transaction::where('cottage_id', null)->whereBetween('checkIn_at', [$checkin, $checkout])->orWhereBetween('checkOut_at', [$checkin, $checkout])->pluck('id')->toArray();

        if(!empty($slot)) {
            $rooms = Room::whereNotIn('id', $slot)->get();
            return response()->json(['rooms' => $rooms], 200);
        }
        $rooms = Room::all();
        return response()->json(['rooms' => $rooms ], 200);
    }
}
