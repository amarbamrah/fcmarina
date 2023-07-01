<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Stadium;

use Illuminate\Support\Str;
use App\Models\StadiumBooking;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StadiumBookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sbs = StadiumBooking::where('user_id', $request['user_id'])->get();
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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }



    public function generateOrder(Request $request)
    {
       

        $key = "rzp_test_Bn6XzeDx8pXFK4";
        $secret = "gVNSxo5kYjNYfooTPWRu9PCS";
        $api = new Api($key, $secret);

        $razorpayOrder = $api->order->create(
            array(
                'receipt' => 'IM' . Str::random(6),
                'amount' => $request['amount'] * 100,
                'currency' => 'INR',
            )
        );

        return ['success' => true, 'orderid' => $razorpayOrder->id, 'key' => $key, 'user' => User::find($request['user_id'])];
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $booking_id=Str::random(6);
        $sb = new StadiumBooking();
        $sb->stadium_id = $request['stadium_id'];
        $sb->user_id = $request['user_id'];
        $sb->total_amount = $request['total_amount'];

        $advance=10/100;
        $advance=$advance*$request['total_amount'];

        
        $sb->advance = $advance;
        $sb->rem_amount =$request['total_amount']-$advance;

        $sb->stadium_type = $request['stadium_type'];
        $sb->from = $request['from'];
        $sb->to = $request['to'];

        $sb->date = $request['date'];
        $sb->booking_id = 'FC-'.substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ123456789"), 1, 6);

        $sb->save();
        return ['success' => true, 'booking_id' => $sb->id];

    }

    /**
     * Display the specified resource.
     */
    public function show(StadiumBooking $stadiumBooking)
    {
        $stadiumBooking->user = User::find($stadiumBooking->user_id);
        $stadiumBooking->stadium = Stadium::find($stadiumBooking->stadium_id);

        $stadiumBooking->day = Carbon::create($stadiumBooking->date)->format('D');

        $stadiumBooking->f_date = Carbon::create($stadiumBooking->date)->format('d');

        $stadiumBooking->f_from = Carbon::create($stadiumBooking->from)->format('h:i');
        $stadiumBooking->f_to = Carbon::create($stadiumBooking->to)->format('h:i');

        $stadiumBooking->booked_by =  'Venue';





        $day_division='';
        if(Carbon::create($stadiumBooking->from)<Carbon::createFromFormat('H:i a','06:00 AM')){
        $day_division='Twilight';
            
        }elseif(Carbon::create($stadiumBooking->from)>=Carbon::createFromFormat('H:i a','06:00 AM') && Carbon::create($stadiumBooking->from)<Carbon::createFromFormat('H:i a','12:00 PM')){
            $day_division='Morning';

        }elseif(Carbon::create($stadiumBooking->from)>=Carbon::createFromFormat('H:i a','12:00 PM') && Carbon::create($stadiumBooking->from)<Carbon::createFromFormat('H:i a','06:00 PM')){
        $day_division='Noon';

        }elseif(Carbon::create($stadiumBooking->from)>=Carbon::createFromFormat('H:i a','06:00 PM') && Carbon::create($stadiumBooking->from)<Carbon::createFromFormat('H:i a','12:00 AM')){
            $day_division='Evening';
    
            }
            else{
            //    $day_division='NA';

        }
        $stadiumBooking->day_division=$day_division;
        return ['success' => true, 'data' => $stadiumBooking];
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
