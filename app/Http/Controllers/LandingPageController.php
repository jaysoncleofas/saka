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
        $data['room'] = Room::findOrFail($id);
        return view('landing.roomshow', $data);
    }

    public function cottage_show($id)
    {
        $data['cottage'] = Cottage::findOrFail($id);
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
