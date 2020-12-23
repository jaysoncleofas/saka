<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Entrancefee;
use App\Models\Breakfast;
use App\Models\Resort;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $data['entranceFees'] = Entrancefee::all();
        $data['breakfasts'] = Breakfast::all();
        $data['resort'] = Resort::find(1);
        return view('admin.settings.index', $data);
    }
}
