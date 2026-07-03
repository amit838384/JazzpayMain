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
use App\Http\Controllers\Admin\MailController;

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
use App\Http\Controllers\Admin\CafeteriaController;
use App\Http\Controllers\Admin\ManageCafeController;

use App\Http\Controllers\Admin\MenuAddonController;

use App\Http\Controllers\Admin\InviteController;
use App\Http\Controllers\Admin\SchoolDashboardController;


use App\Http\Controllers\Admin\CafeteriaDashboardController;
use App\Http\Controllers\Admin\WalletBalanceController;
use App\Http\Controllers\Admin\OrderDetailController;

use App\Http\Controllers\PaymentController;

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

Route::get('/', function () {
    return redirect()->route('login');
});



//Route::get('/', [HomeController::class, 'home'])->name('homepage');

Route::get('csae-policy', [FrontendController::class, 'csae_policy'])->name('csae_policy');



// Route::get('/location/{id}/{title}', [HomeController::class, 'location'])->name('locationpage');

Route::get('/location/{location:place_name}', [HomeController::class, 'location'])->name('locationpage');


Auth::routes();
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->name('logout');


Route::get('/admin', [AdminController::class, 'index'])->name('admin');
Route::get('home', [AdminController::class, 'index'])->name('admin.dashboard');

Route::resources([
    'roles' => RoleController::class,
    'users' => UserController::class,
    'products' => ProductController::class,
    'categories' => CategoryController::class,
    'admin/profession' => ProfessionController::class,
    // 'admin/cafeterias' => CafeteriaController::class,

]);

//------------------Add professional Services--------------------------//


/////////////////////////////////////////--Location Details Solution for everyone--////////////////////////////////////////

Route::prefix('admin')->name('admin.')->group(function () {
	
	Route::get('mails',               [MailController::class, 'index'])->name('mails.index');
	Route::get('mails/{id}',          [MailController::class, 'show'])->name('mails.show');
	Route::get('mails/{id}/edit',     [MailController::class, 'edit'])->name('mails.edit');
	Route::put('mails/{id}',          [MailController::class, 'update'])->name('mails.update');
	
	

    Route::get('profile', [ProfileController::class, 'index'])->name('profile');
	Route::post('changepassword', [ProfileController::class, 'changePassword'])->name('changepassword');

         
	Route::get('dashboard/export/school/{type}',    [AdminController::class, 'exportSchool'])->name('dashboard.export.school');
	Route::get('dashboard/export/cafeteria/{type}', [AdminController::class, 'exportCafeteria'])->name('dashboard.export.cafeteria');

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

    Route::post('re-send-invite-users/store/{email}', [SchoolController::class, 'resend_invite_users_store'])->name('resend_invite_users_store');


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
    Route::post('parents/{profession}/update', [SchoolController::class, 'parents_update'])->name('parents_update');
	Route::post('parents/bulkupload', [SchoolController::class, 'bulk_store']); 
	
	Route::put('parents/reset-password/{id}', [SchoolController::class, 'parents_reset_password'])->name('parents_reset_password');


       ////////////////////////////////--Parents--//////////////////////////

    Route::get('students', [SchoolController::class, 'students'])->name('students');
    Route::post('students/store', [SchoolController::class, 'students_store'])->name('students_store');
    Route::put('/admin/students/{id}/update', [SchoolController::class, 'students_update_new'])->name('students-update-new');

    Route::put('students/change-status/{id}', [SchoolController::class, 'studentschangeStatus'])->name('studentschangeStatus');
    Route::post('students/{profession}/update', [SchoolController::class, 'students_update'])->name('students_update');
    Route::post('students-bulk-update', [SchoolController::class, 'students_bulk_upload'])->name('students_bulk_upload');
	Route::put('students/verify/{id}', [SchoolController::class, 'students_verify'])->name('students_verify');

    

    //////////////////--admin end here--////////////////////////

    Route::get('payforservice', [CafeteriaController::class, 'admin_payforservice'])->name('admin_payforservice');
    Route::get('/admin-plan/{id}/subscriptions', [CafeteriaController::class, 'adminplanSubscriptions'])->name('adminplanSubscriptions');

    Route::get('/statistics-details', [CafeteriaController::class, 'admin_statistics'])->name('statistics.details');


    Route::get('/admin/pfs-service',               [CafeteriaController::class, 'pfs_Service'])->name('pfs_Service');
    Route::get('/admin/pfs-service/export/{type}', [CafeteriaController::class, 'exportPfs'])->name('pfs.export');

    Route::get('/admin/consumption-report',                    [CafeteriaController::class, 'consumptionReport'])->name('all_consumption');
    Route::get('/admin/consumption-report/export/{section}/{type}', [CafeteriaController::class, 'exportConsumption'])->name('all_consumption.export');

    Route::get('/sales-report',              [CafeteriaController::class, 'salesReport'])->name('all_sales');
Route::get('/sales-report/export/{type}',[CafeteriaController::class, 'exportSalesReport'])->name('all_sales.export');




//     ///////////--Cafeterias--/////////////

    Route::get('cafeterias', [CafeteriaController::class, 'cafeterias'])->name('cafeterias');
    Route::get('cafeterias/create', [CafeteriaController::class, 'cafeterias_create'])->name('cafeterias_create');
    Route::post('cafeterias/store', [CafeteriaController::class, 'cafeterias_store'])->name('cafeterias_store');
    Route::post('cafeterias/change-status/{id}', [CafeteriaController::class, 'cafeteriaschangeStatus'])->name('cafeteriaschangeStatus');
    Route::get('cafeterias/{profession}/show', [CafeteriaController::class, 'cafeterias_show'])->name('cafeterias_show');
    Route::get('cafeterias/{profession}/edit', [CafeteriaController::class, 'cafeterias_edit'])->name('cafeterias_edit');
    Route::put('cafeterias/{profession}/update', [CafeteriaController::class, 'cafeterias_update'])->name('cafeterias_update');

//    ///////////--Cafeterias Users--/////////////

    Route::get('cafeterias-user', [CafeteriaController::class, 'cafeterias_user'])->name('cafeterias_user');
    Route::get('cafeterias-user/create', [CafeteriaController::class, 'cafeterias_user_create'])->name('cafeterias_user_create');
    Route::post('cafeterias-user/store', [CafeteriaController::class, 'cafeterias_user_store'])->name('cafeterias_user_store');

    Route::put('cafeterias-user/change-status/{id}', [CafeteriaController::class, 'cafeterias_userchangeStatus'])->name('cafeterias_userchangeStatus');
    Route::get('cafeterias-user/{profession}/show', [CafeteriaController::class, 'cafeterias_user_show'])->name('cafeterias_user_show');
    Route::get('cafeterias-user/{profession}/edit', [CafeteriaController::class, 'cafeterias_user_edit'])->name('cafeterias_user_edit');
    Route::post('cafeterias-user/{profession}/update', [CafeteriaController::class, 'cafeterias_user_update'])->name('cafeterias_user_update');


    ///////////////////////////////////////---Cafeteria Assign---/////////////////////////////////////////////////

    Route::get('assign-user', [CafeteriaController::class, 'assign_user'])->name('assign_user');
    Route::get('assign-user/create', [CafeteriaController::class, 'assign_user_create'])->name('assign_user_create');
    Route::post('assign-user/store', [CafeteriaController::class, 'assign_user_store'])->name('assign_user_store');

    ////////////////////////--Cafeteria User List--//////////////////////////////

    
    Route::get('cafeteriaslist-user', [CafeteriaController::class, 'cafeteriaslist_user'])->name('cafeteriaslist_user');
    Route::put('cafeteriaslist-user/change-status/{id}', [CafeteriaController::class, 'cafeteriaslist_userchangeStatus'])->name('cafeteriaslist_userchangeStatus');
    
    /////////////////////////////////////////////////////////--Cards--//////////////////////////////////////////////////////////////

    Route::get('cards', [CafeteriaController::class, 'cards'])->name('cards');
    Route::post('card/store', [CafeteriaController::class, 'card_add'])->name('card_add');

    /////////////////////////////////////////////////////////--Cards--//////////////////////////////////////////////////////////////

    /////////////////////////////////////////////////////////////////
    /////////////////////--Menu--///////////////////////////////////


        Route::get('menu', [ManageCafeController::class, 'menu'])->name('menu');
        Route::post('menu/store', [ManageCafeController::class, 'menu_store'])->name('menu_store');
        Route::put('menu/change-status/{id}', [ManageCafeController::class, 'menuchangeStatus'])->name('menuchangeStatus');
        Route::post('menu/{profession}/update', [ManageCafeController::class, 'menu_update'])->name('menu_update');
        Route::post('menu/{profession}/delete', [ManageCafeController::class, 'menu_delete'])->name('menu_delete');

    /////////////////////--dish Category--///////////////////////////////////


        Route::get('dish-category', [ManageCafeController::class, 'dish_category'])->name('dish_category');
        Route::post('dish-category/store', [ManageCafeController::class, 'dish_category_store'])->name('dish_category_store');
        Route::put('dish-category/change-status/{id}', [ManageCafeController::class, 'dish_categorychangeStatus'])->name('dish_categorychangeStatus');
        Route::post('dish-category/{profession}/update', [ManageCafeController::class, 'dish_category_update'])->name('dish_category_update');
    

        //////////////////////////////--ingredients--/////////////////////////////////
        Route::get('ingredients-category', [ManageCafeController::class, 'ingredients_category'])->name('ingredients_category');
        Route::post('ingredients-category/store', [ManageCafeController::class, 'ingredients_category_store'])->name('ingredients_category_store');
        Route::put('ingredients-category/change-status/{id}', [ManageCafeController::class, 'ingredients_categorychangeStatus'])->name('ingredients_categorychangeStatus');
        Route::post('ingredients-category/{profession}/update', [ManageCafeController::class, 'ingredients_category_update'])->name('ingredients_category_update');


        ////////////////////////////////--Meal Category--////////////////////////////////////

        
        Route::get('meal-category', [ManageCafeController::class, 'meal_category'])->name('meal_category');
        Route::post('meal-category/store', [ManageCafeController::class, 'meal_category_store'])->name('meal_category_store');


        /////////////////////////////////////---Dish---//////////////////////////////////

        Route::get('dish', [ManageCafeController::class, 'dish'])->name('dish');
        Route::post('dish/store', [ManageCafeController::class, 'dish_store'])->name('dish_store');
        Route::put('dish/change-status/{id}', [ManageCafeController::class, 'dishchangeStatus'])->name('dishchangeStatus');
        Route::post('dish/{profession}/update', [ManageCafeController::class, 'dish_update'])->name('dish_update');
        Route::post('dish/{profession}/delete', [ManageCafeController::class, 'dish_delete'])->name('dish_delete');
		
		
		Route::get('dish/export-pdf',    [ManageCafeController::class, 'dish_export_pdf'])->name('dish_export_pdf');
		Route::get('dish/export-excel',  [ManageCafeController::class, 'dish_export_excel'])->name('dish_export_excel');
		Route::post('dish/bulk-import',  [ManageCafeController::class, 'dish_bulk_import'])->name('dish_bulk_import');
		Route::get('dish/sample-csv',    [ManageCafeController::class, 'dish_import_sample'])->name('dish_import_sample');

        Route::get('get-categories/{cafeteria_id}', [ManageCafeController::class, 'getCategoriesByCafeteria']);
		
		
		
		Route::get('menu-addon',                       [MenuAddonController::class, 'index'])->name('menu_addon');
		Route::post('menu-addon/store',                [MenuAddonController::class, 'store'])->name('menu_addon.store');
		Route::put('menu-addon/{id}/update',            [MenuAddonController::class, 'update'])->name('menu_addon.update');
		Route::put('menu-addon/{id}/change-status',     [MenuAddonController::class, 'changeStatus'])->name('menu_addon.change_status');
		Route::post('menu-addon/{id}/delete',           [MenuAddonController::class, 'delete'])->name('menu_addon.delete');
	

        
                /////////////////////////////////////////////////////////////////////////////
                /////////////////////////////////////////////////////////////////////////////
                                    // ----School Dashboard----
                /////////////////////////////////////////////////////////////////////////////
                /////////////////////////////////////////////////////////////////////////////


    Route::get('school-invite-users', [SchoolDashboardController::class, 'invite_users'])->name('school_invite_users');
    Route::get('school-invite-users/create', [SchoolDashboardController::class, 'invite_users_create'])->name('school_invite_users_create');
    Route::post('school-invite-users/store', [SchoolDashboardController::class, 'invite_users_store'])->name('school_invite_users_store');
     Route::put('school-school-users/change-status/{id}', [SchoolDashboardController::class, 'schooluserschangeStatus'])->name('school_schooluserschangeStatus');



       Route::get('school-manage-users', [SchoolDashboardController::class, 'manage_users'])->name('school_manage_users');


        Route::get('school-students', [SchoolDashboardController::class, 'students'])->name('school_students');
        Route::post('school-students/store', [SchoolDashboardController::class, 'students_store'])->name('school_students_store');
        Route::put('school-students/change-status/{id}', [SchoolDashboardController::class, 'studentschangeStatus'])->name('school_studentschangeStatus');
        Route::post('school-students/{profession}/update', [SchoolDashboardController::class, 'students_update'])->name('school_students_update');



        
    ////////////////////////////////--Parents--//////////////////////////

    Route::get('school-parents', [SchoolDashboardController::class, 'parents'])->name('school_parents');
    Route::post('school-parents/store', [SchoolDashboardController::class, 'parents_store'])->name('school_parents_store');
    Route::put('school-parents/change-status/{id}', [SchoolDashboardController::class, 'parentschangeStatus'])->name('school_parentschangeStatus');
    Route::post('school-parents/{profession}/update', [SchoolDashboardController::class, 'parents_update'])->name('school_parents_update');
	
	Route::get('school-pre-orders', [SchoolDashboardController::class, 'SchoolPreOrders'])->name('school_pre_orders');
	Route::get('School-on-site', [SchoolDashboardController::class, 'SchoolOnsite'])->name('School_onsite');

	Route::get('consumption-by-school', [SchoolDashboardController::class, 'consumption_by_school'])->name('consumption_by_school');



                /////////////////////////////////////////////////////////////////////////////
                /////////////////////////////////////////////////////////////////////////////
                                    // ----Cafeteria Dashboard----
                /////////////////////////////////////////////////////////////////////////////
                /////////////////////////////////////////////////////////////////////////////

	Route::get('cafeteria/students', [CafeteriaDashboardController::class, 'cafeteria_students'])->name('cafeteria_students');
	Route::post('cafeteria-students-amount/store/{id}', [CafeteriaDashboardController::class, 'cafeteria_store_amount'])->name('cafeteria_store_amount');
	Route::get('cafeteria/students/export', [CafeteriaDashboardController::class, 'cafeteria_students_export'])->name('cafeteria_students_export');
	
	
	// Route::put('cafeteria-students/change-status/{id}', [SchoolDashboardController::class, 'studentschangeStatus'])->name('school_studentschangeStatus');
	// Route::post('school-students/{profession}/update', [SchoolDashboardController::class, 'students_update'])->name('school_students_update');
	
	
	Route::get('cafeteria/onsite', [CafeteriaDashboardController::class, 'cafeteria_onsite'])->name('cafeteria_onsite');
	Route::get('cafeteria/pre-orders', [CafeteriaDashboardController::class, 'cafeteria_pre_orders'])->name('cafeteria_pre_orders');


    Route::get('cafeteria/cards', [CafeteriaDashboardController::class, 'cafeteria_cards'])->name('cafeteria_cards');
    Route::post('cafeteria/store', [CafeteriaDashboardController::class, 'cafeteriacard_add'])->name('cafeteriacard_add');


    Route::get('feedback', [AdminController::class, 'app_feedback'])->name('app_feedback');
    Route::post('feedback-status-change/{id}', [AdminController::class, 'feedbackstatuschange'])->name('feedbackstatuschange');


    Route::get('alert_message', [AdminController::class, 'alert_message'])->name('alert_message');
    Route::post('alert_message/{profession}/update', [AdminController::class, 'alert_message_update'])->name('alert_message_update');

    Route::post('alert_message-status-change/{id}', [AdminController::class, 'alert_messagestatuschange'])->name('alert_messagestatuschange');

    Route::post('pos-order', [CafeteriaDashboardController::class, 'pos_order'])->name('pos_order');
    Route::post('pre-order-history', [CafeteriaDashboardController::class, 'pre_order_history'])->name('pre_order_history');

    Route::get('/orders/{id}/view', [CafeteriaDashboardController::class, 'pos_order_show'])->name('orders.show');
    Route::get('/orders/{id}/print', [CafeteriaDashboardController::class, 'pos_order_print'])->name('orders.print');

        /////////////////////--Subscription plans--////////////////////////////

        
	Route::get('cafeteria/Plans-list', [CafeteriaDashboardController::class, 'Plans_list'])->name('cafeteria_Plans_list');
	Route::get('cafeteria/createplan', [CafeteriaDashboardController::class, 'createPlan'])->name('cafeteria_createPlan');
	Route::get('cafeteria/topuplist', [CafeteriaDashboardController::class, 'cafeteria_topuplist'])->name('cafeteria_topuplist');
	Route::post('cafeteria-plans-store', [CafeteriaDashboardController::class, 'cafeteria_plans_store'])->name('cafeteria.plans.store');

    Route::get('/cafeteria_plans/{id}/edit', [CafeteriaDashboardController::class, 'cafeteria_plans_edit'])->name('cafeteria.plans.edit');
    Route::post('/cafeteria-plans/delete', [CafeteriaDashboardController::class, 'cafeteria_plans_delete'])->name('cafeteria.plans.delete');

/////////////////////////////////////////////////////////--Plan List by Student--//////////////////////////////////////////////
	Route::get('/plan/{id}/subscriptions', [CafeteriaDashboardController::class, 'planSubscriptions'])->name('plan.subscriptions');

    Route::get('/plan/toggle-status/{id}', [CafeteriaDashboardController::class, 'toggleStatus'])->name('plan.toggleStatus');

	Route::get('/plan/{id}/subscription-paused', [CafeteriaDashboardController::class, 'planSubscriptions_paused'])->name('plan.subscriptions.paused');



/////////////////////////////////////////////////////////--Plan List by Student--//////////////////////////////////////////////

    ///////////////////////////////////////////////----topup----///////////////////////////////////////////////////////////

    Route::get('topuplist', [WalletBalanceController::class, 'topuplist_parents'])->name('topuplist_parents');
    Route::post('topuplist/store', [WalletBalanceController::class, 'topuplist_parents_store'])->name('topuplist_parents_store');
    Route::post('topuplist/change-status', [WalletBalanceController::class, 'topuplist_parentschangeStatus'])->name('topuplist_parentschangeStatus');
	
	Route::get('topuplist/export-pdf',   [WalletBalanceController::class, 'topuplist_export_pdf'])->name('topuplist_export_pdf');
	Route::get('topuplist/export-excel', [WalletBalanceController::class, 'topuplist_export_excel'])->name('topuplist_export_excel');

    //Route::get('cafeteria-topuplist', [WalletBalanceController::class, 'cafeteria_topuplist'])->name('cafeteria_topuplist');


    Route::get('credit-transfer', [WalletBalanceController::class, 'credit_transfer'])->name('credit_transfer');


    ///////////////////////////////////////////////----Order Details----///////////////////////////////////////////////////////////

    Route::get('pre-orders', [OrderDetailController::class, 'pre_orders'])->name('pre_orders');
	Route::put('pre-orders/invalid/{id}', [OrderDetailController::class, 'pre_orders_invalid'])->name('pre_orders_invalid');
Route::put('pre-orders/refund/{id}',  [OrderDetailController::class, 'pre_orders_refund'])->name('pre_orders_refund');
	
	
    Route::get('onsite-sales', [OrderDetailController::class, 'onsitesales'])->name('onsitesales');
	
	
	
	Route::get('onsite-sales/excel', [OrderDetailController::class, 'onsitesales_csv'])->name('onsitesales_excel');

    Route::get('pay-for-service-sales', [OrderDetailController::class, 'payforservice_sales'])->name('payforservice.sales');
	
	Route::get('onsite-sales/view/{id}', [OrderDetailController::class, 'onsitesales_view'])->name('onsitesales_view');
	
	Route::put('onsite-sales/invalid/{id}', [OrderDetailController::class, 'onsitesales_invalid'])->name('onsitesales_invalid');
	Route::put('onsite-sales/refund/{id}',  [OrderDetailController::class, 'onsitesales_refund'])->name('onsitesales_refund');
	
	




    Route::post('pre-orders-chnagestatus', [OrderDetailController::class, 'pre_orders_chnagestatus'])->name('pre_orders_chnagestatus');


    Route::get('statistics', [OrderDetailController::class, 'statistics'])->name('statistics');
    Route::get('sadaq-payments', [OrderDetailController::class, 'sadaq_payment'])->name('sadaq_payment');


        // Route::get('pre-orders', [OrderDetailController::class, 'pre_orders'])->name('pre_orders');

            Route::get('dish-sales', [OrderDetailController::class, 'dish_sales'])->name('dish_sales');

            Route::get('cafe-pos-report', [OrderDetailController::class, 'cafe_pos_report'])->name('cafe_pos_report');

            Route::get('brand-card-sales', [OrderDetailController::class, 'brand_card_sales'])->name('brand_card_sales');

});

Route::get('/invitelink/code/signup', [InviteController::class, 'showSignup']);

Route::post('invite_users_store', [InviteController::class, 'invite_users_store'])->name('school.signup.submit');

Route::get('/parent/code/signup', [InviteController::class, 'parentshowSignup'])->name('parent.signup');



