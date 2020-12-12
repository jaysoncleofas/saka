<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Room;

class RoomController extends Controller
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
        return view('admin.rooms.index');
    }

    public function create()
    {
        return view('admin.rooms.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'room' => 'required|min:2',
            'price' => 'required',
            'overnightPrice' => 'nullable',
            'extraPerson' => 'nullable',
            'descriptions' => 'nullable|min:2',
        ]);

        $room = new Room();

        if ($request->hasFile('image')) {
            $request->validate([
                'image' => 'bail|image|mimes:jpg,png,jpeg,gif,svg|max:10000',
            ]);
            
            $image = $request->image;
            $name = time().'room.'.$image->getClientOriginalExtension();
            $image->storeAs('public/rooms', $name);
            $room->image = $name;
        }

        $room->name = $request->room;
        $room->price = $request->price;
        $room->overnightPrice = $request->overnightPrice;
        $room->extraPerson = $request->extraPerson;
        $room->descriptions = $request->descriptions;
        $room->save();

        session()->flash('notification', 'Successfully added!');
        session()->flash('type', 'success');

        return redirect()->route('room.index');
    }

    public function edit($id)
    {
        $room = Room::find($id);
        return view('admin.rooms.edit', compact('room'));
    }

    public function update(Request $request, $id)
    {
        $room = Room::find($id);

        $request->validate([
            'room' => 'required|min:2',
            'price' => 'required',
            'overnightPrice' => 'nullable',
            'extraPerson' => 'nullable',
            'descriptions' => 'nullable|min:2',
        ]);

        if ($request->hasFile('image')) {
            $request->validate([
                'image' => 'bail|image|mimes:jpg,png,jpeg,gif,svg|max:10000',
            ]);
            
            $image = $request->image;
            $name = time().'room.'.$image->getClientOriginalExtension();
            $image->storeAs('public/rooms', $name);
            $room->image = $name;
        }

        $room->name = $request->room;
        $room->price = $request->price;
        $room->overnightPrice = $request->overnightPrice;
        $room->extraPerson = $request->extraPerson;
        $room->descriptions = $request->descriptions;
        $room->save();

        session()->flash('notification', 'Successfully updated!');
        session()->flash('type', 'success');

        return redirect()->route('room.index');
    }

    public function destroy($id)
    {
        $room = Room::find($id);
        $room->delete();
        
        session()->flash('notification', 'Successfully deleted!');
        session()->flash('type', 'success');
        return response('success', 200);
    }

    public function datatables()
    {
        $rooms = Room::orderBy('name', 'desc')->get();

        return DataTables::of($rooms)
                ->addColumn('actions', function ($room) {
                    return '<a href="'.route('room.edit', $room->id).'" class="btn btn-primary btn-action mr-1" title="Edit"><i class="fas fa-pencil-alt"></i></a><a class="btn btn-danger btn-action trigger-delete" title="Delete" data-action="'.route('room.destroy', $room->id).'" data-model="room"><i class="fas fa-trash"></i></a>';
                })
                ->addColumn('image', function ($room) {
                    return '<img src="'.($room->image ? asset('storage/rooms/'.$room->image) : asset('images/img07.jpg')).'" class="img-fluid img-preview z-depth-1" style="object-fit: cover;height:90px; width:90px;">';
                })
                ->editColumn('price', function ($room) {
                    return 'P'.number_format($room->price, 0);
                })
                ->rawColumns(['actions', 'image', 'price'])
                ->toJson();
    }

    public function image_remove(Request $request)
    {
        $request->validate([
            'id' => 'required',
        ]);

        $room = Room::findOrFail($request->id);
        $room->image = null;
        $room->save();

        session()->flash('notification', 'Successfully removed!');
        session()->flash('type', 'success');

        return response('success', 200);
    }
}
