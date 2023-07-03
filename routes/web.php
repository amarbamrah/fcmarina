<?php

use App\Http\Controllers\ProfileController;

use App\Http\Controllers\DashboardController;

use App\Http\Controllers\StadiumController;

use App\Http\Controllers\LocationController;


use App\Http\Controllers\StadiumBookingController;


use App\Http\Controllers\AppUserController;





use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

// Route::get('/', function () {
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });

// Route::get('/dashboard', function () {
//     return Inertia::render('Dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

Route::get('/',function(){
    return redirect('/admin');
});


Route::get('/admin',[DashboardController::class,'dashboard']);

Route::get('/terms-conditions',function(){
    return view('tc');
});



Route::prefix('admin')->middleware('auth')->group(function () {



    // Route::get('/allstadiums', [StadiumController::class, 'index'])->name('stadium.index');
    // Route::get('/create-stadiums', [StadiumController::class, 'create'])->name('stadium.create');

    // Route::get('/edit-stadiums/{id}', [StadiumController::class, 'edit']);
    // Route::post('/update-stadiums/{id}', [StadiumController::class, 'update']);

    Route::resource('/stadiums', StadiumController::class);


    Route::resource('/locations', LocationController::class);



    // Route::post('/stadiums/store', [StadiumController::class, 'store'])->name('stadium.store');

    Route::get('/stadium-bookings', [StadiumBookingController::class, 'index'])->name('stadiumbooking.index');

    Route::get('/app-users', [AppUserController::class, 'index'])->name('appuser.index');
});


Route::get('gettime', function () {
    date_default_timezone_set("Asia/Kolkata");
    return date('Y-m-d H:i:s');
});