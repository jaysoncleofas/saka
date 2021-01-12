<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Transaction;
use Carbon\Carbon;
use Auth;
use DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        if($user->role_id == 2) {
            return abort(404);
        }
        $startDay = $request->startdate ? Carbon::parse($request->startdate)->startOfDay() : Carbon::now()->startOfMonth();
        $endDay = $request->enddate ? Carbon::parse($request->enddate)->endOfDay() : Carbon::now()->endOfMonth();
        $data['TotalEntrancefee'] = Transaction::where('status', 'completed')->whereBetween('checkIn_at', [$startDay, $endDay])->sum('totalEntrancefee');
        $data['TotalBill'] = Transaction::where('status', 'completed')->whereBetween('checkIn_at', [$startDay, $endDay])->sum('totalBill');
        
        $data['monthly_sales'] = Transaction::select(
            DB::raw('sum(totalBill) as sums'), 
            DB::raw("DATE_FORMAT(completed_at, '%M-%Y') new_date"),  
            DB::raw('YEAR(completed_at) year, MONTH(completed_at) month'))
            ->groupby('month','year')
            ->where('status', 'completed')
            ->orderBy('completed_at')
            ->pluck('sums');
            $data['total_entrance'] = Transaction::select(
                DB::raw('sum(totalEntranceFee) as sums'), 
                DB::raw("DATE_FORMAT(completed_at, '%M-%Y') new_date"),  
                DB::raw('YEAR(completed_at) year, MONTH(completed_at) month'))
                ->groupby('month','year')
                ->where('status', 'completed')
                ->orderBy('completed_at')
                ->pluck('sums');

            
        // return $data['monthly_sales'];    
        $data['monthly_names'] = Transaction::select(
                    DB::raw("DATE_FORMAT(completed_at, '%M %Y') new_date"),
                    DB::raw('YEAR(completed_at) year, MONTH(completed_at) month'))
                    ->groupby('month','year')
                    ->where('status', 'completed')
                    ->orderBy('completed_at')
                    ->pluck('new_date');


        $data['pending'] = Transaction::whereStatus('pending')->count();
        $data['confirmed'] = Transaction::whereIn('status', ['confirmed', 'active'])->count();
        $data['completed'] = Transaction::whereStatus('completed')->count();
        $data['cancelled'] = Transaction::whereStatus('cancelled')->count();

        return view('admin.reports.index', $data);
    }

    public function datatables(Request $request)
    {
        $startDay = $request->startdate ? Carbon::parse($request->startdate)->startOfDay() : Carbon::now()->startOfMonth();
        $endDay = $request->enddate ? Carbon::parse($request->enddate)->endOfDay() : Carbon::now()->endOfMonth();
        $transactions = Transaction::orderBy('checkIn_at', 'desc')->where('status', 'completed')->whereBetween('checkIn_at', [$startDay, $endDay])->get();
        // return json_encode($startDay);
        return DataTables::of($transactions)
                ->addColumn('guest', function ($transaction) {
                    return '<a href="'.route('guest.show', $transaction->guest_id).'" class="btn btn-link">'.$transaction->guest->firstName.' '.$transaction->guest->lastName.'</a>';
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
                ->editColumn('checkIn_at', function ($transaction) {
                    return $transaction->checkIn_at->format('M d, Y h:i a');
                })
                ->editColumn('totalEntranceFee', function ($transaction) {
                    return 'P'.number_format($transaction->totalEntranceFee, 2);
                })
                ->editColumn('totalBill', function ($transaction) {
                    return 'P'.number_format($transaction->totalBill, 2);
                })
                ->rawColumns(['guest', 'service', 'checkIn_at', 'totalEntranceFee', 'totalBill'])
                ->toJson();
    }
}
