<?php

namespace App\Http\Controllers;

use App\Exports\ReportExport;
use App\Models\Amenity;
use App\Models\Location;
use App\Models\Stadium;
use App\Models\StadiumAmenity;
use App\Models\StadiumBooking;
use App\Models\StadiumImage;
use App\Models\User;
use App\Models\StadiumPhone;

use App\Models\BlockedSlot;
use Illuminate\Support\Str;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

class StadiumController extends Controller
{

    public function index()
    {
        $stadium = Stadium::all();
        return view('admin.stadiums.index', compact('stadium'));

    }

    public function create()
    {
        $ameneties = Amenity::all();
        $locations = Location::all();
        return view('admin.stadiums.create', compact('locations', 'ameneties'));
    }

    public function edit(Stadium $stadium)
    {
        $locations = Location::all();
        $st = $stadium;

        $ameneties = Amenity::all();

        $sams = StadiumAmenity::where('stadium_id', $stadium->id)->get();

        $sas = [];

        foreach ($sams as $sam) {
            array_push($sas, $sam->amenity_id);
        }

        return view('admin.stadiums.edit', compact('st', 'locations', 'ameneties', 'sas'));
    }

    public function store(Request $request)
    {

        $ams = $request['ams'];
        $stadium = new Stadium();
        $stadium->name = $request['name'];
        $stadium->description = $request['description'];
        $stadium->location_id = $request['location'];
        $stadium->type = $request['type'];
        $stadium->address = $request['address'];
        $stadium->contactno = $request['contactno'];

        // saving prices
        $stadium->mon5s = $request['mon5s'];
        $stadium->mon7s = $request['mon7s'];
        $stadium->tue5s = $request['tue5s'];
        $stadium->tue7s = $request['tue7s'];
        $stadium->wed5s = $request['wed5s'];
        $stadium->wed7s = $request['wed7s'];
        $stadium->thu5s = $request['thu5s'];
        $stadium->thu7s = $request['thu7s'];
        $stadium->fri5s = $request['fri5s'];
        $stadium->fri7s = $request['fri7s'];
        $stadium->sat5s = $request['sat5s'];
        $stadium->sat7s = $request['sat7s'];
        $stadium->sun5s = $request['sun5s'];
        $stadium->sun7s = $request['sun7s'];

        $stadium->save();

        $image = $request->file('image');
        $filename = time() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('stadiums'), $filename);

        $simg = new StadiumImage();
        $simg->image = 'stadiums/' . $filename;
        $simg->stadium_id = $stadium->id;
        $simg->save();

        $user = new User();
        $user->name = $stadium->name;
        $user->email = $request->name . '@fcmarina.com';
        $user->status = 1;
        $user->role = 'VC';

        $user->stadium_id = $stadium->id;
        $user->password = Hash::make('fcmarina@123');

        $user->save();

        foreach ($ams as $am) {
            $sa = new StadiumAmenity();
            $sa->stadium_id = $stadium->id;
            $sa->amenity_id = $am;
            $sa->save();
        }
        return redirect('/admin/stadiums');

    }

    public function update(Request $request, $id)
    {

        $ams = $request['ams'];

        $stadium = Stadium::find($id);
        $stadium->name = $request['name'];
        $stadium->description = $request['description'];
        $stadium->location_id = $request['location'];
        $stadium->type = $request['type'];
        $stadium->address = $request['address'];
        $stadium->contactno = $request['contactno'];

        // saving prices
        $stadium->mon5s = $request['mon5s'];
        $stadium->mon7s = $request['mon7s'];
        $stadium->tue5s = $request['tue5s'];
        $stadium->tue7s = $request['tue7s'];
        $stadium->wed5s = $request['wed5s'];
        $stadium->wed7s = $request['wed7s'];
        $stadium->thu5s = $request['thu5s'];
        $stadium->thu7s = $request['thu7s'];
        $stadium->fri5s = $request['fri5s'];
        $stadium->fri7s = $request['fri7s'];
        $stadium->sat5s = $request['sat5s'];
        $stadium->sat7s = $request['sat7s'];
        $stadium->sun5s = $request['sun5s'];
        $stadium->sun7s = $request['sun7s'];

        $stadium->save();

        if ($request->file('image') != null) {

            $image = $request->file('image');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('stadiums'), $filename);

            $simg = new StadiumImage();
            $simg->image = 'stadiums/' . $filename;
            $simg->stadium_id = $stadium->id;
            $simg->save();
        }

        StadiumAmenity::where('stadium_id', $stadium->id)->delete();

        foreach ($ams as $am) {
            $sa = new StadiumAmenity();
            $sa->stadium_id = $stadium->id;
            $sa->amenity_id = $am;
            $sa->save();
        }

        return redirect('admin/stadiums');

    }

    public function reports(Request $request)
    {
        $stds = Stadium::all();

        $stadiumbookings = StadiumBooking::query();

        $month = $request->has('period') && $request['period'] == 'curr' ? Carbon::now() : Carbon::now();

        $dates = CarbonPeriod::since($month->startOfMonth())->days(1)->until($month->endOfMonth());

        if ($request->has('period') && $request['period'] == 'custom') {
            $dates = CarbonPeriod::since(Carbon::create($request['from']))->days(1)->until(Carbon::create($request['to']));

        }
        //return $month;

        //$dates=CarbonPeriod::create($month->startOfMonth(),$month->endOfMonth());

        $days = [];

        $total_hours = 0;
        $total_revs = 0;

        $total_bookings = 0;

        foreach ($dates as $date) {

            $rev = 0;
            $expRev = 0;

            $hours = 0;

            $pendRev = 0;


            if ($request->has('stadium')) {
                $bookings = StadiumBooking::where('stadium_id', $request['stadium'])->whereDate('date', $date)->get();

            } else {
                $bookings = StadiumBooking::whereDate('date', $date)->get();

            }

            $total_bookings += count($bookings);
            foreach ($bookings as $booking) {
                if($booking->status=='Confirmed'){
                    $rev+=$booking->advance;
                    $pendRev+=$booking->rem_amount;
                $expRev+=$booking->payable_amount;

                }
    
                if($booking->status=='Completed'){
                    $rev+=$booking->payable_amount;
                $expRev+=$booking->payable_amount;

                }


    
    
                $hours += Carbon::create($booking->to)->floatDiffInHours(Carbon::create($booking->from));
            }
            $day = [
                'date' => $date->format('D d M Y'),
                'bookings' => $bookings,
                'rev' => $rev,
                'expRev'=>$expRev,
                'pendRev'=>$pendRev,
                'hours' => $hours,
            ];

            $total_hours += $hours;
            $total_revs += $rev;

            array_push($days, $day);

            if ($date->format('d-M-Y') == Carbon::now()->format('d-M-Y')) {
                break;
            }
        }

        // return $days;

        return view('admin.reports.bookings', compact('stadiumbookings', 'stds', 'days', 'total_revs', 'total_bookings', 'total_hours'));
    }

    public function exportReport(Request $request)
    {

        return Excel::download(new ReportExport($request['sid'], $request['from'], $request['to'], $request['period']), 'report.xlsx');

    }

    public function stadiumPhones(Request $request)
    {
        $stadium = Stadium::find($request['stadium_id']);

        $sp = StadiumPhone::where('stadium_id',$request['stadium_id'])->get();
       
        return view('admin.stadiums.phones',compact('sp','stadium'));
    }

    public function storeStadiumPhones(Request $request)
    {
        $stadium = Stadium::find($request['stadium_id']);

        $sp = new StadiumPhone();
        $sp->phone = $request['phone'];
        $sp->stadium_id = $request['stadium_id'];

        $sp->save();
        return redirect()->back();
    }


    public function deleteStadiumPhones(Request $request)
    {

        $sp = StadiumPhone::find($request['phno']);
       
        $sp->delete();
        return redirect()->back();
    }


    

    public function manageUsers(Request $request)
    {
        $stadium = Stadium::find($request['stadium_id']);

       $users=User::where('stadium_id',$stadium->id)->get();
       
        return view('admin.stadiums.users',compact('users','stadium'));
    }


    public function storeUser(Request $request)
    {
        $stadium = Stadium::find($request['stadium_id']);

        $user=new User();
        $user->name=$request['name'];
        $user-> email=$request['email'];
        $user->stadium_id=$request['stadium_id'];

        $user->status = 1;
        $user->role = 'VC';

        $fpassword=Str::random(8);

        $user->password = Hash::make($fpassword);
        $user->fpassword =$fpassword;

        $user->save();
        return redirect()->back();



    }

    public function changeUserStatus(Request $request){
        $user=User::find($request['user_id']);
        $user->status=$request['status'];
        $user->save();
        return redirect()->back();

    }


    public function blockedSlots(Request $request){
        $bs=BlockedSlot::all();
        $stadiums=Stadium::all();
        return view('admin.stadiums.blockedslots',compact('bs','stadiums'));
    }


    public function storeBlockedSlots(Request $request){
        $bs=new BlockedSlot();
        $bs->stadium_id=$request['stadium_id'];
        $bs->from=$request['from'];
        $bs->to=$request['to'];
        $bs->timing_from=$request['timing_from'];

        $bs->timing_to=$request['timing_to'];

        $bs->save();
         return redirect()->back();
    }




    public function deleteBlockedSlots(Request $request){
        $bs=BlockedSlot::find($request['id']);
        

        $bs->delete();
         return redirect()->back();
    }

    public function recPayLinkStatus(Request $request)
    {

        $plink_id = $request['razorpay_payment_link_id'];

        // "https://fcm.imerge.in/rec-paylink-status?razorpay_payment_id=pay_MfFj1Ue87CBiYR&razorpay_payment_link_id=plink_MfFiWiPun23f6T&razorpay_payment_link_reference_id=&razorpay_payment_link_status=paid&razorpay_signature=68e498004bd5f6103c4174549f80c4be1a7f799a93bf5f6bb95ca605f53b08f3"

        $booking = StadiumBooking::where('paylink_id', $plink_id)->first();

        $booking->order_id = $request['order_id'];
        $booking->payment_id = $request['razorpay_payment_id'];
        $booking->signature = $request['razorpay_signature'];

        $booking->status = 'Confirmed';

        $advance = $booking->payable_amount * 10;
        $advance = $advance / 100;
        $booking->advance = $advance;
        $booking->rem_amount = $booking->payable_amount - $advance;

        $booking->save();

        $stadium = Stadium::find($booking->stadium_id);

        $datee = Carbon::create($booking->date)->format('d-m-Y');

        $from = Carbon::create($booking->from)->format('h:i a');
        $to = Carbon::create($booking->to)->format('h:i a');

        $time = str_replace(' ', '%20', $from . '-' . $to);

        $user = User::find($booking->user_id);

        $url = "http://api.nsite.in/api/v2/SendSMS?SenderId=FCMARI&Is_Unicode=false&Is_Flash=false&Message=Slot%20Booked%20!%20%5CnHi%20" . str_replace(' ', '%20', $user->name) . ",%20you%20have%20booked%20a%20slot%20with%20FC%20MARINA%20BOOK%20APP.%20%5CnVenue%20:%20" . str_replace(' ', '%20', $stadium->name) . "%20%5CnDate%20:%20" . $datee . "%20%5CnTime%20:%20" . $time . "%20%5CnCourt%20:%20" . $booking->stadium_type . "%20%5CnAdvance%20Paid:%20" . $booking->advance . "%20%5CnBalance%20to%20pay:%20" . $booking->rem_amount . "%20%5CnBooking%20ID:%20" . $booking->booking_id . "&MobileNumbers=" . $user->phonenumber . "&ApiKey=mLdRdY8ey1ZTzMY0OifcDjaTO7rJ7gMTgsogL8ragGs=&ClientId=7a0c1703-92c1-4a91-918b-4ac7d9b8d1b3";
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

//for debug only!
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $resp = curl_exec($curl);
        curl_close($curl);

        return view('success');

    }

}
