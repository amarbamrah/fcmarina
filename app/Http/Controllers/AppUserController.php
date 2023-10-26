<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PointTransaction;
use Illuminate\Http\Request;


class AppUserController extends Controller
{
    public function index(){
        $appusers = User::where('role', 'User')->get();
        return view('admin.appusers.index', compact('appusers'));
    }

    public function userPoints(Request $request){
        $user=User::find($request['user_id']);
        $trans=PointTransaction::where('user_id',$user->id)->get();
        return view('admin.appusers.points', compact('user','trans'));

    }
}
