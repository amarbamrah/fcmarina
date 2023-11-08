<?php

namespace App\Http\Controllers;

use App\Models\User;

use App\Models\Stadium;

use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function manageAdmins()
    {
        $users=User::where('role','VC')->orderBy('id','DESC')->get();
        $stadiums=Stadium::all();

        return view('admin.masters.admins',compact('users','stadiums'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function manageAdminStadiums(Request $request)
    {
        $user=User::find($request['uid']);
        $stadiums=Stadium::all();
        return view('admin.masters.adminstadiums',compact('stadiums','user'));

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
