<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\CancelBookingReason;
use App\Models\CancelReason;
use App\Models\PointTransaction;
use App\Models\Stadium;
use App\Models\StadiumBooking;
use App\Models\StadiumPhone;
use App\Models\User;
use App\Models\WalletTransaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Razorpay\Api\Api;

class StadiumBookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request['type'] == 'Upcoming') {
            $sbs = StadiumBooking::where('user_id', $request['user_id'])
                ->whereDate('date', '>', Carbon::today())
                ->orWhere(function ($query) {
                    $query->whereDate('date', '=', Carbon::today())
                        ->whereTime('from', '>', Carbon::now()->format('H:i:s'));
                })
                ->get();
        } else {
            $sbs = StadiumBooking::where('user_id', $request['user_id'])
                ->whereDate('date', '>', Carbon::today())
                ->orWhere(function ($query) {
                    $query->whereDate('date', '=', Carbon::today())
                        ->whereTime('from', '<=', Carbon::now()->format('H:i:s'));
                })
                ->get();
        }

        foreach ($sbs as $sb) {
            $sb->day = Carbon::create($sb->date)->format('D');

            $sb->f_date = Carbon::create($sb->date)->format('d');

            $sb->f_from = Carbon::create($sb->from)->format('h:i A');
            $sb->f_to = Carbon::create($sb->to)->format('h:i A');
            $sb->user = User::find($sb->user_id);
            $sb->stadium = Stadium::find($sb->stadium_id);

            if ($sb->status == 'Cancelled') {
                $name = '';
                if ($sb->cancelled_by == $sb->user_id) {
                    $name = 'You';
                } else {
                    $name = 'FC MARINA';
                }
                $sb->cancel_msg = 'Cancelled By ' . $name;
            }
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

        // $key = "rzp_live_vjwBasZlFwdr36";
        // $secret = "24HHwxlXpmXmARFoXvK1syzH";

        $api = new Api($key, $secret);

        $ad = $request['amount'];

        $razorpayOrder = $api->order->create(
            array(
                'receipt' => 'IM' . Str::random(6),
                'amount' => $ad * 100,
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
        $user = User::find($request['user_id']);

        $booking_id = Str::random(6);
        $sb = new StadiumBooking();
        $redeem = $request['redeem'];
        $from = Carbon::create($request['from']);
        $to = Carbon::create($request['to']);
        $hours=$from->floatDiffInHours($to);

        
        $payableAmount = $request['total_amount'];
        $bookingAmount = $request['total_amount'];

        $points = $user->points;

        $pointMsg = '';

        $redeemDiscount=0;

        $welcomeDiscount=0;

        $hdiscount = $request['hdiscount'];

        $discount=0;

        $discount=$hdiscount;

        $payableAmount=$payableAmount-$hdiscount;

        $freeHours=0;


        if ($points > 1000) {
            $ptsToRedeem = floor($points / 1000) * 1000;
            $freeHours=$ptsToRedeem/1000;
            $pointMsg = 'Redeem '.$ptsToRedeem.' points to get '.$freeHours.'hr game free';
        }

        $pointErrMsg = '';

        if ($redeem == 1 && $points > 1000) {
            $perHourPrice=$payableAmount/$hours;
            $redeemDiscount=$freeHours*$perHourPrice;
            $discount+=$redeemDiscount;
            
        } else {
            $pointErrMsg = 'Min Points should be 1000';
        }

        $payableAmount=$payableAmount-$redeemDiscount;

        

        $bCount = StadiumBooking::where('user_id', $request['user_id'])->count();

        $discountMsg = '';

        if ($bCount <= 2) {
            $discountPer = 10;
            $ndiscount = $discountPer * $payableAmount;
            $welcomeDiscount=$ndiscount/100;
            $discount += $welcomeDiscount;
            $discountMsg = '10% off as a Welcome Discount ';
        }


        $payableAmount=$payableAmount-$welcomeDiscount;


        $sb->stadium_id = $request['stadium_id'];
        $sb->user_id = $request['user_id'];
        $sb->sport_type = $request['game'];

        $sb->total_amount = $bookingAmount;

        $sb->payable_amount = $payableAmount;

        $sb->order_id = $request['order_id'];
        $sb->payment_id = $request['payment_id'];
        $sb->signature = $request['signature'];

        $advance = 10 / 100;
        $advance = $advance * $payableAmount;

        $sb->discount = $discount;

        $sb->redeem_discount = $redeemDiscount;
        $sb->welcome_discount = $welcomeDiscount;

        $sb->happyhours_discount = $hdiscount;




        $sb->advance = $advance;

        $sb->status = 'Confirmed';

        $sb->rem_amount = $payableAmount - $advance;

        $sb->stadium_type = $request['stadium_type'];
        $sb->from = $request['from'];
        $sb->to = $request['to'];

        $sb->date = $request['date'];
        $sb->booking_id = 'FC-' . substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ123456789"), 1, 6);

        $sb->save();

        if ($request['is_wallet']) {
            $pt = new WalletTransaction();
            $pt->amount = $advance;
            $pt->type = 'db';
            $pt->user_id = $sb->user_id;

            $pt->remarks = 'Booking ID:' . $sb->booking_id;
            $pt->save();

            $user = User::find($request['user_id']);

            $user->wallet_amount = $user->wallet_amount - $advance;
            $user->save();

        }

        $pts = $request['total_amount'] / 100;
        $pts = round($pts);

        $pt = new PointTransaction();
        $pt->points = $pts * 10;
        $pt->type = 'cr';
        $pt->user_id = $request['user_id'];

        $pt->remarks = 'Earned From Booking ID:' . $booking_id;
        $pt->save();

        $user = User::find($request['user_id']);

        $user->points = $user->points + $pt->points;
        $user->total_points = $user->total_points + $pt->points;
        $user->save();

        $stadium = Stadium::find($request['stadium_id']);

        $datee = Carbon::create($sb->date)->format('d-m-Y');

        $from = Carbon::create($sb->from)->format('h:i a');
        $to = Carbon::create($sb->to)->format('h:i a');

        $time = str_replace(' ', '%20', $from . '-' . $to);

        $url = "http://api.nsite.in/api/v2/SendSMS?SenderId=FCMARI&Is_Unicode=false&Is_Flash=false&Message=Slot%20Booked%20!%20%5CnHi%20" . str_replace(' ', '%20', $user->name) . ",%20you%20have%20booked%20a%20slot%20with%20FC%20MARINA%20BOOK%20APP.%20%5CnVenue%20:%20" . str_replace(' ', '%20', $stadium->name) . "%20%5CnDate%20:%20" . $datee . "%20%5CnTime%20:%20" . $time . "%20%5CnCourt%20:%20" . $sb->stadium_type . "%20%5CnAdvance%20Paid:%20" . $sb->advance . "%20%5CnBalance%20to%20pay:%20" . $sb->rem_amount . "%20%5CnBooking%20ID:%20" . $sb->booking_id . "&MobileNumbers=" . $user->phonenumber . "&ApiKey=mLdRdY8ey1ZTzMY0OifcDjaTO7rJ7gMTgsogL8ragGs=&ClientId=7a0c1703-92c1-4a91-918b-4ac7d9b8d1b3";
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

//for debug only!
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $resp = curl_exec($curl);
        curl_close($curl);

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

        $stadiumBooking->f_from = Carbon::create($stadiumBooking->from)->format('h:i a');
        $stadiumBooking->f_to = Carbon::create($stadiumBooking->to)->format('h:i a');

        $stadiumBooking->booked_by = $stadiumBooking->faculity_id == null ? 'User' : 'Venue';

        $day_division = '';
        if (Carbon::create($stadiumBooking->from) < Carbon::createFromFormat('H:i a', '06:00 AM')) {
            $day_division = 'Twilight';

        } elseif (Carbon::create($stadiumBooking->from) >= Carbon::createFromFormat('H:i a', '06:00 AM') && Carbon::create($stadiumBooking->from) < Carbon::createFromFormat('H:i a', '12:00 PM')) {
            $day_division = 'Morning';

        } elseif (Carbon::create($stadiumBooking->from) >= Carbon::createFromFormat('H:i a', '12:00 PM') && Carbon::create($stadiumBooking->from) < Carbon::createFromFormat('H:i a', '06:00 PM')) {
            $day_division = 'Noon';

        } elseif (Carbon::create($stadiumBooking->from) >= Carbon::createFromFormat('H:i a', '06:00 PM') && Carbon::create($stadiumBooking->from) < Carbon::createFromFormat('H:i a', '12:00 AM')) {
            $day_division = 'Evening';

        } else {
            //    $day_division='NA';

        }
        $stadiumBooking->day_division = $day_division;
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

    public function getBookingsChartData()
    {

        $data = [];

        $startDate = Carbon::now()->subMonth(7)->startOfMonth();
        $endDate = $startDate->endOfMonth();

        $monthName = $startDate->format('M');

        $from = $startDate->format('Y-m-d');
        $to = $endDate->format('Y-m-d');

        $orders = StadiumBooking::whereDate('created_at', '>=', $from)->whereDate('created_at', '<=', $to)->count();

        $month = ['month' => $monthName, 'total_orders' => $orders, 'from' => $from, 'to' => $to];

        //  array_push($data, $month);

        // $to=

        for ($i = 6; $i >= 0; $i--) {
            $startDate = Carbon::now()->subMonth($i)->startOfMonth();
            $endDate = Carbon::now()->subMonth($i)->endOfMonth();
            $monthName = $startDate->format('M');

            $from = $startDate->format('Y-m-d');
            $to = $endDate->format('Y-m-d');

            $orders = StadiumBooking::whereDate('created_at', '>=', $from)->whereDate('created_at', '<=', $to)->count();

            $month = ['month' => $monthName, 'total_orders' => $orders, 'from' => $from, 'to' => $to];

            array_push($data, $month);

        }

        return ['success' => true, 'data' => $data];
    }

    public function cancelReasons(Request $request)
    {
        $sb = StadiumBooking::find($request['booking_id']);
        $bookingDate = Carbon::create($sb->date);
        $refundAmount = $sb->advance;
        if ($bookingDate->diffInHours(Carbon::now()) < 24) {
            $refundAmount = 0;
        }

        return ['success' => true, 'data' => CancelReason::all(), 'refund_amount' => $refundAmount];
    }

    public function cancelBooking(Request $request)
    {

        $booking = StadiumBooking::find($request['booking_id']);
        if ($booking->status == 'Cancelled') {
            return ['success' => false, 'msg' => 'This booking has already been cancelled!'];
        }
        $booking->status = 'Cancelled';
        $booking->cancelled_by = $booking->user_id;

        $booking->save();

        $bookingDate = Carbon::create($booking->date);

        $refundAmount = $booking->advance;
        if ($bookingDate->diffInHours(Carbon::now()) < 24) {
            $refundAmount = 0;
        }

        $cr = CancelReason::find($request['reason_id']);
        $cb = new CancelBookingReason();
        $cb->reason = $cr->title;
        $cb->remarks = $request['remarks'];

        $cb->booking_id = $booking->id;
        $cb->save();

        if ($refundAmount > 0) {
            $pt = new WalletTransaction();
            $pt->amount = $refundAmount;
            $pt->type = 'cr';
            $pt->user_id = $booking->user_id;

            $pt->remarks = 'Refund From Booking ID:' . $booking->booking_id;
            $pt->save();

            $user = User::find($booking->user_id);
            $user->wallet_amount = $user->wallet_amount + $refundAmount;
            $user->save();

        }

        $phone = '';
        $username = '';
        if ($booking->user_id != null) {
            $user = User::find($booking->user_id);
            $phone = $user->phonenumber;
            $name = str_replace(' ', '%20', $user->name);

        }

        $stadium = Stadium::find($booking->stadium_id);
        $sname = str_replace(' ', '%20', $stadium->name);

        $bdate = Carbon::create($booking->date)->format('d-M-Y');
        $from = Carbon::create($booking->from)->format('h:i a');
        $to = Carbon::create($booking->to)->format('h:i a');

        $btime = str_replace(' ', '%20', $from . '-' . $to);

        $url = "http://api.nsite.in/api/v2/SendSMS?SenderId=FCMARI&Is_Unicode=false&Is_Flash=false&Message=Booking%20Cancelled%20!%5Cn%20" . $name . "%20has%20cancelled%20his%20FC%20Marina%20booking%20%5CnVenue%20:%20" . $sname . "%20%5CnDate%20:%20" . $bdate . "%20%5CnTime%20:%20" . $btime . "%20%5CnCourt%20:%20" . $booking->stadium_type . "%20%5CnAdvance%20paid%20has%20been%20Refunded%20%5CnBooking%20ID:%20" . $booking->booking_id . "&MobileNumbers=" . $phone . "&ApiKey=mLdRdY8ey1ZTzMY0OifcDjaTO7rJ7gMTgsogL8ragGs=&ClientId=7a0c1703-92c1-4a91-918b-4ac7d9b8d1b3";
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $resp = curl_exec($curl);
        curl_close($curl);

        $phones = StadiumPhone::where('stadium_id', $booking->stadium_id)->get();
        foreach ($phones as $sphone) {
            $url = "http://api.nsite.in/api/v2/SendSMS?SenderId=FCMARI&Is_Unicode=false&Is_Flash=false&Message=Booking%20Cancelled%20!%5Cn%20" . $name . "%20has%20cancelled%20his%20FC%20Marina%20booking%20%5CnVenue%20:%20" . $sname . "%20%5CnDate%20:%20" . $bdate . "%20%5CnTime%20:%20" . $btime . "%20%5CnCourt%20:%20" . $booking->stadium_type . "%20%5CnAdvance%20paid%20has%20been%20Refunded%20%5CnBooking%20ID:%20" . $booking->booking_id . "&MobileNumbers=" . $sphone->phone . "&ApiKey=mLdRdY8ey1ZTzMY0OifcDjaTO7rJ7gMTgsogL8ragGs=&ClientId=7a0c1703-92c1-4a91-918b-4ac7d9b8d1b3";
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

            $resp = curl_exec($curl);
            curl_close($curl);
        }

        return ['success' => true];
    }

    public function getSummary(Request $request)
    {
        $redeem = $request['redeem'];
        $from = Carbon::create($request['from']);
        $to = Carbon::create($request['to']);

        $hours=$from->floatDiffInHours($to);
        $user = User::find($request['user_id']);

        $bookingAmount = $request['total_amount'];

        $payableAmount = $request['total_amount'];

        $bCount = StadiumBooking::where('user_id', $request['user_id'])->count();


        $points = $user->points;

        $pointMsg = '';

        $redeemDiscount=0;

        // happy hours discount
        $hdiscount=$request['hdiscount'];
      

        $payableAmount=$payableAmount-$hdiscount;


        $discount = $hdiscount;

        $freeHours=0;
        if ($points > 1000) {
            $ptsToRedeem = floor($points / 1000) * 1000;
            $freeHours=$ptsToRedeem/1000;
            $pointMsg = 'Redeem '.$ptsToRedeem.' points to get '.$freeHours.'hr game free';
        }

        $pointErrMsg = '';

        if ($redeem == 1 && $points > 1000) {
            $perHourPrice=$payableAmount/$hours;
            $redeemDiscount=$freeHours*$perHourPrice;
            $payableAmount=$payableAmount-$redeemDiscount;
            
        } else {
            $pointErrMsg = 'Min Points should be 1000';
        }

        $discount+=$redeemDiscount;


        $wdiscount=0;
        $discountMsg = '';

        if ($bCount <= 4) {
            $discountPer = 10;
            $ndiscount = $discountPer * $payableAmount;
            $wdiscount = $ndiscount / 100;

            $discountMsg = '10% off as a Welcome Discount ';

            $payableAmount=$payableAmount-$wdiscount;

        }

        $discount+=$wdiscount;
        $advanceAmount = $payableAmount * 10;
        $advanceAmount = $advanceAmount / 100;

        
        return ['success' => true, 'amount' => $bookingAmount, 'total_amount' => $payableAmount,'discount' => $discount, 'discountMsg' => $discountMsg, 'payable_amount' => $advanceAmount, 'points' => $points, 'pointMsg' => $pointMsg,'hours'=>$hours,'redeem'=>$redeem,'redeemDisc'=>$redeemDiscount,'hdiscount'=>$hdiscount];
    }
}
