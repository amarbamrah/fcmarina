<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\CancelBookingReason;
use App\Models\CancelReason;
use App\Models\PointTransaction;
use App\Models\Refund;
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

            $now = Carbon::now();

            $sbs = StadiumBooking::where('user_id', $request['user_id'])
                ->where('status', '!=', 'Processing')
                ->where(function ($query) use ($now) {
                    $query->whereDate('date', '>', $now->toDateString())
                        ->orWhere(function ($query) use ($now) {
                            $query->whereDate('date', '=', $now->toDateString())
                                ->whereTime('from', '>', $now->toTimeString());
                        });
                })
                ->get();
        } else {
            $now = Carbon::now();
            $sbs = StadiumBooking::where('user_id', $request['user_id'])
                ->where('status', '!=', 'Processing')
                ->where(function ($query) use ($now) {
                    $query->whereDate('date', '<', $now->toDateString())
                        ->orWhere(function ($query) use ($now) {
                            $query->whereDate('date', '=', $now->toDateString())
                                ->whereTime('from', '<', $now->toTimeString());
                        });
                })
                ->get();
        }

        foreach ($sbs as $sb) {
            $sb->day = Carbon::create($sb->date)->format('D');

            $sb->f_date = Carbon::create($sb->date)->format('d');
            $sb->fdate = Carbon::create($sb->date)->format('d M y');

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
    public function generateBookingOrder(Request $request)
    {
        $user = User::find($request['user_id']);
        if ($user->phonenumber == '9311911065') {
            $key = "rzp_test_Bn6XzeDx8pXFK4";
            $secret = "gVNSxo5kYjNYfooTPWRu9PCS";
        } else {

            $key = "rzp_live_vjwBasZlFwdr36";
            $secret = "24HHwxlXpmXmARFoXvK1syzH";
        }

        $api = new Api($key, $secret);

        $ad = $request['amount'];

        $razorpayOrder = $api->order->create(
            array(
                'receipt' => 'IM' . Str::random(6),
                'amount' => $ad * 100,
                'currency' => 'INR',
            )
        );

        $booking_id = Str::random(6);
        $sb = new StadiumBooking();
        $redeem = $request['redeem'];
        $from = Carbon::create($request['from']);
        $to = Carbon::create($request['to']);
        $hours = $from->floatDiffInHours($to);

        $payableAmount = $request['total_amount'];
        $bookingAmount = $request['total_amount'];

        $points = $user->points;

        $pointMsg = '';

        $redeemDiscount = 0;

        $welcomeDiscount = 0;

        $hdiscount = $request['hdiscount'];

        $discount = 0;

        $discount = $hdiscount;

        $payableAmount = $payableAmount - $hdiscount;

        $freeHours = 0;

        if ($points > 1000) {
            $ptsToRedeem = 1000;
            $freeHours = $ptsToRedeem / 1000;
            $pointMsg = 'Redeem ' . $ptsToRedeem . ' points to get Rs 1000 off';
        }

        $pointErrMsg = '';

        if ($redeem == 1 && $points > 1000) {
            $perHourPrice = $payableAmount / $hours;
            $redeemDiscount = 1000;

            if ($redeemDiscount > $payableAmount) {
                $redeemDiscount = $payableAmount;
            }
            $discount += $redeemDiscount;

        } else {
            $pointErrMsg = 'Min Points should be 1000';
        }

        $payableAmount = $payableAmount - $redeemDiscount;

        $bCount = StadiumBooking::where('user_id', $request['user_id'])->count();

        $discountMsg = '';

        if ($bCount <= 2) {
            $welcomeDiscount = $hours * 200;
            $discountMsg = '200 off as a Welcome Discount';

            if ($user->max_woffer < $welcomeDiscount) {
                $welcomeDiscount = $user->max_woffer;
            }

            $discount += $welcomeDiscount;

        }

        $payableAmount = $payableAmount - $welcomeDiscount;

        $sb->stadium_id = $request['stadium_id'];
        $sb->user_id = $request['user_id'];
        $sb->sport_type = $request['game'];

        $sb->total_amount = $bookingAmount;

        $sb->payable_amount = $payableAmount;

        $sb->order_id = $razorpayOrder->id;

        if ($request->has('payment_type') && $request['payment_type'] == 'full') {
            $advance = $payableAmount;
        } else {

            $advance = 10 / 100;
            $advance = $advance * $payableAmount;
        }

        // $advance = 10 / 100;
        // $advance = $advance * $payableAmount;

        $sb->discount = $discount;

        $sb->redeem_discount = $redeemDiscount;
        $sb->welcome_discount = $welcomeDiscount;

        $sb->happyhours_discount = $hdiscount;

        $sb->advance = $advance;

        $sb->status = 'Processing';

        $sb->rem_amount = $payableAmount - $advance;

        $sb->stadium_type = $request['stadium_type'];
        $sb->from = $request['from'];
        $sb->to = $request['to'];

        $sb->date = $request['date'];
        $sb->booking_id = 'FC-' . substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ123456789"), 1, 6);

        $sb->save();

        $pts = $request['total_amount'] / 100;
        $pts = round($pts);
        $user = User::find($request['user_id']);

        return ['success' => true, 'orderid' => $razorpayOrder->id, 'key' => $key, 'user' => $user, 'bookingid' => $sb->id];

    }

    public function generateOrder(Request $request)
    {
        $user = User::find($request['user_id']);
        if ($user->phonenumber == '9311911065') {
            $key = "rzp_test_Bn6XzeDx8pXFK4";
            $secret = "gVNSxo5kYjNYfooTPWRu9PCS";
        } else {

            $key = "rzp_live_vjwBasZlFwdr36";
            $secret = "24HHwxlXpmXmARFoXvK1syzH";
        }

        $api = new Api($key, $secret);

        $ad = $request['amount'];

        $razorpayOrder = $api->order->create(
            array(
                'receipt' => 'IM' . Str::random(6),
                'amount' => $ad * 100,
                'currency' => 'INR',
            )
        );

        return ['success' => true, 'orderid' => $razorpayOrder->id, 'key' => $key, 'user' => $user];
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
        $hours = $from->floatDiffInHours($to);

        $payableAmount = $request['total_amount'];
        $bookingAmount = $request['total_amount'];

        $points = $user->points;

        $pointMsg = '';

        $redeemDiscount = 0;

        $welcomeDiscount = 0;

        $hdiscount = $request['hdiscount'];

        $discount = 0;

        $discount = $hdiscount;

        $payableAmount = $payableAmount - $hdiscount;

        $freeHours = 0;

        if ($points > 1000) {
            $ptsToRedeem = 1000;
            $freeHours = $ptsToRedeem / 1000;
            $pointMsg = 'Redeem ' . $ptsToRedeem . ' points to get 1000 Rs off';
        }

        $pointErrMsg = '';

        if ($redeem == 1 && $points > 1000) {
            $perHourPrice = $payableAmount / $hours;
            $redeemDiscount = 1000;

            if ($redeemDiscount > $payableAmount) {
                $redeemDiscount = $payableAmount;
            }
            $discount += $redeemDiscount;

        } else {
            $pointErrMsg = 'Min Points should be 1000';
        }

        $payableAmount = $payableAmount - $redeemDiscount;

        $bCount = StadiumBooking::where('user_id', $request['user_id'])->count();

        $discountMsg = '';

        if ($bCount <= 2) {
            $welcomeDiscount = $hours * 200;
            $discountMsg = '200 off as a Welcome Discount';

            if ($user->max_woffer < $welcomeDiscount) {
                $welcomeDiscount = $user->max_woffer;
            }

            $discount += $welcomeDiscount;

            $user->max_woffer = $user->max_woffer - $welcomeDiscount;
            $user->save();

        }

        $payableAmount = $payableAmount - $welcomeDiscount;

        $sb->stadium_id = $request['stadium_id'];
        $sb->user_id = $request['user_id'];
        $sb->sport_type = $request['game'];

        $sb->total_amount = $bookingAmount;

        $sb->payable_amount = $payableAmount;

        $sb->order_id = $request['order_id'];
        $sb->payment_id = $request['payment_id'];
        $sb->signature = $request['signature'];

        if ($request->has('payment_type') && $request['payment_type'] == 'full') {
            $advance = $payableAmount;
        } else {
            $advance = 10 / 100;
            $advance = $advance * $payableAmount;
        }

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

        if ($redeemDiscount > 0) {
            $pt = new PointTransaction();
            $pt->points = 1000;
            $pt->type = 'db';
            $pt->user_id = $user->id;
            $pt->booking_id = $sb->id;

            $pt->remarks = 'Redeemed For Booking ID:' . $sb->booking_id;
            $pt->save();

            $user->points = $user->points - 1000;
            $user->save();
        }

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
        $user = User::find($request['user_id']);

        // if ($redeemDiscount == 0) {
        //     $pt = new PointTransaction();
        //     $pt->points = $pts * 10;
        //     $pt->type = 'cr';
        //     $pt->user_id = $request['user_id'];
        //     $pt->booking_id = $sb->id;

        //     $pt->remarks = 'Earned From Booking ID:' . $sb->booking_id;
        //     $pt->save();

        //     $user->points = $user->points + $pt->points;
        //     $user->total_points = $user->total_points + $pt->points;
        //     $user->save();
        // }

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

        $phones = StadiumPhone::where('stadium_id', $stadium->id)->get();
        foreach ($phones as $sphone) {
            $url = "http://api.nsite.in/api/v2/SendSMS?SenderId=FCMARI&Is_Unicode=false&Is_Flash=false&Message=Slot%20Booked%20!%20%5CnHi%20" . str_replace(' ', '%20', $user->name) . ",%20you%20have%20booked%20a%20slot%20with%20FC%20MARINA%20BOOK%20APP.%20%5CnVenue%20:%20" . str_replace(' ', '%20', $stadium->name) . "%20%5CnDate%20:%20" . $datee . "%20%5CnTime%20:%20" . $time . "%20%5CnCourt%20:%20" . $sb->stadium_type . "%20%5CnAdvance%20Paid:%20" . $sb->advance . "%20%5CnBalance%20to%20pay:%20" . $sb->rem_amount . "%20%5CnBooking%20ID:%20" . $sb->booking_id . "&MobileNumbers=" . $sphone->phone . "&ApiKey=mLdRdY8ey1ZTzMY0OifcDjaTO7rJ7gMTgsogL8ragGs=&ClientId=7a0c1703-92c1-4a91-918b-4ac7d9b8d1b3";
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

    /**
     * Display the specified resource.
     */
    public function show(StadiumBooking $stadiumBooking)
    {
        $stadiumBooking->user = User::find($stadiumBooking->user_id);
        $stadiumBooking->stadium = Stadium::find($stadiumBooking->stadium_id);

        $stadiumBooking->day = Carbon::create($stadiumBooking->date)->format('D');

        $stadiumBooking->f_date = Carbon::create($stadiumBooking->date)->format('d');

        $stadiumBooking->fdate = Carbon::create($stadiumBooking->date)->format('d M Y');

        $stadiumBooking->f_from = Carbon::create($stadiumBooking->from)->format('h:i a');
        $stadiumBooking->f_to = Carbon::create($stadiumBooking->to)->format('h:i a');

        $stadiumBooking->created_on = Carbon::create($stadiumBooking->created_at)->format('d M Y h:i a');
        $stadiumBooking->cancelled_on = '';
        if ($stadiumBooking->status == 'Cancelled') {
            $cbr = CancelBookingReason::where('booking_id', $stadiumBooking->id)->first();
            $stadiumBooking->cancelled_on = Carbon::create($cbr->created_at)->format('d M Y h:i a');

        }

        $stadiumBooking->booked_by = $stadiumBooking->faculity_id == null ? 'User' : 'Venue';

        if ($stadiumBooking->faculity_id != null) {
            $vm = User::find($stadiumBooking->faculity_id);
            $stadiumBooking->booked_by = $vm->name . ' [VM]';
        }

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

        $fromTime = Carbon::parse($sb->date . ' ' . $sb->from);

        if ($fromTime->diffInHours(Carbon::now()) < 12) {
            $refundAmount = 0;
        }

        return ['success' => true, 'data' => CancelReason::all(), 'refund_amount' => $refundAmount];
    }

    public function cancelBookingWithRefund(Request $request)
    {

        $booking = StadiumBooking::find($request['booking_id']);
        if ($booking->status == 'Cancelled') {
            return ['success' => false, 'msg' => 'This booking has already been cancelled!'];
        }

        $fromTime = Carbon::parse($booking->date . ' ' . $booking->from);

        if ($fromTime < Carbon::now()) {
            return ['success' => false, 'msg' => 'You cant cancel this booking now'];

        }

        $booking->status = 'Cancelled';
        $booking->cancelled_by = $booking->user_id;

        $booking->save();

        $bookingDate = Carbon::create($booking->date);

        $refundAmount = $booking->advance;

        $fromTime = Carbon::parse($booking->date . ' ' . $booking->from);

        if ($fromTime->diffInHours(Carbon::now()) < 12) {
            $refundAmount = 0;
        }

        $cr = CancelReason::find($request['reason_id']);
        $cb = new CancelBookingReason();
        $cb->reason = $cr->title;
        $cb->remarks = $request['remarks'];

        $cb->booking_id = $booking->id;
        $cb->save();

        $api = new Api($key_id, $secret);

        $api->payment->fetch($booking->payment_id)->refund(array(
            "amount" => $refundAmount*100,
            "speed" => "normal", 
            "notes" => array("notes_key_1" => "Refund from booking ".$booking->booking_id), 
            "receipt" => "Receipt No. ".$booking->booking_id));

        if ($refundAmount > 0) {
            $refund = new Refund();
            $refund->amount = $refundAmount;
            $refund->user_id = $booking->user_id;
            $refund->booking_id = $booking->id;
            $refund->refund_id = $refund_id;
            $refund->save();
        }

        $phone = '';
        $username = '';
        if ($booking->user_id != null) {
            $user = User::find($booking->user_id);
            $phone = $user->phonenumber;
            $name = str_replace(' ', '%20', $user->name);

        }

        // points
        if (PointTransaction::where('booking_id', $booking->id)->exists()) {

            $user = User::find($booking->user_id);

            $pt = PointTransaction::where('booking_id', $booking->id)->where('type', 'cr')->first();
            if ($pt) {
                $points = $pt->points;

                $user->points = $user->points - $pt->points;
                $user->total_points = $user->total_points - $pt->points;
                $user->save();

                $pt = new PointTransaction();
                $pt->points = $points;
                $pt->type = 'db';
                $pt->user_id = $user->id;
                $pt->booking_id = $booking->id;

                $pt->remarks = 'Booking Cancellation Booking ID:' . $booking->booking_id;
                $pt->save();
            }

        }

        $stadium = Stadium::find($booking->stadium_id);
        $sname = str_replace(' ', '%20', $stadium->name);

        $bdate = Carbon::create($booking->date)->format('d-M-Y');
        $from = Carbon::create($booking->from)->format('h:i a');
        $to = Carbon::create($booking->to)->format('h:i a');

        $btime = str_replace(' ', '%20', $from . '-' . $to);

        if ($refundAmount > 0) {
            $url = "http://api.nsite.in/api/v2/SendSMS?SenderId=FCMARI&Is_Unicode=false&Is_Flash=false&Message=Booking%20Cancelled%20!%5Cn%20" . $name . "%20has%20cancelled%20his%20FC%20Marina%20booking%20%5CnVenue%20:%20" . $sname . "%20%5CnDate%20:%20" . $bdate . "%20%5CnTime%20:%20" . $btime . "%20%5CnCourt%20:%20" . $booking->stadium_type . "%20%5CnAdvance%20paid%20has%20been%20Refunded%20%5CnBooking%20ID:%20" . $booking->booking_id . "&MobileNumbers=" . $phone . "&ApiKey=mLdRdY8ey1ZTzMY0OifcDjaTO7rJ7gMTgsogL8ragGs=&ClientId=7a0c1703-92c1-4a91-918b-4ac7d9b8d1b3";
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

            $resp = curl_exec($curl);
            curl_close($curl);
        }

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

    public function cancelBooking(Request $request)
    {

        $booking = StadiumBooking::find($request['booking_id']);
        if ($booking->status == 'Cancelled') {
            return ['success' => false, 'msg' => 'This booking has already been cancelled!'];
        }

        $fromTime = Carbon::parse($booking->date . ' ' . $booking->from);

        if ($fromTime < Carbon::now()) {
            return ['success' => false, 'msg' => 'You cant cancel this booking now'];

        }

        $booking->status = 'Cancelled';
        $booking->cancelled_by = $booking->user_id;

        $booking->save();

        $bookingDate = Carbon::create($booking->date);

        $refundAmount = $booking->advance;

        $fromTime = Carbon::parse($booking->date . ' ' . $booking->from);

        if ($fromTime->diffInHours(Carbon::now()) < 12) {
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

        // points
        if (PointTransaction::where('booking_id', $booking->id)->exists()) {

            $user = User::find($booking->user_id);

            $pt = PointTransaction::where('booking_id', $booking->id)->where('type', 'cr')->first();
            if ($pt) {
                $points = $pt->points;

                $user->points = $user->points - $pt->points;
                $user->total_points = $user->total_points - $pt->points;
                $user->save();

                $pt = new PointTransaction();
                $pt->points = $points;
                $pt->type = 'db';
                $pt->user_id = $user->id;
                $pt->booking_id = $booking->id;

                $pt->remarks = 'Booking Cancellation Booking ID:' . $booking->booking_id;
                $pt->save();
            }

        }

        $stadium = Stadium::find($booking->stadium_id);
        $sname = str_replace(' ', '%20', $stadium->name);

        $bdate = Carbon::create($booking->date)->format('d-M-Y');
        $from = Carbon::create($booking->from)->format('h:i a');
        $to = Carbon::create($booking->to)->format('h:i a');

        $btime = str_replace(' ', '%20', $from . '-' . $to);

        if ($refundAmount > 0) {
            $url = "http://api.nsite.in/api/v2/SendSMS?SenderId=FCMARI&Is_Unicode=false&Is_Flash=false&Message=Booking%20Cancelled%20!%5Cn%20" . $name . "%20has%20cancelled%20his%20FC%20Marina%20booking%20%5CnVenue%20:%20" . $sname . "%20%5CnDate%20:%20" . $bdate . "%20%5CnTime%20:%20" . $btime . "%20%5CnCourt%20:%20" . $booking->stadium_type . "%20%5CnAdvance%20paid%20has%20been%20Refunded%20%5CnBooking%20ID:%20" . $booking->booking_id . "&MobileNumbers=" . $phone . "&ApiKey=mLdRdY8ey1ZTzMY0OifcDjaTO7rJ7gMTgsogL8ragGs=&ClientId=7a0c1703-92c1-4a91-918b-4ac7d9b8d1b3";
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

            $resp = curl_exec($curl);
            curl_close($curl);
        }

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

        if ($request->has('payment_type') && $request['payment_type'] != null) {
            $paymentType = $request['payment_type'];

        } else {
            $paymentType = 'advance';

        }
        $redeem = $request['redeem'];
        $from = Carbon::create($request['from']);
        $to = Carbon::create($request['to']);

        $hours = $from->floatDiffInHours($to);
        $user = User::find($request['user_id']);

        $bookingAmount = $request['total_amount'];

        $payableAmount = $request['total_amount'];

        $bCount = StadiumBooking::where('user_id', $request['user_id'])->count();

        $points = $user->points;

        $pointMsg = '';

        $redeemDiscount = 0;

        // happy hours discount
        $hdiscount = $request['hdiscount'];

        $payableAmount = $payableAmount - $hdiscount;

        $discount = $hdiscount;

        $freeHours = 0;
        if ($points > 1000) {
            $ptsToRedeem = 1000;
            $freeHours = $ptsToRedeem / 1000;
            $pointMsg = 'Redeem ' . $ptsToRedeem . ' points to get 1000Rs off';
        }

        $pointErrMsg = '';

        if ($redeem == 1 && $points > 1000) {
            $perHourPrice = $payableAmount / $hours;
            $redeemDiscount = 1000;
            if ($redeemDiscount > $payableAmount) {
                $redeemDiscount = $payableAmount;
            }
            $payableAmount = $payableAmount - $redeemDiscount;

        } else {
            $pointErrMsg = 'Min Points should be 1000';
        }
        $discount += $redeemDiscount;
        $wdiscount = 0;
        $discountMsg = '';

        if ($bCount <= 2) {

            $wdiscount = $hours * 200;

            if ($user->max_woffer < $wdiscount) {
                $wdiscount = $user->max_woffer;
            }
            $discountMsg = '200 off for an hour as a Welcome Discount ';
            $payableAmount = $payableAmount - $wdiscount;

        }

        $discount += $wdiscount;

        $adAmount = $payableAmount * 10;
        $adAmount = $adAmount / 100;

        if ($paymentType == 'advance') {
            $advanceAmount = $payableAmount * 10;
            $advanceAmount = $advanceAmount / 100;
        } else {
            $advanceAmount = $payableAmount;
        }

        return ['success' => true, 'payment_type' => $paymentType, 'amount' => $bookingAmount, 'total_amount' => $payableAmount, 'discount' => $discount, 'discountMsg' => $discountMsg, 'advance_amount' => $adAmount, 'payable_amount' => $advanceAmount, 'points' => $points, 'pointMsg' => $pointMsg, 'hours' => $hours, 'redeem' => $redeem, 'redeemDisc' => $redeemDiscount, 'hdiscount' => $hdiscount, 'wdiscount' => $wdiscount];
    }

    public function ipayStatus(Request $request)
    {
        $booking = StadiumBooking::where('order_id', $request['payload']['payment']['entity']['order_id'])->first();
        if ($booking->status == 'Processing') {
            $booking->status = 'Confirmed';
            $booking->payment_id=$request['payload']['payment']['entity']['id'];
            $booking->save();

            $user = User::find($booking->user_id);

            if ($booking->redeem_discount > 0) {
                $pt = new PointTransaction();
                $pt->points = 1000;
                $pt->type = 'db';
                $pt->user_id = $user->id;
                $pt->booking_id = $booking->id;

                $pt->remarks = 'Redeemed For Booking ID:' . $booking->booking_id;
                $pt->save();

                $user->points = $user->points - 1000;
                $user->save();
            }

            if ($booking->welcome_discount > 0) {
                $user->max_woffer = $user->max_woffer - $booking->welcome_discount;
                $user->save();
            }

            $stadium = Stadium::find($booking->stadium_id);

            $datee = Carbon::create($booking->date)->format('d-m-Y');

            $from = Carbon::create($booking->from)->format('h:i a');
            $to = Carbon::create($booking->to)->format('h:i a');

            $time = str_replace(' ', '%20', $from . '-' . $to);

            $url = "http://api.nsite.in/api/v2/SendSMS?SenderId=FCMARI&Is_Unicode=false&Is_Flash=false&Message=Slot%20Booked%20!%20%5CnHi%20" . str_replace(' ', '%20', $user->name) . ",%20you%20have%20booked%20a%20slot%20with%20FC%20MARINA%20BOOK%20APP.%20%5CnVenue%20:%20" . str_replace(' ', '%20', $stadium->name) . "%20%5CnDate%20:%20" . $datee . "%20%5CnTime%20:%20" . $time . "%20%5CnCourt%20:%20" . $booking->stadium_type . "%20%5CnAdvance%20Paid:%20" . $booking->advance . "%20%5CnBalance%20to%20pay:%20" . $booking->rem_amount . "%20%5CnBooking%20ID:%20" . $booking->booking_id . "&MobileNumbers=" . $user->phonenumber . "&ApiKey=mLdRdY8ey1ZTzMY0OifcDjaTO7rJ7gMTgsogL8ragGs=&ClientId=7a0c1703-92c1-4a91-918b-4ac7d9b8d1b3";
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

//for debug only!
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

            $resp = curl_exec($curl);
            curl_close($curl);

            $phones = StadiumPhone::where('stadium_id', $stadium->id)->get();
            foreach ($phones as $sphone) {
                $url = "http://api.nsite.in/api/v2/SendSMS?SenderId=FCMARI&Is_Unicode=false&Is_Flash=false&Message=Slot%20Booked%20!%20%5CnHi%20" . str_replace(' ', '%20', $user->name) . ",%20you%20have%20booked%20a%20slot%20with%20FC%20MARINA%20BOOK%20APP.%20%5CnVenue%20:%20" . str_replace(' ', '%20', $stadium->name) . "%20%5CnDate%20:%20" . $datee . "%20%5CnTime%20:%20" . $time . "%20%5CnCourt%20:%20" . $booking->stadium_type . "%20%5CnAdvance%20Paid:%20" . $booking->advance . "%20%5CnBalance%20to%20pay:%20" . $booking->rem_amount . "%20%5CnBooking%20ID:%20" . $booking->booking_id . "&MobileNumbers=" . $sphone->phone . "&ApiKey=mLdRdY8ey1ZTzMY0OifcDjaTO7rJ7gMTgsogL8ragGs=&ClientId=7a0c1703-92c1-4a91-918b-4ac7d9b8d1b3";
                $curl = curl_init($url);
                curl_setopt($curl, CURLOPT_URL, $url);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

                //for debug only!
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

                $resp = curl_exec($curl);
                curl_close($curl);
            }
        }
    }

    public function checkBookingStatus(Request $request)
    {
        $booking = StadiumBooking::find($request['booking_id']);
        return ['success' => true, 'status' => $booking->status];
    }

}
