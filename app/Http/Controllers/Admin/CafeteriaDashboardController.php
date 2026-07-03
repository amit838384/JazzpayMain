<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
///////--Models--///////////

use App\Models\School_Model;
use App\Models\SchoolParent_Model;
use App\Models\SchoolStudent_Model;
use App\Models\ManageCafe\DishCategory_Model;
use App\Models\ManageCafe\Dish_Model;
use App\Models\Restrictedfoodbystudent_Model;

use App\Models\Cafeteria\Cafeteria_Model;
use App\Models\Cafeteria\CafeteriaUser_Model;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Mail\SchoolUserInviteMail; 
use Illuminate\Support\Facades\Auth;
use App\Models\PreOrder_Model;
use App\Models\Order_Model;
use App\Models\OrderItem_Model;
use App\Models\PayForService\Plan_Model;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\PayForService\Subscription_Model;
use App\Models\PayForService\SubscriptionPause_Model;
use Carbon\Carbon;


class CafeteriaDashboardController extends Controller
{
     public function __construct(){
        $this->middleware('auth');
    }
	
	
	public function cafeteria_students(Request $request)
	{
		$cafeteriaadmin = Auth::user()->cafeteria_id;
		$cafeteria = Cafeteria_Model::where('id', $cafeteriaadmin)->first();
		$school = null;
		$parent = SchoolParent_Model::get();

		$query = SchoolStudent_Model::query();

		if ($cafeteria && !empty($cafeteria->school_id)) {
			$school = School_Model::where('id', $cafeteria->school_id)->first();
			if ($school) {
				$query->where('school_id', $school->id);
			}
		}

		if ($request->filled('mobile')) {
			$parentIds = SchoolParent_Model::where('mobile', 'like', '%' . $request->mobile . '%')->pluck('id');
			$query->whereIn('parent_id', $parentIds);
		}
		if ($request->filled('student_name')) {
			$query->where('student_name', 'like', '%' . $request->student_name . '%');
		}
		if ($request->filled('parent_name')) {
			$parentIds = SchoolParent_Model::where('name', 'like', '%' . $request->parent_name . '%')->pluck('id');
			$query->whereIn('parent_id', $parentIds);
		}
		if ($request->filled('admission_no')) {
			$query->where('admission_no', 'like', '%' . $request->admission_no . '%');
		}

		$student = $query->paginate(10);

		// Load restricted foods for current page students only
		$studentIds      = $student->pluck('id');
		$restrictedFoods = DB::table('tbl_restricted_food_by_student')
			->whereIn('student_id', $studentIds)
			->where('status', 1)
			->get(['student_id', 'name'])
			->groupBy('student_id');

		return view('admin.cafeteriaadmin.students.index', compact('school', 'parent', 'student', 'cafeteria', 'restrictedFoods'));
	}
	
	public function cafeteria_students_export(Request $request)
	{
		$cafeteriaadmin = Auth::user()->cafeteria_id;
		$cafeteria = Cafeteria_Model::where('id', $cafeteriaadmin)->first();
		$parent = SchoolParent_Model::get();
		$school = null;
	 
		$query = SchoolStudent_Model::query();
	 
		if ($cafeteria && !empty($cafeteria->school_id)) {
			$school = School_Model::where('id', $cafeteria->school_id)->first();
			if ($school) {
				$query->where('school_id', $school->id);
			}
		}
	 
		if ($request->filled('mobile')) {
			$parentIds = SchoolParent_Model::where('mobile', 'like', '%' . $request->mobile . '%')->pluck('id');
			$query->whereIn('parent_id', $parentIds);
		}
		if ($request->filled('student_name')) {
			$query->where('student_name', 'like', '%' . $request->student_name . '%');
		}
		if ($request->filled('parent_name')) {
			$parentIds = SchoolParent_Model::where('name', 'like', '%' . $request->parent_name . '%')->pluck('id');
			$query->whereIn('parent_id', $parentIds);
		}
		if ($request->filled('admission_no')) {
			$query->where('admission_no', 'like', '%' . $request->admission_no . '%');
		}
	 
		$students = $query->get();
	 
		$filename = 'Students-' . date('d-M-Y') . '.csv';
	 
		$headers = [
			'Content-Type'        => 'text/csv',
			'Content-Disposition' => 'attachment; filename="' . $filename . '"',
			'Pragma'              => 'no-cache',
			'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
			'Expires'             => '0',
		];
	 
		$callback = function () use ($students, $parent, $school) {
			$file = fopen('php://output', 'w');
			fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF)); // UTF-8 BOM
	 
			fputcsv($file, [
				'Admission No', 'Student Name', 'Parent Name', 'Mobile',
				'Parent Email', 'School', 'Credits', 'Daily Limit', 'Verified', 'Status'
			]);
	 
			foreach ($students as $row) {
				$par = $parent->firstWhere('id', $row->parent_id);
				fputcsv($file, [
					$row->id,
					$row->student_name,
					$par->name    ?? '-',
					$par->mobile  ?? '-',
					$par->email   ?? '-',
					$school->school_name ?? '-',
					$row->wallet_balance,
					$row->spend_limit,
					$row->verified,
					$row->status == 1 ? 'Active' : 'Inactive',
				]);
			}
	 
			fclose($file);
		};
	 
		return response()->stream($callback, 200, $headers);
	}

   
	/* public function cafeteria_students()
	{
		$cafeteriaadmin = Auth::user()->cafeteria_id;

		// Cafeteria
		$cafeteria = Cafeteria_Model::where('id', $cafeteriaadmin)->first();

		// Default values
		$school = null;
		$student = collect();

		// School & Students
		if ($cafeteria && !empty($cafeteria->school_id)) {

			$school = School_Model::where('id', $cafeteria->school_id)->first();

			if ($school) {
				$student = SchoolStudent_Model::where('school_id', $school->id)->get();
			}
		}

		// Parents
		$parent = SchoolParent_Model::get();

		return view(
			'admin.cafeteriaadmin.students.index',
			compact('school', 'parent', 'student', 'cafeteria')
		);
	} */

		public function cafeteria_store_amount(Request $request){
			
			$student_id = $request->student_id;
			$amount = $request->amount;

			$student = SchoolStudent_Model::find($student_id);

			if (!$student) {
				return redirect()->back()->with('error', 'Student not found');
			}

			$student->wallet_balance = $amount;
			$student->save();

			return redirect()->back()->with('message', 'Successfully updated');
		}

		public function cafeteria_onsite(Request $request)
{
    $cafeteriaadmin = Auth::user()->cafeteria_id;	
    $userid = Auth::user()->id;
    $school_id = Auth::user()->school_id; 

    $categories = DishCategory_Model::where('cafeteria_id', $cafeteriaadmin)->where('status', 1)->get();
    $firstCategory = $categories->first();

    $items = Dish_Model::with('category')
        ->where('cafeteria_id', $cafeteriaadmin)
        ->get();

    // REMOVED: echo "<pre>"; print_r($items); die;

    $cafeteria = Cafeteria_Model::where('id', $cafeteriaadmin)->first();

    $student = SchoolStudent_Model::with('parent')
        ->where('school_id', $cafeteria->school_id)
        ->get();

    $restrictedFoods = Restrictedfoodbystudent_Model::all();

    $orderdetails = Order_Model::where('cafeteria_id', $cafeteriaadmin)
        ->orderBy('id', 'DESC')
        ->get();

    return view('admin.cafeteriaadmin.onsite.index', compact(
        'categories', 'items', 'firstCategory', 
        'student', 'cafeteria', 'restrictedFoods', 'orderdetails'
    ));
}


		/* public function cafeteria_pre_orders()
		{
			$userid = Auth::user()->id;
			$cafeteria_id = Auth::user()->cafeteria_id;
			$cafeteria = Cafeteria_Model::where('id', $cafeteria_id)->first();

			// Load orders with related order items
			$preorder = PreOrder_Model::where('cafeteria_id', $cafeteria_id)->orderBy('id', 'desc')->get();

			$dish = Dish_Model::get();
			$student = SchoolStudent_Model::get();
			$school = School_Model::get();
			$cafeterias = Cafeteria_Model::get();
			$dish_category = DishCategory_Model::get();

			return view('admin.cafeteriaadmin.cafeteria_pre_orders.index', compact('preorder', 'dish', 'student', 'school', 'cafeteria', 'cafeterias','dish_category'));
		} */
		
		
	public function cafeteria_pre_orders(Request $request)
	{
		$cafeteria_id  = Auth::user()->cafeteria_id;
		$cafeteria     = Cafeteria_Model::where('id', $cafeteria_id)->first();
		$dish          = Dish_Model::get();
		$student       = SchoolStudent_Model::get();
		$school        = School_Model::get();
		$cafeterias    = Cafeteria_Model::get();
		$dish_category = DishCategory_Model::get();

		$query = PreOrder_Model::where('cafeteria_id', $cafeteria_id)->orderBy('id', 'desc');

		if ($request->filled('fdate') || $request->filled('tdate')) {
			$fromDate = $request->filled('fdate')
				? \Carbon\Carbon::parse($request->fdate)->format('Y-m-d')
				: null;
			$toDate = $request->filled('tdate')
				? \Carbon\Carbon::parse($request->tdate)->format('Y-m-d')
				: null;

			// The date column has mixed formats: "2026-02-05" (Y-m-d) and "02 Jul 2026" (d M Y)
			// Normalize both to Y-m-d inside MySQL for comparison
			$query->whereRaw("
				CASE
					WHEN date REGEXP '^[0-9]{4}-[0-9]{2}-[0-9]{2}$'
						THEN STR_TO_DATE(date, '%Y-%m-%d')
					WHEN date REGEXP '^[0-9]{2} [A-Za-z]{3} [0-9]{4}$'
						THEN STR_TO_DATE(date, '%d %b %Y')
					ELSE NULL
				END BETWEEN ? AND ?
			", [
				$fromDate ?? '1970-01-01',
				$toDate   ?? '2099-12-31',
			]);
		}

		if ($request->filled('student_name')) {
			$studentIds = SchoolStudent_Model::where('student_name', 'like', '%' . $request->student_name . '%')->pluck('id');
			$query->whereIn('student_id', $studentIds);
		}
		if ($request->filled('cafeteria_filter')) {
			$query->where('cafeteria_id', $request->cafeteria_filter);
		}
		if ($request->filled('status')) {
			$query->where('payment_status', $request->status);
		}

		// Handle exports before paginating
		if ($request->filled('export')) {
			$preorder = $query->get();

			if ($request->export === 'pdf') {
				return view('admin.cafeteriaadmin.cafeteria_pre_orders.export_pdf',
					compact('preorder', 'dish', 'student', 'school', 'cafeteria', 'cafeterias', 'dish_category'));
			}

			if ($request->export === 'csv') {
				$filename = 'PreOrders-' . date('d-M-Y') . '.csv';
				$headers  = [
					'Content-Type'        => 'text/csv',
					'Content-Disposition' => 'attachment; filename="' . $filename . '"',
					'Pragma'              => 'no-cache',
					'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
					'Expires'             => '0',
				];
				$callback = function () use ($preorder, $dish, $student, $school, $cafeteria) {
					$file = fopen('php://output', 'w');
					fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
					fputcsv($file, ['ID', 'Dish Name', 'Student Name', 'School', 'Cafeteria', 'Meal Type', 'Note', 'Addons', 'Payment Mode', 'Transaction Number', 'Payment Status', 'Date', 'Credits']);
					foreach ($preorder as $row) {
						$di  = $dish->firstWhere('id', $row->dish_id);
						$stu = $student->firstWhere('id', $row->student_id);
						$sch = $school->firstWhere('id', $row->school_id);
						$caf = ($cafeteria && $cafeteria->id == $row->cafeteria_id) ? $cafeteria->cafeteria_name : '-';
						fputcsv($file, [
							$row->id,
							$di->dish_name     ?? '-',
							$stu->student_name ?? '-',
							$sch->school_name  ?? '-',
							$caf,
							$row->meal_type    ?? '-',
							$row->note         ?? '-',
							$row->addons       ?? '-',
							$row->payment_type ?? '--',
							$row->transaction_no ?? '--',
							$row->payment_status == 1 ? 'Success' : ($row->payment_status == 3 ? 'Refunded' : ($row->payment_status == 2 ? 'Failed' : 'Pending')),
							$row->date,
							$row->total_price,
						]);
					}
					fclose($file);
				};
				return response()->stream($callback, 200, $headers);
			}
		}

		$preorder = $query->paginate(15);

		return view('admin.cafeteriaadmin.cafeteria_pre_orders.index',
			compact('preorder', 'dish', 'student', 'school', 'cafeteria', 'cafeterias', 'dish_category'));
	}
	
	
	public function cafeteria_topuplist(Request $request)
	{
		$cafeteria_id = Auth::user()->cafeteria_id;
		$cafeteria    = Cafeteria_Model::where('id', $cafeteria_id)->first();
		$student      = SchoolStudent_Model::get();

		$query = PreOrder_Model::where('cafeteria_id', $cafeteria_id)
							   ->orderBy('id', 'desc');

		if ($request->filled('student_name')) {
			$studentIds = SchoolStudent_Model::where('student_name', 'like', '%' . $request->student_name . '%')->pluck('id');
			$query->whereIn('student_id', $studentIds);
		}

		$data        = $query->paginate(15);
		$totalAmount = PreOrder_Model::where('cafeteria_id', $cafeteria_id)->sum('total_price');

		return view('admin.cafeteriaadmin.topuplist.index',
			compact('data', 'cafeteria', 'student', 'totalAmount'));
	}


	 public function cafeteria_cards()
		{

			$userid = Auth::user()->id;
			$school_id = Auth::user()->school_id;
			$cafeteria_id = Auth::user()->cafeteria_id;
			$cafeteria = Cafeteria_Model::where('id',$cafeteria_id)->first(); 
		
			$school = School_Model::where('id',$school_id)->get();   // school
			$parent = SchoolParent_Model::get(); // parent
			$data = SchoolStudent_Model::where('school_id',$school_id)->get();  //student
			// echo "<pre>"; print_r($data); die;
			return view('admin.cafeteriaadmin.cards.index', compact('school','parent','data', 'cafeteria'));
		}


		public function cafeteriacard_add(Request $request)
        {
			// echo "<pre>"; print_r($request->all()); die;
            $data = SchoolStudent_Model::where('id', $request->id)->first(); 

            if ($data) {
                $data->card_no = $request->number;  
                $data->card_status = 1;

                $data->save();

                return redirect()->back()->with('success', 'Card Added Successfully!');
            } else {
                return redirect()->back()->with('error', 'Student not found!');
            }
        }


		public function pos_order(Request $request)
	{
		// Debug removed: DON'T use die or print_r here
		// echo "<pre>"; print_r($request->all()); die;  ❌
		
		$student = SchoolStudent_Model::where('card_no', $request->cardNo)->first();

		if (!$student) {
			return response()->json(['status' => false, 'message' => 'Student not found']);
		}

		$transactionNo = "SD" . rand(111111, 999999);

		$totalAmount = $request->total_amount;
		$walletUsed  = $request->wallet_used ?? 0;
		$payable     = $totalAmount - $walletUsed;

		$order = Order_Model::create([
			'transaction_no' => $transactionNo,
			'parent_id'      => $student->parent_id,
			'student_id'     => $student->id,
			'school_id'      => $student->school_id,
			'cafeteria_id'   => $request->cafeteria_id,
			'date'           => now()->format('d M Y'),
			'grand_total'    => $request->after_discount,
			'total_amount'   => $request->total_amount,
			'discount'       => $request->discount_percent,
			'after_discount' => $request->after_discount,
			'wallet_used'    => $walletUsed,
			'payable'        => $payable,
			'payment_type'   => $request->payment_method,
			'payment_status' => 1,
		]);

		foreach ($request->cart as $cart) {
			OrderItem_Model::create([
				'order_id'    => $order->id,
				'dish_id'     => $cart['id'],
				'qty'         => $cart['qty'],
				'dish_price'  => $cart['price'],
				'total_price' => $cart['price'] * $cart['qty'],
			]);
		}

		// Deduct from wallet
		if ($walletUsed > 0) {
			$student->wallet_balance -= $walletUsed;
			$student->save();
		}

		return response()->json([
			'status'  => true,
			'message' => 'Order saved successfully',
			'transaction_no' => $transactionNo,
		]);
	}

		public function pos_order_show($id)
		{
			// Main order
			$order = Order_Model::with('details')->findOrFail($id);
			// echo "<pre>"; print_r($order); die;

			$dish = Dish_Model::get();


			$student = SchoolStudent_Model::find($order->student_id);


			return view('admin.cafeteriaadmin.onsite.invoiceshow', compact('order', 'student', 'dish'));
		}

		public function pos_order_print($id)
		{
			$order = Order_Model::with('details')->findOrFail($id);
			$student = SchoolStudent_Model::find($order->student_id);
			$dish = Dish_Model::get();

			$pdf = Pdf::loadView('admin.cafeteriaadmin.onsite.invoice_template', compact('order', 'student', 'dish'));
			return $pdf->download('invoice_'.$order->id.'.pdf');
		}
				


		public function createPlan()
		{
			return view('admin.cafeteriaadmin.plan_create.create');
			
		}


		public function cafeteria_plans_store(Request $request)
		{

			 $cafe_user_id = Auth::id();
			$cafeteria_id = Auth::user()->cafeteria_id;

			// echo $c_id; die;
			$request->validate([
				'name' => 'required|string|max:255',
				'duration_days' => 'required|numeric|min:1',
				'price' => 'required|numeric|min:1',
				'meals' => 'required|array',
				'auto_renew' => 'required|boolean',
			]);

			Plan_Model::create([
				'cafeteria_id' => $cafeteria_id,
				'cafeteria_user_id' => $cafe_user_id,
				'name' => $request->name,
				'duration_days' => $request->duration_days,
				'price' => $request->price,
				'meals' => implode(',', $request->meals),
				'active' => $request->active ? 1 : 0,
				'auto_renew' => $request->auto_renew,
			]);

			return redirect()->route('admin.cafeteria_Plans_list')->with('success', 'Plan created successfully!');
		}

		// public function listPlans()
		// {
		// 	$plans = CafeteriaPlan_Model::where('cafeteria_id', Auth::id())->get();
		// 	return view('cafeteria.plan_list', compact('plans'));
		// }

		public function Plans_list()
		{
			$plans = Plan_Model::where('cafeteria_id', Auth::user()->cafeteria_id)->get();
			return view('admin.cafeteriaadmin.plan_create.index', compact('plans'));
		}

		

		public function cafeteria_plans_edit()
		{
			
		}
		public function cafeteria_plans_delete()
		{
			
		}

		///////////////////////////////--Plan Subscriptions--////////////////////////////////////

		public function planSubscriptions($planId)
		{
			$cafeteriaId = Auth::user()->cafeteria_id;

			$subscriptions = Subscription_Model::with(['student', 'parent'])
				->where('cafeteria_id', $cafeteriaId)
				->where('plan_id', $planId)
				->orderBy('id', 'desc')
				->get()
				->map(function ($sub) {
					$today = Carbon::now()->startOfDay();
					$start = Carbon::parse($sub->start_date)->startOfDay();
					$end = Carbon::parse($sub->end_date)->startOfDay();

					$totalDays = $start->diffInDays($end) + 1;

					if ($today->lt($start)) {
						$remaining = $totalDays;
						$status = 'upcoming';
						$daysUntilStart = $today->diffInDays($start);
						$note = "Starts in {$daysUntilStart} days";

					} elseif ($today->between($start, $end)) {
						$remaining = $today->diffInDays($end, false) + 1; 
						$remaining = max(0, $remaining);
						$status = 'active';
						$note = "Active — {$remaining} days left";

					} else {
						$remaining = 0;
						$status = 'completed';
						$note = "Completed on {$end->format('d M Y')}";
					}

					$sub->total_days = $totalDays;
					$sub->remaining_days_dynamic = $remaining;
					$sub->status_dynamic = $status;
					$sub->note = $note;

					return $sub;
				});

			return view('admin.cafeteriaadmin.plan_create.subscriptions', compact('subscriptions'));
		}


		public function toggleStatus($id)
		{
			$plan = Plan::find($id);

			if (!$plan) {
				return redirect()->back()->with('error', 'Plan not found');
			}

			$plan->active = !$plan->active;
			$plan->save();

			return redirect()->back()->with('success', 'Status updated');
		}

		public function planSubscriptions_paused($subId)
        {
            $cafeteriaId = Auth::user()->cafeteria_id;

			$subscriptions = Subscription_Model::where('id',$subId)->first();
			$student = SchoolStudent_Model::where('id',$subscriptions->student_id)->first();
			$parent = SchoolParent_Model::where('id',$subscriptions->parent_id)->first();

            $paused = SubscriptionPause_Model::where('subscription_id',$subId)->get();

            return view('admin.cafeteriaadmin.plan_create.subscriptions_paused', compact('subscriptions','paused','student','parent'));
        }




            //////////////////////////////////////////////////////////////////////////////////
}
