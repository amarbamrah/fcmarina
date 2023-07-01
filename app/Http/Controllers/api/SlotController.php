<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;


class SlotController extends Controller
{
    public function index(Request $request){
        $today=Carbon::now();
        return ['success'=>true,'data'=>$slots];
    }
}
