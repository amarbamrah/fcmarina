<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\BlockedSlot;
use App\Models\Bslot;
use App\Models\HappyHour;
use App\Models\Stadium;
use App\Models\StadiumBooking;
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

        $sbs = StadiumBooking::where('stadium_id', $request['stadium_id'])->whereDate('date', $date)->where('status', '!=', 'Cancelled')->where('status', '!=', 'Processing')->get();

        $stadium = Stadium::find($request['stadium_id']);

        $offers = HappyHour::where('stadium_id', $stadium->id)->where('days', 'LIKE', '%' . $date->format('D') . '%')->get();

        foreach ($offers as $offer) {
            $offer->f_timing = 'From ' . Carbon::create($offer->from)->format('h:i a') . ' - ' . Carbon::create($offer->to)->format('h:i a');
        }

        if ($request->has('stadium_type')) {
            $selGameType = $request['stadium_type'];
        } else {
            if ($stadium->type == 'both' || $stadium->type == '9s') {
                $selGameType = '5s';

            } else {
                $selGameType = $stadium->type;
            }
        }

        $price = $stadium->mon5s;

        $stadium5sPrice = 0;
        $stadium7sPrice = 0;

        $bbSlots = BlockedSlot::where('stadium_id', $stadium->id)->get();

        switch ($date->format('D')) {
            case 'Mon':$price = $selGameType == '5s' ? $stadium->mon5s : $stadium->mon7s;
                break;
            case 'Tue':$price = $selGameType == '5s' ? $stadium->tue5s : $stadium->tue7s;
                break;
            case 'Wed':$price = $selGameType == '5s' ? $stadium->wed5s : $stadium->wed7s;
                break;
            case 'Thu':$price = $selGameType == '5s' ? $stadium->thu5s : $stadium->thu7s;
                break;
            case 'Fri':$price = $selGameType == '5s' ? $stadium->fri5s : $stadium->fri7s;
                break;
            case 'Sat':$price = $selGameType == '5s' ? $stadium->sat5s : $stadium->sat7s;
                break;
            case 'Sun':$price = $selGameType == '5s' ? $stadium->sun5s : $stadium->sun7s;
                break;
        }

        switch ($date->format('D')) {
            case 'Mon':$stadium5sPrice = $stadium->mon5s;
                $stadium7sPrice = $stadium->mon7s;
                break;
            case 'Tue':$stadium5sPrice = $stadium->tue5s;
                $stadium7sPrice = $stadium->tue7s;
                break;
            case 'Wed':$stadium5sPrice = $stadium->wed5s;
                $stadium7sPrice = $stadium->wed7s;
                break;
            case 'Thu':$stadium5sPrice = $stadium->thu5s;
                $stadium7sPrice = $stadium->thu7s;
                break;
            case 'Fri':$stadium5sPrice = $stadium->fri5s;
                $stadium7sPrice = $stadium->fri7s;
                break;
            case 'Sat':$stadium5sPrice = $stadium->sat5s;
                $stadium7sPrice = $stadium->sat7s;
                break;
            case 'Sun':$stadium5sPrice = $stadium->sun5s;
                $stadium7sPrice = $stadium->sun7s;
                break;
        }

        $dayName = $date->format('D');
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

            $slot->discount = 0;

            foreach ($offers as $offer) {

                $starttime = Carbon::create($offer->from);
                $endtime = Carbon::create($offer->to);
                $periods = [];
                while ($starttime->lte($endtime)) {
                    $to = $starttime->copy()->addMinutes(30);
                    array_push($periods, $starttime->toTimeString());
                    $starttime = $to;
                }

                foreach ($periods as $i => $period) {
                    if (($i + 1) < count($periods) && $period == Carbon::create($slot->from)->toTimeString()) {
                        $slot->discount = $offer->discount;
                        $slot->hours = $offer->hours;
                        $slot->offer_id = $offer->id;

                    }
                }
            }


             // BULK BLOCK
             foreach ($bbSlots as $bb) {
                $startDate = Carbon::create($bb->from);
                $endDate = Carbon::create($bb->to);

              

                if ($date->between($startDate, $endDate) || $date->eq($startDate) || $date->eq($endDate)) {
                    // $checkDate is within the date range defined by $startDate and $endDate
                    $starttime = Carbon::create($bb->timing_from);
                    $endtime = Carbon::create($bb->timing_to);
                    $bperiods = [];
                    while ($starttime->lte($endtime)) {
                        $to = $starttime->copy()->addMinutes(30);
                        array_push($bperiods, $starttime->toTimeString());
                        $starttime = $to;
                    }

                    foreach ($bperiods as $i => $period) {
                        if (($i + 1) < count($bperiods) && $period == Carbon::create($slot->from)->toTimeString()) {
                            $slot->isFilled = true;

                        }
                    }


                } else {
                }
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

                        } else if ($stadium->type == '5s' && $sb->stadium_type == '5s') {
                            $slot->isFilled = true;
                            $fully = 2;
                        } else {
                            if ($fully == 0) {
                                $fully++;
                                if ($selGameType == '7s') {
                                    $slot->isFilled = true;

                                } else {
                                    $slot->isFilled = false;

                                }
                            } else {
                                $slot->isFilled = true;

                            }
                        }

                    }
                }
            }

            if (Carbon::create($slot->from) < $now && $date->toDateString() == Carbon::now()->toDateString()) {
                $slot->isFilled = true;
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

            $slot->discount = 0;

            foreach ($offers as $offer) {

                $starttime = Carbon::create($offer->from);
                $endtime = Carbon::create($offer->to);
                $periods = [];
                while ($starttime->lte($endtime)) {
                    $to = $starttime->copy()->addMinutes(30);
                    array_push($periods, $starttime->toTimeString());
                    $starttime = $to;
                }

                foreach ($periods as $i => $period) {
                    if (($i + 1) < count($periods) && $period == Carbon::create($slot->from)->toTimeString()) {
                        $slot->discount = $offer->discount;
                        $slot->hours = $offer->hours;
                        $slot->offer_id = $offer->id;
                    }
                }
            }


             // BULK BLOCK
             foreach ($bbSlots as $bb) {
                $startDate = Carbon::create($bb->from);
                $endDate = Carbon::create($bb->to);

              

                if ($date->between($startDate, $endDate) || $date->eq($startDate) || $date->eq($endDate)) {
                    // $checkDate is within the date range defined by $startDate and $endDate
                    $starttime = Carbon::create($bb->timing_from);
                    $endtime = Carbon::create($bb->timing_to);
                    $bperiods = [];
                    while ($starttime->lte($endtime)) {
                        $to = $starttime->copy()->addMinutes(30);
                        array_push($bperiods, $starttime->toTimeString());
                        $starttime = $to;
                    }

                    foreach ($bperiods as $i => $period) {
                        if (($i + 1) < count($bperiods) && $period == Carbon::create($slot->from)->toTimeString()) {
                            $slot->isFilled = true;

                        }
                    }


                } else {
                }
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

                        } else if ($stadium->type == '5s' && $sb->stadium_type == '5s') {
                            $slot->isFilled = true;
                            $fully = 2;
                        } else {
                            if ($fully == 0) {
                                $fully++;
                                if ($selGameType == '7s') {
                                    $slot->isFilled = true;

                                } else {
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

            $slot->discount = 0;

            foreach ($offers as $offer) {

                $starttime = Carbon::create($offer->from);
                $endtime = Carbon::create($offer->to);
                $periods = [];
                while ($starttime->lte($endtime)) {
                    $to = $starttime->copy()->addMinutes(30);
                    array_push($periods, $starttime->toTimeString());
                    $starttime = $to;
                }

                foreach ($periods as $i => $period) {
                    if (($i + 1) < count($periods) && $period == Carbon::create($slot->from)->toTimeString()) {
                        $slot->discount = $offer->discount;
                        $slot->hours = $offer->hours;
                        $slot->offer_id = $offer->id;
                    }
                }
            }

            // BULK BLOCK
            foreach ($bbSlots as $bb) {
                $startDate = Carbon::create($bb->from);
                $endDate = Carbon::create($bb->to);

              

                if ($date->between($startDate, $endDate) || $date->eq($startDate) || $date->eq($endDate)) {
                    // $checkDate is within the date range defined by $startDate and $endDate
                    $starttime = Carbon::create($bb->timing_from);
                    $endtime = Carbon::create($bb->timing_to);
                    $bperiods = [];
                    while ($starttime->lte($endtime)) {
                        $to = $starttime->copy()->addMinutes(30);
                        array_push($bperiods, $starttime->toTimeString());
                        $starttime = $to;
                    }

                    foreach ($bperiods as $i => $period) {
                        if (($i + 1) < count($bperiods) && $period == Carbon::create($slot->from)->toTimeString()) {
                            $slot->isFilled = true;

                        }
                    }


                } else {
                }
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

                        } else if ($stadium->type == '5s' && $sb->stadium_type == '5s') {
                            $slot->isFilled = true;
                            $fully = 2;
                        } else {
                            if ($fully == 0) {
                                $fully++;
                                if ($selGameType == '7s') {
                                    $slot->isFilled = true;

                                } else {
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

            $slot->discount = 0;

            foreach ($offers as $offer) {

                $starttime = Carbon::create($offer->from);
                $endtime = Carbon::create($offer->to);
                $periods = [];
                while ($starttime->lte($endtime)) {
                    $to = $starttime->copy()->addMinutes(30);
                    array_push($periods, $starttime->toTimeString());
                    $starttime = $to;
                }

                foreach ($periods as $i => $period) {
                    if (($i + 1) < count($periods) && $period == Carbon::create($slot->from)->toTimeString()) {
                        $slot->discount = $offer->discount;
                        $slot->hours = $offer->hours;
                        $slot->offer_id = $offer->id;
                    }
                }
            }



             // BULK BLOCK
             foreach ($bbSlots as $bb) {
                $startDate = Carbon::create($bb->from);
                $endDate = Carbon::create($bb->to);

              

                if ($date->between($startDate, $endDate) || $date->eq($startDate) || $date->eq($endDate)) {
                    // $checkDate is within the date range defined by $startDate and $endDate
                    $starttime = Carbon::create($bb->timing_from);
                    $endtime = Carbon::create($bb->timing_to);
                    $bperiods = [];
                    while ($starttime->lte($endtime)) {
                        $to = $starttime->copy()->addMinutes(30);
                        array_push($bperiods, $starttime->toTimeString());
                        $starttime = $to;
                    }

                    foreach ($bperiods as $i => $period) {
                        if (($i + 1) < count($bperiods) && $period == Carbon::create($slot->from)->toTimeString()) {
                            $slot->isFilled = true;

                        }
                    }


                } else {
                }
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

                        } else if ($stadium->type == '5s' && $sb->stadium_type == '5s') {
                            $slot->isFilled = true;
                            $fully = 2;
                        } else {
                            if ($fully == 0) {
                                $fully++;
                                if ($selGameType == '7s') {
                                    $slot->isFilled = true;

                                } else {
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

        // calculate curr time
        $currentTime = Carbon::now();
        if ($date->format('d M Y') == $currentTime->format('d M Y')) {

// Define the time boundaries for the sections
            $twilightStart = Carbon::createFromTime(0, 0); // 12:00 AM
            $twilightEnd = Carbon::createFromTime(6, 0); // 6:00 AM
            $morningStart = Carbon::createFromTime(6, 0); // 6:00 AM
            $morningEnd = Carbon::createFromTime(12, 0); // 12:00 PM
            $noonStart = Carbon::createFromTime(12, 0); // 12:00 PM
            $noonEnd = Carbon::createFromTime(18, 0); // 6:00 PM
            $eveningStart = Carbon::createFromTime(18, 0); // 6:00 PM
            $eveningEnd = Carbon::createFromTime(0, 0)->addDay(); // 12:00 AM of the next day

// Check which section the current time falls into
            if ($currentTime >= $twilightStart && $currentTime < $twilightEnd) {
                $section = "Twilight";
            } elseif ($currentTime >= $morningStart && $currentTime < $morningEnd) {
                $section = "Morning";
            } elseif ($currentTime >= $noonStart && $currentTime < $noonEnd) {
                $section = "Noon";
            } else {
                $section = "Evening";
            }

        } else {
            $section = "Twilight";

        }
        return ['success' => true, 'section' => $section, 'stadium5sPrice' => $stadium5sPrice * 2, 'stadium7sPrice' => $stadium7sPrice * 2, 'data' => $data, 'price' => $price, 'stadium_types' => $stadium->type, 'sel_game_type' => $selGameType, 'offers' => $offers];
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
