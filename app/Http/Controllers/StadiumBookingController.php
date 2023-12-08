<?php

namespace App\Http\Controllers;

use App\Models\StadiumBooking;

use App\Models\Stadium;

use App\Models\BookingPayment;


use Carbon\Carbon;

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

        if($request->has('status') && $request['status']!="all"){
            $stadiumbooking->where('status', $request['status']);
        }

        if($request->has('date') && $request['date']!=null){
            $stadiumbooking->whereDate('date', $request['date']);
        }else{
            $stadiumbooking->whereDate('date', Carbon::now());

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
        $booking->booking_payments=BookingPayment::where('booking_id',$booking->id)->get();
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
    public function ipayStatus(Request $request)
    {
        $booking=StadiumBooking::where('order_id',$request['payload']['payment']['entity']['order_id'])->frist();
        $booking->status='Confirmed';
        $booking->save();
    }


    public function fetchPaymentFromLink(Request $request){
        
    }

}
