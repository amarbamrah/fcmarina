<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\AppUser;
use App\Models\User;

use App\Models\PointTransaction;


use Illuminate\Http\Request;

class UserController extends Controller
{
    public function checkUser(Request $request){
        $login_otp = sprintf("%06d", mt_rand(1, 999999));
        $login_user = User::where('phonenumber',$request['phonenumber'])->first();

        $phno="91" .$request['phonenumber'];
        $url = "http://api.nsite.in/api/v2/SendSMS?SenderId=IMERGE&Is_Unicode=true&Is_Flash=false&Message=Your%20OTP%20for%20iMerge%20is%20" . $login_otp . ".%20Pls%20do%20not%20share%20OTP%20with%20anyone%20for%20security%20reason.%20iMerge&MobileNumbers=" . $phno . "&ApiKey=6bzNYp7wj1STwzvLoQagWntahuB0uw3tTud3C+y/HzI=&ClientId=6438b16f-384e-43fa-88ab-375f55a6c1a9";
        //$url="http://164.52.202.248:6005/api/v2/SendSMS?SenderId=MYSTRT&Is_Unicode=false&Is_Flash=false&Message=Your%20OTP%20for%20Mystreet%20is%20".$otp.".%20Pls%20do%20not%20share%20OTP%20with%20anyone%20for%20security%20reason.&MobileNumbers=".$phno."&ApiKey=6bzNYp7wj1STwzvLoQagWntahuB0uw3tTud3C+y/HzI=&ClientId=6438b16f-384e-43fa-88ab-375f55a6c1a9";
        $json = file_get_contents($url);
        $jsondate = json_decode($json, true);
        if ($jsondate["ErrorCode"] == 0) {


        if($login_user){
            $user_id = $login_user->id;
            User::where('id', $user_id)->update(['otp' => $login_otp]);
            //$login_user->update(['last_otp'=>$login_otp]);
            return ['success'=>true,'message'=>'Existing User'];
        }
        else
        {
            return ['success'=>true, 'message'=>'New User', 'otp'=>$login_otp];
        }
    }
    }

    public function userRegister(Request $request){
        //return $request;
        $login_user = User::where('phonenumber',$request['phonenumber'])->first();
        //return $login_user;
        if($login_user)
        {
            return ['success'=>false, 'message'=>'Already Registered'];            
        }
        else
        {
            $user = new User();
            $user->name = $request->name;
            $user->phonenumber = $request->phonenumber;
            $user->email = $request->email;
            $user->gender = $request->gender;
            $user->age = $request->age;
            $user->points = 500;
            $user->total_points = 500;


            $user->status = 1;
            $user->role = 'User';

            $user->save();
            $user_id = $user->id;

            $pt=new PointTransaction();
            $pt->points=500;
            $pt->type='cr';
            $pt->user_id=$user->id;

            $pt->remarks='Welcome Points';
            $pt->save();


            return ['success'=>true, 'message'=>'Registered Successfully', 'user_id'=>$user_id];
        }
    }

    public function userInfo(Request $request){
        //return $request;

        $user = User::find($request['user_id']);
        //return $user_info;

        if($user){
            return ['success'=>true, 'data'=>$user ];
        }else{
            return ['success'=>false];
        }
    }

    public function validateLogin(Request $request){
        //return $request;

        $user_info = User::where(['phonenumber'=>$request['phonenumber'], 'otp'=>$request['otp']])->first();
        //return $user_info;

        if($user_info){
            return ['success'=>true, 'message'=>'Valid User', 'user_id'=>$user_info->id];
        }else{
            return ['success'=>false, 'message'=>'Invalid Otp'];
        }
    }
}
