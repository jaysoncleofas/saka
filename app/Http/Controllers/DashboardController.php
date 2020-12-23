<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Cottage;
use App\Models\Room;
use App\Models\Client;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Transaction;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $startDay = $request->startdate ? Carbon::parse($request->startdate)->startOfDay() : Carbon::now()->startOfDay();
        $endDay = $request->enddate ? Carbon::parse($request->enddate)->endOfDay() : Carbon::now()->endOfDay();

        $data['active_transactions'] = Transaction::whereStatus('active')->whereBetween('checkIn_at', [$startDay, $endDay])->count();
        // $data['total_transactions'] = Transaction::whereStatus('active')->whereBetween('checkIn_at', [$startDay, $endDay])->count();
        $data['walkin'] = Transaction::whereStatus('active')->whereIs_reservation(0)->whereBetween('checkIn_at', [$startDay, $endDay])->count();
        $data['reservation'] = Transaction::whereStatus('active')->whereIs_reservation(1)->whereBetween('checkIn_at', [$startDay, $endDay])->count();
        return view('admin.dashboard', $data);
    }

    public function transaction_datatables(Request $request)
    {
        $startDay = $request->startdate ? Carbon::parse($request->startdate)->startOfDay() : Carbon::now()->startOfDay();
        $endDay = $request->enddate ? Carbon::parse($request->enddate)->endOfDay() : Carbon::now()->endOfDay();

        $transactions = Transaction::whereStatus('active')->whereBetween('checkIn_at', [$startDay, $endDay])->orderBy('created_at', 'asc')->get();

        return DataTables::of($transactions)
                ->editColumn('id', function ($transaction) {
                    return '<a href="'.route('transaction.invoice', $transaction->id).'">INV-'.$transaction->id.'</a>';
                })
                ->addColumn('client', function ($transaction) {
                    return '<a href="'.route('client.show', $transaction->client_id).'" class="btn btn-link">'.$transaction->client->firstName.' '.$transaction->client->lastName.'</a>';
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
                ->addColumn('reservation', function ($transaction) {
                    if($transaction->is_reservation) {
                        return 'Reservation';
                    }
                    return 'Walk-in';
                })
                ->addColumn('actions', function ($transaction) {
                    return '<a href="'.route('transaction.show', $transaction->id).'" class="btn btn-primary btn-action mr-1" title="Show">Details</a>';
                    // return '<a href="'.route('transaction.show', $transaction->id).'" class="btn btn-info btn-action mr-1" title="Show"><i class="fas fa-eye"></i></a><a href="'.route('transaction.edit', $transaction->id).'" class="btn btn-primary btn-action mr-1" title="Edit"><i class="fas fa-pencil-alt"></i></a><a class="btn btn-danger btn-action trigger-delete" title="Delete" data-action="'.route('transaction.destroy', $transaction->id).'" data-model="transaction"><i class="fas fa-trash"></i></a>';
                })
                ->rawColumns(['actions', 'client', 'cottage', 'checkin', 'checkout', 'reservation', 'id'])
                ->toJson();
    }
}
