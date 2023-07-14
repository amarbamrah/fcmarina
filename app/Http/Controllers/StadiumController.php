<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\Stadium;
use App\Models\StadiumImage;

use App\Models\StadiumBooking;
use Carbon\Carbon;
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
        $locations = Location::all();
        return view('admin.stadiums.create', compact('locations'));
    }

    public function edit(Stadium $stadium)
    {
        $locations = Location::all();
        $st=$stadium;

        return view('admin.stadiums.edit', compact('st', 'locations'));
    }

    public function store(Request $request)
    {
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

        return redirect('/admin/stadiums');

    }

    public function update(Request $request, $id)
    {
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
        return redirect('admin/stadiums');

    }


    public function reports(Request $request){
        $stds=Stadium::all();

        $stadiumbookings = StadiumBooking::query();


        if($request->has('stadium') && $request['stadium']!="all"){
            $stadiumbookings->where('stadium_id', $request['stadium']);
        }

        if($request->has('status') && $request['status']!="all"){
            $stadiumbookings->where('status', $request['status']);
        }

        if($request->has('date') && $request['date']!=null){
            $stadiumbookings->whereDate('date', $request['date']);
        }else{
            $stadiumbookings->whereDate('date', Carbon::now());

        }
        $stadiumbookings=$stadiumbookings->get();
        return view('admin.reports.bookings',compact('stadiumbookings','stds'));
    }

}
