<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Entrancefee;

class EntrancefeeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function edit($id)
    {
        $data['entranceFee'] = Entrancefee::find($id);
        return view('admin.settings.editEntrance', $data);
    }

    public function update(Request $request, $id)
    {
        $entrance = Entrancefee::find($id);

        $request->validate([
            'title' => 'required|min:2',
            'price' => 'required',
            'nightPrice' => 'required',
        ]);

        $entrance->title = $request->title;
        $entrance->price = $request->price;
        $entrance->nightPrice = $request->nightPrice;
        $entrance->save();

        session()->flash('notification', 'Successfully updated!');
        session()->flash('type', 'success');

        return redirect()->route('setting.index');
    }
}
