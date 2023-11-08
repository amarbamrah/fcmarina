<?php

use App\Http\Controllers\ProfileController;

use App\Http\Controllers\DashboardController;

use App\Http\Controllers\StadiumController;

use App\Http\Controllers\LocationController;

use App\Http\Controllers\CancelReasonController;



use App\Http\Controllers\StadiumBookingController;

use App\Http\Controllers\HappyHourController;

use App\Http\Controllers\UserController;






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



Route::get('/nlogin',function(){
    return view('login');
});

Route::prefix('admin')->middleware('auth')->group(function () {



    // Route::get('/allstadiums', [StadiumController::class, 'index'])->name('stadium.index');
    // Route::get('/create-stadiums', [StadiumController::class, 'create'])->name('stadium.create');

    // Route::get('/edit-stadiums/{id}', [StadiumController::class, 'edit']);
    // Route::post('/update-stadiums/{id}', [StadiumController::class, 'update']);


    Route::get('/stadiums/phno',[StadiumController::class,'stadiumPhones']);

    Route::post('/stadiums/phno',[StadiumController::class,'storeStadiumPhones']);

    Route::post('/stadiums/delete-phno',[StadiumController::class,'deleteStadiumPhones']);

    Route::get('/stadiums/manage-users',[StadiumController::class,'manageUsers']);

    Route::post('/stadiums/manage-users',[StadiumController::class,'storeUser']);

    Route::post('/stadiums/change-user-status',[StadiumController::class,'changeUserStatus']);


    Route::get('/stadiums/blocked-slots',[StadiumController::class,'blockedSlots']);


    Route::get('/manage-admins',[UserController::class,'manageAdmins']);

    Route::get('/admin-stadiums',[UserController::class,'manageAdminStadiums']);

    Route::post('/assign-admin-stadiums',[UserController::class,'assignUser']);

    Route::post('/remove-assign-user',[UserController::class,'removeAssignUser']);

    



   

    Route::post('/stadiums/blocked-slots',[StadiumController::class,'storeBlockedSlots']);


    Route::post('/stadiums/delete-blocked-slots',[StadiumController::class,'deleteBlockedSlots']);




    Route::get('/points',[AppUserController::class,'userPoints']);





    Route::resource('/stadiums', StadiumController::class);




    Route::resource('/locations', LocationController::class);

    Route::resource('/cancel-reasons', CancelReasonController::class);




    // Route::post('/stadiums/store', [StadiumController::class, 'store'])->name('stadium.store');

    Route::resource('/stadium-bookings', StadiumBookingController::class);


    Route::resource('/happy-hours', HappyHourController::class);


    Route::get('/app-users', [AppUserController::class, 'index'])->name('appuser.index');

    Route::get('/reports', [StadiumController::class, 'reports']);

    Route::post('/export-report', [StadiumController::class, 'exportReport']);


    
    Route::get('/fetch-payment-from-link',[StadiumBookingController::class,'fetchPaymentFromLink']);

});


Route::get('/rec-paylink-status',[StadiumController::class,'recPayLinkStatus']);


Route::get('gettime', function () {
    date_default_timezone_set("Asia/Kolkata");
    return date('Y-m-d H:i:s');
});