<?php

namespace App\Http\Controllers;

use App\Models\HappyHour;

use App\Models\Stadium;

use Illuminate\Http\Request;

class HappyHourController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stds=Stadium::all();
        $hrs=HappyHour::all();
        return view('admin.happyhours.index',compact('hrs','stds'));
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
        $happyHour = new HappyHour();
        $happyHour->from = $request['from'];
        $happyHour->to = $request['to'];
        $happyHour->days = json_encode($request['days']);
        $happyHour->user_id = $request['user_id'];
        $happyHour->discount = $request['discount'];
        $happyHour->hours = $request['hours'];

        

        $happyHour->stadium_id= $request['stadium'];

        $happyHour->save();
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(HappyHour $happyHour)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(HappyHour $happyHour)
    {
        $stds=Stadium::all();
        return view('admin.happyhours.edit',compact('happyHour','stds'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, HappyHour $happyHour)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HappyHour $happyHour)
    {
        //
    }
}
