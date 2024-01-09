<?php

namespace App\Exports;

use App\Models\User;
use App\Models\StadiumBooking;
use App\Models\Stadium;


use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class UserExport implements FromView
{

    public function __construct($sid, $from, $to, $period)
    {
        $this->period = $period;
        $this->stadium = $sid;
        $this->from = $from;
        $this->to = $to;

    }

    public function view(): View
    {
        $appusers = User::query();
        $stadiums = Stadium::all();
        $appusers = $appusers->where('role', 'User');
        if ($this->period!=null) {

            if ($this->period == 'All') {
                $appusers = $appusers;
            }

            if ($this->period == 'Today') {
                $appusers = $appusers->whereDate('created_at', Carbon::now());
            }

            if ($this->period == 'Month') {
                $appusers->whereDate('created_at', '>=', Carbon::now()->firstOfMonth())->whereDate('created_at', '<=', Carbon::now());
            }

            if ($this->period == 'custom') {
                $appusers->whereDate('created_at', '>=', $this->from)->whereDate('created_at', '<=', $this->to);
            }
        }

        if ($this->stadium!=null && $this->stadium!='All') {
            $stadiumId = $this->stadium;

            $appusers->whereHas('bookings', function ($query) use ($stadiumId) {
                $query->where('stadium_id', $stadiumId)
                      ->where('created_at', '=', function ($subquery) {
                          $subquery->select('created_at')
                                  ->from('stadium_bookings')
                                  ->whereColumn('user_id', 'users.id')
                                  ->orderBy('created_at')
                                  ->limit(1);
                      });
            });

        }


        $appusers = $appusers->paginate(10);
        foreach ($appusers as $user) {
            if (StadiumBooking::where('user_id', $user->id)->exists()) {
                $booking = StadiumBooking::where('user_id', $user->id)->first();
                $user->fstadium = $booking->stadium->name;
                $user->sport = $booking->sport_type;

            } else {
                $user->fstadium = '';

            }
        }

        return view('admin.appusers.exports', compact('appusers'));

    }
}
