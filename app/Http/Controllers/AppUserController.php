<?php

namespace App\Http\Controllers;

use App\Models\PointTransaction;
use App\Models\Stadium;
use App\Models\StadiumBooking;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;


use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UserExport;

class AppUserController extends Controller
{
    public function index(Request $request)
    {
        $appusers = User::query();
        $stadiums = Stadium::all();
        $appusers = $appusers->where('role', 'User');
        if ($request->has('period')) {

            if ($request['period'] == 'All') {
                $appusers = $appusers;
            }

            if ($request['period'] == 'Today') {
                $appusers = $appusers->whereDate('created_at', Carbon::now());
            }

            if ($request['period'] == 'Month') {
                $appusers->whereDate('created_at', '>=', Carbon::now()->firstOfMonth())->whereDate('created_at', '<=', Carbon::now());
            }

            if ($request['period'] == 'custom') {
                $appusers->whereDate('created_at', '>=', $request['from'])->whereDate('created_at', '<=', $request['to']);
            }
        }

        if ($request->has('stadium') && $request['stadium'] != 'All') {
            $stadiumId = $request->input('stadium');

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
        return view('admin.appusers.index', compact('appusers', 'stadiums'));
    }

    public function userPoints(Request $request)
    {
        $user = User::find($request['user_id']);
        $trans = PointTransaction::where('user_id', $user->id)->get();
        return view('admin.appusers.points', compact('user', 'trans'));

    }

    public function exportReport(Request $request){
        return Excel::download(new UserExport($request['sid'], $request['from'], $request['to'], $request['period']), 'report.xlsx');

    }


    public function bookings(Request $request){
        $user=User::find($request['user_id']);
        $stadiumbooking=StadiumBooking::where('user_id',$user->id)->get();
        

        return view('admin.appusers.bookings',compact('stadiumbooking','user'));

    }
}
