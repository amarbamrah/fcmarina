<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\Stadium;
use App\Models\StadiumImage;

use App\Models\StadiumBooking;

use App\Models\Amenity;

use App\Models\StadiumAmenity;




use Carbon\Carbon;

use Carbon\CarbonPeriod;

use Carbon\CarbonInterval;


use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StadiumController extends Controller
{

    public function index()
    {
        $stadium = Stadium::all();
        return view('admin.stadiums.index', compact('stadium'));

    }

    public function create()
    {
        $ameneties=Amenity::all();
        $locations = Location::all();
        return view('admin.stadiums.create', compact('locations','ameneties'));
    }

    public function edit(Stadium $stadium)
    {
        $locations = Location::all();
        $st=$stadium;

        $ameneties=Amenity::all();

        $sams=StadiumAmenity::where('stadium_id',$stadium->id)->get();

        $sas=[];



        foreach($sams as $sam){
            array_push($sas,$sam->amenity_id );
        }


        return view('admin.stadiums.edit', compact('st', 'locations','ameneties','sas'));
    }

    public function store(Request $request)
    {

        $ams= $request['ams'];
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


        foreach($ams as $am){
            $sa=new StadiumAmenity();
            $sa->stadium_id=$stadium->id;
            $sa->amenity_id=$am;
            $sa->save();
        }
        return redirect('/admin/stadiums');

    }

    public function update(Request $request, $id)
    {

        $ams= $request['ams'];

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


        StadiumAmenity::where('stadium_id',$stadium->id)->delete();

        foreach($ams as $am){
            $sa=new StadiumAmenity();
            $sa->stadium_id=$stadium->id;
            $sa->amenity_id=$am;
            $sa->save();
        }


        return redirect('admin/stadiums');

    }


    public function reports(Request $request){
        $stds=Stadium::all();

        $stadiumbookings = StadiumBooking::query();

        $month=$request->has('period') && $request['period']=='curr'?Carbon::create($request['month']):Carbon::now();



        $dates = CarbonPeriod::since($month->startOfMonth())->days(1)->until($month->endOfMonth());

        if($request->has('period') && $request['period']=='custom'){
        $dates = CarbonPeriod::since(Carbon::create($request['from']))->days(1)->until(Carbon::create($request['to']));
            
        }
        //return $month;


        //$dates=CarbonPeriod::create($month->startOfMonth(),$month->endOfMonth());

        


        $days=[];

        $total_hours=0;
        $total_revs=0;

        $total_bookings=0;

        foreach($dates as $date){

            
            $rev=0;
            $hours=0;

            
            if($request->has('stadium')){
                $bookings=StadiumBooking::where('stadium_id',$request['stadium'])->whereDate('date',$date)->get();

            }else{
                $bookings=StadiumBooking::whereDate('date',$date)->get();

            }

            $total_bookings+=count($bookings);
            foreach($bookings as $booking){
                if($booking->status!='Cancelled'){
                $hours+=Carbon::create($booking->from)->floatDiffInHours(Carbon::create($booking->to));
                $rev+=$booking->total_amount;
                }
            }
            $day=[
                'date'=>$date->format('D d M Y'),
                'bookings'=>$bookings,
                'rev'=>$rev,
                'hours'=>$hours
            ];

            $total_hours+=$hours;
            $total_revs+=$rev;
            
            array_push($days,$day);

            if($date->format('d-M-Y')==Carbon::now()->format('d-M-Y')){
                break;
            }
        }

       // return $days;

        return view('admin.reports.bookings',compact('stadiumbookings','stds','days','total_revs','total_bookings','total_hours'));
    }

}
