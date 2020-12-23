<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cottage;
use App\Models\Room;
use App\Models\Entrancefee;
use App\Models\Breakfast;
use App\Models\Client;
use App\Models\Transaction;

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

        $client = Client::whereContact($request->contactNumber)->first();
        if(empty($client)) {
            $client = Client::create([
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
        $transaction->client_id = $client->id;
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

        return redirect()->back();
    }
}
