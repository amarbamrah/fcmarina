<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\BookingPayment;
use App\Models\Location;
use App\Models\Stadium;
use App\Models\StadiumBooking;

use App\Models\HappyHour;

use App\Models\StadiumImage;
use App\Models\User;

use App\Models\StadiumAmenity;
use App\Models\Amenity;

use App\Models\CancelBookingReason;
use App\Models\CancelReason;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Razorpay\Api\Api;

class StadiumController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stadiums = Stadium::all();

        foreach ($stadiums as $stadium) {
            $stadium->images = StadiumImage::where('stadium_id', $stadium->id)->get();
            $loc = Location::find($stadium->location_id);
            $stadium->location_name = $loc->name;

            $stadium->mon5s = $stadium->mon5s*2;
            $stadium->mon7s = $stadium->mon7s*2;

            $slotsLeft = 2;
            $stadium->slots_left = $slotsLeft;

            $happyHours=HappyHour::where('stadium_id',$stadium->id)->first();
            if($happyHours){
                $stadium->happy_hour_msg=$happyHours->discount.'% off from '.Carbon::create($happyHours->from)->format('h:i a'). ' to '.Carbon::create($happyHours->to)->format('h:i a');

            }

        }
        return ['data' => $stadiums, 'success' => true];
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
        $loc = Location::find($stadium->location_id);
        $stadium->location_name = $loc->name;

        $stadium->images = StadiumImage::where('stadium_id', $stadium->id)->get();
        $slotsLeft = 2;
        $stadium->slots_left = $slotsLeft;


        $stadium->mon5s = $stadium->mon5s*2;
        $stadium->mon7s = $stadium->mon7s*2;



        $ams=[];
        $sas=StadiumAmenity::where('stadium_id',$stadium->id)->get();

        foreach($sas as $sa){
            $am=Amenity::find($sa->amenity_id);
            array_push($ams,[
                'title'=>$am->title,
            ]);
        }
        $stadium->amenities= $ams;
      
        $happyHours=HappyHour::where('stadium_id',$stadium->id)->first();
        if($happyHours){
            $stadium->happy_hour_msg=$happyHours->discount.'% off from '.Carbon::create($happyHours->from)->format('h:i a'). ' to '.Carbon::create($happyHours->to)->format('h:i a');

        }
        return ['data' => $stadium, 'success' => true];

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

    public function venueLogin()
    {

    }

    public function bookings(Request $request)
    {
        $user = User::find($request['user_id']);
        $stadium = Stadium::find($user->stadium_id);
        $sbs = StadiumBooking::where('stadium_id', $stadium->id)->whereDate('date', Carbon::Create($request['date']))->get();
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

    public function venueBookings(Request $request)
    {

        $stadium = Stadium::find($request['stadium_id']);
        $sbs = StadiumBooking::where('stadium_id', $stadium->id)->where('status','!=','Processing')->whereDate('date', Carbon::Create($request['date']))->get();

        $bookings = [];
        foreach ($sbs as $sb) {

            $user = User::find($sb->user_id);

            $username = $user == null ? $sb->name : $user->name;

            $booking = [
                'id' => $sb->id,
                'title' => 'Booking ID:' . $sb->booking_id . 'Timeslots:' . $sb->from . '-' . $sb->to . ' ' . $username,
                'date' => $sb->from,
                'booking_id' => $sb->booking_id,

                'f_from' => Carbon::create($sb->from)->format('h:i'),
                'f_to' => Carbon::create($sb->to)->format('h:i'),
                'name' => $username,
                'start' => Carbon::createFromFormat('Y-m-d H:i:s', $sb->date . ' ' . $sb->from, 'Asia/Kolkata'),
                'end' => Carbon::createFromFormat('Y-m-d H:i:s', $sb->date . ' ' . $sb->to),
                'color' => 'transparent',
                'fcolor' => '#FFF2E7',

            ];
            array_push($bookings, $booking);
        }

        return ['success' => true, 'data' => $bookings];

    }

    public function bookingsHistory(Request $request)
    {
        $user = User::find($request['user_id']);
        $stadium = Stadium::find($user->stadium_id);
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

    public function createBooking(Request $request)
    {
        $booking_id = Str::random(6);
        $sb = new StadiumBooking();
        $sb->stadium_id = $request['stadium_id'];

        $sb->stadium_type = $request['stadium_type'];
        $sb->from = $request['from'];

        $sb->sport_type = $request['game'];

        if ($request->has('booked_for') && $request['booked_for'] != null) {
            $sb->booked_for = $request['booked_for'];
            $sb->total_amount = 0;
            $sb->rem_amount = 0;
            $sb->advance = 0;
            $sb->status = 'Completed';


        } else {

            $sb->total_amount = $request['total_amount'];

            $sb->payable_amount = $request['total_amount']-$request['discount'];

            $advance=$sb->payable_amount*10;
            $advance=$advance/100;
            $sb->discount = $request['discount'];
            $sb->rem_amount = $sb->payable_amount;
            $sb->advance = 0;
            $sb->status = 'Processing';

        }

        $sb->to = $request['to'];

        $sb->faculity_id = $request['user_id'];
        $sb->date = $request['date'];
        $sb->booking_id = 'FC-' . substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ123456789"), 1, 6);

        if ($request->has('phone') && User::where('phonenumber', $request['phone'])->exists()) {
            $user = User::where('phonenumber', $request['phone'])->first();
            $sb->phone = $user->phonenumber;
            $sb->email = $user->email;
            $sb->name = $user->name;
            $sb->user_id = $user->id;

        } else {
            $sb->phone = $request['phone'];
            $sb->email = $request['email'];
            $sb->name = $request['name'];
        }

        $sb->save();

        if ($request['booked_for'] == null) {
            $phno = $request['phone'];

            $name = $request['name'];

            // $key = "rzp_live_vjwBasZlFwdr36";
            // $secret = "24HHwxlXpmXmARFoXvK1syzH";

                  $key = "rzp_test_Bn6XzeDx8pXFK4";
       $secret = "gVNSxo5kYjNYfooTPWRu9PCS";

            $api = new Api($key, $secret);

            $amount = $advance*100;

            $response = $api->paymentLink->create(array('amount' => $amount, 'currency' => 'INR', 'accept_partial' => false,
                'description' => 'For FC Marina Booking', 'customer' => array('name' => $name,
                    'contact' => '+91' . $phno), 'notify' => array('sms' => false, 'email' => false),
                'reminder_enable' => false, 'callback_url' => 'https://fcm.imerge.in/rec-paylink-status',
                'callback_method' => 'get'));

            $link = $response->short_url;


            
            $paylinkId=$response->id;

            $sb->paylink_id = $paylinkId;
            $sb->save();

            $link = rawurlencode($link);
            //  $url = "http://api.nsite.in/api/v2/SendSMS?SenderId=FCMARI&Is_Unicode=false&Is_Flash=false&Message=Dear%20" . $name . ",%20please%20make%20the%20payment%20to%20confirm%20your%20slot%20booking%20at%20FC%20MARINA%20Var.%20\nClick:%20" . $link . "%20to%20make%20the%20payment.%20\nPayment%20link%20is%20valid%20for%205%20minutes.%20Thank%20you.%20FC%20MARINA%20BOOKING%20APP.&MobileNumbers=" . $phno . "&ApiKey=mLdRdY8ey1ZTzMY0OifcDjaTO7rJ7gMTgsogL8ragGs=&ClientId=7a0c1703-92c1-4a91-918b-4ac7d9b8d1b3";

            $url = 'http://api.nsite.in/api/v2/SendSMS?SenderId=FCMARI&Is_Unicode=false&Is_Flash=false&Message=Dear%20' . $name . ',%20please%20make%20the%20payment%20to%20confirm%20your%20slot%20booking%20at%20FC%20MARINA%20Var.%20Click:%20' . $link . '%20to%20make%20the%20payment.%20Payment%20link%20is%20valid%20for%205%20minutes.%20Thank%20you.%20FC%20MARINA%20BOOKING%20APP.&MobileNumbers=' . $phno . '&ApiKey=mLdRdY8ey1ZTzMY0OifcDjaTO7rJ7gMTgsogL8ragGs=&ClientId=7a0c1703-92c1-4a91-918b-4ac7d9b8d1b3';
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

//for debug only!
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

            $resp = curl_exec($curl);
            curl_close($curl);
        }

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

        $month = ['month' => $monthName, 'total_sts' => $sts, 'from' => $from, 'to' => $to];

        //  array_push($data, $month);

        // $to=

        for ($i = 6; $i >= 0; $i--) {
            $startDate = Carbon::now()->subMonth($i)->startOfMonth();
            $endDate = Carbon::now()->subMonth($i)->endOfMonth();
            $monthName = $startDate->format('M');

            $from = $startDate->format('Y-m-d');
            $to = $endDate->format('Y-m-d');

            $sts = Stadium::whereDate('created_at', '>=', $from)->whereDate('created_at', '<=', $to)->count();

            $month = ['month' => $monthName, 'total_sts' => $sts, 'from' => $from, 'to' => $to];
            array_push($data, $month);
        }
        return ['success' => true, 'data' => $data];
    }

    public function confirmBookingFromRpay(Request $request)
    {
        $booking=Booking::where('plink_id',$request['razorpay_payment_link_id'])->first();
        if($booking){
            $booking->status='Confirmed';
            $booking->save();
        }
    }


    public function completeBooking(Request $request)
    {
        $booking = StadiumBooking::find($request['booking_id']);
        $booking->status = 'Completed';
        $booking->rem_amount = 0;

        $booking->save();

        $paymentMode = $request['payment_mode'];
        if ($paymentMode == 'CashUpi') {
            $pb = new BookingPayment();
            $pb->amount = $request['upi_amount'];
            $pb->booking_id = $request['booking_id'];
            $pb->user_id = $request['user_id'];

            $pb->payment_mode = 'Cash';
            $pb->save();

            $pb = new BookingPayment();
            $pb->amount = $request['cash_amount'];
            $pb->booking_id = $request['booking_id'];
            $pb->user_id = $request['user_id'];

            $pb->payment_mode = 'Upi';
            $pb->save();
        } else {
            $pb = new BookingPayment();
            $pb->amount = $booking->rem_amount;
            $pb->booking_id = $request['booking_id'];
            $pb->payment_mode = $request['payment_mode'];
            $pb->user_id = $request['user_id'];

            $pb->save();
        }

        return ['success' => true, 'message' => 'Booking Completed Successfully'];
    }

    public function sendPaymentLink(Request $request)
    {
        $phno = $request['phone'];

        $name = $request['name'];

        $key = "rzp_live_vjwBasZlFwdr36";
        $secret = "24HHwxlXpmXmARFoXvK1syzH";

        $api = new Api($key, $secret);

        $amount = $request['amount'];

        $response = $api->paymentLink->create(array('amount' => $amount, 'currency' => 'INR', 'accept_partial' => false,
            'description' => 'For FC Marina Booking', 'customer' => array('name' => $name,
                'contact' => '+91' . $phno), 'notify' => array('sms' => false, 'email' => false),
            'reminder_enable' => false, 'callback_url' => 'https://fcm.imerge.in/rec-paylink-status',
            'callback_method' => 'get'));

        $link = $response->short_url;

        $link = rawurlencode($link);
        //  $url = "http://api.nsite.in/api/v2/SendSMS?SenderId=FCMARI&Is_Unicode=false&Is_Flash=false&Message=Dear%20" . $name . ",%20please%20make%20the%20payment%20to%20confirm%20your%20slot%20booking%20at%20FC%20MARINA%20Var.%20\nClick:%20" . $link . "%20to%20make%20the%20payment.%20\nPayment%20link%20is%20valid%20for%205%20minutes.%20Thank%20you.%20FC%20MARINA%20BOOKING%20APP.&MobileNumbers=" . $phno . "&ApiKey=mLdRdY8ey1ZTzMY0OifcDjaTO7rJ7gMTgsogL8ragGs=&ClientId=7a0c1703-92c1-4a91-918b-4ac7d9b8d1b3";

        $url = 'http://api.nsite.in/api/v2/SendSMS?SenderId=FCMARI&Is_Unicode=false&Is_Flash=false&Message=Dear%20' . $name . ',%20please%20make%20the%20payment%20to%20confirm%20your%20slot%20booking%20at%20FC%20MARINA%20Var.%20Click:%20' . $link . '%20to%20make%20the%20payment.%20Payment%20link%20is%20valid%20for%205%20minutes.%20Thank%20you.%20FC%20MARINA%20BOOKING%20APP.&MobileNumbers=' . $phno . '&ApiKey=mLdRdY8ey1ZTzMY0OifcDjaTO7rJ7gMTgsogL8ragGs=&ClientId=7a0c1703-92c1-4a91-918b-4ac7d9b8d1b3';
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

//for debug only!
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $resp = curl_exec($curl);
        curl_close($curl);

        return ['success' => true, 'link' => $url, 'name' => $name, 'phone' => $phno];

    }



    public function cancelBooking(Request $request)
    {
        $refundType=$request['refund_type'];
        $booking = StadiumBooking::find($request['booking_id']);
        $booking->status = 'Cancelled';
        $booking->cancelled_by=30;
        $booking->save();

        $cr = CancelReason::find($request['reason_id']);
        $cb = new CancelBookingReason();
        $cb->reason = $cr->title;
        $cb->remarks = $request['remarks'];

        $cb->booking_id = $booking->id;
        $cb->save();
        $bookingDate = Carbon::create($booking->date);

        $refundAmount = $booking->advance;
    
        if($refundType="yes"){

        }else{

        }

        return ['success' => true];
    }

    
}
