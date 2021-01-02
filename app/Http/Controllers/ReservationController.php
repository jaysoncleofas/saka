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
        $data['approved'] = Transaction::whereIs_reservation(1)->whereBetween('checkIn_at', [$startDay, $endDay])->whereStatus('active')->count();
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
        $startDay = $request->startdate ? Carbon::parse($request->startdate)->startOfDay() : Carbon::now()->startOfWeek()->startOfDay();
        $endDay = $request->enddate ? Carbon::parse($request->enddate)->endOfDay() : Carbon::now()->endOfMonth();
        // $data['pending'] = Transaction::whereIs_reservation(1)->whereBetween('checkIn_at', [$startDay, $endDay])->whereStatus('pending')->count();
        // $data['approved'] = Transaction::whereIs_reservation(1)->whereBetween('checkIn_at', [$startDay, $endDay])->whereStatus('active')->count();

        $transactions = Transaction::where('is_reservation', 1)->whereBetween('checkIn_at', [$startDay, $endDay])->orderBy('created_at', 'asc')->get();

        return DataTables::of($transactions)
                ->editColumn('id', function ($transaction) {
                    return '<a href="'.route('transaction.invoice', $transaction->id).'">INV-'.$transaction->id.'</a>';
                })
                ->addColumn('guest', function ($transaction) {
                    return '<a href="'.route('guest.show', $transaction->guest_id).'" class="btn btn-link">'.$transaction->guest->firstName.' '.$transaction->guest->lastName.'</a>';
                })
                ->addColumn('cottage', function ($transaction) {
                    if($transaction->cottage) {
                        return $transaction->cottage->name;
                    } else {
                        return '-';
                    }
                })
                ->addColumn('room', function ($transaction) {
                    if($transaction->room) {
                        return $transaction->room->name;
                    } else {
                        return '-';
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
                ->addColumn('approve', function ($transaction) {
                    if($transaction->status == 'paid') {
                        return '-';
                    } else {
                        return '<label class="custom-switch mt-2 pl-0"><input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input active-mode-switch" '.( $transaction->status == 'active' ? 'checked' : '') .' data-id="'. $transaction->id .'" data-action="'.route('reservation.approve').'" data-model="reservation"><span class="custom-switch-indicator"></span><span class="custom-switch-description">Approve</span></label>';
                    }
                })
                ->editColumn('status', function ($transaction) {
                    if($transaction->status == 'pending') {
                        return '<span class="badge badge-secondary">Pending</span>';
                    } elseif($transaction->status == 'active') {
                        return '<span class="badge badge-primary">Active</span>';
                    } elseif($transaction->status == 'paid') {
                        return '<span class="badge badge-success">Paid</span>';
                    }
                })
                ->addColumn('actions', function ($transaction) {
                    return '<a href="'.route('transaction.show', $transaction->id).'" class="btn btn-info btn-action mr-1" title="Show"><i class="fas fa-eye"></i></a><a href="'.route('transaction.edit', $transaction->id).'" class="btn btn-primary btn-action mr-1" title="Edit"><i class="fas fa-pencil-alt"></i></a><a class="btn btn-danger btn-action trigger-delete" title="Delete" data-action="'.route('transaction.destroy', $transaction->id).'" data-model="transaction"><i class="fas fa-trash"></i></a>';
                })
                ->rawColumns(['actions', 'guest', 'cottage', 'checkin', 'checkout', 'id', 'approve','status'])
                ->toJson();
    }

    public function approve(Request $request)
    {
        $transaction = Transaction::findOrfail($request->id);

        $transaction->update([
            'status' => $request->status
        ]);
        $status = $request->status == 'active' ? 'Reservation Approved' : 'Reservation Disapproved';
        return json_encode(['text' => 'success', 'return' => '1', 'status' => $status]);
    }
}
