<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
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
        return view('admin.users.index');
    }

    public function edit($id)
    {
        $user = User::find($id);
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);

        $request->validate([
            'firstName' => 'required|min:2',
            'lastName' => 'required|min:2',
            'email' => 'required|email',
            'userType' => 'required',
            'password' => 'nullable|min:6',
        ]);

        $user->firstName = $request->firstName;
        $user->lastName = $request->lastName;
        $user->email = $request->email;
        $user->role_id = $request->userType;
        if($request->password) {
            $user->password = bcrypt($request->password);
        }
        $user->save();

        session()->flash('notification', 'Update Successfull!');
        session()->flash('type', 'success');

        return redirect()->back();
    }

    public function datatables()
    {
        $users = User::with('role')->orderBy('firstName', 'desc')->get();

        return DataTables::of($users)
                ->addColumn('actions', function ($user) {
                    return '<a href="'.route('user.edit', $user->id).'" class="btn btn-primary btn-action mr-1" title="Edit"><i class="fas fa-pencil-alt"></i></a><a class="btn btn-danger btn-action" title="Delete" data-toggle="modal" data-target="#deleteModal" data-backdrop="static" data-keyboard="false" data-id="'.$user->id.'"><i class="fas fa-trash"></i></a>';
                })
                ->rawColumns(['actions'])
                ->toJson();
    }
}
