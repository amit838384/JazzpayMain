<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthenticationController;
use App\Http\Controllers\Api\ClientAuthenticationController;
use App\Http\Controllers\Api\ProfessionalUserController;
use App\Http\Controllers\Api\ClientUserController;
use Tymon\JWTAuth\Facades\JWTAuth;

use App\Http\Controllers\Api\ParentController;



/*

|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

    Route::get('/get-parent-details/{code}', [ParentController::class, 'get_parent_details']);

    Route::post('/signup', [ParentController::class, 'parent_signup']);
    Route::post('/login', [ParentController::class, 'parent_login']);

    Route::post('/parent-forgot-password', [ParentController::class, 'parent_forgot_password']);
    Route::post('/parent-change-password', [ParentController::class, 'parent_change_password']);


    Route::get('/grade-all', [ParentController::class, 'grade_all']);
    
    Route::get('/restricted-food', [ParentController::class, 'restricted_food']);

	Route::get('/dashboard', [ParentController::class, 'dashboard']);


Route::middleware(['auth:parent_api'])->group(function () {

    Route::get('/user-profile', [ParentController::class, 'profile']);
    Route::post('/update-profile', [ParentController::class, 'update']);
    Route::post('/student-list', [ParentController::class, 'student_list']);
    Route::post('/student-detail', [ParentController::class, 'student_detail']);

    Route::get('/parent-profile', [ParentController::class, 'parent_profile']);

    Route::post('/feedback', [ParentController::class, 'parent_feedback']);


    Route::post('/add-student', [ParentController::class, 'add_student']);

    Route::post('/update-spend-limit-student', [ParentController::class, 'update_spend_limit_student']);
    Route::post('/add-restricted-food', [ParentController::class, 'add_restricted_food']);

    Route::get('/dashboard', [ParentController::class, 'dashboard']);

    Route::post('/add-topup', [ParentController::class, 'topup']);

    Route::post('/add-wallet-balance', [ParentController::class, 'add_topu_wallet_balance']);
    Route::post('/student-to-parent-transfer', [ParentController::class, 'student_to_parent_transfer']);

    
    Route::get('/get-wallet-balance', [ParentController::class, 'get_topu_wallet_balance']);

    Route::get('/get-topup', [ParentController::class, 'get_topup']);


    ///////////////////////////////--PreOrders--//////////////////////////////

    
    Route::get('/all-category', [ParentController::class, 'all_category']);

    Route::post('/category-wise-dish', [ParentController::class, 'category_wise_dish']);

    Route::post('/pre-order', [ParentController::class, 'pre_order']);

    Route::get('/pre-order-cart-details', [ParentController::class, 'pre_order_cart_details']);


    Route::post('/checkout', [ParentController::class, 'checkout']);

    Route::post('/student-list-preorders', [ParentController::class, 'student_list_preorders']);


    Route::post('/dish-decrease', [ParentController::class, 'dish_decrease']);

    Route::post('/credit-transfer-history', [ParentController::class, 'credit_transfer_history']);


    Route::post('/pre-order-history', [ParentController::class, 'pre_order_history']);
	
    Route::post('/consumptions-history', [ParentController::class, 'consumptions_history']);

    Route::post('/update-email', [ParentController::class, 'updateEmail']);

    Route::post('/delete-account', [ParentController::class, 'deleteaccount']);


    Route::post('/wallet-transaction', [ParentController::class, 'wallettransaction']);
	
    Route::post('/child-money-transfer', [ParentController::class, 'child_money_transfer']);

    Route::post('/list-plans', [ParentController::class, 'listPlans']);
    Route::post('/parent/subscribe', [ParentController::class, 'subscribe']);
    Route::post('/parent/my-subscriptions', [ParentController::class, 'mySubscriptions']);
    Route::post('/parent/pause-subscription', [ParentController::class, 'pauseSubscription']);
    


});