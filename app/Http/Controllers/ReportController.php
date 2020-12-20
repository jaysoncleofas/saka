<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Transaction;

class ReportController extends Controller
{
    public function index()
    {
        return view('admin.reports.index');
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
}
