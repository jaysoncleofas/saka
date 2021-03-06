<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cottage;
use App\Models\Room;
use App\Models\Transaction;
use Auth; 
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use App\Models\Bill;
use App\Models\Entrancefee;
use App\Models\Breakfast;
use App\Models\Resort;
use App\Models\Guest;

class TransactionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $startDay = $request->startdate ? Carbon::parse($request->startdate)->startOfDay() : Carbon::now()->startOfMonth();
        $endDay = $request->enddate ? Carbon::parse($request->enddate)->endOfDay() : Carbon::now()->endOfMonth();

        $data['pending'] = Transaction::whereBetween('checkIn_at', [$startDay, $endDay])->whereStatus('pending')->count();
        $data['confirmed'] = Transaction::whereBetween('checkIn_at', [$startDay, $endDay])->whereIn('status', ['confirmed', 'active'])->count();
        $data['completed'] = Transaction::whereBetween('checkIn_at', [$startDay, $endDay])->whereStatus('completed')->count();
        $data['cancelled'] = Transaction::whereBetween('checkIn_at', [$startDay, $endDay])->whereStatus('cancelled')->count();

        return view('admin.transactions.index', $data);
    }

    public function create()
    {
        $data['cottages'] = Cottage::orderBy('name')->get();
        $data['rooms'] = Room::orderBy('name')->get();
        $data['entranceFees'] = Entrancefee::all();
        $data['breakfasts'] = Breakfast::where('is_active', 1)->get();
        return view('admin.transactions.create', $data);
    }

    public function guest_create()
    {
        $data['cottages'] = Cottage::orderBy('name')->get();
        $data['rooms'] = Room::orderBy('name')->get();
        $data['entranceFees'] = Entrancefee::all();
        $data['breakfasts'] = Breakfast::where('is_active', 1)->get();
        return view('admin.transactions.guest', $data);
    }

    public function store(Request $request)
    {
        // return $request->all();
        $request->validate([
            'is_reservation' => 'nullable',
            'checkin' => 'required',
            'rent_type' => 'required',
            'type' => 'required',
            'roomcottageid' => 'nullable',
            'addons' => 'nullable',
            'adults' => 'required',
            'kids' => 'required',
            'senior' => 'required',
            'notes' => 'nullable',
            'existing_guest' => 'required',
            'existing_guest_id' => 'nullable',
            'firstName' => 'nullable',
            'lastName' => 'nullable',
            'contactNumber' => 'nullable',
            'email' => 'nullable',
            'address' => 'nullable',
        ]);

        $checkIn_at = Carbon::parse($request->checkin);
        if($request->existing_guest == 1) {
            $guest = Guest::findOrfail($request->existing_guest_id);
        } else {
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
        }

        if($request->rent_type == 'exclusive_rental') {
            $transaction = $this->exclusive_store($checkIn_at, $request->type, $request->adults, $request->kids, $request->senior, $request->notes, $guest, $request->is_reservation);
            if(!is_object($transaction) && $transaction== 1) {
                return response()->json(['status' => 'error', 'text' => 'Sorry, the date is not available.'], 200);
            }
        } elseif ($request->rent_type == 'cottage') {
            $transaction = $this->cottage_store($checkIn_at, $request->type, $request->roomcottageid, $request->adults, $request->kids, $request->senior, $request->notes, $guest, $request->is_reservation);
            if(!is_object($transaction) && $transaction == 1) {
                return response()->json(['status' => 'error', 'text' => 'Sorry, the date is not available.'], 200);
            }
        } elseif ($request->rent_type == 'room') {
            $transaction = $this->room_store($checkIn_at, $request->type, $request->roomcottageid, $request->adults, $request->addons, $request->kids, $request->senior, $request->notes, $guest, $request->is_reservation);
            if(!is_object($transaction) && $transaction == 1) {
                return response()->json(['status' => 'error', 'text' => 'Sorry, the date is not available.'], 200);
            }
            if(!is_object($transaction) && $transaction == 2) {
                return response()->json(['status' => 'error', 'text' => 'Sorry, the Maximum capacity for the room is exceeded.'], 200);
            }
        }

        if($transaction) {
            if($request->guest_t == 1) {
                return response()->json(['status' => 'success', 'link' => route('transaction.guest_show', $transaction->id)], 200);
            } else {
                return response()->json(['status' => 'success', 'link' => route('transaction.show', $transaction->id)], 200);
            }
        } else {
            return response()->json(['status' => 'error'], 200);
        }

    }

    public function exclusive_store($checkIn_at, $type, $adults, $kids, $senior, $notes, $guest, $is_reservation)
    {
        if($type == 'overnight') {
            $checkIn_at = Carbon::parse($checkIn_at)->setHour(9);  
            $checkout = Carbon::parse($checkIn_at)->addDays(1)->setHour(11);   
            $is_reserved = Transaction::whereDate('checkIn_at', $checkIn_at)->whereIn('type', [$type, 'night'])->first();
        } else {
            $checkIn_at = Carbon::parse($checkIn_at)->setHour(9);  
            $checkout = Carbon::parse($checkIn_at)->setHour(17);
            $is_reserved = Transaction::whereDate('checkIn_at', $checkIn_at)->where('type', $type)->first();
        }
        if($is_reserved) {
            return 1;
            // 'Sorry, the date is not available.';
        }
        $resort = Resort::find(1);
        $extraPerson = null;
        $totalpax = $adults + $kids + $senior;
        $maxpax = $type == 'day' ? $resort->exclusive_daycapacity : $resort->exclusive_overnightcapacity;
        $extraPersonTotal = 0;
        if($totalpax > $maxpax) {
            $extraPerson = $totalpax - $maxpax;
            $extraPersonTotal = $extraPerson * ($type == 'day' ? $resort->exclusive_day_extra : $resort->exclusive_overnight_extra);
        }
        $rentBill = $type == 'day' ? $resort->exclusive_dayprice : $resort->exclusive_overnightprice;
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
        $transaction->checkIn_at = $checkIn_at;
        $transaction->checkOut_at = $checkout;
        $transaction->adults = $adults;
        $transaction->kids = $kids;
        $transaction->senior = $senior;
        $transaction->type = $type;
        $transaction->is_breakfast = 0;
        $transaction->is_freebreakfast = 0;
        $transaction->status = 'active';
        $transaction->notes = $notes;
        $transaction->is_reservation = $is_reservation ? 1 : 0;
        $transaction->extraPerson = $extraPerson;
        $transaction->extraPersonTotal = $extraPersonTotal;
        $transaction->totalEntranceFee = $extraPersonTotal;
        $transaction->breakfastfees = 0;
        $transaction->rentBill = $rentBill;
        $transaction->totalBill = $totalBill;
        $transaction->controlCode = $controlCode;
        $transaction->save();

        return $transaction;
    }

    public function cottage_store($checkIn_at, $type, $roomcottageid, $adults, $kids, $senior, $notes, $guest, $is_reservation)
    {
        $cottage = Cottage::findOrFail($roomcottageid);
        if($type == 'night') {
            $checkIn_at = Carbon::parse($checkIn_at)->setHour(17);  
            $checkout = Carbon::parse($checkIn_at)->setHour(21);  
        } else {
            $checkIn_at = Carbon::parse($checkIn_at)->setHour(9);  
            $checkout = Carbon::parse($checkIn_at)->setHour(17);  
        }

        // $is_reserved = Transaction::where('cottage_id', $cottage->id)->whereDate('checkIn_at', '=', $checkIn_at)->where('type', $type)->count();
        // if($is_reserved >= $cottage->units) {
        //     return 1;
        // }

        $entranceFees = Entrancefee::all();
        $adultfees = 0;
        $kidfees = 0;
        $seniorfees = 0;
        foreach($entranceFees as $fee) {
            $fees = 0;
            if($type == 'day') {
                $fees = $fee->price;
            } elseif($type == 'night') {
                $fees = $fee->nightPrice;
            } else {
                $fees = $fee->overnightPrice;
            }
            if($fee->title == 'Adults') {
                $adultfees = $adults * $fees;
            } elseif ($fee->title == 'Kids') {
                $kidfees = $kids * $fees;
            } elseif ($fee->title == 'Senior Citizen') {
                $seniorfees = $senior * $fees;
            }
        }
        $totalEntranceFee = $adultfees + $kidfees + $seniorfees;
        $rentBill = $type == 'day' ? $cottage->price : $cottage->nightPrice;
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
        $transaction->checkIn_at = $checkIn_at;
        $transaction->checkOut_at = $checkout;
        $transaction->adults = $adults;
        $transaction->kids = $kids;
        $transaction->senior = $senior;
        $transaction->type = $type;
        $transaction->is_breakfast = 0;
        $transaction->is_freebreakfast = 0;
        $transaction->status = 'active';
        $transaction->notes = $notes;
        $transaction->is_reservation = $is_reservation ? 1 : 0;
        $transaction->extraPerson = null;
        $transaction->extraPersonTotal = 0;
        $transaction->totalEntranceFee = $totalEntranceFee;
        $transaction->breakfastfees = 0;
        $transaction->rentBill = $rentBill;
        $transaction->totalBill = $totalBill;
        $transaction->controlCode = $controlCode;
        $transaction->save();
        return $transaction;
    }

    public function room_store($checkIn_at, $type, $roomcottageid, $adults, $addons, $kids, $senior, $notes, $guest, $is_reservation)
    {
        $room = Room::findOrFail($roomcottageid);
        $totalpax = $adults + $kids + $senior;
        // if($room->max < $totalpax) {
        //     return 2;
        // }
        if($type == 'night') {
            $checkIn_at = Carbon::parse($checkIn_at)->setHour(17);  
            $checkout = Carbon::parse($checkIn_at)->setHour(21);  
        } elseif($type == 'day') {
            $checkIn_at = Carbon::parse($checkIn_at)->setHour(9);  
            $checkout = Carbon::parse($checkIn_at)->setHour(17);  
        }else {
            $checkIn_at = Carbon::parse($checkIn_at)->setHour(14);  
            $checkout = Carbon::parse($checkIn_at)->addDay(1)->setHour(11);  
        }

        // $is_reserved = Transaction::where('room_id', $room->id)->whereDate('checkIn_at', '=', $checkIn_at)->first();
        // if($is_reserved) {
        //     return 1;
        // }

        $extraPerson = null;
        $entranceFees = Entrancefee::all();
        $adultfees = 0;
        $kidfees = 0;
        $seniorfees = 0;
        foreach($entranceFees as $fee) {
            $fees = 0;
            if($type == 'day') {
                $fees = $fee->price;
            } elseif($type == 'night') {
                $fees = $fee->nightPrice;
            } else {
                $fees = $fee->overnightPrice;
            }
            if($fee->title == 'Adults') {
                $adultfees = $adults * $fees;
            } elseif ($fee->title == 'Kids') {
                $kidfees = $kids * $fees;
            } elseif ($fee->title == 'Senior Citizen') {
                $seniorfees = $senior * $fees;
            }
        }
        $totalEntranceFee = $adultfees + $kidfees + $seniorfees;

        $rentBill = 0;
        $extraPersonTotal = 0;
        $totalEntranceFee = 0;
        if($room->entrancefee == 'Inclusive') {
            if($totalpax > $room->max) {
                $extraPerson = $totalpax - $room->max;
                $extraPersonTotal = $extraPerson * $room->extraPerson;
            }
        } else {
            // Exclusive 
            $extraPerson = $totalpax;
            $extraPersonTotal = $extraPerson * $room->extraPerson;
        }
        $rentBill = $room->price;

        $resort = Resort::findOrFail(1);
        $breakfastfees = 0;
        if($addons) {
            foreach($addons as $breakfast_id) {
                $breakfastP = Breakfast::findOrfail($breakfast_id);
                $breakfastfees = $breakfastfees + $breakfastP->price;
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
        $transaction->checkIn_at = $checkIn_at;
        $transaction->checkOut_at = $checkout;
        $transaction->adults = $adults;
        $transaction->kids = $kids;
        $transaction->senior = $senior;
        $transaction->type = $type;
        $transaction->is_breakfast = 1;
        $transaction->is_freebreakfast = 1;
        $transaction->status = 'active';
        $transaction->notes = $notes;
        $transaction->is_reservation = $is_reservation ? 1 : 0;
        $transaction->extraPerson = $extraPerson;
        $transaction->extraPersonTotal = $extraPersonTotal;
        $transaction->totalEntranceFee = $totalEntranceFee;
        $transaction->breakfastfees = $breakfastfees;
        $transaction->rentBill = $rentBill;
        $transaction->totalBill = $totalBill;
        $transaction->controlCode = $controlCode;
        $transaction->save();

        if($addons) {
            $transaction->breakfasts()->sync($addons);
        }

        return $transaction;
    }

    public function show($id)
    {
        $data['transaction'] = Transaction::findOrFail($id);
        $data['entranceFees'] = Entrancefee::all();
        return view('admin.transactions.show', $data);
    }

    public function guest_show($id)
    {
        $data['transaction'] = Transaction::findOrFail($id);
        $data['entranceFees'] = Entrancefee::all();
        return view('admin.transactions.guest_show', $data);
    }

    public function edit($id)
    {
        $data['transaction'] = Transaction::findOrFail($id);
        $data['cottages'] = Cottage::orderBy('name')->get();
        $data['rooms'] = Room::orderBy('name')->get();
        $data['entranceFees'] = Entrancefee::all();
        $data['breakfasts'] = Breakfast::where('is_active', 1)->get();
        return view('admin.transactions.edit', $data);
    }

    public function update(Request $request, $id)
    {
        // return $request->all();
        $transaction = Transaction::findOrFail($id);

        // return $request->all();
        $request->validate([
            'guest' => 'required',
            'checkin' => 'required',
            'checkout' => 'nullable',
            'Adults' => 'nullable|numeric',
            'Kids' => 'nullable|numeric',
            'Senior_Citizen' => 'nullable|numeric',
            'type' => 'required',
            'isbreakfast' => 'required',
            'cottage' => 'required_without:room',
            'room' => 'required_without:cottage',
            'notes' => 'nullable',
            'is_reservation' => 'nullable',
        ]);

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

        $transaction->guest_id = $request->guest;
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

        session()->flash('notification', 'Successfully updated!');
        session()->flash('type', 'success');

        return redirect()->route('transaction.show', $transaction->id);
    }

    public function destroy($id)
    {
        $transaction = Transaction::findOrFail($id);
        $transaction->delete();
        
        session()->flash('notification', 'Successfully deleted!');
        session()->flash('type', 'success');
        return response('success', 200);
    }

    public function datatables(Request $request)
    {
        $startDay = $request->startdate ? Carbon::parse($request->startdate)->startOfDay() : Carbon::now()->startOfMonth();
        $endDay = $request->enddate ? Carbon::parse($request->enddate)->endOfDay() : Carbon::now()->endOfMonth();

        $transactions = Transaction::orderBy('created_at', 'desc')->whereBetween('checkIn_at', [$startDay, $endDay])->get();

        return DataTables::of($transactions)
                ->editColumn('id', function ($transaction) {
                    return '<a href="'.route('transaction.invoice', $transaction->id).'">CTRL-'.$transaction->id.'</a>';
                })
                ->addColumn('guest', function ($transaction) {
                    return '<a href="'.route('guest.show', $transaction->guest_id).'">'.$transaction->guest->firstName.' '.$transaction->guest->lastName.'</a>';
                })
                ->addColumn('service', function ($transaction) {
                    if($transaction->cottage) {
                        return 'Cottage: '.$transaction->cottage->name;
                    } elseif($transaction->room) {
                        return 'Room: '.$transaction->room->name;
                    } elseif($transaction->is_exclusive) {
                        return 'Exclusive Rental';
                    } 
                })
                ->addColumn('checkin', function ($transaction) {
                    return $transaction->checkIn_at->format('M d, Y h:i a');
                })
                ->addColumn('checkout', function ($transaction) {
                    if($transaction->checkOut_at) {
                        return $transaction->checkOut_at->format('M d, Y h:i a');
                    } else {
                        return '-';
                    }
                })
                ->addColumn('reservation', function ($transaction) {
                    if($transaction->is_reservation) {
                        // return '<span class="badge badge-warning">Reservation</span>';
                        return 'Reservation';
                    }
                    return 'Walk-in';
                })
                ->editColumn('status', function ($transaction) {
                    if($transaction->status == 'pending') {
                        return '<span class="badge badge-secondary">Pending</span>';
                    } elseif($transaction->status == 'confirmed') {
                        return '<span class="badge badge-warning">Confirmed</span>';
                    } elseif($transaction->status == 'active') {
                        return '<span class="badge badge-primary">Active</span>';
                    } elseif($transaction->status == 'completed') {
                        return '<span class="badge badge-success">Completed</span>';
                    } elseif($transaction->status == 'cancelled') {
                        return '<span class="badge badge-danger">Cancelled</span>';
                    }
                })
                ->addColumn('actions', function ($transaction) {
                    $html = '';
                    $html .= '<div class="btn-group">
                    <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-cog"></i>
                    </button>
                    <div class="dropdown-menu">
                      <a class="dropdown-item" href="'.route('transaction.show', $transaction->id).'">View</a>
                      <a class="dropdown-item" href="';
                      if($transaction->cottage_id){
                        $html .= route('transaction.edit_cottage', $transaction->id);
                      } elseif($transaction->room_id){
                        $html .= route('transaction.edit_room', $transaction->id);
                      }if($transaction->is_exclusive){
                        $html .= route('transaction.edit_exclusive', $transaction->id);
                      }
                      $html .= '">Edit</a>
                      <a class="dropdown-item trigger-delete2" data-action="'.route('transaction.destroy', $transaction->id).'" data-model="reservation" href="#">Delete</a>
                      <div class="dropdown-divider"></div>';
        
                    if($transaction->status == 'pending') {
                        $html .='<a class="dropdown-item trigger-confirm" data-id="'. $transaction->id .'" data-action="'.route('reservation.confirm', $transaction->id).'" data-model="reservation" href="#">Confirm</a>';
                    }

                    if($transaction->status != 'cancelled' && $transaction->status != 'completed') {
                        $html .= '<a class="dropdown-item trigger-cancel" data-action="'.route('reservation.cancel', $transaction->id).'" data-model="reservation" href="#">Cancel</a>
                        </div>
                    </div>';
                    }
                  return $html;
                })
                ->addColumn('usetype', function ($transaction) {
                    return ucfirst($transaction->type).' use';
                })
                ->rawColumns(['actions', 'guest', 'service', 'checkin', 'id', 'reservation', 'status', 'usetype'])
                ->toJson();
    }

    public function invoice($id)
    {
        $data['transaction'] = Transaction::findOrfail($id);
        $data['entranceFees'] = Entrancefee::all();
        return view('admin.transactions.invoice', $data);
    }

    public function complete($id)
    {
        $user = Auth::user();
        $transaction = Transaction::findOrfail($id);

        $transaction->update([
            'status' => 'completed',
            'receivedby_id' => $user->id,
            'completed_at' => Carbon::now()
        ]);

        return response('success', 200);
    }

    public function get_available_rooms_cottages(Request $request)
    {
        $request->validate([
            'checkin' => 'required',
            'rent_type' => 'required',
            'type' => 'required',
        ]);
        $checkin = Carbon::parse($request->checkin);
        $available_data = [];
        if($request->rent_type == 'cottage') {
            $cottages = Cottage::orderBy('name')->get();
            foreach($cottages as $cottage) {
                $slot = Transaction::where('cottage_id', $cottage->id)->whereDate('checkIn_at', $checkin)->where('type', $request->type)->where('status', '!=', 'cancelled')->count();
                if($slot && $request->edit == 1 && $request->cottageid == $cottage->id) {
                    $slot = $slot - 1;
                } 
                if($slot < $cottage->units) {
                    $test = $cottage->units - $slot;
                    $exclusive = Transaction::whereDate('checkIn_at', $checkin)->where('type', $request->type)->where('is_exclusive', 1)->where('status', '!=', 'cancelled')->count();
                    if(!$exclusive){
                        $available_data[] = [
                            'is_selected' => ($request->cottageid == $cottage->id) ? 1 : 0,
                            'name' => $cottage->name, 
                            'id' => $cottage->id,
                            'text' => 'P'.(number_format(($request->type == 'day' ? $cottage->price : $cottage->nightPrice), 2)).', '.$cottage->descriptions.', '.$test.' unit'.($test > 1 ? 's' : '').' available.'
                        ];
                    }
                }
            }
        } elseif($request->rent_type == 'room') {
            $new_arr = [];
            if($request->type == 'day'){
                $slot = Transaction::whereNotNull('room_id')->whereDate('checkIn_at', $checkin)->where('type', 'day')->where('status', '!=', 'cancelled')->pluck('room_id')->toArray();
            } else {
                $slot = Transaction::whereNotNull('room_id')->whereDate('checkIn_at', $checkin)->whereIn('type', ['night', 'overnight'])->where('status', '!=', 'cancelled')->pluck('room_id')->toArray();
            }
            if($request->edit && $request->roomid) {
                foreach($slot as $item) {
                    if($item != $request->roomid) {
                        $new_arr[] = $item;
                    }
                }
            } else {
                foreach($slot as $item) {
                    $new_arr[] = $item;
                }
            }
            $rooms = Room::whereNotIn('id', $new_arr)->orderBy('name')->get();
            foreach($rooms as $room) {
                $exclusive = Transaction::whereDate('checkIn_at', $checkin)->where('type', 'overnight')->where('is_exclusive', 1)->where('status', '!=', 'cancelled')->count();
                if(!$exclusive){
                    $available_data[] = [
                        'is_selected' => ($request->roomid == $room->id) ? 1 : 0,
                        'name' => $room->name, 
                        'id' => $room->id,
                        'text' => 'P'.number_format($room->price, 2).', '.$room->descriptions
                    ];
                }
            }
        }
        return response()->json(['type' => ucwords($request->rent_type == 'exclusive_rental' ? 'exclusive rental' : $request->rent_type), 'data' => $available_data], 200);
    }

    public function edit_cottage($id)
    {
        $data['transaction'] = Transaction::findOrFail($id);
        $data['cottages'] = Cottage::orderBy('name')->get();
        $data['entranceFees'] = Entrancefee::all();
        return view('admin.transactions.edit_cottage', $data);
    }

    public function edit_room($id)
    {
        $data['transaction'] = Transaction::findOrFail($id);
        $data['rooms'] = Room::orderBy('name')->get();
        $data['entranceFees'] = Entrancefee::all();
        $data['breakfasts'] = Breakfast::where('is_active', 1)->get();
        $data['transactionbreakfasts'] = [];
        foreach($data['transaction']->breakfasts as $item) 
        {
            $data['transactionbreakfasts'][] = $item->id;
        }
        return view('admin.transactions.edit_room', $data);
    }

    public function edit_exclusive($id)
    {
        $data['transaction'] = Transaction::findOrFail($id);
        $data['entranceFees'] = Entrancefee::all();
        return view('admin.transactions.edit_exclusive', $data);
    }

    public function update_cottage(Request $request, $id)
    {
        $transaction = Transaction::findOrFail($id);

        $request->validate([
            'checkin' => 'required',
            'type' => 'required',
            'roomcottageid' => 'required',
            'adults' => 'required|numeric',
            'kids' => 'required|numeric',
            'senior' => 'required|numeric',
            'notes' => 'nullable',
        ]);

        $cottage = Cottage::findOrFail($request->roomcottageid);
        if($request->type == 'night') {
            $checkIn_at = Carbon::parse($request->checkin)->setHour(17);  
            $checkout = Carbon::parse($request->checkin)->setHour(21);  
        } else {
            $checkIn_at = Carbon::parse($request->checkin)->setHour(9);  
            $checkout = Carbon::parse($request->checkin)->setHour(17);  
        }

        
        $is_reserved = Transaction::where('cottage_id', $cottage->id)->whereDate('checkIn_at', '=', $checkIn_at)->where('type', $request->type)->count();
        $is_reserved = $is_reserved - 1;
        if($is_reserved >= $cottage->units) {
            return response()->json(['status' => 'error', 'text' => 'Not Available'], 200);
        }

        $entranceFees = Entrancefee::all();
        $adultfees = 0;
        $kidfees = 0;
        $seniorfees = 0;
        foreach($entranceFees as $fee) {
            if($fee->title == 'Adults') {
                $adultfees = $request->adults*($request->type != 'day' ? $fee->nightPrice : $fee->price);
            } elseif ($fee->title == 'Kids') {
                $kidfees = $request->kids*($request->type != 'day' ? $fee->nightPrice : $fee->price);
            } elseif ($fee->title == 'Senior Citizen') {
                $seniorfees = $request->senior*($request->type != 'day' ? $fee->nightPrice : $fee->price);
            }
        }
        $totalEntranceFee = $adultfees + $kidfees + $seniorfees;

        $rentBill = ($request->type != 'day' ? $cottage->nightPrice : $cottage->price);

        $totalBill = $totalEntranceFee + $rentBill;

        $transaction->cottage_id = $cottage->id;
        $transaction->checkIn_at = $checkIn_at;
        $transaction->checkOut_at = $checkout;
        $transaction->adults = $request->adults;
        $transaction->kids = $request->kids;
        $transaction->senior = $request->senior;
        $transaction->type = $request->type;
        $transaction->notes = $request->notes;
        $transaction->totalEntranceFee = $totalEntranceFee;
        $transaction->rentBill = $rentBill;
        $transaction->totalBill = $totalBill;
        $transaction->update();

        return response()->json(['status' => 'success', 'link' => route('transaction.show', $transaction->id)], 200);
    }

    public function update_room(Request $request, $id)
    {
        // return $request->all();
        $request->validate([
            'checkin' => 'required',
            'type' => 'required',
            'roomcottageid' => 'required',
            'adults' => 'required|numeric',
            'kids' => 'required|numeric',
            'senior' => 'required|numeric',
            'notes' => 'nullable',
        ]);

        $transaction = Transaction::findOrFail($id);
        $room = Room::findOrFail($request->roomcottageid);
        $totalpax = $request->adults + $request->kids + $request->senior_citizen;
        // if($room->max < $totalpax) {
        //     return response()->json(['status' => 'error', 'text' => 'The Maximum capacity for this room is '.$room->max.'pax'], 200);
        // }
        $checkIn_at = Carbon::parse($request->checkin);
        if($request->type == 'night') {
            $checkin = Carbon::parse($request->checkin)->setHour(17);  
            $checkout = Carbon::parse($request->checkin)->setHour(21);  
        } else {
            $checkin = Carbon::parse($request->checkin)->setHour(14);  
            $checkout = Carbon::parse($request->checkin)->addDay(1)->setHour(11);  
        }

        // $is_reserved = Transaction::where('room_id', $room->id)->whereDate('checkIn_at', '=', $checkIn_at)->first();
        // if($is_reserved) {
        //     return response()->json(['status' => 'error', 'text' => 'Sorry, the room was already reserved.'], 200);
        // }

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
        $totalEntranceFee = 0;
        if($room->entrancefee == 'Inclusive') {
            if($totalpax > $room->max) {
                $extraPerson = $totalpax - $room->max;
                $extraPersonTotal = $extraPerson * $room->extraPerson;
            }
        } else {
            // Exclusive 
            $extraPerson = $totalpax;
            $extraPersonTotal = $extraPerson * $room->extraPerson;
        }
        $rentBill = $room->price;

        $resort = Resort::findOrFail(1);
        $breakfastfees = 0;
        if($request->addons) {
            foreach($request->addons as $breakfast_id) {
                $breakfastP = Breakfast::findOrfail($breakfast_id);
                $breakfastfees = $breakfastfees + $breakfastP->price;
            }
        }

        $totalBill = $totalEntranceFee + $extraPersonTotal + $breakfastfees + $rentBill;

        $transaction->room_id = $room->id;
        $transaction->checkIn_at = $checkIn_at;
        $transaction->checkOut_at = $checkout;
        $transaction->adults = $request->adults;
        $transaction->kids = $request->kids;
        $transaction->senior = $request->senior;
        $transaction->type = $request->type;
        $transaction->is_freebreakfast = 1;
        // $transaction->status = 'active';
        $transaction->notes = $request->notes;
        $transaction->extraPerson = $extraPerson;
        $transaction->extraPersonTotal = $extraPersonTotal;
        $transaction->totalEntranceFee = $totalEntranceFee;
        $transaction->breakfastfees = $breakfastfees;
        $transaction->rentBill = $rentBill;
        $transaction->totalBill = $totalBill;
        $transaction->update();

        if($request->addons) {
            $transaction->breakfasts()->sync($request->addons);
        } else {
            $transaction->breakfasts()->sync(array());
        }

        return response()->json(['status' => 'success', 'link' => route('transaction.show', $transaction->id)], 200);
    }

    public function update_exclusive(Request $request, $id)
    {
        $transaction = Transaction::findOrFail($id);

        $request->validate([
            'checkin' => 'required',
            'type' => 'required',
            'adults' => 'required|numeric',
            'kids' => 'required|numeric',
            'senior' => 'required|numeric',
            'notes' => 'nullable',
        ]);

        if($request->type == 'overnight') {
            $checkin = Carbon::parse($request->checkin)->setHour(9);  
            $checkout = Carbon::parse($request->checkin)->addDays(1)->setHour(11);   
        } else {
            $checkin = Carbon::parse($request->checkin)->setHour(9);  
            $checkout = Carbon::parse($request->checkin)->setHour(17);
        }

        $resort = Resort::find(1);
        $extraPerson = null;
        $totalpax = $request->adults + $request->kids + $request->senior;
        $maxpax = $request->type == 'day' ? $resort->exclusive_daycapacity : $resort->exclusive_overnightcapacity;
        $extraPersonTotal = 0;
        if($totalpax > $maxpax) {
            $extraPerson = $totalpax - $maxpax;
            $extraPersonTotal = $extraPerson * ($request->type == 'day' ? $resort->exclusive_day_extra : $resort->exclusive_overnight_extra);
        }
        $rentBill = $request->type == 'day' ? $resort->exclusive_dayprice : $resort->exclusive_overnightprice;
        $totalBill = $extraPersonTotal + $rentBill;


        $transaction->checkIn_at = $checkin;
        $transaction->checkOut_at = $checkout;
        $transaction->adults = $request->adults;
        $transaction->kids = $request->kids;
        $transaction->senior = $request->senior;
        $transaction->type = $request->type;
        $transaction->notes = $request->notes;
        $transaction->extraPerson = $extraPerson;
        $transaction->extraPersonTotal = $extraPersonTotal;
        $transaction->totalEntranceFee = $extraPersonTotal;
        $transaction->rentBill = $rentBill;
        $transaction->totalBill = $totalBill;
        $transaction->update();

        return response()->json(['status' => 'success', 'link' => route('transaction.show', $transaction->id)], 200);
    }

    public function summary(Request $request)
    {
        $request->validate([
            'checkin' => 'required',
            'adults' => 'required|numeric',
            'kids' => 'required|numeric',
            'senior_citizen' => 'required|numeric',
            'type' => 'required',
        ]);

        if($request->existing_guest == 1) {
            $guest = Guest::findOrfail($request->existing_guest_id);
        }

        if($request->rent_type == 'cottage') {
            if($request->type == 'night') {
                $checkin = Carbon::parse($request->checkin)->setHour(17);  
                $checkout = Carbon::parse($request->checkin)->setHour(21);  
            } else {
                $checkin = Carbon::parse($request->checkin)->setHour(9);  
                $checkout = Carbon::parse($request->checkin)->setHour(17);  
            }
        } elseif ($request->rent_type == 'room') {
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
        } else {
            if($request->type == 'overnight') {
                $checkin = Carbon::parse($request->checkin)->setHour(9);  
                $checkout = Carbon::parse($request->checkin)->addDays(1)->setHour(11);   
            } else {
                $checkin = Carbon::parse($request->checkin)->setHour(9);  
                $checkout = Carbon::parse($request->checkin)->setHour(17);
            }
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
        if($request->rent_type == 'cottage') {
            $cottage = Cottage::findOrFail($request->roomcottageid);
            $rent_name = 'Cottage - '.$cottage->name; 
            $rentBill = $request->type == 'day' ? $cottage->price : $cottage->nightPrice;
            $totalBill = $totalEntranceFee + $rentBill;
        } elseif($request->rent_type == 'room') {
            $totalpax = $request->adults + $request->kids + $request->senior_citizen;
            $room = Room::findOrFail($request->roomcottageid);
            $rent_name = 'Room - '.$room->name;
            $rentBill = 0;
            $extraPersonTotal = 0;
            $totalEntranceFee = 0;
            if($room->entrancefee == 'Inclusive') {
                if($totalpax > $room->max) {
                    $extraPerson = $totalpax - $room->max;
                    $extraPersonTotal = $extraPerson * $room->extraPerson;
                }
            } else {
                // Exclusive 
                $extraPerson = $totalpax;
                $extraPersonTotal = $extraPerson * $room->extraPerson;
            }
            $rentBill = $room->price;
    
            $resort = Resort::findOrFail(1);
            $breakfastfees = 0;
            if($request->type == 'overnight' && $request->breakfast) {
                foreach($request->breakfast as $breakfast_id) {
                    $breakfastP = Breakfast::findOrfail($breakfast_id);
                    $breakfastfees = $breakfastfees + $breakfastP->price;
                }
            }
    
            $totalBill = $totalEntranceFee + $extraPersonTotal + $breakfastfees + $rentBill;
        } else {
            $resort = Resort::find(1);
            $rent_name = 'Exclusive Rental';
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
        }

        $html = '';
        $html .='<div class="">
                    <div class="row is-size-15">
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-lg-6 col-md-6">';
                                if($request->is_reservation == 1) {
                                    $html .='   <strong>Reservation:</strong> Yes <br>';
                                }
                        $html .='   <strong>Rent:</strong> '.$rent_name.' <br>
                                    <strong>Use:</strong> '.ucfirst($request->type).' Use<br>
                                    <strong>Check In:</strong> '.date('m/d/Y h:i a', strtotime($checkin)).' <br>
                                    <strong>Check Out:</strong> '.date('m/d/Y h:i a', strtotime($checkout)).' <br>
                                    <strong>Adults:</strong> '.$request->adults.' <br>
                                    <strong>Kids:</strong> '.$request->kids.' <br>
                                    <strong>Senior Citizens:</strong> '.$request->senior_citizen.' <br>
                                </div>
                                <div class="col-lg-6 col-md-6">
                                    <strong>Guest:</strong> '.(!empty($guest) ? $guest->firstName : $request->firstName).' '.(!empty($guest) ? $guest->lastName : $request->lastName).' <br>
                                    <strong>Contact Number:</strong> '.(!empty($guest) ? $guest->contact : $request->contactNumber).' <br>
                                    <strong>Email:</strong> '.(!empty($guest) ? $guest->email : $request->email).' <br>
                                    <strong>Address:</strong> '.(!empty($guest) ? $guest->address : $request->address).' <br>
                                </div>
                            </div>
                        </div>
                    </div>';
        if($request->rent_type == 'cottage') {
            $html .='   <div class="row">
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
                                                <td>P'.number_format($request->type == 'day' ? $cottage->price : $cottage->nightPrice).'</td>
                                                <td>P<span class="totalprice">'.number_format($request->type == 'day' ? $cottage->price : $cottage->nightPrice).'</span></td>
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
                        </div>';
        } elseif($request->rent_type == 'room') {
            $html .='   <div class="row">
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
                    $html .='<tr>';
                    $html .='    <td>'. number_format($room->extraPerson, 0) .' per head</td>';
                    $html .='    <td>'. $extraPerson .'</td>';
                    $html .='    <td>P'. number_format($room->extraPerson, 2) .'</td>';
                    $html .='    <td>P'.number_format($extraPersonTotal, 2).'</td>';
                    $html .='</tr>';
                } else if($room->entrancefee == 'Inclusive' && $extraPerson) {
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
                        </div>';
        } else {
            $html .='   <div class="row">
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
                                                    <td>P'. number_format(($request->type == 'day' ? $resort->exclusive_day_extra : $resort->exclusive_overnight_extra), 2) .'</td>
                                                    <td>P<span class="totalprice">'.  number_format($extraPersonTotal, 2) .'</span></td>
                                                </tr>';
                                            }
                $html .='                   </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>';
        }

        $html .='   <div class="row mt-2">
                        <div class="col-md-4 float-md-right ml-auto">
                            <ul class="striped list-unstyled float-right">
                                <li><strong>TOTAL:</strong><span class="ml-2">P<span id="total_invoice">'.number_format($totalBill, 2).'</span></span></li>
                            </ul>
                        </div>
                    </div>
                </div>';
        return response()->json(['data' => $html], 200);    
    }
}
