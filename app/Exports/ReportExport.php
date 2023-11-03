<?php

namespace App\Exports;

use App\Models\Stadium;
use App\Models\StadiumBooking;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ReportExport implements FromView
{

    public function __construct($sid, $from, $to, $period)
    {
        $this->period = $period;
        $this->sid = $sid;
        $this->from = $from;
        $this->to = $to;

    }

    public function view(): View
    {

        $stds = Stadium::all();

        $stadiumbookings = StadiumBooking::query();

        $month = $this->period == 'curr' ? Carbon::now() : Carbon::now();

        $dates = CarbonPeriod::since($month->startOfMonth())->days(1)->until($month->endOfMonth());

        if ($this->period == 'custom') {
            $dates = CarbonPeriod::since(Carbon::create($this->from))->days(1)->until(Carbon::create($this->to));

        }
        //return $month;

        //$dates=CarbonPeriod::create($month->startOfMonth(),$month->endOfMonth());

        $days = [];

        $total_hours = 0;
        $total_revs = 0;

        $total_bookings = 0;

        foreach ($dates as $date) {

            $rev = 0;
            $hours = 0;

            if ($this->sid != null) {
                $bookings = StadiumBooking::where('stadium_id', $this->sid)->whereDate('date', $date)->where('status', '!=', 'Processing')->where('status', '!=', 'Cancelled')->where('booked_for', null)->get();

            } else {
                $bookings = StadiumBooking::whereDate('date', $date)->where('status', '!=', 'Processing')->where('status', '!=', 'Cancelled')->where('booked_for', null)->get();

            }

            $total_bookings += count($bookings);

            foreach ($bookings as $booking) {
                if ($booking->status == 'Confirmed') {
                    $rev += $booking->advance;
                    $pendRev += $booking->rem_amount;
                    $expRev += $booking->payable_amount;

                }

                if ($booking->status == 'Completed') {
                    if (BookingPayment::where('booking_id', $booking->id)->where('payment_mode', 'Upi')->exists()) {
                        $bp = BookingPayment::where('booking_id', $booking->id)->where('payment_mode', 'Upi')->first();
                        $booking->upi = $bp->amount;

                    } else {
                        $booking->upi = 0;

                    }

                    if (BookingPayment::where('booking_id', $booking->id)->where('payment_mode', 'Cash')->exists()) {
                        $bp = BookingPayment::where('booking_id', $booking->id)->where('payment_mode', 'Cash')->first();
                        $booking->cash = $bp->amount;

                    } else {
                        $booking->cash = 0;

                    }

                    $rev += $booking->payable_amount;
                    $expRev += $booking->payable_amount;

                }

                $hours += Carbon::create($booking->to)->floatDiffInHours(Carbon::create($booking->from));
            }

            $day = [
                'date' => $date->format('D d M Y'),
                'bookings' => $bookings,
                'rev' => $rev,
                'hours' => $hours,
            ];

            $total_hours += $hours;
            $total_revs += $rev;

            array_push($days, $day);

            if ($date->format('d-M-Y') == Carbon::now()->format('d-M-Y')) {
                break;
            }
        }

        return view('admin.reports.export', compact('stadiumbookings', 'stds', 'days', 'total_revs', 'total_bookings', 'total_hours'));

    }
}
