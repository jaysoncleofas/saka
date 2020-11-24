<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cottage;
use App\Models\Room;

class TransactionController extends Controller
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
        return view('admin.clients.index');
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
            'firstName' => 'required|min:2',
            'lastName' => 'required|min:2',
            'middleName' => 'nullable|min:2',
            'contact' => 'nullable',
            'age' => 'nullable',
            'address' => 'nullable',
        ]);

        $client = new Client();
        $client->firstName = $request->firstName;
        $client->lastName = $request->lastName;
        $client->middleName = $request->middleName;
        $client->contact = $request->contact;
        $client->age = $request->age;
        $client->address = $request->address;
        $client->save();

        session()->flash('notification', 'Successfully added!');
        session()->flash('type', 'success');

        return redirect()->route('client.index');
    }

    public function edit($id)
    {
        $client = Client::find($id);
        return view('admin.clients.edit', compact('client'));
    }

    public function update(Request $request, $id)
    {
        $client = Client::find($id);

        $request->validate([
            'firstName' => 'required|min:2',
            'lastName' => 'required|min:2',
            'middleName' => 'nullable|min:2',
            'contact' => 'nullable',
            'age' => 'nullable',
            'address' => 'nullable',
        ]);

        $client->firstName = $request->firstName;
        $client->lastName = $request->lastName;
        $client->middleName = $request->middleName;
        $client->contact = $request->contact;
        $client->age = $request->age;
        $client->address = $request->address;
        $client->save();

        session()->flash('notification', 'Successfully updated!');
        session()->flash('type', 'success');

        return redirect()->back();
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
        $clients = Client::orderBy('firstname', 'desc')->get();

        return DataTables::of($clients)
                ->addColumn('actions', function ($client) {
                    return '<a href="'.route('client.edit', $client->id).'" class="btn btn-primary btn-action mr-1" title="Edit"><i class="fas fa-pencil-alt"></i></a><a class="btn btn-danger btn-action trigger-delete" title="Delete" data-action="'.route('client.destroy', $client->id).'" data-model="client"><i class="fas fa-trash"></i></a>';
                })
                ->rawColumns(['actions'])
                ->toJson();
    }
}
