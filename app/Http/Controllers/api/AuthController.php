<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\Stadium;
use App\Models\StadiumImage;




use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function venueLogin(Request $request)
    {
        if (Auth::attempt(['email' => $request['username'], 'password' => $request['password']])) {
            $user= Auth::user();
         //   $token = $user()->createToken($request['username']);
            return ['success' => true, 'user' =>$user,'token'=>'abc'];
        } else {
            return ['success' => false];
        }
    }


    public function stadiumDetails(Request $request)
    {
        $user=User::find($request['user_id']);
        $stadium=Stadium::find($user->stadium_id);
        $stadium->images=StadiumImage::where('stadium_id',$stadium->id)->get();
        return ['success'=>true,'data'=>$stadium];
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }


    


}
