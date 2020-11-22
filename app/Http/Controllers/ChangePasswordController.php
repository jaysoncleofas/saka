<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Auth;

class ChangePasswordController extends Controller
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
        return view('admin.change-password');
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        if (Hash::check($request->currentPassword, $user['password'])) {
            $this->validate($request, [
                'currentPassword' => 'required|min:6',
                'newPassword' => 'required|min:6|confirmed',
            ]);
            $user->password = bcrypt($request->newPassword);
            $user->save();

            session()->flash('notification', 'You have successfully changed your password');
            session()->flash('type', 'success');
        } else {
            session()->flash('notification', 'Invalid Current Password');
            session()->flash('type', 'error');
        }
        return redirect()->back();
    }
}
