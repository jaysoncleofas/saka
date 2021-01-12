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
        $data['cottages'] = Cottage::all();
        $data['rooms'] = Room::all();
        $data['entranceFees'] = Entrancefee::all();
        $data['breakfasts'] = Breakfast::where('is_active', 1)->get();
        return view('admin.transactions.create', $data);
    }

    public function guest_create()
    {
        $data['cottages'] = Cottage::all();
        $data['rooms'] = Room::all();
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
            if($request->existing_guest == 2) {
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
        $extraPerson = null;
        $totalpax = $adults + $kids + $senior;
        $maxpax = $type == 'day' ? 60 : 30;
        $extraPersonTotal = 0;
        if($totalpax > $maxpax) {
            $extraPerson = $totalpax - $maxpax;
            $extraPersonTotal = $extraPerson * ($type == 'day' ? 200 : 250);
        }
        $rentBill = $type == 'day' ? 15000 : 25000;
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

        $is_reserved = Transaction::where('cottage_id', $cottage->id)->whereDate('checkIn_at', '=', $checkIn_at)->where('type', $type)->count();
        if($is_reserved >= $cottage->units) {
            return 1;
        }

        $entranceFees = Entrancefee::all();
        $adultfees = 0;
        $kidfees = 0;
        $seniorfees = 0;
        foreach($entranceFees as $fee) {
            if($fee->title == 'Adults') {
                $adultfees = $adults * $fee->price;
            } elseif ($fee->title == 'Kids') {
                $kidfees = $kids * $fee->price;
            } elseif ($fee->title == 'Senior Citizen') {
                $seniorfees = $senior * $fee->price;
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
        if($room->max < $totalpax) {
            return 2;
        }
        if($type == 'night') {
            $checkIn_at = Carbon::parse($checkIn_at)->setHour(17);  
            $checkout = Carbon::parse($checkIn_at)->setHour(21);  
        } else {
            $checkIn_at = Carbon::parse($checkIn_at)->setHour(14);  
            $checkout = Carbon::parse($checkIn_at)->addDay(1)->setHour(11);  
        }

        $is_reserved = Transaction::where('room_id', $room->id)->whereDate('checkIn_at', '=', $checkIn_at)->first();
        if($is_reserved) {
            return 1;
        }

        $extraPerson = null;
        $entranceFees = Entrancefee::all();
        $adultfees = 0;
        $kidfees = 0;
        $seniorfees = 0;
        foreach($entranceFees as $fee) {
            if($fee->title == 'Adults') {
                $adultfees = $adults * $fee->nightPrice;
            } elseif ($fee->title == 'Kids') {
                $kidfees = $kids * $fee->nightPrice;
            } elseif ($fee->title == 'Senior Citizen') {
                $seniorfees = $senior * $fee->nightPrice;
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
        $data['cottages'] = Cottage::all();
        $data['rooms'] = Room::all();
        $data['entranceFees'] = Entrancefee::all();
        $data['breakfasts'] = Breakfast::all();
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
            $cottages = Cottage::all();
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
                            'text' => 'P'.(number_format(($request->type == 'day' ? $cottage->price : $cottage->nightPrice), 2)).', '.$cottage->descriptions.', '.$test.' units available.'
                        ];
                    }
                }
            }
        } elseif($request->rent_type == 'room') {
            $new_arr = [];
            $slot = Transaction::whereNotNull('room_id')->whereDate('checkIn_at', $checkin)->where('status', '!=', 'cancelled')->pluck('room_id')->toArray();
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
            $rooms = Room::whereNotIn('id', $new_arr)->get();
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
        $data['cottages'] = Cottage::all();
        $data['entranceFees'] = Entrancefee::all();
        return view('admin.transactions.edit_cottage', $data);
    }

    public function edit_room($id)
    {
        $data['transaction'] = Transaction::findOrFail($id);
        $data['rooms'] = Room::all();
        $data['entranceFees'] = Entrancefee::all();
        $data['breakfasts'] = Breakfast::all();
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
        if($room->max < $totalpax) {
            return response()->json(['status' => 'error', 'text' => 'The Maximum capacity for this room is '.$room->max.'pax'], 200);
        }
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
            if($fee->title == 'Adults') {
                $adultfees = $request->adults * $fee->nightPrice;
            } elseif ($fee->title == 'Kids') {
                $kidfees = $request->kids * $fee->nightPrice;
            } elseif ($fee->title == 'Senior Citizen') {
                $seniorfees = $request->senior * $fee->nightPrice;
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
        $transaction->status = 'active';
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

        $extraPerson = null;
        $totalpax = $request->adults + $request->kids + $request->senior;
        $maxpax = $request->type == 'day' ? 60 : 30;
        $extraPersonTotal = 0;
        if($totalpax > $maxpax) {
            $extraPerson = $totalpax - $maxpax;
            $extraPersonTotal = $extraPerson * ($request->type == 'day' ? 200 : 250);
        }
        $rentBill = $request->type == 'day' ? 15000 : 25000;
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
}
