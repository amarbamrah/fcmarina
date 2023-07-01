<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\Stadium;
use App\Models\StadiumImage;
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

        return view('stadiums/edit', compact('stadium', 'locations'));
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
        $stadium->price_5s = $request['price_5s'];
        $stadium->price_7s = $request['price_7s'];
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
        $stadium->price_5s = $request['price_5s'];
        $stadium->price_7s = $request['price_7s'];
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
        return redirect('allstadiums');

    }

}
