<?php

namespace App\Http\Controllers;

use App\Models\Slide;

use App\Models\Stadium;

use Illuminate\Http\Request;

class SlideController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $slides=Slide::all();
        $stadiums=Stadium::all();
        return view('admin.masters.slides.index',compact('slides','stadiums'));
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
        $image = $request->file('image');
        $filename = time() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('stadiums'), $filename);

        $slide=new Slide();
        $slide->image=$filename;
        $slide->stadium_id=$request['stadium'];
        $slide->save();
        return redirect()->back();

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
        $slide->delete();
        return redirect()->back();

    }
}
