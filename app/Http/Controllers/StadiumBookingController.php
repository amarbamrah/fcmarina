<?php

namespace App\Http\Controllers;

use App\Models\StadiumBooking;

use App\Models\Stadium;

use Illuminate\Http\Request;

class StadiumBookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $stds=Stadium::all();
        $stadiumbooking=StadiumBooking::all();

        $stadiumbooking = StadiumBooking::query();


        if($request->has('stadium') && $request['stadium']!="all"){
            $stadiumbooking->where('stadium_id', $request['stadium']);
        }

        if($request->has('date') && $request['date']!=null){
            $stadiumbooking->whereDate('date', $request['date']);
        }
        $stadiumbooking=$stadiumbooking->get();
        return view('admin.stadiumbookings.index',compact('stadiumbooking','stds'));
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
    public function show(StadiumBooking $stadiumBooking)
    {
        $booking=$stadiumBooking;
        return view('admin.stadiumbookings.view',compact('booking'));
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StadiumBooking $stadiumBooking)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, StadiumBooking $stadiumBooking)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StadiumBooking $stadiumBooking)
    {
        //
    }
}
