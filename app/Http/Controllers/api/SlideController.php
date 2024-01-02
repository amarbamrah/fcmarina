<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Slide;
use Illuminate\Http\Request;

class SlideController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $slides=Slide::orderBy('id','DESC')->get();
        foreach($slides as $slide){
            $slide->image='https://fcm.imerge.in/stadiums/'.$slide->image;
        }
        return ['data'=>$slides,'success'=>true];
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
    public function show(Slide $slide)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Slide $slide)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Slide $slide)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Slide $slide)
    {
        //
    }
}
