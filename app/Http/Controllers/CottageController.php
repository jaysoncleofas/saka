<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Cottage;
use App\Models\CottageImage;
use Auth;

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
        $user = Auth::user();
        if($user->role_id == 2) {
            return abort(404);
        }
        return view('admin.cottages.index');
    }

    public function create()
    {
        $user = Auth::user();
        if($user->role_id == 2) {
            return abort(404);
        }
        return view('admin.cottages.create');
    }

    public function store(Request $request)
    {
        // return $request->all();
        $request->validate([
            'cottage' => 'required|min:2',
            'price' => 'required',
            'nightPrice' => 'required',
            'units' => 'required',
            'descriptions' => 'nullable|min:2',
            'images.*' => 'bail|nullable|image|mimes:jpg,png,jpeg,gif,svg|max:10000',
        ]);

        $cottage = new Cottage();
        
        if ($request->hasFile('image')) {
            $request->validate([
                'image' => 'bail|image|mimes:jpg,png,jpeg,gif,svg|max:10000',
            ]);
            
            $coverimage = $request->image;
            $covername = time().'cottage.'.$coverimage->getClientOriginalExtension();
            $coverimage->storeAs('public/cottages', $covername);
            $cottage->image = $covername;
        }

        $cottage->name = $request->cottage;
        $cottage->price = $request->price;
        $cottage->nightPrice = $request->nightPrice;
        $cottage->units = $request->units;
        $cottage->descriptions = $request->descriptions;
        $cottage->save();

        if ($request->hasFile('images')) {
            foreach($request->images as $key => $image) {                
                $name = time().'cottage-'.$key.'.'.$image->getClientOriginalExtension();
                $image->storeAs('public/cottages', $name);

                $cottageimage = new CottageImage();
                $cottageimage->cottage_id = $cottage->id;
                $cottageimage->path = $name;
                if($key == 0) {
                    $cottageimage->is_cover = 1;
                }
                $cottageimage->save();
            }
        }

        session()->flash('notification', 'Successfully added!');
        session()->flash('type', 'success');

        return redirect()->route('cottage.index');
    }

    public function edit($id)
    {
        $user = Auth::user();
        if($user->role_id == 2) {
            return abort(404);
        }
        $cottage = Cottage::findOrFail($id);
        return view('admin.cottages.edit', compact('cottage'));
    }

    public function update(Request $request, $id)
    {
        $cottage = Cottage::find($id);

        $request->validate([
            'cottage' => 'required|min:2',
            'price' => 'required',
            'nightPrice' => 'required',
            'units' => 'required',
            'extraPerson' => 'nullable',
            'descriptions' => 'nullable|min:2',
        ]);

        if ($request->hasFile('image')) {
            $request->validate([
                'image' => 'bail|image|mimes:jpg,png,jpeg,gif,svg|max:50000',
            ]);
            
            $image = $request->image;
            $name = time().'cottage.'.$image->getClientOriginalExtension();
            $image->storeAs('public/cottages', $name);
            $cottage->image = $name;
        }

        $cottage->name = $request->cottage;
        $cottage->price = $request->price;
        $cottage->nightPrice = $request->nightPrice;
        $cottage->units = $request->units;
        $cottage->descriptions = $request->descriptions;
        $cottage->save();

        if($request->hasFile('images')) {
            foreach($request->images as $key => $image) {                
                $name = time().'cottage-'.$key.'.'.$image->getClientOriginalExtension();
                $image->storeAs('public/cottages', $name);

                $cottageimage = new CottageImage();
                $cottageimage->cottage_id = $cottage->id;
                $cottageimage->path = $name;

                $cottageimages = CottageImage::where('cottage_id', $cottage->id)->where('is_cover', 1)->first();
                if(!$cottageimages) {
                    $cottageimage->is_cover = 1;
                }
                $cottageimage->save();
            }
        }

        if($request->coverimage) {
            $oldcoverimage = CottageImage::where('cottage_id', $cottage->id)->where('is_cover', 1)->first();
            if($oldcoverimage) {
                $oldcoverimage->update([
                    'is_cover' => 0
                ]);
            }

            $coverimage = CottageImage::findOrFail($request->coverimage);
            $coverimage->update([
                'is_cover' => 1
            ]);
        }

        session()->flash('notification', 'Successfully updated!');
        session()->flash('type', 'success');

        return redirect()->route('cottage.index');
    }

    public function destroy($id)
    {
        $user = Auth::user();
        if($user->role_id == 2) {
            return abort(404);
        }
        $cottage = Cottage::find($id);
        $cottage->delete();
        
        session()->flash('notification', 'Successfully deleted!');
        session()->flash('type', 'success');
        return response('success', 200);
    }

    public function datatables()
    {
        $cottages = Cottage::orderBy('name')->get();

        return DataTables::of($cottages)
                ->addColumn('actions', function ($cottage) {
                    return '<a href="'.route('cottage.edit', $cottage->id).'" class="btn btn-primary btn-action mr-1" title="Edit"><i class="fas fa-pencil-alt"></i></a><a class="btn btn-danger btn-action trigger-delete" title="Delete" data-action="'.route('cottage.destroy', $cottage->id).'" data-model="cottage"><i class="fas fa-trash"></i></a>';
                })
                ->addColumn('image', function ($cottage) {
                    return '<img src="'.($cottage->coverimage() ? asset('storage/cottages/'.$cottage->coverimage()->path) : asset('images/img07.jpg')).'" class="img-fluid img-preview z-depth-1" style="object-fit: cover;height:90px; width:90px;">';
                })
                ->editColumn('price', function ($cottage) {
                    return 'P'.number_format($cottage->price, 0);
                })
                ->editColumn('nightPrice', function ($cottage) {
                    return 'P'.number_format($cottage->nightPrice, 0);
                })
                ->rawColumns(['actions', 'image', 'price', 'nightPrice'])
                ->toJson();
    }

    public function image_remove(Request $request)
    {
        $request->validate([
            'id' => 'required',
        ]);

        $cottage = Cottage::findOrFail($request->id);
        $cottage->image = null;
        $cottage->save();

        session()->flash('notification', 'Successfully removed!');
        session()->flash('type', 'success');

        return response('success', 200);
    }

    public function coverimage_remove(Request $request)
    {
        $request->validate([
            'id' => 'required',
        ]);

        $cottageimage = CottageImage::findOrFail($request->id);
        $is_cover = 0;
        if($cottageimage->is_cover) {
            $is_cover = 1;
            $cottage_id = $cottageimage->cottage_id;
        }
        $cottageimage->delete();

        if($is_cover == 1) {
            $firstcottageimage = CottageImage::where('cottage_id', $cottage_id)->first();
            if($firstcottageimage) {
                $firstcottageimage->update([
                    'is_cover' => 1
                ]);
            }
        }

        session()->flash('notification', 'Successfully removed!');
        session()->flash('type', 'success');

        return response('success', 200);
    }
}
