<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Breakfast;

class BreakfastController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create()
    {
        return view('admin.settings.addBreakfast');
    }

    public function store(Request $request)
    {
        
        $request->validate([
            'title' => 'required|min:2',
            'price' => 'required',
            'status' => 'nullable',
            'notes' => 'required',
        ]);
            
        $breakfast = new Breakfast;
        $breakfast->title = $request->title;
        $breakfast->price = $request->price;
        $breakfast->is_active = $request->status ? 1 : 0;
        $breakfast->notes = $request->notes;
        $breakfast->save();

        session()->flash('notification', 'Successfully created!');
        session()->flash('type', 'success');

        return redirect()->route('setting.index');
    }

    public function edit($id)
    {
        $data['breakfast'] = Breakfast::find($id);
        return view('admin.settings.editBreakfast', $data);
    }

    public function update(Request $request, $id)
    {
        $breakfast = Breakfast::find($id);

        $request->validate([
            'title' => 'required|min:2',
            'price' => 'required',
            'status' => 'nullable',
            'notes' => 'required',
        ]);

        $breakfast->title = $request->title;
        $breakfast->price = $request->price;
        $breakfast->is_active = $request->status ? 1 : 0;
        $breakfast->notes = $request->notes;
        $breakfast->save();

        session()->flash('notification', 'Successfully updated!');
        session()->flash('type', 'success');

        return redirect()->route('setting.index');
    }

    public function destroy($id)
    {
        $breakfast = Breakfast::findOrFail($id);
        $breakfast->delete();
        
        session()->flash('notification', 'Successfully deleted!');
        session()->flash('type', 'success');
        return response('success', 200);
    }
}
