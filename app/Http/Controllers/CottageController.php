<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Cottage;

class CottageController extends Controller
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
        return view('admin.cottages.index');
    }

    public function create()
    {
        return view('admin.cottages.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'cottage' => 'required|min:2',
            'price' => 'required',
            'overnightPrice' => 'nullable',
            'descriptions' => 'nullable|min:2',
        ]);

        $cottage = new Cottage();
        $cottage->name = $request->cottage;
        $cottage->price = $request->price;
        $cottage->overnightPrice = $request->overnightPrice;
        $cottage->descriptions = $request->descriptions;
        $cottage->save();

        session()->flash('notification', 'Successfully added!');
        session()->flash('type', 'success');

        return redirect()->route('cottage.index');
    }

    public function edit($id)
    {
        $cottage = Cottage::find($id);
        return view('admin.cottages.edit', compact('cottage'));
    }

    public function update(Request $request, $id)
    {
        $cottage = Cottage::find($id);

        $request->validate([
            'cottage' => 'required|min:2',
            'price' => 'required',
            'overnightPrice' => 'nullable',
            'extraPerson' => 'nullable',
            'descriptions' => 'nullable|min:2',
        ]);

        $cottage->name = $request->cottage;
        $cottage->price = $request->price;
        $cottage->overnightPrice = $request->overnightPrice;
        $cottage->descriptions = $request->descriptions;
        $cottage->save();

        session()->flash('notification', 'Successfully updated!');
        session()->flash('type', 'success');

        return redirect()->back();
    }

    public function destroy($id)
    {
        $cottage = Cottage::find($id);
        $cottage->delete();
        
        session()->flash('notification', 'Successfully deleted!');
        session()->flash('type', 'success');
        return response('success', 200);
    }

    public function datatables()
    {
        $cottages = Cottage::orderBy('name', 'desc')->get();

        return DataTables::of($cottages)
                ->addColumn('actions', function ($cottage) {
                    return '<a href="'.route('cottage.edit', $cottage->id).'" class="btn btn-primary btn-action mr-1" title="Edit"><i class="fas fa-pencil-alt"></i></a><a class="btn btn-danger btn-action trigger-delete" title="Delete" data-action="'.route('cottage.destroy', $cottage->id).'" data-model="cottage"><i class="fas fa-trash"></i></a>';
                })
                ->rawColumns(['actions'])
                ->toJson();
    }
}
