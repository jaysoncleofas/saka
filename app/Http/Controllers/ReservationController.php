<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Transaction;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use Auth;

class ReservationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $data['pending'] = Transaction::whereIs_reservation(1)->whereStatus('pending')->count();
        return view('admin.reservations.index', $data);
    }

    public function store(Request $request)
    {
        // return $request->all();
        $request->validate([
            'firstName' => 'required',
            'lastName' => 'required',
            'contactNumber' => 'required',
            'checkin' => 'required',
            'checkout' => 'required',
            'adult' => 'required|numeric',
            'kids' => 'required|numeric',
            'senior' => 'nullable|numeric',
            'types' => 'nullable',
            'breakfast' => 'nullable',
            'cottages' => 'required_without:rooms',
            'rooms' => 'required_without:cottages',
        ]);

        $client = Client::whereContact($request->contactNumber)->first();
        if(empty($client)) {
            $client = Client::create([
                'firstName' => $request->firstName,
                'lastName' => $request->lastName,
                'contact' => $request->contactNumber,
            ]);
        }

        $auth = Auth::user();
        $transaction = new Transaction();
        $transaction->client_id = $client->id;
        $transaction->user_id = $auth->id;
        $transaction->checkIn_at = $request->checkin;
        $transaction->checkOut_at = $request->checkout;
        $transaction->adult = $request->adult ?? 0;
        $transaction->kids = $request->kids ?? 0;
        $transaction->senior = $request->senior ?? 0;
        $transaction->type_id = $request->types;
        $transaction->is_breakfast = $request->breakfast;
        $transaction->status = 'pending';
        $transaction->is_reservation = 1;
        $transaction->save();

        $transaction->rooms()->sync($request->rooms);
        $transaction->cottages()->sync($request->cottages);

        session()->flash('notification', 'Your reservation has been sent, expect a call to confirm your reservation.');
        session()->flash('type', 'success');

        return redirect()->back();
    }

    public function datatables()
    {
        $transactions = Transaction::where('is_reservation', 1)->orderBy('created_at', 'asc')->get();

        return DataTables::of($transactions)
                ->editColumn('id', function ($transaction) {
                    return '<a href="'.route('transaction.invoice', $transaction->id).'">INV-'.$transaction->id.'</a>';
                })
                ->addColumn('client', function ($transaction) {
                    return '<a href="'.route('client.show', $transaction->client_id).'" class="btn btn-link">'.$transaction->client->firstName.' '.$transaction->client->lastName.'</a>';
                })
                ->addColumn('cottage', function ($transaction) {
                    $cottages_array = [];
                    foreach ($transaction->cottages as $cottage) {
                        array_push($cottages_array, $cottage->name);
                    }
                    return implode(', ', $cottages_array);
                })
                ->addColumn('room', function ($transaction) {
                    $rooms_array = [];
                    foreach ($transaction->rooms as $room) {
                        array_push($rooms_array, $room->name);
                    }
                    return implode(', ', $rooms_array);
                })
                ->addColumn('checkin', function ($transaction) {
                    if($transaction->checkOut_at) {
                        return $transaction->checkIn_at->format('M d, Y h:i a').' / '.$transaction->checkOut_at->format('M d, Y h:i a');
                        return $transaction->checkOut_at->format('M d, Y h:i a');
                    } else {
                        return $transaction->checkIn_at->format('M d, Y h:i a').' / - ';
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
                ->rawColumns(['actions', 'client', 'cottage', 'checkin', 'id', 'approve','status'])
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
