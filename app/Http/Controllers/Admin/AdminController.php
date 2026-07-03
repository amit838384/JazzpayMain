<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
//Model

use App\Models\School_Model;
use App\Models\Feedback_Model;
use App\Models\SchoolParent_Model;

use App\Models\AlertMessage_Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Models\Cafeteria\Cafeteria_Model;
use App\Models\SchoolStudent_Model;
class AdminController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    
 
	/* public function index()
	{
		$user = Auth::user();
		$schoolID = $user->school_id;
		$cafeteriaID = $user->cafeteria_id;
		$role = $user->role;

		$scount = collect(); 
		$ccount = collect();

		if ($role == "superadmin") {
			$ccount = Cafeteria_Model::get();
			$scount = School_Model::get();
		}
		 elseif ($role == "schooladmin")
		{
			$scount = School_Model::where('id', $schoolID)->get();
			$ccount = SchoolStudent_Model::where('school_id', $schoolID)->get();
		}

		return view('admin.dashboard', compact('role', 'scount', 'ccount'));
	} */
	
	
	// public function index(Request $request)
	// {
	// 	$user        = Auth::user();
	// 	$schoolID    = $user->school_id;
	// 	$cafeteriaID = $user->cafeteria_id;
	// 	$role        = $user->role;

	// 	$scount = collect();
	// 	$ccount = collect();

	// 	if ($role == "superadmin") {
	// 		$ccount = Cafeteria_Model::get();
	// 		$scount = School_Model::get();
	// 	} elseif ($role == "schooladmin") {
	// 		$scount = School_Model::where('id', $schoolID)->get();
	// 		$ccount = SchoolStudent_Model::where('school_id', $schoolID)->get();
	// 	}

	// 	// ---- Summary cards: TODO — compute from your Order / Topup models ----
	// 	$totalSales         = 0;
	// 	$totalPreOrderSales = 0;
	// 	$totalOnsiteSales   = 0;
	// 	$totalTopup         = 0;

	// 	// ---- School-wise sales: TODO ----
	// 	// each row needs: school_name, cafeteria_name, onsite_sales, cafe_topup, parent_topup
	// 	$schoolSales = collect();

	// 	// ---- Cafeteria summary: TODO ----
	// 	// each row needs: cafeteria_name, pre_order, cash, credit_card, used_topup,
	// 	//                 gross_amount, discount, return_amount, net_amount
	// 	$cafeteriaSummary = collect();

	// 	return view('admin.dashboard', compact(
	// 		'role', 'scount', 'ccount',
	// 		'totalSales', 'totalPreOrderSales', 'totalOnsiteSales', 'totalTopup',
	// 		'schoolSales', 'cafeteriaSummary'
	// 	));
	// }



	public function index(Request $request)
	{
		$user = Auth::user();
		$role = $user->role;

		$scount = collect();
		$ccount = collect();
		if ($role == "superadmin") {
			$ccount = Cafeteria_Model::get();
			$scount = School_Model::get();
		} elseif ($role == "schooladmin") {
			$scount = School_Model::where('id', $user->school_id)->get();
			$ccount = SchoolStudent_Model::where('school_id', $user->school_id)->get();
		}

		[$fSchool, $fFrom, $fTo, $fCafe, $cafeFrom, $cafeTo] = $this->dashboardFilters($request);

		// Summary cards (respect section-1 school + date)
		$totals             = $this->buildSummaryTotals($fSchool, $fFrom, $fTo);
		$totalOnsiteSales   = $totals['onsite'];
		$totalPreOrderSales = $totals['preorder'];
		$totalSales         = $totals['sales'];
		$totalTopup         = $totals['topup'];

		// Tables (raw rows -> format for display)
		$schoolSales = $this->buildSchoolSales($fSchool, $fFrom, $fTo)->map(function ($r) {
		return (object) [
			'school_name'    => $r->school_name ?? '-',
			'cafeteria_name' => $r->cafeteria_name ?? '-',
			'onsite_sales'   => number_format($r->onsite_sales, 2),
			'cafe_topup'     => number_format($r->cafe_topup, 2),
			'parent_topup'   => number_format($r->parent_topup, 2),
			];
		});

		$cafeteriaSummary = $this->buildCafeteriaSummary($fCafe, $cafeFrom, $cafeTo)->map(function ($r) {
		return (object) [
			'cafeteria_name' => $r->cafeteria_name ?? '-',
				'pre_order'      => number_format($r->pre_order, 2),
				'cash'           => number_format($r->cash, 2),
				'credit_card'    => number_format($r->credit_card, 2),
				'used_topup'     => number_format($r->used_topup, 2),
				'gross_amount'   => number_format($r->gross_amount, 2),
				'discount'       => number_format($r->discount, 2),
				'return_amount'  => number_format($r->return_amount, 2),
				'net_amount'     => number_format($r->net_amount, 2),
			];
		});

		// Cafeteria list for the filter dropdown (role-scoped)
			// Cafeteria list for the filter dropdown (role-scoped)
			if ($role == 'superadmin') {
				$cafeterias = Cafeteria_Model::where('status', 1)->orderBy('id', 'desc')->get();
			} elseif ($role == 'schooladmin') {
				$cafeterias = Cafeteria_Model::where('status', 1)
					->where('school_id', $user->school_id)->orderBy('id', 'desc')->get();
			} elseif ($role == 'cafeteriaadmin') {
				$cafeterias = Cafeteria_Model::where('status', 1)
					->where('id', $user->cafeteria_id)->orderBy('id', 'desc')->get();
			} else {
				$cafeterias = collect();
			}

			return view('admin.dashboard', compact(
				'role', 'scount', 'ccount', 'cafeterias',   // <-- add cafeterias
				'totalSales', 'totalPreOrderSales', 'totalOnsiteSales', 'totalTopup',
				'schoolSales', 'cafeteriaSummary'
			));

	}


	

	// ===================== SHARED HELPERS =====================

	private function dashboardFilters(Request $request)
	{
		$user = Auth::user();
		$fSchool  = $request->input('school_id');
		$fCafe    = $request->input('cafeteria_id');

		if ($user->role == "schooladmin")    $fSchool = $user->school_id;
		if ($user->role == "cafeteriaadmin") $fCafe   = $user->cafeteria_id;

		return [
			$fSchool,
			$this->parseDate($request->input('from_date')),
			$this->parseDate($request->input('to_date')),
			$fCafe,
			$this->parseDate($request->input('cafe_from_date')),
			$this->parseDate($request->input('cafe_to_date')),
		];
	}

	/* private function buildSummaryTotals($schoolId, $from, $to)
	{
		$oq = DB::table('tbl_orders')->where('payment_status', 1);
		if ($schoolId) $oq->where('school_id', $schoolId);
		if ($from) $oq->whereDate('created_at', '>=', $from);
		if ($to)   $oq->whereDate('created_at', '<=', $to);
		$onsite = (float) $oq->sum('grand_total');

		$pq = DB::table('tbl_preorders')->where('payment_status', 1);
		if ($schoolId) $pq->where('school_id', $schoolId);
		if ($from) $pq->whereDate('created_at', '>=', $from);
		if ($to)   $pq->whereDate('created_at', '<=', $to);
		$preorder = (float) $pq->sum('total_price');

		$tq = DB::table('tbl_parents_topup as t')->where('t.payment_status', 1);
		if ($schoolId) {
			$tq->join('tbl_school_parents as p', 'p.id', '=', 't.parent_id')
			   ->where('p.school_id', $schoolId);
		}
		if ($from) $tq->whereDate('t.created_at', '>=', $from);
		if ($to)   $tq->whereDate('t.created_at', '<=', $to);
		$topup = (float) $tq->sum('t.amount');

		return ['onsite' => $onsite, 'preorder' => $preorder, 'sales' => $onsite + $preorder, 'topup' => $topup];
	} */
	
// 	private function buildSummaryTotals($schoolId, $from, $to)
// {
//     // Onsite sales = tbl_orders grand_total
//     $oq = DB::table('tbl_orders')->where('payment_status', 1);
//     if ($schoolId) $oq->where('school_id', $schoolId);
//     if ($from) $oq->whereDate('created_at', '>=', $from);
//     if ($to)   $oq->whereDate('created_at', '<=', $to);
//     $onsite = (float) $oq->sum('grand_total');
 
//     // Pre-order sales = tbl_preorders total_price
//     $pq = DB::table('tbl_preorders')->where('payment_status', 1);
//     if ($schoolId) $pq->where('school_id', $schoolId);
//     if ($from) $pq->whereDate('created_at', '>=', $from);
//     if ($to)   $pq->whereDate('created_at', '<=', $to);
//     $preorder = (float) $pq->sum('total_price');
 
//     // Parent topup = tbl_parents_topup amount
//     $tq = DB::table('tbl_parents_topup as t')->where('t.payment_status', 1);
//     if ($schoolId) {
//         $tq->join('tbl_school_parents as p', 'p.id', '=', 't.parent_id')
//            ->where('p.school_id', $schoolId);
//     }
//     if ($from) $tq->whereDate('t.created_at', '>=', $from);
//     if ($to)   $tq->whereDate('t.created_at', '<=', $to);
//     $topup = (float) $tq->sum('t.amount');
 
//     return [
//         'onsite'   => $onsite,
//         'preorder' => $preorder,
//         'sales'    => $onsite + $preorder,
//         'topup'    => $topup,
//     ];
// }

private function buildSummaryTotals($schoolId, $from, $to)
{
    $user = Auth::user();
    $role = $user->role;

    // Force scope so it never shows global data
    if ($role == 'schooladmin') {
        $schoolId = $user->school_id;      // <-- add this
    }

    // ---- Onsite (orders) ----
    $oq = DB::table('tbl_orders')->where('payment_status', 1);
    if ($schoolId) $oq->where('school_id', $schoolId);
    if ($role == 'cafeteriaadmin') $oq->where('cafeteria_id', $user->cafeteria_id);
    if ($from) $oq->whereDate('created_at', '>=', $from);
    if ($to)   $oq->whereDate('created_at', '<=', $to);
    $onsite = (float) $oq->sum('grand_total');

    // ---- Pre-orders ----
    $pq = DB::table('tbl_preorders')->where('payment_status', 1);
    if ($schoolId) $pq->where('school_id', $schoolId);
    if ($role == 'cafeteriaadmin') $pq->where('cafeteria_id', $user->cafeteria_id);
    if ($from) $pq->whereDate('created_at', '>=', $from);
    if ($to)   $pq->whereDate('created_at', '<=', $to);
    $preorder = (float) $pq->sum('total_price');

    // ---- Topup (parent wallet top-ups) ----
    // Topups aren't tied to a cafeteria, so scope them by school.
    $topupSchool = $schoolId;
    if ($role == 'cafeteriaadmin') {
        $cafe = Cafeteria_Model::find($user->cafeteria_id);
        $topupSchool = $cafe->school_id ?? null;
    }

    $topup = 0;
    // For a cafeteria user with no linked school, leave topup at 0 (nothing to scope to)
    if (! ($role == 'cafeteriaadmin' && ! $topupSchool)) {
        $tq = DB::table('tbl_parents_topup as t')->where('t.payment_status', 1);
        if ($topupSchool) {
            $tq->join('tbl_school_parents as p', 'p.id', '=', 't.parent_id')
               ->where('p.school_id', $topupSchool);
        }
        if ($from) $tq->whereDate('t.created_at', '>=', $from);
        if ($to)   $tq->whereDate('t.created_at', '<=', $to);
        $topup = (float) $tq->sum('t.amount');
    }

    return [
        'onsite'   => $onsite,
        'preorder' => $preorder,
        'sales'    => $onsite + $preorder,
        'topup'    => $topup,
    ];
}


	private function buildSchoolSales($schoolId, $from, $to)
{
    $user = Auth::user();

    if ($user->role == 'schooladmin') {
        $schoolId = $user->school_id;      // <-- add this
    }

    $cafQuery = DB::table('tbl_cafeteria as c')
        ->leftJoin('tbl_school as s', 's.id', '=', 'c.school_id')
        ->where('c.status', 1);

    if ($schoolId) {
        $cafQuery->where('c.school_id', $schoolId);
    }
    if ($user->role == 'cafeteriaadmin') {
        $cafQuery->where('c.id', $user->cafeteria_id);
    }
		$cafeterias = $cafQuery
			->select('c.id as cafeteria_id', 'c.school_id', 'c.cafeteria_name', 's.school_name')
			->orderBy('c.id', 'desc')
			->get();

		return $cafeterias->map(function ($caf) use ($from, $to) {
			$onsite = DB::table('tbl_orders')->where('payment_status', 1)->where('cafeteria_id', $caf->cafeteria_id);
			if ($from) $onsite->whereDate('created_at', '>=', $from);
			if ($to)   $onsite->whereDate('created_at', '<=', $to);

			$cafeTopup = 0;
			$parentTopup = 0;
			if ($caf->school_id) {
				$ct = DB::table('tbl_student_credit_transfer as ct')
					->join('tbl_school_student as st', 'st.id', '=', 'ct.student_id')
					->where('st.school_id', $caf->school_id);
				if ($from) $ct->whereDate('ct.created_at', '>=', $from);
				if ($to)   $ct->whereDate('ct.created_at', '<=', $to);
				$cafeTopup = (float) $ct->sum('ct.amount');

				$pt = DB::table('tbl_parents_topup as t')
					->join('tbl_school_parents as p', 'p.id', '=', 't.parent_id')
					->where('p.school_id', $caf->school_id)->where('t.payment_status', 1);
				if ($from) $pt->whereDate('t.created_at', '>=', $from);
				if ($to)   $pt->whereDate('t.created_at', '<=', $to);
				$parentTopup = (float) $pt->sum('t.amount');
			}

			return (object) [
				'school_name'    => $caf->school_name ?? '—',
				'cafeteria_name' => $caf->cafeteria_name,
				'onsite_sales'   => (float) $onsite->sum('grand_total'),
				'cafe_topup'     => $cafeTopup,
				'parent_topup'   => $parentTopup,
			];
		});
	}

	/* private function buildCafeteriaSummary($cafeId, $from, $to)
	{
		$q = DB::table('tbl_cafeteria')->where('status', 1);
		if ($cafeId) $q->where('id', $cafeId);

		return $q->get(['id', 'cafeteria_name'])->map(function ($caf) use ($from, $to) {
			$oq = DB::table('tbl_orders')->where('payment_status', 1)->where('cafeteria_id', $caf->id);
			if ($from) $oq->whereDate('created_at', '>=', $from);
			if ($to)   $oq->whereDate('created_at', '<=', $to);

			$cash       = (float) (clone $oq)->where('payment_type', 'cash')->sum('grand_total');
			$creditCard = (float) (clone $oq)->whereIn('payment_type', ['credit', 'creditcard', 'card'])->sum('grand_total');
			$usedTopup  = (float) (clone $oq)->sum('wallet_used');
			$gross      = (float) (clone $oq)->sum('total_amount');
			$discount   = (float) (clone $oq)->sum('discount');

			$pq = DB::table('tbl_preorders')->where('payment_status', 1)->where('cafeteria_id', $caf->id);
			if ($from) $pq->whereDate('created_at', '>=', $from);
			if ($to)   $pq->whereDate('created_at', '<=', $to);
			$preOrder = (float) $pq->sum('total_price');

			$returnAmount = 0;
			return (object) [
				'cafeteria_name' => $caf->cafeteria_name,
				'pre_order'      => $preOrder,
				'cash'           => $cash,
				'credit_card'    => $creditCard,
				'used_topup'     => $usedTopup,
				'gross_amount'   => $gross,
				'discount'       => $discount,
				'return_amount'  => $returnAmount,
				'net_amount'     => $gross - $discount - $returnAmount,
			];
		});
	} */
	
	private function buildCafeteriaSummary($cafeId, $from, $to)
	{
		$user = Auth::user();

		// Fetch cafeterias via the model (role-scoped + DESC)
		$query = Cafeteria_Model::where('status', 1);

		if ($user->role == 'schooladmin') {
			$query->where('school_id', $user->school_id);
		}
		if ($cafeId) {
			$query->where('id', $cafeId);
		}

		$cafeterias = $query->orderBy('id', 'desc')->get();

		return $cafeterias->map(function ($caf) use ($from, $to) {
			// Onsite orders for this cafeteria
			$oq = DB::table('tbl_orders')->where('payment_status', 1)->where('cafeteria_id', $caf->id);
			if ($from) $oq->whereDate('created_at', '>=', $from);
			if ($to)   $oq->whereDate('created_at', '<=', $to);

			$cash       = (float) (clone $oq)->where('payment_type', 'cash')->sum('grand_total');
			$creditCard = (float) (clone $oq)->whereIn('payment_type', ['credit', 'creditcard', 'card'])->sum('grand_total');
			$usedTopup  = (float) (clone $oq)->sum('wallet_used');
			$gross      = (float) (clone $oq)->sum('total_amount');
			$discount   = (float) (clone $oq)->sum('discount');

			// Pre-orders for this cafeteria
			$pq = DB::table('tbl_preorders')->where('payment_status', 1)->where('cafeteria_id', $caf->id);
			if ($from) $pq->whereDate('created_at', '>=', $from);
			if ($to)   $pq->whereDate('created_at', '<=', $to);
			$preOrder = (float) $pq->sum('total_price');

			$returnAmount = 0;

			return (object) [
				'cafeteria_name' => $caf->cafeteria_name,
				'pre_order'      => $preOrder,
				'cash'           => $cash,
				'credit_card'    => $creditCard,
				'used_topup'     => $usedTopup,
				'gross_amount'   => $gross,
				'discount'       => $discount,
				'return_amount'  => $returnAmount,
				'net_amount'     => $gross - $discount - $returnAmount,
			];
		});
	}



	// ===================== EXPORTS =====================

	public function exportSchool(Request $request, $type)
	{
		[$fSchool, $fFrom, $fTo] = $this->dashboardFilters($request);
		$rows = $this->buildSchoolSales($fSchool, $fFrom, $fTo);

		$headings = ['Sr No', 'School', 'Cafeteria', 'Onsite Sales', 'Cafe Topup', 'Parent Topup'];
		$data = $rows->values()->map(function ($r, $i) {
			return [$i + 1, $r->school_name, $r->cafeteria_name,
					number_format($r->onsite_sales, 2), number_format($r->cafe_topup, 2), number_format($r->parent_topup, 2)];
		})->toArray();

		return $type === 'pdf'
			? $this->downloadPdf('School-wise Sales', $headings, $data, 'school-sales')
			: $this->downloadCsv($headings, $data, 'school-sales');
	}

	public function exportCafeteria(Request $request, $type)
	{
		[, , , $fCafe, $cafeFrom, $cafeTo] = $this->dashboardFilters($request);
		$rows = $this->buildCafeteriaSummary($fCafe, $cafeFrom, $cafeTo);

		$headings = ['Sr No', 'Cafeteria', 'Pre Order', 'Cash', 'Credit Card', 'Used Topup', 'Gross', 'Discount', 'Return', 'Net'];
		$data = $rows->values()->map(function ($r, $i) {
			return [$i + 1, $r->cafeteria_name, number_format($r->pre_order, 2), number_format($r->cash, 2),
					number_format($r->credit_card, 2), number_format($r->used_topup, 2), number_format($r->gross_amount, 2),
					number_format($r->discount, 2), number_format($r->return_amount, 2), number_format($r->net_amount, 2)];
		})->toArray();

		return $type === 'pdf'
			? $this->downloadPdf('Cafeteria Summary', $headings, $data, 'cafeteria-summary')
			: $this->downloadCsv($headings, $data, 'cafeteria-summary');
	}

	private function downloadCsv($headings, $rows, $filename)
	{
		$name = $filename . '-' . date('Y-m-d') . '.csv';
		return response()->streamDownload(function () use ($headings, $rows) {
			$out = fopen('php://output', 'w');
			fputcsv($out, $headings);
			foreach ($rows as $row) fputcsv($out, $row);
			fclose($out);
		}, $name, ['Content-Type' => 'text/csv']);
	}

	private function downloadPdf($title, $headings, $rows, $filename)
	{
		$pdf = \PDF::loadView('admin.exports.table', compact('title', 'headings', 'rows'));
		return $pdf->download($filename . '-' . date('Y-m-d') . '.pdf');
	}

	private function parseDate($value)
	{
		if (empty($value)) return null;
		foreach (['d-M-Y', 'd-m-Y', 'Y-m-d', 'd/m/Y'] as $fmt) {
			try { return Carbon::createFromFormat($fmt, $value)->format('Y-m-d'); } catch (\Exception $e) {}
		}
		try { return Carbon::parse($value)->format('Y-m-d'); } catch (\Exception $e) { return null; }
	}


	 
	   public function app_feedback()
	{
			$feedback = Feedback_Model::get();
			$parent = SchoolParent_Model::get();

		return view('admin.external_pages.feedback.index', compact('feedback','parent'));

	}

	public function feedbackstatuschange($id)
	{
		$feedback = Feedback_Model::find($id);

		if (!$feedback) {
			return redirect()->back()->with('error', 'Feedback not found.');
		}

		// Toggle status: if 1 make 0, if 0 make 1
		$feedback->status = $feedback->status == 1 ? 0 : 1;
		$feedback->save();

		return redirect()->back()->with('success', 'Feedback status updated successfully.');
	}


	  public function alert_message()
	{
			$message = AlertMessage_Model::get();

		return view('admin.external_pages.alert_message.index', compact('message'));

	}

	public function alert_message_update(Request $request, $id)
	{
			$message = AlertMessage_Model::where('id',$id)->first();
			$message->message = $request->message;
			$message->update();
		return redirect()->back()->with('success', 'Message Updated successfully.');
	}


	public function alert_messagestatuschange($id)
	{
		$feedback = AlertMessage_Model::find($id);
		if (!$feedback) {
			return redirect()->back()->with('error', 'Message not found.');
		}
		$feedback->status = $feedback->status == 1 ? 0 : 1;
		$feedback->save();
		return redirect()->back()->with('success', 'Status updated successfully.');
	}


}
