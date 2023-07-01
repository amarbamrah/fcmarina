<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\PointTransaction;

use App\Models\User;

use Illuminate\Http\Request;
use Carbon\Carbon;

class PointTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $pts=PointTransaction::where('user_id',$request['user_id'])->get();
        $user=User::find($request['user_id']);
        foreach($pts as $pt){
            $pt->f_date=Carbon::create($pt->created_at)->format('d M Y');
        }
        return ['success'=>true,'data'=>$pts,'user'=>$user];
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
    public function show(PointTransaction $pointTransaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PointTransaction $pointTransaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PointTransaction $pointTransaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PointTransaction $pointTransaction)
    {
        //
    }
}
