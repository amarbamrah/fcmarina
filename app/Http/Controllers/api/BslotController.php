<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Bslot;
use App\Models\StadiumBooking;

use App\Models\Stadium;

use Carbon\Carbon;
use Illuminate\Http\Request;

class BslotController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $bslots = Bslot::all();
        $twilight = Bslot::where('day_time', 'Twilight')->get();
        $morning = Bslot::where('day_time', 'Morning')->get();
        $noon = Bslot::where('day_time', 'Noon')->get();
        $evening = Bslot::where('day_time', 'Evening')->get();

        $now = Carbon::now();

        $date = $request->has('date') ? Carbon::create($request['date']) : Carbon::now();

        $sbs = StadiumBooking::where('stadium_id', $request['stadium_id'])->whereDate('date', $date)->get();


        $stadium=Stadium::find($request['stadium_id']);

        if($request->has('stadium_type')){
           $selGameType=$request['stadium_type'];
        }else{
            if($stadium->type=='both'){
             $selGameType='5s';
                
            }else{
             $selGameType=$stadium->type;
            }
        }
        
        $dayName=$date->format('D');
       // return ['day'=>$dayName];
        foreach ($twilight as $i => $slot) {

            $fully = 0;

            if ($i % 2 == 0) {
                $slot->start = Carbon::create($slot->from)->format('h a');
                $slot->end = null;

            } else {
                $slot->end = Carbon::create($slot->to)->format('h a');
                $slot->start = null;

            }
            if (Carbon::create($slot->from) < $now && $date->toDateString() == Carbon::now()->toDateString()) {
                $slot->isFilled = true;
            } else {
                $slot->isFilled = false;

            }

            foreach ($sbs as $sb) {

                $starttime = Carbon::create($sb->from);
                $endtime = Carbon::create($sb->to);
                $periods = [];
                while ($starttime->lte($endtime)) {
                    $to = $starttime->copy()->addMinutes(30);
                    array_push($periods, $starttime->toTimeString());
                    $starttime = $to;
                }

                foreach ($periods as $i => $period) {
                    if (($i + 1) < count($periods) && $period == Carbon::create($slot->from)->toTimeString()) {
                        if ($sb->stadium_type == '7s') {
                            $slot->isFilled = true;
                            $fully = 2;

                        } else {
                            if ($fully == 0) {
                                $fully++;
                                if($selGameType=='7s'){
                                    $slot->isFilled = true;

                                }else{
                                    $slot->isFilled = false;

                                }
                            } else {
                                $slot->isFilled = true;

                            }
                        }

                    }
                }
            }

        }

        foreach ($morning as $i => $slot) {
            $fully = 0;

            if ($i % 2 == 0) {
                $slot->start = Carbon::create($slot->from)->format('h a');
                $slot->end = null;

            } else {
                $slot->end = Carbon::create($slot->to)->format('h a');
                $slot->start = null;

            }

            if (Carbon::create($slot->from) < $now && $date->toDateString() == Carbon::now()->toDateString()) {
                $slot->isFilled = true;
            } else {
                $slot->isFilled = false;

            }

            foreach ($sbs as $sb) {

                $starttime = Carbon::create($sb->from);
                $endtime = Carbon::create($sb->to);
                $periods = [];
                while ($starttime->lte($endtime)) {
                    $to = $starttime->copy()->addMinutes(30);
                    array_push($periods, $starttime->toTimeString());
                    $starttime = $to;
                }

                foreach ($periods as $i => $period) {
                    if (($i + 1) < count($periods) && $period == Carbon::create($slot->from)->toTimeString()) {
                        if ($sb->stadium_type == '7s') {
                            $slot->isFilled = true;
                            $fully = 2;

                        } else {
                            if ($fully == 0) {
                                $fully++;
                                if($selGameType=='7s'){
                                    $slot->isFilled = true;

                                }else{
                                    $slot->isFilled = false;

                                }
                            } else {
                                $slot->isFilled = true;

                            }
                        }

                    }
                }
            }

        }

        foreach ($noon as $i => $slot) {
            $fully = 0;

            if ($i % 2 == 0) {
                $slot->start = Carbon::create($slot->from)->format('h a');
                $slot->end = null;

            } else {
                $slot->end = Carbon::create($slot->to)->format('h a');
                $slot->start = null;

            }
            if (Carbon::create($slot->from) < $now && $date->toDateString() == Carbon::now()->toDateString()) {

                $slot->isFilled = true;
            } else {
                $slot->isFilled = false;

            }

            foreach ($sbs as $sb) {

                $starttime = Carbon::create($sb->from);
                $endtime = Carbon::create($sb->to);
                $periods = [];
                while ($starttime->lte($endtime)) {
                    $to = $starttime->copy()->addMinutes(30);
                    array_push($periods, $starttime->toTimeString());
                    $starttime = $to;
                }

                foreach ($periods as $i => $period) {
                    if (($i + 1) < count($periods) && $period == Carbon::create($slot->from)->toTimeString()) {
                        if ($sb->stadium_type == '7s') {
                            $slot->isFilled = true;
                            $fully = 2;

                        } else {
                            if ($fully == 0) {
                                $fully++;
                                if($selGameType=='7s'){
                                    $slot->isFilled = true;

                                }else{
                                    $slot->isFilled = false;

                                }
                            } else {
                                $slot->isFilled = true;

                            }
                        }

                    }
                }
            }

        }

        foreach ($evening as $i => $slot) {
            $fully = 0;

            if ($i % 2 == 0) {
                $slot->start = Carbon::create($slot->from)->format('h a');
                $slot->end = null;

            } else {
                $slot->end = Carbon::create($slot->to)->format('h a');
                $slot->start = null;

            }
            if (Carbon::create($slot->from) < $now && $date->toDateString() == Carbon::now()->toDateString()) {

                $slot->isFilled = true;
            } else {
                $slot->isFilled = false;

            }

            foreach ($sbs as $sb) {

                $starttime = Carbon::create($sb->from);
                $endtime = Carbon::create($sb->to);
                $periods = [];
                while ($starttime->lte($endtime)) {
                    $to = $starttime->copy()->addMinutes(30);
                    array_push($periods, $starttime->toTimeString());
                    $starttime = $to;
                }

                foreach ($periods as $i => $period) {
                    if (($i + 1) < count($periods) && $period == Carbon::create($slot->from)->toTimeString()) {
                        if ($sb->stadium_type == '7s') {
                            $slot->isFilled = true;
                            $fully = 2;

                        } else {
                            if ($fully == 0) {
                                $fully++;
                                if($selGameType=='7s'){
                                    $slot->isFilled = true;

                                }else{
                                    $slot->isFilled = false;

                                }
                            } else {
                                $slot->isFilled = true;

                            }
                        }

                    }
                }
            }

        }
        $data = [
            'twilight' => $twilight,
            'morning' => $morning,
            'noon' => $noon,
            'evening' => $evening,
            

        ];

        $price=200;
        if($selGameType=='7s'){
            $price=400;
        }
        return ['success' => true, 'data' => $data,'price'=>$price,'stadium_types'=>$stadium->type,'sel_game_type'=>$selGameType];
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
    public function show(Bslot $bslot)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Bslot $bslot)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Bslot $bslot)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bslot $bslot)
    {
        //
    }
}
