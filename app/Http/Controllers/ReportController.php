<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Transaction;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $startDay = $request->startdate ? Carbon::parse($request->startdate)->startOfDay() : Carbon::now()->subDays(29)->startOfDay();
        $endDay = $request->enddate ? Carbon::parse($request->enddate)->endOfDay() : Carbon::now();
        $data['TotalEntrancefee'] = Transaction::where('status', 'paid')->whereBetween('checkIn_at', [$startDay, $endDay])->sum('totalEntrancefee');
        $data['TotalBill'] = Transaction::where('status', 'paid')->whereBetween('checkIn_at', [$startDay, $endDay])->sum('totalBill');
        return view('admin.reports.index', $data);
    }

    public function datatables(Request $request)
    {
        $startDay = $request->startdate ? Carbon::parse($request->startdate)->startOfDay() : Carbon::now()->subDays(29)->startOfDay();
        $endDay = $request->enddate ? Carbon::parse($request->enddate)->endOfDay() : Carbon::now();
        $transactions = Transaction::orderBy('checkIn_at', 'desc')->where('status', 'paid')->whereBetween('checkIn_at', [$startDay, $endDay])->get();
        // return json_encode($startDay);
        return DataTables::of($transactions)
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
                ->editColumn('checkIn_at', function ($transaction) {
                    return $transaction->checkIn_at->format('M d, Y h:i a');
                })
                ->editColumn('totalEntranceFee', function ($transaction) {
                    return 'P'.number_format($transaction->totalEntranceFee, 2);
                })
                ->editColumn('totalBill', function ($transaction) {
                    return 'P'.number_format($transaction->totalBill, 2);
                })
                ->rawColumns(['client', 'cottage', 'room', 'checkIn_at', 'totalEntranceFee', 'totalBill'])
                ->toJson();
    }
}
