<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Stadium;

use App\Models\StadiumImage;

use App\Models\StadiumBooking;

use App\Models\User;


use Carbon\Carbon;



use App\Models\Location;

use Illuminate\Http\Request;

use Illuminate\Support\Str;


class StadiumController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stadiums=Stadium::all();
        

        foreach($stadiums as $stadium){
            $stadium->images=StadiumImage::where('stadium_id',$stadium->id)->get();
            $loc=Location::find($stadium->location_id);
            $stadium->location_name=$loc->name;

            $slotsLeft=2;
            $stadium->slots_left=$slotsLeft;

        }
        return ['data'=>$stadiums,'success'=>true];
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
    public function show(Stadium $stadium)
    {
        $loc=Location::find($stadium->location_id);
            $stadium->location_name=$loc->name;
            $stadium->images=StadiumImage::where('stadium_id',$stadium->id)->get();
            $slotsLeft=2;
            $stadium->slots_left=$slotsLeft;

        return ['data'=>$stadium,'success'=>true];

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Stadium $stadium)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Stadium $stadium)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Stadium $stadium)
    {
        //
    }


    public function venueLogin(){

    }





    public function bookings(Request $request){
        $user=User::find($request['user_id']);
        $stadium=Stadium::find($user->stadium_id);
        $sbs = StadiumBooking::where('stadium_id', $stadium->id)->whereDate('date',Carbon::Create($request['date']))->get();
        foreach ($sbs as $sb) {
            $sb->day = Carbon::create($sb->date)->format('D');

            $sb->f_date = Carbon::create($sb->date)->format('d');

            $sb->f_from = Carbon::create($sb->from)->format('h:i');
            $sb->f_to = Carbon::create($sb->to)->format('h:i');
            $sb->user = User::find($sb->user_id);
            $sb->stadium = Stadium::find($sb->stadium_id);
        }
        return ['success' => true, 'data' => $sbs];

    }


    public function bookingsHistory(Request $request){
        $user=User::find($request['user_id']);
        $stadium=Stadium::find($user->stadium_id);
        $sbs = StadiumBooking::where('stadium_id', $stadium->id)->get();
        foreach ($sbs as $sb) {
            $sb->day = Carbon::create($sb->date)->format('D');

            $sb->f_date = Carbon::create($sb->date)->format('d');

            $sb->f_from = Carbon::create($sb->from)->format('h:i');
            $sb->f_to = Carbon::create($sb->to)->format('h:i');
            $sb->user = User::find($sb->user_id);
            $sb->stadium = Stadium::find($sb->stadium_id);
        }
        return ['success' => true, 'data' => $sbs];

    }






    public function createBooking(Request $request){
        $booking_id=Str::random(6);
        $sb = new StadiumBooking();
        $sb->stadium_id = $request['stadium_id'];
        $sb->total_amount = $request['total_amount'];
        $sb->rem_amount = 0;
        $sb->advance = $request['total_amount'];

        $sb->stadium_type = $request['stadium_type'];
        $sb->from = $request['from'];

        $sb->status = 'Confirmed';
        $sb->sport_type = $request['game'];

        $sb->booked_for=$request['booked_for'];


        $sb->to = $request['to'];

        $sb->faculity_id=$request['user_id'];
        $sb->date = $request['date'];
        $sb->booking_id = 'FC-'.substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ123456789"), 1, 6);


        if($request->has('phone') && User::where('phonenumber',$request['phone'])->exists()){
            $user= User::where('phonenumber',$request['phone'])->first();
            $sb->phone = $user->phonenumber;
            $sb->email = $user->email;
            $sb->name = $user->name;
            $sb->user_id = $user->id;

        }else{
            $sb->phone = $request['phone'];
            $sb->email = $request['email'];
            $sb->name = $request['name'];
        }



        $sb->save();
        return ['success' => true, 'booking_id' => $sb->id];
    }





    public function getStadiumChartData()
    {

        $data = [];

        $startDate = Carbon::now()->subMonth(7)->startOfMonth();
        $endDate = $startDate->endOfMonth();

        $monthName = $startDate->format('M');

        $from = $startDate->format('Y-m-d');
        $to = $endDate->format('Y-m-d');

        $sts = Stadium::whereDate('created_at', '>=', $from)->whereDate('created_at', '<=', $to)->count();

        $month = ['month' => $monthName, 'total_sts' => $sts,'from'=>$from,'to'=>$to];

      //  array_push($data, $month);

        // $to=

        for ($i = 6; $i >=0; $i--) {
            $startDate = Carbon::now()->subMonth($i)->startOfMonth();
            $endDate = Carbon::now()->subMonth($i)->endOfMonth();
            $monthName = $startDate->format('M');

            $from = $startDate->format('Y-m-d');
            $to = $endDate->format('Y-m-d');

            $sts = Stadium::whereDate('created_at', '>=', $from)->whereDate('created_at', '<=', $to)->count();

            $month = ['month' => $monthName, 'total_sts' => $sts,'from'=>$from,'to'=>$to];


            array_push($data, $month);

        }

        return ['success' => true, 'data' => $data];
    }


    public function completeBooking(Request $request){
        $booking=Booking::find($request['booking_id']);
        $booking->status='Completed';
        $booking->save();
        return ['success'=>true,'message'=>'Booking Completed Successfully'];
    }
}
