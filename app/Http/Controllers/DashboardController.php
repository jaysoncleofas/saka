<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Cottage;
use App\Models\Room;
use App\Models\Client;

class DashboardController extends Controller
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
        $users = User::count();
        $cottages = Cottage::count();
        $rooms = Room::count();
        $clients = Client::count();
        return view('admin.dashboard', compact('users', 'cottages', 'rooms', 'clients'));
    }
}
