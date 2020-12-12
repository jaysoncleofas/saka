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
    public function index()
    {
        $data['active_transactions'] = Transaction::whereStatus('active')->count();
        $data['total_transactions'] = Transaction::count();
        $data['walkin'] = Transaction::whereIs_reservation(0)->count();
        $data['reservation'] = Transaction::whereIs_reservation(1)->count();
        return view('admin.dashboard', $data);
    }

    public function transaction_datatables()
    {
        $transactions = Transaction::whereStatus('active')->orderBy('created_at', 'asc')->get();

        return DataTables::of($transactions)
                ->editColumn('id', function ($transaction) {
                    return '<a href="'.route('transaction.invoice', $transaction->id).'">INV-'.$transaction->id.'</a>';
                })
                ->addColumn('client', function ($transaction) {
                    return '<a href="'.route('client.show', $transaction->client_id).'" class="btn btn-link">'.$transaction->client->firstName.' '.$transaction->client->lastName.'</a>';
                })
                ->addColumn('cottage', function ($transaction) {
                    foreach($transaction->cottages as $cottage) {
                        return $cottage->name;
                    }
                })
                ->addColumn('room', function ($transaction) {
                    foreach($transaction->rooms as $room) {
                        return $room->name;
                    }
                })
                ->addColumn('checkin', function ($transaction) {
                    if($transaction->checkOut_at) {
                        return $transaction->checkIn_at->format('M d, Y h:i a').' / '.$transaction->checkOut_at->format('M d, Y h:i a');
                        return $transaction->checkOut_at->format('M d, Y h:i a');
                    } else {
                        return $transaction->checkIn_at->format('M d, Y h:i a').' / - ';
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
                ->rawColumns(['actions', 'client', 'cottage', 'checkin', 'reservation', 'id'])
                ->toJson();
    }
}
