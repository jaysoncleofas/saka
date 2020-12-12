<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cottage;
use App\Models\Room;
use App\Models\Transaction;
use Auth; 
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;

class TransactionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('admin.transactions.index');
    }

    public function create()
    {
        $cottages = Cottage::all();
        $rooms = Room::all();
        return view('admin.transactions.create', compact('cottages', 'rooms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'client' => 'required',
            'checkin' => 'required',
            'checkout' => 'nullable',
            'adult' => 'nullable|numeric',
            'kids' => 'nullable|numeric',
            'senior' => 'nullable|numeric',
            'types' => 'nullable',
            'breakfast' => 'nullable',
            'cottages' => 'nullable',
            'rooms' => 'nullable',
        ]);

        $auth = Auth::user();
        $transaction = new Transaction();
        $transaction->client_id = $request->client;
        $transaction->user_id = $auth->id;
        $transaction->checkIn_at = $request->checkin;
        $transaction->checkOut_at = $request->checkout;
        $transaction->adult = $request->adult;
        $transaction->kids = $request->kids;
        $transaction->senior = $request->senior;
        $transaction->type_id = $request->types;
        $transaction->is_breakfast = $request->breakfast;
        $transaction->status = 'active';
        $transaction->save();

        $transaction->rooms()->sync($request->rooms);
        $transaction->cottages()->sync($request->cottages);

        session()->flash('notification', 'Successfully added!');
        session()->flash('type', 'success');

        return redirect()->route('transaction.show', $transaction->id);
    }

    public function show($id)
    {
        $transaction = Transaction::find($id);
        return view('admin.transactions.show', compact('transaction'));
    }

    public function edit($id)
    {
        $transaction = Transaction::find($id);
        $cottages = Cottage::all();
        $rooms = Room::all();
        return view('admin.transactions.edit', compact('transaction', 'cottages', 'rooms'));
    }

    public function update(Request $request, $id)
    {
        // return $request->all();
        $transaction = Transaction::findOrFail($id);

        $request->validate([
            'client' => 'required',
            'checkin' => 'required',
            'checkout' => 'nullable',
            'adult' => 'nullable|numeric',
            'kids' => 'nullable|numeric',
            'senior' => 'nullable|numeric',
            'types' => 'nullable',
            'breakfast' => 'nullable',
            'cottages' => 'nullable',
            'rooms' => 'nullable',
        ]);
        
        $auth = Auth::user();
        
        $transaction->client_id = $request->client;
        $transaction->user_id = $auth->id;
        $transaction->checkIn_at = $request->checkin;
        $transaction->checkOut_at = $request->checkout;
        $transaction->adult = $request->adult;
        $transaction->kids = $request->kids;
        $transaction->senior = $request->senior;
        $transaction->type_id = $request->types;
        $transaction->is_breakfast = $request->breakfast;
        $transaction->save();

        if (isset($request->rooms)) {
            $transaction->rooms()->sync($request->rooms);
        } else {
            $transaction->rooms()->sync(array());
        }

        if (isset($request->cottages)) {
            $transaction->cottages()->sync($request->cottages);
        } else {
            $transaction->cottages()->sync(array());
        }

        session()->flash('notification', 'Successfully updated!');
        session()->flash('type', 'success');

        return redirect()->route('transaction.show', $transaction->id);
    }

    public function destroy($id)
    {
        $client = Client::find($id);
        $client->delete();
        
        session()->flash('notification', 'Successfully deleted!');
        session()->flash('type', 'success');
        return response('success', 200);
    }

    public function datatables()
    {
        $transactions = Transaction::orderBy('created_at', 'desc')->get();

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
                    } elseif($transaction->status == 'active') {
                        return '<span class="badge badge-primary">Active</span>';
                    } elseif($transaction->status == 'paid') {
                        return '<span class="badge badge-success">Paid</span>';
                    }
                })
                ->addColumn('actions', function ($transaction) {
                    return '<a href="'.route('transaction.show', $transaction->id).'" class="btn btn-info btn-action mr-1" title="Show"><i class="fas fa-eye"></i></a><a href="'.route('transaction.edit', $transaction->id).'" class="btn btn-primary btn-action mr-1" title="Edit"><i class="fas fa-pencil-alt"></i></a><a class="btn btn-danger btn-action trigger-delete" title="Delete" data-action="'.route('transaction.destroy', $transaction->id).'" data-model="transaction"><i class="fas fa-trash"></i></a>';
                })
                ->rawColumns(['actions', 'client', 'cottage', 'room', 'checkin', 'id', 'reservation', 'status'])
                ->toJson();
    }

    public function invoice($id)
    {
        $transaction = Transaction::find($id);
        return view('admin.transactions.invoice', compact('transaction'));
    }

    public function pay($id)
    {
        $transaction = Transaction::findOrfail($id);

        $transaction->update([
            'status' => 'paid'
        ]);

        return response('success', 200);
    }

    public function unpaid($id)
    {
        $transaction = Transaction::findOrfail($id);

        $transaction->update([
            'status' => 'active'
        ]);

        return response('success', 200);
    }
}
