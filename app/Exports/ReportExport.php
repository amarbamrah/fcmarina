<?php

namespace App\Exports;

use App\Invoice;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

use App\Models\Stadium;
use App\Models\StadiumBooking;
use Carbon\Carbon;

use Carbon\CarbonPeriod;

use Carbon\CarbonInterval;


use App\Models\User;

class ReportExport implements FromView
{

    public function __construct($sid,$from,$to,$period)
    {
        $this->period = $period;
        $this->sid = $sid;
        $this->from = $from;
        $this->to = $to;

    }


    public function view(): View
    {

        $stds=Stadium::all();

        $stadiumbookings = StadiumBooking::query();

        $month=$this->period=='curr'?Carbon::now():Carbon::now();



        $dates = CarbonPeriod::since($month->startOfMonth())->days(1)->until($month->endOfMonth());

        if($this->period=='custom'){
            $dates = CarbonPeriod::since(Carbon::create($this->from))->days(1)->until(Carbon::create($this->to));
            
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

            
            if($this->sid!=null){
                $bookings=StadiumBooking::where('stadium_id',$this->sid)->whereDate('date',$date)->get();

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

        return view('admin.reports.export',compact('stadiumbookings','stds','days','total_revs','total_bookings','total_hours'));
        
    }
}