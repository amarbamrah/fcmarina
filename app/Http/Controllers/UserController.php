<?php

namespace App\Http\Controllers;

use App\Models\User;

use App\Models\Stadium;

use App\Models\StadiumUser;


use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function manageAdmins(Request $request)
    {
        if($request->has('isdisabled')){
            $users=User::where('role','VC')->orderBy('id','DESC')->get();

        }else{
            $users=User::where('role','VC')->where('status',1)->orderBy('id','DESC')->get();

        }
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

        $astadiums=$user->stadiums;
        

        return view('admin.masters.adminstadiums',compact('astadiums','stadiums','user'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function assignUser(Request $request)
    {
        $su=new StadiumUser();
        $su->user_id=$request['uid'];
        $su->stadium_id=$request['sid'];
        $su->save();
        return redirect()->back();

    }


    public function removeAssignUser(Request $request)
    {
        $su=StadiumUser::where('stadium_id',$request['sid'])->where('user_id',$request['uid'])->delete();
        return redirect()->back();

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
