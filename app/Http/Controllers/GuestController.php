<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Guest;
use DB;


class GuestController extends Controller
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
        return view('admin.guests.index');
    }

    public function create()
    {
        return view('admin.guests.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'firstName' => 'required|min:2',
            'lastName' => 'required|min:2',
            'middleInitial' => 'nullable|min:2',
            'contact' => 'required|unique:guests',
            'age' => 'nullable',
            'address' => 'nullable',
        ]);

        $guest = new Guest();
        $guest->firstName = $request->firstName;
        $guest->lastName = $request->lastName;
        $guest->middleName = $request->middleInitial;
        $guest->contact = $request->contact;
        $guest->age = $request->age;
        $guest->address = $request->address;
        $guest->save();

        session()->flash('notification', 'Successfully added!');
        session()->flash('type', 'success');

        return redirect()->route('guest.show', compact('guest'));
    }

    public function show($id)
    {
        $guest = Guest::findOrFail($id);
        return view('admin.guests.show', compact('guest'));
    }

    public function edit($id)
    {
        $guest = Guest::findOrFail($id);
        return view('admin.guests.edit', compact('guest'));
    }

    public function update(Request $request, $id)
    {
        $guest = Guest::findOrFail($id);

        $request->validate([
            'firstName' => 'required|min:2',
            'lastName' => 'required|min:2',
            'middleInitial' => 'nullable|min:2',
            'contact' => 'required|unique:guests,contact,'.$guest->id,
            'age' => 'nullable',
            'address' => 'nullable',
        ]);

        $guest->firstName = $request->firstName;
        $guest->lastName = $request->lastName;
        $guest->middleName = $request->middleInitial;
        $guest->contact = $request->contact;
        $guest->age = $request->age;
        $guest->address = $request->address;
        $guest->save();

        session()->flash('notification', 'Successfully updated!');
        session()->flash('type', 'success');

        return redirect()->route('guest.index');
    }

    public function destroy($id)
    {
        $guest = Guest::findOrFail($id);
        $guest->delete();
        
        session()->flash('notification', 'Successfully deleted!');
        session()->flash('type', 'success');
        return response('success', 200);
    }

    public function datatables()
    {
        $guests = Guest::orderBy('firstname', 'desc')->get();

        return DataTables::of($guests)
                ->addColumn('actions', function ($guest) {
                    return '<a href="'.route('guest.show', $guest->id).'" class="btn btn-info btn-action mr-1" title="Show"><i class="fas fa-eye"></i></a><a href="'.route('guest.edit', $guest->id).'" class="btn btn-primary btn-action mr-1" title="Edit"><i class="fas fa-pencil-alt"></i></a><a class="btn btn-danger btn-action trigger-delete" title="Delete" data-action="'.route('guest.destroy', $guest->id).'" data-model="guest"><i class="fas fa-trash"></i></a>';
                })
                ->rawColumns(['actions'])
                ->toJson();
    }

    public function get_guests()
    {
        // $guests = Guest::orderBy('firstName', 'desc')->get();
        $guests = DB::table('guests')->select(DB::raw('CONCAT(firstName, " ", lastName, " (",contact,")") AS text'), 'id')->orderBy('firstName', 'asc')->get();
        return response()->json(['guests' => $guests], 200);
    }
}
