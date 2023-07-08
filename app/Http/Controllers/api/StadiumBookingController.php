<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\CancelBookingReason;
use App\Models\CancelReason;
use App\Models\Stadium;
use App\Models\StadiumBooking;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Models\PointTransaction;
use Illuminate\Support\Str;
use Razorpay\Api\Api;

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

        $ad = $request['amount'] * 10;
        $ad = $ad / 100;

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

        $booking_id = Str::random(6);
        $sb = new StadiumBooking();
        $sb->stadium_id = $request['stadium_id'];
        $sb->user_id = $request['user_id'];
        $sb->sport_type = $request['game'];

        $sb->total_amount = $request['total_amount'];

        $advance = 10 / 100;
        $advance = $advance * $request['total_amount'];

        $sb->advance = $advance;

        $sb->status = 'Confirmed';

        $sb->rem_amount = $request['total_amount'] - $advance;

        $sb->stadium_type = $request['stadium_type'];
        $sb->from = $request['from'];
        $sb->to = $request['to'];

        $sb->date = $request['date'];
        $sb->booking_id = 'FC-' . substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ123456789"), 1, 6);

        $sb->save();

        $pts=$request['total_amount']/100;
        $pts=round($pts);

        $pt = new PointTransaction();
        $pt->points = $pts*10;
        $pt->type = 'cr';
        $pt->user_id = $request['user_id'];

        $pt->remarks = 'Earned From Booking ID:'.$booking_id;
        $pt->save();



        $user=User::find($request['user_id']);

        $user->points = $user->points+$pt->points;
            $user->total_points = $user->total_points+$pt->points;
            $user->save();

            $stadium=Stadium::find($request['stadium_id']);

            $date=Carbon::create($sb->date)->format('d M Y');


            $url = 'http://api.nsite.in/api/v2/SendSMS?SenderId=FCMARI&Is_Unicode=false&Is_Flash=false&Message=Slot%20Booked%20!%20%5CnHi%20'.str_replace(' ','%20',$user->name).',%20you%20have%20booked%20a%20slot%20with%20FC%20MARINA%20BOOK%20APP.%20%5CnVenue%20:%20'.$stadium->name.'%20%5CnDate%20:%20'.$date.'%20%5CnTime%20:%20Var%20%5CnCourt%20:%20'.$sb->stadium_type.'%20%5CnAdvance%20Paid:%20'.$sb->advance.'%20%5CnBalance%20to%20pay:%20'.$sb->rem_amount.'%20%5CnBooking%20ID:%20'.$sb->booking_id.'&MobileNumbers='.$user->phonenumber.'&ApiKey=mLdRdY8ey1ZTzMY0OifcDjaTO7rJ7gMTgsogL8ragGs=&ClientId=7a0c1703-92c1-4a91-918b-4ac7d9b8d1b3';

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

        $stadiumBooking->f_from = Carbon::create($stadiumBooking->from)->format('h:i');
        $stadiumBooking->f_to = Carbon::create($stadiumBooking->to)->format('h:i');

        $stadiumBooking->booked_by = 'Venue';

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

    public function cancelReasons()
    {
        return ['success' => true, 'data' => CancelReason::all(), 'refund_amount' => 50];
    }

    public function cancelBooking(Request $request)
    {
        $booking = StadiumBooking::find($request['booking_id']);
        $booking->status = 'Cancelled';
        $booking->save();

        $cr = CancelReason::find($request['reason_id']);
        $cb = new CancelBookingReason();
        $cb->reason = $cr->title;
        $cb->remarks = $request['remarks'];

        $cb->booking_id = $booking->id;
        $cb->save();
        return ['success' => true];
    }

    public function getSummary(Request $request)
    {
        $user = User::find($request['user_id']);
        $total_amount = $request['total_amount'];

        $bCount = StadiumBooking::where('user_id', $request['user_id'])->count();
        $discount = 0;
        $discountPer = 0;

        $discountMsg = '';
        if ($bCount <= 2) {
            $discountPer = 10;
            $discount = $discountPer * $total_amount;
            $discount = $discount / 100;

            $discountMsg = '10% off as a Welcome Discount ';

        }

        $amount = $total_amount - $discount;

        $payable_amount = $amount * 10;
        $payable_amount = $payable_amount / 100;

        return ['success' => true, 'amount' => $total_amount, 'total_amount' => $amount, 'discount' => $discount, 'discountPer' => $discountPer, 'discountMsg' => $discountMsg, 'payable_amount' => $payable_amount];
    }
}
