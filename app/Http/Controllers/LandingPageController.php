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
// use App\Notifications\ReservationSent;
use App\Rules\ReCaptchaRule;
use App\Models\Payment;
use App\Mail\ReservationSent;
use Illuminate\Support\Facades\Mail;

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
        $data['breakfasts'] = Breakfast::where('is_active', 1)->get();
        return view('landing.contact', $data);
    }

    public function room_show($id)
    {
        $data['entranceFees'] = Entrancefee::all();
        $data['breakfasts'] = Breakfast::where('is_active', 1)->get();
        $data['room'] = Room::findOrFail($id);
        $data['payments'] = Payment::all();
        return view('landing.roomshow', $data);
    }

    public function room_reservation_summary(Request $request, $id)
    {
        $request->validate([
            'firstName' => 'required',
            'lastName' => 'required',
            'contactNumber' => 'required',
            'email' => 'required',
            'address' => 'required',
            'checkin' => 'required',
            'payment' => 'required',
            'adults' => 'required|numeric',
            'kids' => 'required|numeric',
            'senior_citizen' => 'required|numeric',
            'type' => 'required'
        ]);

        $room = Room::findOrFail($id);
        $totalpax = $request->adults + $request->kids + $request->senior_citizen;
        // if($room->max < $totalpax) {
        //     session()->flash('notification', 'The Maximum capacity for this room is '.$room->max.'pax');
        //     return response()->json(['status' => 'error', 'message' => 'The Maximum capacity for this room is '.$room->max.'pax'], 200); 
        // }
        $checkIn_at = Carbon::parse($request->checkin);
        if($request->type == 'day') {
            $checkin = Carbon::parse($request->checkin)->setHour(9);  
            $checkout = Carbon::parse($request->checkin)->setHour(17);  
        } elseif($request->type == 'night') {
            $checkin = Carbon::parse($request->checkin)->setHour(17);  
            $checkout = Carbon::parse($request->checkin)->setHour(21);  
        } else {
            $checkin = Carbon::parse($request->checkin)->setHour(14);  
            $checkout = Carbon::parse($request->checkin)->addDay(1)->setHour(11);  
        }

        $extraPerson = null;
        $entranceFees = Entrancefee::all();
        $adultfees = 0;
        $kidfees = 0;
        $seniorfees = 0;
        foreach($entranceFees as $fee) {
            $fees = 0;
            if($request->type == 'day') {
                $fees = $fee->price;
            } elseif($request->type == 'night') {
                $fees = $fee->nightPrice;
            } else {
                $fees = $fee->overnightPrice;
            }
            if($fee->title == 'Adults') {
                $adultfees = $request->adults * $fees;
            } elseif ($fee->title == 'Kids') {
                $kidfees = $request->kids * $fees;
            } elseif ($fee->title == 'Senior Citizen') {
                $seniorfees = $request->senior_citizen * $fees;
            }
        }
        $totalEntranceFee = $adultfees + $kidfees + $seniorfees;

        $rentBill = 0;
        $extraPersonTotal = 0;
        if($totalpax > $room->max) {
            $extraPerson = $totalpax - $room->max;
            $extraPersonTotal = $extraPerson * $room->extraPerson;
        }
        if($room->entrancefee == 'Inclusive') {
            $totalEntranceFee = 0;
        }
        $rentBill = $room->price;

        $resort = Resort::findOrFail(1);
        $breakfastfees = 0;
        if($request->type == 'overnight') {
            if($request->breakfast) {
                foreach($request->breakfast as $breakfast_id) {
                    $breakfastP = Breakfast::findOrfail($breakfast_id);
                    $breakfastfees = $breakfastfees + $breakfastP->price;
                }
            }
        }

        $totalBill = $totalEntranceFee + $extraPersonTotal + $breakfastfees + $rentBill;
        $payment = Payment::findOrFail($request->payment);

        $html = '';
        $html .='<div class="">
                    <div class="row is-size-15">
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-lg-6 col-md-6">
                                    <strong>Room:</strong> '.$room->name.' <br>
                                    <strong>Use:</strong> '.ucfirst($request->type).' Use<br>
                                    <strong>Check In:</strong> '.date('m/d/Y h:i a', strtotime($checkin)).' <br>
                                    <strong>Check Out:</strong> '.date('m/d/Y h:i a', strtotime($checkout)).' <br>
                                    <strong>Entrance Fee:</strong> '.$room->entrancefee.' <br>
                                    <strong>Payment:</strong> '.$payment->name.' <br>
                                    <strong>Adults:</strong> '.$request->adults.' <br>
                                    <strong>Kids:</strong> '.$request->kids.' <br>
                                    <strong>Senior Citizens:</strong> '.$request->senior_citizen.' <br>
                                </div>
                                <div class="col-lg-6 col-md-6">
                                    <strong>Guest:</strong> '.$request->firstName.' '.$request->lastName.' <br>
                                    <strong>Contact Number:</strong> '.$request->contactNumber.' <br>
                                    <strong>Email:</strong> '.$request->email.' <br>
                                    <strong>Address:</strong> '.$request->address.' <br>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12 mt-5">
                            <strong>Bill Summary</strong>
                            <div class="table-responsive mt-2">
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
                                        <tr>
                                            <td>'.$room->name.'</td>
                                            <td>1</td>
                                            <td>P'.number_format($room->price, 2).'</td>
                                            <td>P<span class="totalprice">'.number_format($room->price, 2).'</span></td>
                                        </tr>';
        if($room->entrancefee == 'Exclusive') {
            foreach ($entranceFees as $entrancefee) {
                if ($entrancefee->title == 'Adults' && $request->adults > 0) {
                $html .='           <tr>
                                        <td>Adults</td>
                                        <td>'.$request->adults.'</td>
                                        <td>';
                                        $fees = 0;
                                        if($request->type == 'day') {
                                            $fees = $entrancefee->price;
                                        } elseif($request->type == 'night') {
                                            $fees = $entrancefee->nightPrice;
                                        } else {
                                            $fees = $entrancefee->overnightPrice;
                                        }
                                        $html .='P'.number_format($fees, 2).'';
                $html .='               </td>
                                        <td>P'.number_format($adultfees, 2).'</td>
                                    </tr>';
                }
                if ($entrancefee->title == 'Kids' && $request->kids > 0) {
                    $html .='           <tr>
                                            <td>Kids</td>
                                            <td>'.$request->kids.'</td>
                                            <td>';
                                            $fees = 0;
                                            if($request->type == 'day') {
                                                $fees = $entrancefee->price;
                                            } elseif($request->type == 'night') {
                                                $fees = $entrancefee->nightPrice;
                                            } else {
                                                $fees = $entrancefee->overnightPrice;
                                            }
                                            $html .='P'.number_format($fees, 2).'';
                    $html .='               </td>
                                            <td>P'.number_format($kidfees, 2).'</td>
                                        </tr>';
                }
                if ($entrancefee->title == 'Senior Citizen' && $request->senior_citizen > 0) {
                    $html .='           <tr>
                                            <td>Senior Citizens</td>
                                            <td>'.$request->senior_citizen.'</td>
                                            <td>';
                                            $fees = 0;
                                            if($request->type == 'day') {
                                                $fees = $entrancefee->price;
                                            } elseif($request->type == 'night') {
                                                $fees = $entrancefee->nightPrice;
                                            } else {
                                                $fees = $entrancefee->overnightPrice;
                                            }
                                            $html .='P'.number_format($fees, 2).'';
                    $html .='               </td>
                                            <td>P'.number_format($seniorfees, 2).'</td>
                                        </tr>';
                }
            }
        }
        if ($extraPerson){
            $html .='    <tr>
                <td>Extra Person</td>
                <td>'. $extraPerson .'</td>
                <td>P'. number_format($room->extraPerson, 2) .'</td>
                <td>P<span class="totalprice">'.  number_format($extraPersonTotal, 2) .'</span></td>
            </tr>';
        }
        if ($request->breakfast && $request->type == 'overnight'){
        $html .='    <tr>
                <td>Breakfast Add ons:</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>';

            foreach ($request->breakfast as $breakfast_id){
                $breakfastP = Breakfast::findOrfail($breakfast_id);
                $html .='<tr>
                    <td>'.$breakfastP->title.'</td>
                    <td>1</td>
                    <td>P'. number_format($breakfastP->price, 2) .'</td>
                    <td>P<span class="totalprice">'. number_format($breakfastP->price, 2) .'</span></td>
                </tr>';
            }
        }
        $html .='                   </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-4 float-md-right ml-auto">
                            <ul class="striped list-unstyled">
                                <li><strong>TOTAL:</strong><span class="float-right">P<span id="total_invoice">'.number_format($totalBill, 2).'</span></span></li>
                            </ul>
                        </div>
                        <div class="col-lg-12">
                            <button type="submit" class="btn btn-lg btn-dark button-primary large w-inline-block radius-zero mt-3 btn-book">Confirm</button>
                            <button type="submit" class="btn btn-lg btn-outline-dark button-secondary large w-inline-block radius-zero mt-3 btn-back">Back</button>
                        </div>
                    </div>
                </div>';
        return response()->json(['data' => $html], 200); 
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
            'payment' => 'required',
            'adults' => 'required|numeric',
            'kids' => 'required|numeric',
            'senior_citizen' => 'required|numeric',
            'type' => 'required'
        ]);

        $room = Room::findOrFail($id);
        $totalpax = $request->adults + $request->kids + $request->senior_citizen;
        // if($room->max < $totalpax) {
        //     session()->flash('notification', 'The Maximum capacity for this room is '.$room->max.'pax');
        //     session()->flash('type', 'error');
        //     return redirect()->back()->withInput($request->input());
        // }
        $checkIn_at = Carbon::parse($request->checkin);
        if($request->type == 'night') {
            $checkin = Carbon::parse($request->checkin)->setHour(17);  
            $checkout = Carbon::parse($request->checkin)->setHour(21);  
        } else {
            $checkin = Carbon::parse($request->checkin)->setHour(14);  
            $checkout = Carbon::parse($request->checkin)->addDay(1)->setHour(11);  
        }

        $is_reserved = Transaction::where('room_id', $room->id)->whereDate('checkIn_at', '=', $checkIn_at)->first();
        // if($is_reserved) {
        //     session()->flash('notification', 'Sorry, the room was already reserved.');
        //     session()->flash('type', 'error');
        //     return redirect()->back()->withInput($request->input());
        // }

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
            $fees = 0;
            if($request->type == 'day') {
                $fees = $fee->price;
            } elseif($request->type == 'night') {
                $fees = $fee->nightPrice;
            } else {
                $fees = $fee->overnightPrice;
            }
            if($fee->title == 'Adults') {
                $adultfees = $request->adults * $fees;
            } elseif ($fee->title == 'Kids') {
                $kidfees = $request->kids * $fees;
            } elseif ($fee->title == 'Senior Citizen') {
                $seniorfees = $request->senior_citizen * $fees;
            }
        }
        $totalEntranceFee = $adultfees + $kidfees + $seniorfees;

        $rentBill = 0;
        $extraPersonTotal = 0;
        if($totalpax > $room->max) {
            $extraPerson = $totalpax - $room->max;
            $extraPersonTotal = $extraPerson * $room->extraPerson;
        }
        if($room->entrancefee == 'Inclusive') {
            $totalEntranceFee = 0;
        }
        $rentBill = $room->price;

        $resort = Resort::findOrFail(1);
        $breakfastfees = 0;
        $isbreakfast = $request->type != 'overnight' ? 0 : $request->isbreakfast;
        $breakfastfees = 0;
        if($request->type == 'overnight') {
            if($request->breakfast) {
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
        $transaction->payment_id = $request->payment;
        $transaction->save();

        if($isbreakfast == 1) {
            $transaction->breakfasts()->sync($request->breakfast);
        }
        
        $resort = Resort::find(1);
        $entranceFees = Entrancefee::all();
        Mail::to($guest)->send(new ReservationSent($transaction, $resort));
        // $guest->notify(new ReservationSent($transaction));
        $msg = "Thank you for checking us out. We are reviewing your reservation: control#".$transaction->id.". We sent the reservation link to your email.";
        $smsResult = \App\Helpers\CustomSMS::send($guest->contact, $msg);
        session()->flash('type', 'success');
        if($transaction->payment_id == 1) {
            session()->flash('notification', 'Resevation was sent successfully.');
        } else {
            session()->flash('notification', 'We have sent an email with instructions for Online bank / GCash deposit. We will message you as soon as we receive your payment.');
        }
        return redirect()->route('landing.transaction_show', $controlCode);    
    }

    public function cottage_reservation_summary(Request $request, $id)
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
            'type' => 'required',
            'payment' => 'required'
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
        // if($is_reserved >= $cottage->units) {
        //     session()->flash('notification', 'Sorry, the cottage was already reserved.');
        //     session()->flash('type', 'error');
        //     return redirect()->back()->withInput($request->input());
        // }

        $entranceFees = Entrancefee::all();
        $adultfees = 0;
        $kidfees = 0;
        $seniorfees = 0;
        foreach($entranceFees as $fee) {
            $fees = 0;
            if($request->type == 'day') {
                $fees = $fee->price;
            } elseif($request->type == 'night') {
                $fees = $fee->nightPrice;
            } else {
                $fees = $fee->overnightPrice;
            }
            if($fee->title == 'Adults') {
                $adultfees = $request->adults * $fees;
            } elseif ($fee->title == 'Kids') {
                $kidfees = $request->kids * $fees;
            } elseif ($fee->title == 'Senior Citizen') {
                $seniorfees = $request->senior_citizen * $fees;
            }
        }
        $totalEntranceFee = $adultfees + $kidfees + $seniorfees;
        $rentBill = $request->type == 'day' ? $cottage->price : $cottage->nightPrice;
        $totalBill = $totalEntranceFee + $rentBill;
        $payment = Payment::findOrFail($request->payment);

        $html = '';
        $html .='<div class="">
                    <div class="row is-size-15">
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-lg-6 col-md-6">
                                    <strong>Cottage:</strong> '.$cottage->name.' <br>
                                    <strong>Use:</strong> '.ucfirst($request->type).' Use<br>
                                    <strong>Check In:</strong> '.date('m/d/Y h:i a', strtotime($checkin)).' <br>
                                    <strong>Check Out:</strong> '.date('m/d/Y h:i a', strtotime($checkout)).' <br>
                                    <strong>Payment:</strong> '.$payment->name.' <br>
                                    <strong>Adults:</strong> '.$request->adults.' <br>
                                    <strong>Kids:</strong> '.$request->kids.' <br>
                                    <strong>Senior Citizens:</strong> '.$request->senior_citizen.' <br>
                                </div>
                                <div class="col-lg-6 col-md-6">
                                    <strong>Guest:</strong> '.$request->firstName.' '.$request->lastName.' <br>
                                    <strong>Contact Number:</strong> '.$request->contactNumber.' <br>
                                    <strong>Email:</strong> '.$request->email.' <br>
                                    <strong>Address:</strong> '.$request->address.' <br>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12 mt-5">
                            <strong>Bill Summary</strong>
                            <div class="table-responsive mt-2">
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
                                        <tr>
                                            <td>'.$cottage->name.'</td>
                                            <td>1</td>
                                            <td>P'.number_format($rentBill, 2).'</td>
                                            <td>P<span class="totalprice">'.number_format($rentBill, 2).'</span></td>
                                        </tr>';
        foreach ($entranceFees as $entrancefee) {
            if ($entrancefee->title == 'Adults' && $request->adults > 0) {
            $html .='           <tr>
                                    <td>Adults</td>
                                    <td>'.$request->adults.'</td>
                                    <td>';
                                    $fees = 0;
                                    if($request->type == 'day') {
                                        $fees = $entrancefee->price;
                                    } elseif($request->type == 'night') {
                                        $fees = $entrancefee->nightPrice;
                                    } else {
                                        $fees = $entrancefee->overnightPrice;
                                    }
                                    $html .='P'.number_format($fees, 2).'';
            $html .='               </td>
                                    <td>P'.number_format($adultfees, 2).'</td>
                                </tr>';
            }
            if ($entrancefee->title == 'Kids' && $request->kids > 0) {
                $html .='           <tr>
                                        <td>Kids</td>
                                        <td>'.$request->kids.'</td>
                                        <td>';
                                        $fees = 0;
                                        if($request->type == 'day') {
                                            $fees = $entrancefee->price;
                                        } elseif($request->type == 'night') {
                                            $fees = $entrancefee->nightPrice;
                                        } else {
                                            $fees = $entrancefee->overnightPrice;
                                        }
                                        $html .='P'.number_format($fees, 2).'';
                $html .='               </td>
                                        <td>P'.number_format($kidfees, 2).'</td>
                                    </tr>';
            }
            if ($entrancefee->title == 'Senior Citizen' && $request->senior_citizen > 0) {
                $html .='           <tr>
                                        <td>Senior Citizens</td>
                                        <td>'.$request->senior_citizen.'</td>
                                        <td>';
                                        $fees = 0;
                                        if($request->type == 'day') {
                                            $fees = $entrancefee->price;
                                        } elseif($request->type == 'night') {
                                            $fees = $entrancefee->nightPrice;
                                        } else {
                                            $fees = $entrancefee->overnightPrice;
                                        }
                                        $html .='P'.number_format($fees, 2).'';
                $html .='               </td>
                                        <td>P'.number_format($seniorfees, 2).'</td>
                                    </tr>';
            }
        }
        $html .='                   </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-4 float-md-right ml-auto">
                            <ul class="striped list-unstyled">
                                <li><strong>TOTAL:</strong><span class="float-right">P<span id="total_invoice">'.number_format($totalBill, 2).'</span></span></li>
                            </ul>
                        </div>
                        <div class="col-lg-12">
                            <button type="submit" class="btn btn-lg btn-dark button-primary large w-inline-block radius-zero mt-3 btn-book">Confirm</button>
                            <button type="submit" class="btn btn-lg btn-outline-dark button-secondary large w-inline-block radius-zero mt-3 btn-back">Back</button>
                        </div>
                    </div>
                </div>';
        return response()->json(['data' => $html], 200);    
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
            'type' => 'required',
            'payment' => 'required'
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
            $fees = 0;
            if($request->type == 'day') {
                $fees = $fee->price;
            } elseif($request->type == 'night') {
                $fees = $fee->nightPrice;
            } else {
                $fees = $fee->overnightPrice;
            }
            if($fee->title == 'Adults') {
                $adultfees = $request->adults * $fees;
            } elseif ($fee->title == 'Kids') {
                $kidfees = $request->kids * $fees;
            } elseif ($fee->title == 'Senior Citizen') {
                $seniorfees = $request->senior_citizen * $fees;
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
        $transaction->payment_id = $request->payment;
        $transaction->save();

        $resort = Resort::find(1);
        $entranceFees = Entrancefee::all();
        Mail::to($guest)->send(new ReservationSent($transaction, $resort));
        // $guest->notify(new ReservationSent($transaction));
        $msg = "Thank you for checking us out. We are reviewing your reservation: control#".$transaction->id.". We sent the reservation link to your email.";
        $smsResult = \App\Helpers\CustomSMS::send($guest->contact, $msg);
        session()->flash('type', 'success');
        if($transaction->payment_id == 1) {
            session()->flash('notification', 'Resevation was sent successfully.');
        } else {
            session()->flash('notification', 'We have sent an email with instructions for Online bank / GCash deposit. We will message you as soon as we receive your payment.');
        }
        return redirect()->route('landing.transaction_show', $controlCode);    
    }

    public function exclusive_rental_summary(Request $request)
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
            'type' => 'required',
            'payment' => 'required'
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

        // if($is_reserved) {
        //     session()->flash('notification', 'Sorry, the date is not available.');
        //     session()->flash('type', 'error');
        //     return redirect()->back()->withInput($request->input());
        // }
        $resort = Resort::find(1);
        $extraPerson = null;
        $totalpax = $request->adults + $request->kids + $request->senior_citizen;
        $maxpax = $request->type == 'day' ? $resort->exclusive_daycapacity : $resort->exclusive_overnightcapacity;
        $extraPersonTotal = 0;
        if($totalpax > $maxpax) {
            $extraPerson = $totalpax - $maxpax;
            $extraPersonTotal = $extraPerson * ($request->type == 'day' ? $resort->exclusive_day_extra : $resort->exclusive_overnight_extra);
        }
        $rentBill = $request->type == 'day' ? $resort->exclusive_dayprice : $resort->exclusive_overnightprice;
        $totalBill = $extraPersonTotal + $rentBill;
        $payment = Payment::findOrFail($request->payment);

        $html = '';
        $html .='<div class="">
                    <div class="row is-size-15">
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-lg-6 col-md-6">
                                    <strong>Rental:</strong> Exclusive <br>
                                    <strong>Use:</strong> '.ucfirst($request->type).' Use<br>
                                    <strong>Check In:</strong> '.date('m/d/Y h:i a', strtotime($checkin)).' <br>
                                    <strong>Check Out:</strong> '.date('m/d/Y h:i a', strtotime($checkout)).' <br>
                                    <strong>Payment:</strong> '.$payment->name.' <br>
                                    <strong>Adults:</strong> '.$request->adults.' <br>
                                    <strong>Kids:</strong> '.$request->kids.' <br>
                                    <strong>Senior Citizens:</strong> '.$request->senior_citizen.' <br>
                                </div>
                                <div class="col-lg-6 col-md-6">
                                    <strong>Guest:</strong> '.$request->firstName.' '.$request->lastName.' <br>
                                    <strong>Contact Number:</strong> '.$request->contactNumber.' <br>
                                    <strong>Email:</strong> '.$request->email.' <br>
                                    <strong>Address:</strong> '.$request->address.' <br>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12 mt-5">
                            <strong>Bill Summary</strong>
                            <div class="table-responsive mt-2">
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
                                        <tr>
                                            <td>Exclusive Rental</td>
                                            <td>1</td>
                                            <td>P'.number_format($rentBill, 2).'</td>
                                            <td>P<span class="totalprice">'.number_format($rentBill, 2).'</span></td>
                                        </tr>';
                                        if ($extraPerson){
                                            $html .='    <tr>
                                                <td>Extra Person</td>
                                                <td>'. $extraPerson .'</td>
                                                <td>P'. number_format(($request->type == 'day' ? 200 : 250), 2) .'</td>
                                                <td>P<span class="totalprice">'.  number_format($extraPersonTotal, 2) .'</span></td>
                                            </tr>';
                                        }
        $html .='                   </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-4 float-md-right ml-auto">
                            <ul class="striped list-unstyled">
                                <li><strong>TOTAL:</strong><span class="float-right">P<span id="total_invoice">'.number_format($totalBill, 2).'</span></span></li>
                            </ul>
                        </div>
                        <div class="col-lg-12">
                            <button type="submit" class="btn btn-lg btn-dark button-primary large w-inline-block radius-zero mt-3 btn-book">Confirm</button>
                            <button type="submit" class="btn btn-lg btn-outline-dark button-secondary large w-inline-block radius-zero mt-3 btn-back">Back</button>
                        </div>
                    </div>
                </div>';
        return response()->json(['data' => $html], 200);   
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
            'type' => 'required',
            'payment' => 'required'
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
        $resort = Resort::find(1);
        $extraPerson = null;
        $totalpax = $request->adults + $request->kids + $request->senior_citizen;
        $maxpax = $request->type == 'day' ? $resort->exclusive_daycapacity : $resort->exclusive_overnightcapacity;
        $extraPersonTotal = 0;
        if($totalpax > $maxpax) {
            $extraPerson = $totalpax - $maxpax;
            $extraPersonTotal = $extraPerson * ($request->type == 'day' ? $resort->exclusive_day_extra : $resort->exclusive_overnight_extra);
        }
        $rentBill = $request->type == 'day' ? $resort->exclusive_dayprice : $resort->exclusive_overnightprice;
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
        $transaction->payment_id = $request->payment;
        $transaction->save();

        $entranceFees = Entrancefee::all();
        Mail::to($guest)->send(new ReservationSent($transaction, $resort));
        // $guest->notify(new ReservationSent($transaction));
        $msg = "Thank you for checking us out. We are reviewing your reservation: control#".$transaction->id.". We sent the reservation link to your email.";
        $smsResult = \App\Helpers\CustomSMS::send($guest->contact, $msg);
        session()->flash('type', 'success');
        if($transaction->payment_id == 1) {
            session()->flash('notification', 'Resevation was sent successfully.');
        } else {
            session()->flash('notification', 'We have sent an email with instructions for Online bank / GCash deposit. We will message you as soon as we receive your payment.');
        }
        return redirect()->route('landing.transaction_show', $controlCode);    
    }

    public function transaction_show($code) 
    {
        $data['transaction'] = Transaction::where('controlCode', $code)->first();
        // $data['cottages'] = Cottage::all();
        // $data['rooms'] = Room::all();
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
        $data['breakfasts'] = Breakfast::where('is_active', 1)->get();
        $data['payments'] = Payment::all();
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
        $slot = Transaction::where('cottage_id', null)->where('status', '!=', 'cancelled')->whereBetween('checkIn_at', [$checkin, $checkout])->orWhereBetween('checkOut_at', [$checkin, $checkout])->pluck('id')->toArray();

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
        $day3 = Carbon::now()->addDays(1);
        $slot = Transaction::where('room_id', $room->id)->where('status', '!=', 'cancelled')->whereDate('checkIn_at', '>=', $day3)->orWhere('is_exclusive', 1)->pluck('checkIn_at')->toArray();
        return response()->json(['dates' => $slot ], 200);
    }

    public function getcottages_available(Request $request, $id)
    {
        $cottage = Cottage::findOrFail($id);
        $checkin = Carbon::parse($request->checkin); 

        $slot = Transaction::where('cottage_id', $cottage->id)->where('status', '!=', 'cancelled')->whereDate('checkIn_at', $checkin)->pluck('checkIn_at')->toArray();

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
        $exclusive = Transaction::whereDate('checkIn_at', $checkin)->where('type', $request->usetype)->where('is_exclusive', 1)->where('status', '!=', 'cancelled')->count();
        if($exclusive){
            return response()->json(['status' => 'not available', 'unit' => 0], 200);
        }

        $slot = Transaction::where('cottage_id', $cottage->id)->whereDate('checkIn_at', $checkin)->where('type', $request->usetype)->where('status', '!=', 'cancelled')->count();

        if($slot >= $cottage->units) {
            $test = $cottage->units;
            return response()->json(['status' => 'not available', 'unit' => $test], 200);
        } else {
            $test = $cottage->units - $slot;
            $unit_text =  $test > 1 ? 'units' : 'unit';
            return response()->json(['status' => $test.' '.$unit_text.' available', 'unit' => $test], 200);
        }
    }
    

    public function cottage_available(Request $request)
    {
  
        $checkin = Carbon::parse($request->checkin); 
        $checkout = Carbon::parse($request->checkout); 
        $slot = Transaction::where('room_id', null)->where('status', '!=', 'cancelled')->whereBetween('checkIn_at', [$checkin, $checkout])->orWhereBetween('checkOut_at', [$checkin, $checkout])->pluck('id')->toArray();

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
        $slot = Transaction::where('cottage_id', null)->where('status', '!=', 'cancelled')->whereBetween('checkIn_at', [$checkin, $checkout])->orWhereBetween('checkOut_at', [$checkin, $checkout])->pluck('id')->toArray();

        if(!empty($slot)) {
            $rooms = Room::whereNotIn('id', $slot)->get();
            return response()->json(['rooms' => $rooms], 200);
        }
        $rooms = Room::all();
        return response()->json(['rooms' => $rooms ], 200);
    }

    public function exclusive_rental()
    {
        $data['payments'] = Payment::all();
        return view('landing.exclusiverental', $data);
    }

    public function getexclusive_available(Request $request)
    {
        $checkin = Carbon::parse($request->checkin); 
        if($request->tranid){
            $slot = Transaction::whereDate('checkIn_at', $checkin)->where('id', '!=',$request->tranid)->where('status', '!=', 'cancelled')->get();
        } else {
            $slot = Transaction::whereDate('checkIn_at', $checkin)->where('status', '!=', 'cancelled')->get();
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

    public function getroom_available(Request $request, $id)
    {
        $checkin = Carbon::parse($request->checkin); 
        if($request->tranid){
            $slot = Transaction::where('room_id', $id)->whereDate('checkIn_at', $checkin)->where('id', '!=',$request->tranid)->where('status', '!=', 'cancelled')->get();
        } else {
            $slot = Transaction::where('room_id', $id)->whereDate('checkIn_at', $checkin)->where('status', '!=', 'cancelled')->get();
        }
        $day_available = true;
        $night_available = true;
        $overnight_available = true;
        foreach($slot as $item) {
            if($item->type == 'day') {
                $day_available = false;
            } elseif ($item->type == 'night' || $item->type == 'overnight') {
                $overnight_available = false;
                $night_available = false;
            }
        }
        $available = [];
        if($day_available) {
            $available[] = 'day';
        } 
        if($overnight_available) {
            $available[] = 'overnight';
        }
        if($night_available) {
            $available[] = 'night';
        }
 
        return response()->json(['status' => $available ], 200);
    }
}
