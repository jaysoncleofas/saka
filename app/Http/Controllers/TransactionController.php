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

        $data['pending'] = Transaction::whereIs_reservation(1)->whereBetween('checkIn_at', [$startDay, $endDay])->whereStatus('pending')->count();
        $data['confirmed'] = Transaction::whereIs_reservation(1)->whereBetween('checkIn_at', [$startDay, $endDay])->whereStatus('confirmed')->count();
        $data['completed'] = Transaction::whereIs_reservation(1)->whereBetween('checkIn_at', [$startDay, $endDay])->whereStatus('completed')->count();
        $data['cancelled'] = Transaction::whereIs_reservation(1)->whereBetween('checkIn_at', [$startDay, $endDay])->whereStatus('cancelled')->count();

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
            'is_reservation' => 'nullable'
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

        $transaction = new Transaction();
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

        session()->flash('notification', 'Successfully added!');
        session()->flash('type', 'success');

        return redirect()->route('transaction.show', $transaction->id);
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
                      <a class="dropdown-item" href="'.route('transaction.edit', $transaction->id).'">Edit</a>
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
}
