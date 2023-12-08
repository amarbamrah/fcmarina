<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Stadium;
use App\Models\StadiumImage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function venueLogin(Request $request)
    {
        if (Auth::attempt(['email' => $request['username'], 'password' => $request['password']])) {
            $user = Auth::user();
            $token = $request->user()->createToken($request['username']);
            if ($user->role == 'Admin') {
                $stadium = Stadium::first();
            } else {
                $stadium = $user->stadiums[0];
            }

            return ['success' => true, 'user' => $user, 'token' => $token->plainTextToken,'stadium_id'=>$stadium->id];
        } else {
            return ['success' => false];
        }
    }

    public function stadiumDetails(Request $request)
    {
       
            $stadium = Stadium::find($request['stadium_id']);
        
            $stadium->images = StadiumImage::where('stadium_id', $stadium->id)->get();




            return ['success' => true, 'data' => $stadium];
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
    public function vcStadiums(Request $request)
    {
        $user=auth()->user();
        $stadiums=$user->stadiums;
        foreach($stadiums as $stadium){
            $stadium->images = StadiumImage::where('stadium_id', $stadium->id)->get();
        }

        return ['success'=>true,'data'=>$stadiums];
    }

}
