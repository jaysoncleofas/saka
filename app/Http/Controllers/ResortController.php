<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Resort;

class ResortController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function update(Request $request, $id)
    {
        $resort = Resort::find($id);

        $request->validate([
            'resortName' => 'required|min:2',
            'email' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'day' => 'required',
            'night' => 'required',
            'overnight' => 'required',
            'breakfastPrice' => 'required',
            'facebook' => 'required',
            'instagram' => 'required',
            'twitter' => 'required',
        ]);

       
        $resort->name = $request->resortName;
        $resort->email = $request->email;
        $resort->phone = $request->phone;
        $resort->address = $request->address;
        $resort->breakfastPrice = $request->breakfastPrice;
        $resort->day = $request->day;
        $resort->night = $request->night;
        $resort->overnight = $request->overnight;
        $resort->facebook = $request->facebook;
        $resort->instagram = $request->instagram;
        $resort->twitter = $request->twitter;
        $resort->is_promo = $request->promo ?? 0;
        $resort->save();

        session()->flash('notification', 'Successfully updated!');
        session()->flash('type', 'success');

        return redirect()->route('setting.index');
    }
}
