<?php

namespace App\Http\Controllers;

use App\Models\HappyHour;

use App\Models\Stadium;

use Illuminate\Http\Request;

class HappyHourController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stds=Stadium::all();
        $hrs=HappyHour::all();
        return view('admin.happyhours.index',compact('hrs','stds'));
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
    public function show(HappyHour $happyHour)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(HappyHour $happyHour)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, HappyHour $happyHour)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HappyHour $happyHour)
    {
        //
    }
}
