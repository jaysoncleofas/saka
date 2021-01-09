<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Guest;
use App\Models\Transaction;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use Auth;
use App\Models\Cottage;
use App\Models\Room;
use App\Models\Entrancefee;
use App\Models\Breakfast;
use App\Models\Resort;
use App\Notifications\ReservationApproved;
use App\Notifications\ReservationCancelled;

class ReservationController extends Controller
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
        $data['approved'] = Transaction::whereIs_reservation(1)->whereBetween('checkIn_at', [$startDay, $endDay])->whereStatus('approved')->count();
        $data['completed'] = Transaction::whereIs_reservation(1)->whereBetween('checkIn_at', [$startDay, $endDay])->whereStatus('completed')->count();
        $data['cancelled'] = Transaction::whereIs_reservation(1)->whereBetween('checkIn_at', [$startDay, $endDay])->whereStatus('cancelled')->count();
        return view('admin.reservations.index', $data);
    }

    public function create()
    {
        $data['cottages'] = Cottage::all();
        $data['rooms'] = Room::all();
        $data['entranceFees'] = Entrancefee::all();
        $data['breakfasts'] = Breakfast::all();
        return view('admin.reservations.create', $data);
    }

    public function datatables(Request $request)
    {
        $resort = Resort::findOrfail(1);
        $startDay = $request->startdate ? Carbon::parse($request->startdate)->startOfDay() : Carbon::now()->startOfWeek()->startOfDay();
        $endDay = $request->enddate ? Carbon::parse($request->enddate)->endOfDay() : Carbon::now()->endOfMonth();
        $transactions = Transaction::where('is_reservation', 1)->whereBetween('checkIn_at', [$startDay, $endDay])->orderBy('created_at', 'desc')->get();

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
                ->addColumn('type', function ($transaction) use($resort) {
                    $sched = '';
                    if($transaction->type == 'day') {
                        $sched = $resort->day;
                    } elseif ($transaction->type == 'night') {
                        $sched = $resort->day;
                    } elseif ($transaction->type == 'overnight') {
                        $sched = $resort->overnight;
                    }
                    return ucfirst($transaction->type).' '.$sched;
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

                ->editColumn('status', function ($transaction) {
                    if($transaction->status == 'pending') {
                        return '<span class="badge badge-secondary">Pending</span>';
                    } elseif($transaction->status == 'approved') {
                        return '<span class="badge badge-warning">Approved</span>';
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
                        $html .='<a class="dropdown-item trigger-approve" data-id="'. $transaction->id .'" data-action="'.route('reservation.approve', $transaction->id).'" data-model="reservation" href="#">Approve</a>';
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

                ->rawColumns(['actions', 'guest', 'service', 'checkin', 'type','status', 'checkout', 'usetype', 'id'])
                ->toJson();
    }

    public function approve($id)
    {
        $transaction = Transaction::findOrfail($id);

        $transaction->update([
            'status' => 'approved',
            'approved_at' => Carbon::now()
        ]);
        $transaction->guest->notify(new ReservationApproved($transaction));
        $msg = "Thank you for choosing Saka Resort. Your reservation: control#".$transaction->id." has been approved. We look forward to hosting your stay.";
        $smsResult = \App\Helpers\CustomSMS::send($transaction->guest->contact, $msg);
        return response('success', 200);
    }

    public function cancel($id)
    {
        $transaction = Transaction::findOrfail($id);

        $transaction->update([
            'status' => 'cancelled',
            'cancelled_at' => Carbon::now()
        ]);
        $transaction->guest->notify(new ReservationCancelled($transaction));
        $msg = "Your reservation has been cancelled. Due to some reasons, we need to cancel your reservation: control#".$transaction->id;
        $smsResult = \App\Helpers\CustomSMS::send($transaction->guest->contact, $msg);
        return response('success', 200);
    }
}
