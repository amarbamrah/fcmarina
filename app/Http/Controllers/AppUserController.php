<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\StadiumBooking;

use App\Models\PointTransaction;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AppUserController extends Controller
{
    public function index(Request $request){
        $appusers = User::query();
        $appusers=$appusers->where('role','User');
        if($request->has('period')){

            if($request['period']=='All'){
                $appusers=$appusers;
            }

            if($request['period']=='Today'){
                $appusers=$appusers->whereDate('created_at',Carbon::now());
            }

            if($request['period']=='Month'){
                $appusers->whereDate('created_at','>=',Carbon::now()->firstOfMonth())->whereDate('created_at','<=',Carbon::now());
            }

            if($request['period']=='custom'){
                $appusers->whereDate('created_at','>=',$request['from'])->whereDate('created_at','<=',$request['to']);
            }
        }

        
        $appusers=$appusers->paginate(10);
        foreach($appusers as $user){
            if(StadiumBooking::where('user_id',$user->id)->exists()){
                $booking=StadiumBooking::where('user_id',$user->id)->first();
                $user->fstadium=$booking->stadium->name;
                $user->sport=$booking->sport_type;


            }else{
                $user->fstadium='';

            }
        }
        return view('admin.appusers.index', compact('appusers'));
    }

    public function userPoints(Request $request){
        $user=User::find($request['user_id']);
        $trans=PointTransaction::where('user_id',$user->id)->get();
        return view('admin.appusers.points', compact('user','trans'));

    }
}
