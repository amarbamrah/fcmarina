<?php

namespace App\Http\Controllers;

use App\Models\User;

class AppUserController extends Controller
{
    public function index(){
        $appusers = User::where('role', 'User')->get();
        return view('admin.appusers.index', compact('appusers'));
    }
}
