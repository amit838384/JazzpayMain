<?php

use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ProfessionController;
use App\Http\Controllers\Admin\AdminHomeController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\ProfessionalController;
use App\Http\Controllers\Admin\ClientController;
// use App\Http\Controllers\prof_dashboard\ProfLoginController;
use App\Http\Controllers\ProfessionalDashboard\ProfessionaLogin;

use App\Http\Controllers\Admin\ProductdetailController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\Admin\AdminLocationController;
use App\Http\Controllers\Admin\AdminAboutController;
use App\Http\Controllers\Admin\LocationDetailController;

use App\Http\Controllers\Frontend\HomeController;

use App\Http\Controllers\Admin\SchoolController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/clear-cache', function() {
    // Clear cache
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    Artisan::call('optimize:clear');

    // Optionally, you can regenerate cache
    // Artisan::call('config:cache');
    // Artisan::call('route:cache');
    // Artisan::call('view:cache');

    return "All caches have been cleared and regenerated!";
});


// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', [HomeController::class, 'home'])->name('homepage');


// Route::get('/location/{id}/{title}', [HomeController::class, 'location'])->name('locationpage');

Route::get('/location/{location:place_name}', [HomeController::class, 'location'])->name('locationpage');


Auth::routes();


Route::get('/admin', [AdminController::class, 'index'])->name('admin');


Route::resources([
    'roles' => RoleController::class,
    'users' => UserController::class,
    'products' => ProductController::class,
    'categories' => CategoryController::class,
    'admin/profession' => ProfessionController::class,
    // 'admin/professionService' => AdminHomeController::class,

]);

//------------------Add professional Services--------------------------//


/////////////////////////////////////////--Location Details Solution for everyone--////////////////////////////////////////

Route::prefix('admin')->name('admin.')->group(function () {

    Route::get('profile', [ProfileController::class, 'index'])->name('profile');
Route::post('changepassword', [ProfileController::class, 'changePassword'])->name('changepassword');



///////////--School--/////////////

    Route::get('school', [SchoolController::class, 'school'])->name('school');
    Route::get('school/create', [SchoolController::class, 'school_create'])->name('school_create');
    Route::post('school/store', [SchoolController::class, 'school_store'])->name('school_store');
    Route::put('school/change-status/{id}', [SchoolController::class, 'schoolchangeStatus'])->name('schoolchangeStatus');

    // Route::get('school/{profession}/show', [SchoolController::class, 'school_show'])->name('school_show');
    // Route::get('school/{profession}/edit', [SchoolController::class, 'school_edit'])->name('school_edit');
    Route::post('school/{profession}/update', [SchoolController::class, 'school_update'])->name('school_update');


///////////--Invites User for school--/////////////

    Route::get('invite-users', [SchoolController::class, 'invite_users'])->name('invite_users');
    Route::get('invite-users/create', [SchoolController::class, 'invite_users_create'])->name('invite_users_create');
    Route::post('invite-users/store', [SchoolController::class, 'invite_users_store'])->name('invite_users_store');
    // Route::get('school/{profession}/show', [SchoolController::class, 'school_show'])->name('school_show');
    // Route::get('school/{profession}/edit', [SchoolController::class, 'school_edit'])->name('school_edit');
    // Route::post('school/{profession}/update', [SchoolController::class, 'school_update'])->name('school_update');




    ///////////--User list for school--/////////////

        Route::get('school-users', [SchoolController::class, 'schoolusers'])->name('schoolusers');
        Route::put('school-users/change-status/{id}', [SchoolController::class, 'schooluserschangeStatus'])->name('schooluserschangeStatus');




    ////////////////////////////////--Parents--//////////////////////////

    Route::get('parents', [SchoolController::class, 'parents'])->name('parents');
    Route::post('parents/store', [SchoolController::class, 'parents_store'])->name('parents_store');
    Route::put('parents/change-status/{id}', [SchoolController::class, 'parentschangeStatus'])->name('parentschangeStatus');

    // Route::get('parents/{profession}/show', [SchoolController::class, 'parents_show'])->name('parents_show');
    // Route::get('parents/{profession}/edit', [SchoolController::class, 'parents_edit'])->name('parents_edit');
    Route::post('parents/{profession}/update', [SchoolController::class, 'parents_update'])->name('parents_update');


       ////////////////////////////////--Parents--//////////////////////////

    Route::get('students', [SchoolController::class, 'students'])->name('students');
    Route::post('parents/store', [SchoolController::class, 'students_store'])->name('students_store');
    Route::put('students/change-status/{id}', [SchoolController::class, 'studentschangeStatus'])->name('studentschangeStatus');
    Route::post('students/{profession}/update', [SchoolController::class, 'students_update'])->name('students_update');

    //////////////////--admin end here--////////////////////////




});
