<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

    use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
///////--Models--///////////
use Illuminate\Support\Facades\Auth;
use App\Models\PayForService\Subscription_Model;
use App\Models\PayForService\SubscriptionPause_Model;
use App\Models\School_Model;
use Carbon\Carbon;
use App\Models\SchoolParent_Model;
use App\Models\SchoolStudent_Model;
use App\Models\PayForService\Plan_Model;

use App\Models\Cafeteria\Cafeteria_Model;
use App\Models\Cafeteria\CafeteriaUser_Model;

use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Mail\SchoolUserInviteMail; 

/////
use Illuminate\Support\Facades\DB;

class CafeteriaController extends Controller
{
         public function __construct()
    {
        $this->middleware('auth');
    }

    public function cafeterias()
    {
        $data = Cafeteria_Model::orderby('id','DESC')->get();
        return view('admin.cafeterias.index', compact('data'));
    }

  

        public function cafeterias_store(Request $request)
        {
            $request->validate([
                'cafeteria_name' => 'required|string|max:255',
                'address' => 'required|string|max:500',
            ]);
            
            $school = new Cafeteria_Model();
            $school->cafeteria_name = $request->input('cafeteria_name');
            $school->address = $request->input('address');
            $school->save();

            
            return redirect()->route('admin.cafeterias')->with('success', 'Cafeterias added successfully!');
        }

            public function cafeterias_update(Request $request, $id)
        {
            $school = Cafeteria_Model::findOrFail($id);
            $school->cafeteria_name = $request->input('cafeteria_name');
            $school->address = $request->input('address');
            $school->save();

            return redirect()->route('admin.cafeterias')->with('success', 'Cafeterias updated successfully!');
        }

        

        public function cafeteriaschangeStatus($id, Request $request)
        {
            $cafeteria = Cafeteria_Model::find($id);
              if (!$cafeteria) {
                    return response()->json(['success' => false, 'message' => 'Cafeteria not found.']);
                }

                $cafeteria->status = $request->status;
                $cafeteria->save();

            return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
        }


        /////////////////////////////////////--Cafeteria Users--//////////////////////////////
        //////////////////////////////////////////////////////////////////////////
        //////////////////////////////////////////////////////////////////////////
            /* public function cafeterias_user()
            {

                $cafe = Cafeteria_Model::orderby('id','DESC')->get();
                $data = User::where('role','cafeteriaadmin')->get();
                return view('admin.cafeterias_user.index', compact('data','cafe'));
            } */
			
			public function cafeterias_user(Request $request)
			{
				$cafe = Cafeteria_Model::orderby('id', 'DESC')->get();

				$query = User::where('role', 'cafeteriaadmin');

				if ($request->filled('email')) {
					$query->where('email', 'like', '%' . $request->email . '%');
				}

				if ($request->filled('cafeteria_id')) {
					$query->where('cafeteria_id', $request->cafeteria_id);
				}

				$data = $query->paginate(10);

				return view('admin.cafeterias_user.index', compact('data', 'cafe'));
			}

           public function cafeterias_user_store(Request $request)
                {
                    $request->validate([
                        'cafe_id' => 'required', 
                        'email' => 'required|email|unique:users,email', 
                    ]);

                    $plainPassword = Str::random(10);

                    $cafeteriaUser = new User();
                    $cafeteriaUser->cafeteria_id = $request->input('cafe_id');
                    $cafeteriaUser->email = $request->input('email');
                    $cafeteriaUser->role = 'cafeteriaadmin';
                    $cafeteriaUser->invite_code = rand(111111, 999999);
                    $cafeteriaUser->password = Hash::make($plainPassword); 
                    $cafeteriaUser->save();

                    Mail::to($cafeteriaUser->email)->send(
                        new SchoolUserInviteMail($cafeteriaUser, $plainPassword)
                    );

                    return redirect()->route('admin.cafeterias_user')
                        ->with('success', 'Cafeteria user added successfully!');
                }




            public function cafeterias_user_update(Request $request, $id)
            {
                $school = CafeteriaUser_Model::findOrFail($id);
                $school->cafeteria_name = $request->input('name');
                $school->address = $request->input('address');
                $school->save();

                return redirect()->route('admin.cafeterias_user')->with('success', 'Cafeterias updated successfully!');
            }

        

            public function cafeterias_userchangeStatus($id)
            {
                $school = CafeteriaUser_Model::findOrFail($id);
                $school->status = $school->status == 1 ? 0 : 1;
                $school->save();

                return redirect()->back()->with('success', 'Status updated successfully!');
            }

           /////////////////////////--Cafeteria User list--///////////////////


             /*     public function cafeteriaslist_user()
            {
                $cafe = Cafeteria_Model::get();
                $data = User::where('role','cafeteriaadmin')->get();
                return view('admin.cafeterias_user_list.index', compact('data','cafe'));
            } */
			
	public function cafeteriaslist_user(Request $request)
	{
		$cafe = Cafeteria_Model::get();

		$query = User::where('role', 'cafeteriaadmin');

		if ($request->filled('name')) {
			$query->where('name', 'like', '%' . $request->name . '%');
		}

		if ($request->filled('cafeteria_id')) {
			$query->where('cafeteria_id', $request->cafeteria_id);
		}

		$data = $query->paginate(10);

		return view('admin.cafeterias_user_list.index', compact('data', 'cafe'));
	}


            public function cafeteriaslist_userchangeStatus($id)
            {
                $school = User::findOrFail($id);
                $school->status = $school->status == 1 ? 0 : 1;
                $school->save();

                return redirect()->back()->with('success', 'Status updated successfully!');
            }


            //////////////////////////////////--Assign Cafeteria--////////////////////////////////////////

             public function assign_user()
            {
                
                $cafe = Cafeteria_Model::get();
                $school = School_Model::get();

                return view('admin.assign_cafeteria.index', compact('cafe','school'));
            }
                public function assign_user_store(Request $request)
                {

                    // echo "<pre>"; print_r($request->all()); die;

                    $school_id = $request->school_id;
                    $cafe_id   = $request->cafe_id;

                    $cafe = Cafeteria_Model::find($cafe_id);


                    // Assign and save
                    $cafe->school_id = $school_id;
                    $cafe->save();

                     return redirect()->back()->with('success', 'Assign successfully!');
                }


                /*  public function cards()
                {
                
                    $school = School_Model::get();   // school

                    $parent = SchoolParent_Model::get(); // parent

                    $data = SchoolStudent_Model::orderby('id','DESC')->get();  //student

                    // echo "<pre>"; print_r($data); die;

                    return view('admin.cards.index', compact('school','parent','data'));
                } */
				
				
		public function cards(Request $request)
		{
			$school = School_Model::get();
			$parent = SchoolParent_Model::get();

			$query = SchoolStudent_Model::orderby('id', 'DESC');

			if ($request->filled('card_number')) {
				$query->where('card_no', 'like', '%' . $request->card_number . '%');
			}

			if ($request->filled('student_name')) {
				$query->where('student_name', 'like', '%' . $request->student_name . '%');
			}

			if ($request->filled('parent_name')) {
				$parentIds = SchoolParent_Model::where('name', 'like', '%' . $request->parent_name . '%')->pluck('id');
				$query->whereIn('parent_id', $parentIds);
			}

			if ($request->filled('school_id')) {
				$query->where('school_id', $request->school_id);
			}

			if ($request->filled('card_type')) {
				if ($request->card_type === 'written') {
					$query->whereNotNull('card_no')->where('card_no', '!=', '');
				} elseif ($request->card_type === 'unwritten') {
					$query->where(function($q) {
						$q->whereNull('card_no')->orWhere('card_no', '');
					});
				}
			}

			$data = $query->paginate(10);

			return view('admin.cards.index', compact('school', 'parent', 'data'));
		}
				
				

       public function card_add(Request $request)
        {
          

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


        public function admin_payforservice(Request $request)
        {
            $cafeterias = Cafeteria_Model::where('status', 1)->get();

            $query = Plan_Model::query()
                ->leftJoin('tbl_cafeteria as c', 'c.id', '=', 'tbl_plans.cafeteria_id')
                ->select('tbl_plans.*', 'c.cafeteria_name');

            if ($request->filled('cafeteria_id')) {
                $query->where('tbl_plans.cafeteria_id', $request->cafeteria_id);
            }

            $plans = $query->orderBy('tbl_plans.id', 'desc')->paginate(15)->withQueryString();

            return view('admin.payforservice.index', compact('plans', 'cafeterias'));
        }


        public function adminplanSubscriptions($planId)
		{
			$cafeteriaId = Auth::user()->cafeteria_id;

			$subscriptions = Subscription_Model::with(['student', 'parent'])
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

			return view('admin.payforservice.subscriptions', compact('subscriptions'));
		}

        

        public function admin_statistics(Request $request)
        {
            $user = Auth::user();
            $role = $user->role;

            // Dropdown data
            $schools    = School_Model::where('status', 1)->get();
            $cafeterias = Cafeteria_Model::where('status', 1)->get();

            // Filters
            $from        = $this->parseDate($request->input('from_date'));
            $to          = $this->parseDate($request->input('to_date'));
            $studentName = $request->input('student_name');
            $schoolId    = $request->input('school_id');
            $cafeId      = $request->input('cafeteria_id');
            $status      = $request->input('status'); // '', 'success', 'pending', 'failed'

            // Role scope
            if ($role == 'schooladmin')    $schoolId = $user->school_id;
            if ($role == 'cafeteriaadmin') $cafeId   = $user->cafeteria_id;

            // Map status text -> payment_status value
            $statusVal = null;
            if ($status === 'success') $statusVal = 1;
            if ($status === 'pending') $statusVal = 0;
            if ($status === 'failed')  $statusVal = 2;

            // ---------- Onsite sales (order items) ----------
            $onsite = DB::table('tbl_order_items as oi')
                ->join('tbl_orders as o', 'o.id', '=', 'oi.order_id')
                ->leftJoin('tbl_dish as d', 'd.id', '=', 'oi.dish_id')
                ->leftJoin('tbl_school_student as st', 'st.id', '=', 'o.student_id')
                ->leftJoin('tbl_school as s', 's.id', '=', 'o.school_id')
                ->leftJoin('tbl_cafeteria as c', 'c.id', '=', 'o.cafeteria_id')
                ->select(
                    'oi.id',
                    'd.dish_name',
                    'st.student_name',
                    's.school_name',
                    'c.cafeteria_name',
                    DB::raw("'Onsite Sales' as order_type"),
                    'oi.qty',
                    'o.payment_type as payment_mode',
                    'o.payment_status',
                    'oi.total_price as credits',
                    'o.created_at'
                );

            if ($from)        $onsite->whereDate('o.created_at', '>=', $from);
            if ($to)          $onsite->whereDate('o.created_at', '<=', $to);
            if ($studentName) $onsite->where('st.student_name', 'like', "%{$studentName}%");
            if ($schoolId)    $onsite->where('o.school_id', $schoolId);
            if ($cafeId)      $onsite->where('o.cafeteria_id', $cafeId);
            if (!is_null($statusVal)) $onsite->where('o.payment_status', $statusVal);

            // ---------- Pre orders ----------
            $pre = DB::table('tbl_preorders as p')
                ->leftJoin('tbl_dish as d', 'd.id', '=', 'p.dish_id')
                ->leftJoin('tbl_school_student as st', 'st.id', '=', 'p.student_id')
                ->leftJoin('tbl_school as s', 's.id', '=', 'p.school_id')
                ->leftJoin('tbl_cafeteria as c', 'c.id', '=', 'p.cafeteria_id')
                ->select(
                    'p.id',
                    'd.dish_name',
                    'st.student_name',
                    's.school_name',
                    'c.cafeteria_name',
                    DB::raw("'Pre Order' as order_type"),
                    'p.qty',
                    'p.payment_type as payment_mode',
                    'p.payment_status',
                    'p.total_price as credits',
                    'p.created_at'
                );

            if ($from)        $pre->whereDate('p.created_at', '>=', $from);
            if ($to)          $pre->whereDate('p.created_at', '<=', $to);
            if ($studentName) $pre->where('st.student_name', 'like', "%{$studentName}%");
            if ($schoolId)    $pre->where('p.school_id', $schoolId);
            if ($cafeId)      $pre->where('p.cafeteria_id', $cafeId);
            if (!is_null($statusVal)) $pre->where('p.payment_status', $statusVal);

            // ---------- Union + paginate ----------
            $union = $onsite->unionAll($pre);

            $sales = DB::query()
                ->fromSub($union, 'sales')
                ->orderByDesc('created_at')
                ->paginate(20)
                ->withQueryString();

            return view('admin.statistics.statistics', compact('sales', 'schools', 'cafeterias', 'role'));
        }

        private function parseDate($value)
        {
            if (empty($value)) {
                return null;
            }
            foreach (['d-M-Y', 'd-m-Y', 'Y-m-d', 'd/m/Y'] as $fmt) {
                try {
                    return \Carbon\Carbon::createFromFormat($fmt, $value)->format('Y-m-d');
                } catch (\Exception $e) {
                    // try next format
                }
            }
            try {
                return \Carbon\Carbon::parse($value)->format('Y-m-d');
            } catch (\Exception $e) {
                return null;
            }
        }


        public function pfs_Service(Request $request)
        {
            $user = Auth::user();
            $role = $user->role;

            $schools    = School_Model::where('status', 1)->get();
            $cafeterias = Cafeteria_Model::where('status', 1)->get();

            $sales = $this->buildPfsQuery($request)->orderByDesc('sub.created_at')->paginate(20)->withQueryString();

            return view('admin.pfs_services.pay_for_service', compact('sales', 'schools', 'cafeterias', 'role'));

        }


        private function buildPfsQuery(Request $request)
        {
            $user = Auth::user();
            $role = $user->role;

            $from        = $this->parseDate($request->input('from_date'));
            $to          = $this->parseDate($request->input('to_date'));
            $studentName = $request->input('student_name');
            $schoolId    = $request->input('school_id');
            $cafeId      = $request->input('cafeteria_id');

            if ($role == 'schooladmin')    $schoolId = $user->school_id;
            if ($role == 'cafeteriaadmin') $cafeId   = $user->cafeteria_id;

            $q = DB::table('tbl_subscriptions as sub')
                ->leftJoin('tbl_plans as pl', 'pl.id', '=', 'sub.plan_id')
                ->leftJoin('tbl_school_student as st', 'st.id', '=', 'sub.student_id')
                ->leftJoin('tbl_school as s', 's.id', '=', 'sub.school_id')
                ->leftJoin('tbl_cafeteria as c', 'c.id', '=', 'sub.cafeteria_id')
                ->select(
                    'sub.id',
                    'pl.name as service_name',
                    'st.student_name',
                    'st.grade',
                    's.school_name',
                    'c.cafeteria_name',
                    DB::raw("'Credit Card' as payment_mode"),
                    'sub.payment_status',
                    'sub.price as credits',
                    'sub.status as sub_status',
                    'sub.created_at'
                );

            if ($from)        $q->whereDate('sub.created_at', '>=', $from);
            if ($to)          $q->whereDate('sub.created_at', '<=', $to);
            if ($studentName) $q->where('st.student_name', 'like', "%{$studentName}%");
            if ($schoolId)    $q->where('sub.school_id', $schoolId);
            if ($cafeId)      $q->where('sub.cafeteria_id', $cafeId);

            return $q;
        }

        public function exportPfs(Request $request, $type)
        {
            $rows = $this->buildPfsQuery($request)->orderByDesc('pfs.created_at')->get();

            $headings = ['ID', 'Service Name', 'Student Name', 'School', 'Cafeteria', 'Grade', 'Date', 'Payment Mode', 'Payment Status', 'Credits'];
            $data = $rows->map(function ($r) {
                $status = $r->payment_status == 1 ? 'Success' : ($r->payment_status == 2 ? 'Failed' : 'Pending');
                return [
                    $r->id, $r->service_name, $r->student_name, $r->school_name, $r->cafeteria_name,
                    $r->grade, \Carbon\Carbon::parse($r->created_at)->format('d-M-Y'),
                    $r->payment_mode, $status, rtrim(rtrim(number_format($r->credits, 2), '0'), '.'),
                ];
            })->toArray();

            if ($type === 'pdf') {
                $title = 'Pay For Service Orders';
                $pdf = \PDF::loadView('admin.exports.table', compact('title', 'headings', 'data'))
                        ->setPaper('a4', 'landscape');
                return $pdf->download('pay-for-service-' . date('Y-m-d') . '.pdf');
            }

            return response()->streamDownload(function () use ($headings, $data) {
                $out = fopen('php://output', 'w');
                fputcsv($out, $headings);
                foreach ($data as $row) fputcsv($out, $row);
                fclose($out);
            }, 'pay-for-service-' . date('Y-m-d') . '.csv', ['Content-Type' => 'text/csv']);
        }



        // ------------------------------------Report Consumption------------------------------------------

        public function consumptionReport(Request $request)
        {
            $schools        = School_Model::where('status', 1)->get();
            $cafeteriaUsers = DB::table('users')
                ->where('role', 'cafeteriaadmin')
                ->whereNotNull('cafeteria_id')
                ->get(['id', 'name', 'email', 'cafeteria_id']);

            // Section 1 — wallet balances
            $walletStudents = $this->buildWalletStudents($request->input('wallet_school_id'));

            // Section 2 — consumption
            $consumption = $this->buildConsumption(
                $request->input('cr_school_id'),
                $this->parseDate($request->input('cr_date'))
            );

            // Section 3 — sales summary dishes wise
            $salesSummary = $this->buildSalesSummary(
                $request->input('cafeteria_id'),
                $this->parseDate($request->input('ss_from')),
                $this->parseDate($request->input('ss_to'))
            );

            return view('admin.report.consumption_report', compact(
                'schools', 'cafeteriaUsers', 'walletStudents', 'consumption', 'salesSummary'
            ));
        }

        // ---------- SECTION BUILDERS (shared by page + export) ----------

        private function buildWalletStudents($schoolId)
        {
            if (!$schoolId) return collect();

            return DB::table('tbl_school_student')
                ->where('school_id', $schoolId)
                ->where('status', 1)
                ->get(['id', 'student_name', 'grade', 'admission_no', 'wallet_balance']);
        }

        private function buildConsumption($schoolId, $date)
        {
            if (!$schoolId || !$date) return collect();

            $onsite = DB::table('tbl_orders as o')
                ->join('tbl_order_items as oi', 'oi.order_id', '=', 'o.id')
                ->where('o.payment_status', 1)
                ->where('o.school_id', $schoolId)
                ->whereDate('o.created_at', $date)
                ->select('o.student_id', DB::raw('oi.qty as qty'), DB::raw('oi.total_price as amount'));

            $pre = DB::table('tbl_preorders as p')
                ->where('p.payment_status', 1)
                ->where('p.school_id', $schoolId)
                ->whereDate('p.created_at', $date)
                ->select('p.student_id', DB::raw('p.qty as qty'), DB::raw('p.total_price as amount'));

            $union = $onsite->unionAll($pre);

            return DB::query()->fromSub($union, 't')
                ->leftJoin('tbl_school_student as st', 'st.id', '=', 't.student_id')
                ->groupBy('t.student_id', 'st.student_name', 'st.grade')
                ->select(
                    'st.student_name',
                    'st.grade',
                    DB::raw('SUM(t.qty) as total_qty'),
                    DB::raw('SUM(t.amount) as total_amount')
                )->get();
        }

        private function buildSalesSummary($cafeId, $from, $to)
        {
            if (!$cafeId) return collect();
            $from = $from ?: date('Y-m-d');
            $to   = $to   ?: date('Y-m-d');

            $onsite = DB::table('tbl_order_items as oi')
                ->join('tbl_orders as o', 'o.id', '=', 'oi.order_id')
                ->where('o.payment_status', 1)
                ->select('oi.dish_id', 'o.student_id',
                        DB::raw('oi.qty as qty'), DB::raw('oi.total_price as amount'),
                        'o.cafeteria_id', 'o.created_at');

            $pre = DB::table('tbl_preorders as p')
                ->where('p.payment_status', 1)
                ->select('p.dish_id', 'p.student_id',
                        DB::raw('p.qty as qty'), DB::raw('p.total_price as amount'),
                        'p.cafeteria_id', 'p.created_at');

            $union = $onsite->unionAll($pre);

            return DB::query()->fromSub($union, 't')
                ->leftJoin('tbl_dish as d', 'd.id', '=', 't.dish_id')
                ->where('t.cafeteria_id', $cafeId)
                ->whereDate('t.created_at', '>=', $from)
                ->whereDate('t.created_at', '<=', $to)
                ->groupBy('t.dish_id', 'd.dish_name')
                ->select(
                    't.dish_id',
                    'd.dish_name',
                    DB::raw('COUNT(DISTINCT t.student_id) as num_customers'),
                    DB::raw('SUM(t.qty) as invoice_qty'),
                    DB::raw('SUM(t.amount) as total_amount')
                )->get();
        }

        // ---------- EXPORT (one endpoint, all three sections) ----------

        public function exportConsumption(Request $request, $section, $type)
        {
            switch ($section) {
                case 'wallet':
                    $rows = $this->buildWalletStudents($request->input('wallet_school_id'));
                    $title = 'Student Wallet Balance';
                    $headings = ['Sl No', 'Student Name', 'Grade', 'Admission No', 'Wallet Balance'];
                    $data = $rows->values()->map(fn($r, $i) =>
                        [$i + 1, $r->student_name, $r->grade, $r->admission_no, $r->wallet_balance])->toArray();
                    break;

                case 'consumption':
                    $rows = $this->buildConsumption($request->input('cr_school_id'), $this->parseDate($request->input('cr_date')));
                    $title = 'Consumption Report';
                    $headings = ['Sl No', 'Student Name', 'Grade', 'Total Quantity', 'Total Amount'];
                    $data = $rows->values()->map(fn($r, $i) =>
                        [$i + 1, $r->student_name, $r->grade, $r->total_qty, number_format($r->total_amount, 2)])->toArray();
                    break;

                case 'sales':
                    $rows = $this->buildSalesSummary($request->input('cafeteria_id'), $this->parseDate($request->input('ss_from')), $this->parseDate($request->input('ss_to')));
                    $title = 'Sales Summary Dishes Wise';
                    $headings = ['Sl No', 'Dish Id', 'Dish Name', 'Number of Customers', 'Invoice Quantity', 'Total Amount', 'Return Quantity', 'Return Amount', 'Discount Amount', 'Net Amount'];
                    $data = $rows->values()->map(fn($r, $i) =>
                        [$i + 1, $r->dish_id, $r->dish_name, $r->num_customers, $r->invoice_qty,
                        number_format($r->total_amount, 2), 0, '0.00', '0.00', number_format($r->total_amount, 2)])->toArray();
                    break;

                default:
                    abort(404);
            }

            if ($type === 'pdf') {
                $pdf = \PDF::loadView('admin.exports.table', compact('title', 'headings', 'data'))->setPaper('a4', 'landscape');
                return $pdf->download(\Illuminate\Support\Str::slug($title) . '-' . date('Y-m-d') . '.pdf');
            }

            return response()->streamDownload(function () use ($headings, $data) {
                $out = fopen('php://output', 'w');
                fputcsv($out, $headings);
                foreach ($data as $row) fputcsv($out, $row);
                fclose($out);
            }, \Illuminate\Support\Str::slug($title) . '-' . date('Y-m-d') . '.csv', ['Content-Type' => 'text/csv']);
        }

// --------------------------------------------sales report---------------------------------------------------------------

public function salesReport(Request $request)
{
    $schools    = School_Model::where('status', 1)->get();
    $cafeterias = Cafeteria_Model::where('status', 1)->get();

    $rows = $this->buildSalesReport($request);

    return view('admin.sales_report.sales_report', compact('rows', 'schools', 'cafeterias'));
}


private function buildSalesReport(Request $request)
{
    $user = Auth::user();
    $role = $user->role;

    $from     = $this->parseDate($request->input('from_date'));
    $to       = $this->parseDate($request->input('to_date'));
    $schoolId = $request->input('school_id');
    $cafeId   = $request->input('cafeteria_id');

    if ($role == 'schooladmin')    $schoolId = $user->school_id;
    if ($role == 'cafeteriaadmin') $cafeId   = $user->cafeteria_id;

    // ---- Onsite orders, grouped by day ----
    $onsite = DB::table('tbl_orders')->where('payment_status', 1);
    if ($schoolId) $onsite->where('school_id', $schoolId);
    if ($cafeId)   $onsite->where('cafeteria_id', $cafeId);
    if ($from)     $onsite->whereDate('created_at', '>=', $from);
    if ($to)       $onsite->whereDate('created_at', '<=', $to);
    $onsite = $onsite->groupBy(DB::raw('DATE(created_at)'))
        ->select(
            DB::raw('DATE(created_at) as d'),
            DB::raw("SUM(CASE WHEN payment_type='cash' THEN grand_total ELSE 0 END) as cash"),
            DB::raw("SUM(CASE WHEN payment_type IN ('credit','creditcard','card') THEN grand_total ELSE 0 END) as cc_onsite"),
            DB::raw('SUM(wallet_used) as onsite_wallet'),
            DB::raw('SUM(grand_total) as onsite_gross')
        )->get()->keyBy('d');

    // ---- Pre-orders, grouped by day ----
    $pre = DB::table('tbl_preorders')->where('payment_status', 1);
    if ($schoolId) $pre->where('school_id', $schoolId);
    if ($cafeId)   $pre->where('cafeteria_id', $cafeId);
    if ($from)     $pre->whereDate('created_at', '>=', $from);
    if ($to)       $pre->whereDate('created_at', '<=', $to);
    $pre = $pre->groupBy(DB::raw('DATE(created_at)'))
        ->select(
            DB::raw('DATE(created_at) as d'),
            DB::raw('SUM(total_price) as pre_order'),
            DB::raw("SUM(CASE WHEN payment_type IN ('credit','creditcard','card') THEN total_price ELSE 0 END) as cc_pre"),
            DB::raw("SUM(CASE WHEN payment_type='wallet' THEN total_price ELSE 0 END) as pre_wallet")
        )->get()->keyBy('d');

    // ---- Parent top-ups, grouped by day (school-scoped) ----
    $ptop = DB::table('tbl_parents_topup as t')->where('t.payment_status', 1);
    if ($schoolId) {
        $ptop->join('tbl_school_parents as p', 'p.id', '=', 't.parent_id')->where('p.school_id', $schoolId);
    }
    if ($from) $ptop->whereDate('t.created_at', '>=', $from);
    if ($to)   $ptop->whereDate('t.created_at', '<=', $to);
    $ptop = $ptop->groupBy(DB::raw('DATE(t.created_at)'))
        ->select(DB::raw('DATE(t.created_at) as d'), DB::raw('SUM(t.amount) as parent_topup'))
        ->get()->keyBy('d');

    // ---- Cafe top-ups (student credit transfer), grouped by day (school-scoped) ----
    $ctop = DB::table('tbl_student_credit_transfer as ct')
        ->join('tbl_school_student as st', 'st.id', '=', 'ct.student_id');
    if ($schoolId) $ctop->where('st.school_id', $schoolId);
    if ($from)     $ctop->whereDate('ct.created_at', '>=', $from);
    if ($to)       $ctop->whereDate('ct.created_at', '<=', $to);
    $ctop = $ctop->groupBy(DB::raw('DATE(ct.created_at)'))
        ->select(DB::raw('DATE(ct.created_at) as d'), DB::raw('SUM(ct.amount) as cafe_topup'))
        ->get()->keyBy('d');

    // ---- Merge every date that appears in any source ----
    $dates = collect()
        ->merge($onsite->keys())->merge($pre->keys())
        ->merge($ptop->keys())->merge($ctop->keys())
        ->unique()->sort()->values();

    return $dates->map(function ($d) use ($onsite, $pre, $ptop, $ctop) {
        $o = $onsite->get($d);
        $p = $pre->get($d);

        $cash        = (float) ($o->cash ?? 0);
        $ccOnsite    = (float) ($o->cc_onsite ?? 0);
        $onsiteWall  = (float) ($o->onsite_wallet ?? 0);
        $onsiteGross = (float) ($o->onsite_gross ?? 0);

        $preOrder    = (float) ($p->pre_order ?? 0);
        $ccPre       = (float) ($p->cc_pre ?? 0);
        $preWallet   = (float) ($p->pre_wallet ?? 0);

        $topUsed     = $onsiteWall + $preWallet;
        $net         = $cash + $ccPre + $ccOnsite + $topUsed;

        return (object) [
            'date'         => $d,
            'pre_order'    => $preOrder,
            'cash'         => $cash,
            'cc_pre'       => $ccPre,
            'cc_onsite'    => $ccOnsite,
            'top_used'     => $topUsed,
            'gross_amount' => $onsiteGross,
            'net_amount'   => $net,
            'cafe_topup'   => (float) ($ctop->get($d)->cafe_topup ?? 0),
            'parent_topup' => (float) ($ptop->get($d)->parent_topup ?? 0),
        ];
    });
}

public function exportSalesReport(Request $request, $type)
{
    $rows = $this->buildSalesReport($request);

    $headings = ['Sr No', 'Date', 'Pre Order', 'Cash', 'Credit Card (Pre)', 'Credit Card (Onsite)',
                 'Top Used', 'Gross Amount', 'Net Amount', 'Cafe Topup', 'Parent Topup'];

    $fmt = fn($v) => rtrim(rtrim(number_format($v, 2), '0'), '.');

    $data = $rows->values()->map(function ($r, $i) use ($fmt) {
        return [
            $i + 1, \Carbon\Carbon::parse($r->date)->format('d-M-Y'),
            $fmt($r->pre_order), $fmt($r->cash), $fmt($r->cc_pre), $fmt($r->cc_onsite),
            $fmt($r->top_used), $fmt($r->gross_amount), $fmt($r->net_amount),
            $fmt($r->cafe_topup), $fmt($r->parent_topup),
        ];
    })->toArray();

    if ($type === 'pdf') {
        $title = 'Sales Report';
        $pdf = \PDF::loadView('admin.exports.table', compact('title', 'headings', 'data'))->setPaper('a4', 'landscape');
        return $pdf->download('sales-report-' . date('Y-m-d') . '.pdf');
    }

    return response()->streamDownload(function () use ($headings, $data) {
        $out = fopen('php://output', 'w');
        fputcsv($out, $headings);
        foreach ($data as $row) fputcsv($out, $row);
        fclose($out);
    }, 'sales-report-' . date('Y-m-d') . '.csv', ['Content-Type' => 'text/csv']);
}

            
            
}
