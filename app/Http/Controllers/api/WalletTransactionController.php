<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\WalletTransaction;
use App\Models\User;
use Illuminate\Http\Request;

use Carbon\Carbon;
class WalletTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $wts=WalletTransaction::where('user_id',$request['user_id'])->get();
        $user=User::find($request['user_id']);

        foreach($wts as $pt){
            $pt->f_date=Carbon::create($pt->created_at)->format('d M Y');
        }
        return ['data'=>$wts,'success'=>true,'user'=>$user];
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
    public function show(WalletTransaction $walletTransaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WalletTransaction $walletTransaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WalletTransaction $walletTransaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WalletTransaction $walletTransaction)
    {
        //
    }
}
