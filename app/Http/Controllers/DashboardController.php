<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Stadium;
use App\Models\StadiumBooking;


class DashboardController extends Controller
{

    public function __construct(){
        return $this->middleware('auth');
    }
    public function dashboard(){
        $tb=StadiumBooking::count();
        $tc=User::where('role','User')->count();
        $ts=Stadium::count();
        return view('admin.index',compact('tb','tc','ts'));
    }
}
