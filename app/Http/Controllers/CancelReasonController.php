<?php

namespace App\Http\Controllers;

use App\Models\CancelReason;
use Illuminate\Http\Request;

class CancelReasonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $crs=CancelReason::all();
        return view('admin.masters.crs.index',compact('crs'));
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
        $cr=new CancelReason();
        $cr->title=$request['title'];
        $cr->save();
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(CancelReason $cancelReason)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CancelReason $cancelReason)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CancelReason $cancelReason)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CancelReason $cancelReason)
    {
        //
    }
}
