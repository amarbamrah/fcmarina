<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Amenity;
use App\Models\BookingPayment;
use App\Models\CancelBookingReason;
use App\Models\CancelReason;
use App\Models\HappyHour;
use App\Models\Location;
use App\Models\PointTransaction;
use App\Models\Stadium;
use App\Models\StadiumAmenity;
use App\Models\StadiumBooking;
use App\Models\StadiumImage;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Razorpay\Api\Api;

class StadiumController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $stadiums = Stadium::where('status',1)->get();

        if($request->has('disabled') && $request['disabled']==1){
              $stadiums = Stadium::all();

        }
        foreach ($stadiums as $stadium) {
            $stadium->images = StadiumImage::where('stadium_id', $stadium->id)->get();
            $loc = Location::find($stadium->location_id);
            $stadium->location_name = $loc->name;

            $slotsLeft = 2;
            $stadium->slots_left = $slotsLeft;

            $happyHours = HappyHour::where('stadium_id', $stadium->id)->first();
            if ($happyHours) {
                $stadium->happy_hour_msg = $happyHours->discount . '% off from ' . Carbon::create($happyHours->from)->format('h:i a') . ' to ' . Carbon::create($happyHours->to)->format('h:i a');

            }

            $fprice = 0;
            $sprice = 0;

            if ($stadium->type == '5s' || $stadium->type == 'both') {
                switch (Carbon::now()->format('D')) {
                    case 'Mon':$fprice = $stadium->mon5s;
                        break;
                    case 'Tue':$fprice = $stadium->tue5s;
                        break;
                    case 'Wed':$fprice = $stadium->wed5s;
                        break;
                    case 'Thu':$fprice = $stadium->thu5s;
                        break;
                    case 'Fri':$fprice = $stadium->fri5s;
                        break;
                    case 'Sat':$fprice = $stadium->sat5s;
                        break;
                    case 'Sun':$fprice = $stadium->sun5s;
                        break;
                }
            }

            if ($stadium->type == '7s' || $stadium->type == 'both') {
                switch (Carbon::now()->format('D')) {
                    case 'Mon':$sprice = $stadium->mon7s;
                        break;
                    case 'Tue':$sprice = $stadium->tue7s;
                        break;
                    case 'Wed':$sprice = $stadium->wed7s;
                        break;
                    case 'Thu':$sprice = $stadium->thu7s;
                        break;
                    case 'Fri':$sprice = $stadium->fri7s;
                        break;
                    case 'Sat':$sprice = $stadium->sat7s;
                        break;
                    case 'Sun':$sprice = $stadium->sun7s;
                        break;
                }
            }

            $stadium->mon5s = $fprice * 2;
            $stadium->mon7s = $sprice * 2;

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

        $fprice = 0;
        $sprice = 0;
        $nprice = 0;

        if ($stadium->type == '5s' || $stadium->type == 'both' || $stadium->type == '9s') {
            switch (Carbon::now()->format('D')) {
                case 'Mon':$fprice = $stadium->mon5s;
                    break;
                case 'Tue':$fprice = $stadium->tue5s;
                    break;
                case 'Wed':$fprice = $stadium->wed5s;
                    break;
                case 'Thu':$fprice = $stadium->thu5s;
                    break;
                case 'Fri':$fprice = $stadium->fri5s;
                    break;
                case 'Sat':$fprice = $stadium->sat5s;
                    break;
                case 'Sun':$fprice = $stadium->sun5s;
                    break;
            }
        }

        if ($stadium->type == '7s' || $stadium->type == 'both' || $stadium->type == '9s') {
            switch (Carbon::now()->format('D')) {
                case 'Mon':$sprice = $stadium->mon7s;
                    break;
                case 'Tue':$sprice = $stadium->tue7s;
                    break;
                case 'Wed':$sprice = $stadium->wed7s;
                    break;
                case 'Thu':$sprice = $stadium->thu7s;
                    break;
                case 'Fri':$sprice = $stadium->fri7s;
                    break;
                case 'Sat':$sprice = $stadium->sat7s;
                    break;
                case 'Sun':$sprice = $stadium->sun7s;
                    break;
            }
        }

        if ($stadium->type == '9s') {
            switch (Carbon::now()->format('D')) {
                case 'Mon':$nprice = $stadium->mon9s;
                    break;
                case 'Tue':$nprice = $stadium->tue9s;
                    break;
                case 'Wed':$nprice = $stadium->wed9s;
                    break;
                case 'Thu':$nprice = $stadium->thu9s;
                    break;
                case 'Fri':$nprice = $stadium->fri9s;
                    break;
                case 'Sat':$nprice = $stadium->sat9s;
                    break;
                case 'Sun':$nprice = $stadium->sun9s;
                    break;
            }
        }

        $stadium->mon5s = $fprice * 2;
        $stadium->mon7s = $sprice * 2;
        $stadium->mon9s = $nprice * 2;


        $ams = [];
        $sas = StadiumAmenity::where('stadium_id', $stadium->id)->get();

        foreach ($sas as $sa) {
            $am = Amenity::find($sa->amenity_id);
            array_push($ams, [
                'title' => $am->title,
            ]);
        }
        $stadium->amenities = $ams;

        $happyHours = HappyHour::where('stadium_id', $stadium->id)->first();
        if ($happyHours) {
            $stadium->happy_hour_msg = $happyHours->discount . '% off from ' . Carbon::create($happyHours->from)->format('h:i a') . ' to ' . Carbon::create($happyHours->to)->format('h:i a');

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

        $hours = 0;

        $rev = 0;

        $exprev = 0;


        $stadium = Stadium::find($request['stadium_id']);

        if ($request['type'] == 'Cancelled') {
            $sbs = StadiumBooking::where('stadium_id', $stadium->id)->where('status', 'Cancelled')->whereDate('date', Carbon::Create($request['date']))->get();

        } else {
            $sbs = StadiumBooking::where('stadium_id', $stadium->id)->where('status', '!=', 'Cancelled')->where('status', '!=', 'Processing')->whereDate('date', Carbon::Create($request['date']))->get();

        }

        $bookings = [];
        foreach ($sbs as $sb) {

            if ($sb->status == 'Confirmed') {
                $rev += $sb->advance;
                $exprev+=$sb->rem_amount;

            }

            if ($sb->status == 'Completed') {
                $rev += $sb->payable_amount;
            }

            $hours += Carbon::create($sb->to)->floatDiffInHours(Carbon::create($sb->from));

            $user = User::find($sb->user_id);

            $username = $user == null ? $sb->name : $user->name;

            $color = '#129890';

            if ($sb->faculity_id == null) {
                $color = '#129890';

            }

            if ($sb->faculity_id != null && $sb->booked_for != null) {
                $color = '#1764AB';

            }

            if ($sb->faculity_id != null && $sb->booked_for == null) {
                $color = '#2D7813';

            }

            $booking = [
                'id' => $sb->id,
                'title' => 'Booking ID:' . $sb->booking_id . 'Timeslots:' . $sb->from . '-' . $sb->to . ' ' . $username,
                'date' => $sb->from,
                'booking_id' => $sb->booking_id,
                'stadium_type' => $sb->stadium_type,
                'price' => $sb->payable_amount,
                'f_from' => Carbon::create($sb->from)->format('h:i a'),
                'f_to' => Carbon::create($sb->to)->format('h:i a'),
                'name' => $username,
                'status' => $sb->status,
                'booked_for' => $sb->booked_for,
                'booked_by' => $sb->faculity_id == null ? 'User' : 'VM',
                'start' => Carbon::createFromFormat('Y-m-d H:i:s', $sb->date . ' ' . $sb->from, 'Asia/Kolkata'),
                'end' => Carbon::createFromFormat('Y-m-d H:i:s', $sb->date . ' ' . $sb->to),
                'startDate' => Carbon::createFromFormat('Y-m-d H:i:s', $sb->date . ' ' . $sb->from, 'Asia/Kolkata'),
                'endDate' => Carbon::createFromFormat('Y-m-d H:i:s', $sb->date . ' ' . $sb->to),
                'color' => 'transparent',
                'fcolor' => $color,

            ];
            array_push($bookings, $booking);
        }

        return ['success' => true, 'data' => $bookings, 'hours' => $hours, 'revenue' => $rev,'exprev'=>$exprev];

    }

    public function bookingsHistory(Request $request)
    {
        $user = User::find($request['user_id']);
        $stadium = Stadium::find($user->stadium_id);
        $sbs = StadiumBooking::where('stadium_id', $stadium->id)->get();
        foreach ($sbs as $sb) {
            $sb->day = Carbon::create($sb->date)->format('D');

            $sb->f_date = Carbon::create($sb->date)->format('d');

            $sb->f_from = Carbon::create($sb->from)->format('h:i A');
            $sb->f_to = Carbon::create($sb->to)->format('h:i A');
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

            $sb->payable_amount = $request['total_amount'] - $request['discount'];

            $advance = $sb->payable_amount * 10;
            $advance = $advance / 100;
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

            $key = "rzp_live_vjwBasZlFwdr36";
            $secret = "24HHwxlXpmXmARFoXvK1syzH";

            // $key = "rzp_test_Bn6XzeDx8pXFK4";
            // $secret = "gVNSxo5kYjNYfooTPWRu9PCS";

            $api = new Api($key, $secret);

            $amount = $advance * 100;

            $response = $api->paymentLink->create(array('amount' => $amount, 'currency' => 'INR', 'accept_partial' => false,
                'description' => 'For FC Marina Booking', 'customer' => array('name' => $name,
                    'contact' => '+91' . $phno), 'notify' => array('sms' => false, 'email' => false),
                'reminder_enable' => false, 'callback_url' => 'https://fcm.imerge.in/rec-paylink-status',
                'callback_method' => 'get'));

            $link = $response->short_url;

            $paylinkId = $response->id;

            $sb->paylink_id = $paylinkId;
            $sb->save();

            $link = rawurlencode($link);

            $from = Carbon::create($sb->from)->format('h:i a');
            $to = Carbon::create($sb->to)->format('h:i a');

            $btime = str_replace(' ', '%20', $from . '-' . $to);

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
        $booking = Booking::where('plink_id', $request['razorpay_payment_link_id'])->first();
        if ($booking) {
            $booking->status = 'Confirmed';
            $booking->save();
        }
    }

    public function applyDiscount(Request $request)
    {
        $booking = StadiumBooking::find($request['booking_id']);
        $booking->discount=$booking->discount+$request['amount'];
        $booking->vm_discount=$booking->vm_discount+$request['amount'];

        $booking->payable_amount=$booking->payable_amount-$request['amount'];
        $booking->rem_amount=$booking->rem_amount-$request['amount'];
        
        $booking->save();
        return ['success' => true];
    }

    public function completeBooking(Request $request)
    {
        $booking = StadiumBooking::find($request['booking_id']);

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

        $booking->status = 'Completed';
        $booking->rem_amount = 0;

        $booking->save();

        if ($booking->redeem_discount == 0 && $booking->user_id != null) {
            if (PointTransaction::where('booking_id', $booking->id)->where('type', 'cr')->exists()) {
                
            }else{
                $pts = $booking->total_amount / 100;
                $pts = round($pts);
                $user = User::find($booking->user_id);
                $pt = new PointTransaction();
                $pt->points = $pts * 10;
                $pt->type = 'cr';
                $pt->user_id = $user->id;
                $pt->booking_id = $booking->id;
                $pt->remarks = 'Earned From Booking ID:' . $booking->booking_id;
                $pt->save();

                $user->points = $user->points + $pt->points;
                $user->total_points = $user->total_points + $pt->points;
                $user->save();
            }
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
        $refundType = $request['refund_type'];
        $booking = StadiumBooking::find($request['booking_id']);

        if ($booking->status == 'Cancelled') {
            return ['success' => false, 'msg' => 'This booking has already been cancelled!'];
        }

        $booking->status = 'Cancelled';
        $booking->cancelled_by = auth()->user()->id;
        $booking->save();

        $cr = CancelReason::find($request['reason_id']);
        $cb = new CancelBookingReason();
        $cb->reason = $cr->title;
        $cb->remarks = $request['remarks'];

        $cb->booking_id = $booking->id;
        $cb->save();

        $bookingDate = Carbon::create($booking->date);

        $refundAmount = $booking->advance;

        $msg = 'Booking Cancelled Succewssfully';

        if ($refundType == "yes" && $booking->payment_id != null) {
            // $key = "rzp_test_Bn6XzeDx8pXFK4";
            // $secret = "gVNSxo5kYjNYfooTPWRu9PCS";

            $key = "rzp_live_vjwBasZlFwdr36";
            $secret = "24HHwxlXpmXmARFoXvK1syzH";
            $api = new Api($key, $secret);

            $resp = $api->payment->fetch($booking->payment_id)->refund(array(
                "amount" => $refundAmount * 100,
                "speed" => "normal",
                "notes" => array("notes_key_1" => "Refund for cancellation"),
                "receipt" => "Receipt No." . $booking->id));
            $cb->refund_id = $resp->id;
            $cb->refund_amount = $refundAmount;
            $cb->save();
            $msg = 'Booking Cancelled Succewssfully and amount refund initiate';

        } else {

        }

        $uname = '';
        $phone = '';
        if ($booking->user_id == null) {
            $uname = $booking->name;
            $phone = $booking->phone;

        } else {
            $uname = User::find($booking->user_id)->name;
            $phone = User::find($booking->user_id)->phonenumber;

        }

        $stadium = Stadium::find($booking->stadium_id);
        $uname = str_replace(' ', '%20', $uname);

        $sname = str_replace(' ', '%20', $stadium->name);

        $bdate = Carbon::create($booking->date)->format('d-M-Y');
        $from = Carbon::create($booking->from)->format('h:i a');
        $to = Carbon::create($booking->to)->format('h:i a');

        $btime = str_replace(' ', '%20', $from . '-' . $to);

        $ca = 0;

        if ($refundType == "yes") {
            $ca = 0;
        } else {
            $ca = $refundAmount;
        }

        $url = 'http://api.nsite.in/api/v2/SendSMS?SenderId=FCMARI&Is_Unicode=false&Is_Flash=false&Message=Booking%20Declined!%20\n' . $uname . '%20FC%20Marina%20has%20declined%20a%20FC%20Marina%20booking.%20\nVenue%20:%20' . $sname . '%20\nDate%20:' . $bdate . '%20\nTime%20:' . $btime . '%20\nCourt%20:' . $booking->stadium_type . '%20\nCancellation%20Penalty%20of%20' . $ca . '%20has%20been%20charged.%20\nBooking%20ID:%20' . $booking->booking_id . '&MobileNumbers=' . $phone . '&ApiKey=mLdRdY8ey1ZTzMY0OifcDjaTO7rJ7gMTgsogL8ragGs=&ClientId=7a0c1703-92c1-4a91-918b-4ac7d9b8d1b3';

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $resp = curl_exec($curl);
        curl_close($curl);
        return ['success' => true, 'msg' => $msg];
    }

}
