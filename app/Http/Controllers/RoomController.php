<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Room;
use App\Models\RoomImage;
use Auth;

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
        $user = Auth::user();
        if($user->role_id == 2) {
            return abort(404);
        }
        return view('admin.rooms.index');
    }

    public function create()
    {
        $user = Auth::user();
        if($user->role_id == 2) {
            return abort(404);
        }
        return view('admin.rooms.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'room' => 'required|min:2',
            'price' => 'required',
            'extraPerson' => 'nullable',
            'entrancefee' => 'required',
            'descriptions' => 'nullable|min:2',
            'min' => 'nullable',
            'max' => 'required',
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
        $room->extraPerson = $request->extraPerson;
        $room->entrancefee = $request->entrancefee;
        $room->descriptions = $request->descriptions;
        $room->max = $request->max;
        $room->min = $request->min;
        $room->save();

        if ($request->hasFile('images')) {
            foreach($request->images as $key => $image) {                
                $name = time().'room-'.$key.'.'.$image->getClientOriginalExtension();
                $image->storeAs('public/rooms', $name);

                $roomimage = new RoomImage();
                $roomimage->room_id = $room->id;
                $roomimage->path = $name;
                if($key == 0) {
                    $roomimage->is_cover = 1;
                }
                $roomimage->save();
            }
        }

        session()->flash('notification', 'Successfully added!');
        session()->flash('type', 'success');

        return redirect()->route('room.index');
    }

    public function edit($id)
    {
        $user = Auth::user();
        if($user->role_id == 2) {
            return abort(404);
        }
        $room = Room::find($id);
        return view('admin.rooms.edit', compact('room'));
    }

    public function update(Request $request, $id)
    {
        $room = Room::find($id);

        $request->validate([
            'room' => 'required|min:2',
            'price' => 'required',
            'extraPerson' => 'nullable',
            'entrancefee' => 'required',
            'descriptions' => 'nullable|min:2',
            'min' => 'nullable',
            'max' => 'required',
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
        $room->extraPerson = $request->extraPerson;
        $room->entrancefee = $request->entrancefee;
        $room->descriptions = $request->descriptions;
        $room->max = $request->max;
        $room->min = $request->min;
        $room->save();

        
        if($request->hasFile('images')) {
            foreach($request->images as $key => $image) {                
                $name = time().'room-'.$key.'.'.$image->getClientOriginalExtension();
                $image->storeAs('public/rooms', $name);

                $roomimage = new RoomImage();
                $roomimage->room_id = $room->id;
                $roomimage->path = $name;

                $roomimages = RoomImage::where('room_id', $room->id)->where('is_cover', 1)->first();
                if(!$roomimages) {
                    $roomimage->is_cover = 1;
                }
                $roomimage->save();
            }
        }

        if($request->coverimage) {
            $oldcoverimage = RoomImage::where('room_id', $room->id)->where('is_cover', 1)->first();
            if($oldcoverimage) {
                $oldcoverimage->update([
                    'is_cover' => 0
                ]);
            }

            $coverimage = RoomImage::findOrFail($request->coverimage);
            $coverimage->update([
                'is_cover' => 1
            ]);
        }

        session()->flash('notification', 'Successfully updated!');
        session()->flash('type', 'success');

        return redirect()->route('room.index');
    }

    public function destroy($id)
    {
        $user = Auth::user();
        if($user->role_id == 2) {
            return abort(404);
        }
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
                    return '<img src="'.($room->coverimage() ? asset('storage/rooms/'.$room->coverimage()->path) : asset('images/img07.jpg')).'" class="img-fluid img-preview z-depth-1" style="object-fit: cover;height:90px; width:90px;">';
                })
                ->editColumn('price', function ($room) {
                    return 'P'.number_format($room->price, 0);
                })
                ->editColumn('extraPerson', function ($room) {
                    return ($room->extraPerson !=0 ? 'P'.number_format($room->extraPerson, 0) : '-');
                    // return 'P'.number_format($room->price, 0);
                })
                ->addColumn('capacity', function ($room) {
                    return ($room->min ? $room->min.'-'.$room->max : $room->max);
                    // return 'P'.number_format($room->price, 0);
                })
                ->rawColumns(['actions', 'image', 'price', 'extraPerson', 'capacity'])
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

    public function coverimage_remove(Request $request)
    {
        $request->validate([
            'id' => 'required',
        ]);

        $roomimage = RoomImage::findOrFail($request->id);
        $is_cover = 0;
        if($roomimage->is_cover) {
            $is_cover = 1;
            $room_id = $roomimage->room_id;
        }
        $roomimage->delete();

        if($is_cover == 1) {
            $firstroomimage = RoomImage::where('room_id', $room_id)->first();
            if($firstroomimage) {
                $firstroomimage->update([
                    'is_cover' => 1
                ]);
            }
        }

        session()->flash('notification', 'Successfully removed!');
        session()->flash('type', 'success');

        return response('success', 200);
    }
}
