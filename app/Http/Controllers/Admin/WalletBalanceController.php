<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
///////--Models--///////////

use App\Models\School_Model;
use App\Models\SchoolUser_Model;
use App\Models\SchoolParent_Model;
use App\Models\SchoolStudent_Model;
use App\Models\ParentTopup_Model;

use App\Models\User;

use Illuminate\Support\Facades\Mail;
use App\Mail\SchoolUserInviteMail; 
use App\Mail\ParentInviteMail; 
use App\Http\Controllers\Admin\Auth;



class WalletBalanceController extends Controller
{
		/* public function topuplist_parents()
        {
            // echo "test"; die;
            $school = School_Model::get();
            $topup = ParentTopup_Model::orderBy('created_at', 'desc')->get();
            $data = SchoolParent_Model::where('view', 1)->get();
            return view('admin.parent_topup.index', compact('school','data','topup'));
        } */
		
		
	public function topuplist_parents(Request $request)
	{
		$school = School_Model::get();
		$data   = SchoolParent_Model::where('view', 1)->get();

		$query = ParentTopup_Model::orderBy('created_at', 'desc');

		if ($request->filled('from_date')) {
			$query->whereDate('created_at', '>=', \Carbon\Carbon::parse($request->from_date));
		}

		if ($request->filled('to_date')) {
			$query->whereDate('created_at', '<=', \Carbon\Carbon::parse($request->to_date));
		}

		if ($request->filled('mobile')) {
			$parentIds = SchoolParent_Model::where('mobile', 'like', '%' . $request->mobile . '%')->pluck('id');
			$query->whereIn('parent_id', $parentIds);
		}

		if ($request->filled('parent_name')) {
			$parentIds = SchoolParent_Model::where('name', 'like', '%' . $request->parent_name . '%')->pluck('id');
			$query->whereIn('parent_id', $parentIds);
		}

		if ($request->filled('transaction_number')) {
			$query->where('transaction_number', 'like', '%' . $request->transaction_number . '%');
		}

		if ($request->filled('school_id')) {
			$parentIds = SchoolParent_Model::where('school_id', $request->school_id)->pluck('id');
			$query->whereIn('parent_id', $parentIds);
		}

		if ($request->filled('status')) {
			$query->where('payment_status', $request->status);
		}

		$topup = $query->paginate(10);

		return view('admin.parent_topup.index', compact('school', 'data', 'topup'));
	}
	
	
	public function topuplist_export_pdf(Request $request)
	{
		$school = School_Model::get();
		$data   = SchoolParent_Model::where('view', 1)->get();
	 
		$query = ParentTopup_Model::orderBy('created_at', 'desc');
	 
		if ($request->filled('from_date')) {
			$query->whereDate('created_at', '>=', \Carbon\Carbon::parse($request->from_date));
		}
		if ($request->filled('to_date')) {
			$query->whereDate('created_at', '<=', \Carbon\Carbon::parse($request->to_date));
		}
		if ($request->filled('mobile')) {
			$parentIds = SchoolParent_Model::where('mobile', 'like', '%' . $request->mobile . '%')->pluck('id');
			$query->whereIn('parent_id', $parentIds);
		}
		if ($request->filled('parent_name')) {
			$parentIds = SchoolParent_Model::where('name', 'like', '%' . $request->parent_name . '%')->pluck('id');
			$query->whereIn('parent_id', $parentIds);
		}
		if ($request->filled('transaction_number')) {
			$query->where('transaction_number', 'like', '%' . $request->transaction_number . '%');
		}
		if ($request->filled('school_id')) {
			$parentIds = SchoolParent_Model::where('school_id', $request->school_id)->pluck('id');
			$query->whereIn('parent_id', $parentIds);
		}
		if ($request->filled('status')) {
			$query->where('payment_status', $request->status);
		}
	 
		$topup = $query->get();
	 
		$fromDate = $request->from_date ? \Carbon\Carbon::parse($request->from_date)->format('d-M-Y') : 'All';
		$toDate   = $request->to_date   ? \Carbon\Carbon::parse($request->to_date)->format('d-M-Y')   : 'All';
	 
		return view('admin.parent_topup.export_pdf', compact('topup', 'data', 'school', 'fromDate', 'toDate'));
	}
	 
	public function topuplist_export_excel(Request $request)
	{
		$school = School_Model::get();
		$data   = SchoolParent_Model::where('view', 1)->get();

		$query = ParentTopup_Model::orderBy('created_at', 'desc');

		if ($request->filled('from_date')) {
			$query->whereDate('created_at', '>=', \Carbon\Carbon::parse($request->from_date));
		}
		if ($request->filled('to_date')) {
			$query->whereDate('created_at', '<=', \Carbon\Carbon::parse($request->to_date));
		}
		if ($request->filled('mobile')) {
			$parentIds = SchoolParent_Model::where('mobile', 'like', '%' . $request->mobile . '%')->pluck('id');
			$query->whereIn('parent_id', $parentIds);
		}
		if ($request->filled('parent_name')) {
			$parentIds = SchoolParent_Model::where('name', 'like', '%' . $request->parent_name . '%')->pluck('id');
			$query->whereIn('parent_id', $parentIds);
		}
		if ($request->filled('transaction_number')) {
			$query->where('transaction_number', 'like', '%' . $request->transaction_number . '%');
		}
		if ($request->filled('school_id')) {
			$parentIds = SchoolParent_Model::where('school_id', $request->school_id)->pluck('id');
			$query->whereIn('parent_id', $parentIds);
		}
		if ($request->filled('status')) {
			$query->where('payment_status', $request->status);
		}

		$topup = $query->get();

		$fromDate = $request->from_date ? \Carbon\Carbon::parse($request->from_date)->format('d-M-Y') : 'All';
		$toDate   = $request->to_date   ? \Carbon\Carbon::parse($request->to_date)->format('d-M-Y')   : 'All';

		$filename = 'Topup-' . $fromDate . '-to-' . $toDate . '.csv';

		$headers = [
			'Content-Type'        => 'text/csv',
			'Content-Disposition' => 'attachment; filename="' . $filename . '"',
			'Pragma'              => 'no-cache',
			'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
			'Expires'             => '0',
		];

		$callback = function () use ($topup, $data, $school) {
			$file = fopen('php://output', 'w');

			// BOM for Excel UTF-8 compatibility
			fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

			fputcsv($file, ['SI NO.', 'Parent Name', 'Transaction Number', 'School Name', 'Date', 'Amount', 'Status']);

			$i = 1;
			foreach ($topup as $row) {
				$par = $data->firstWhere('id', $row->parent_id);
				$sch = $par ? $school->firstWhere('id', $par->school_id) : null;

				$statusText = 'Pending';
				if ($row->payment_status == 1) $statusText = 'Success';
				if ($row->payment_status == 2) $statusText = 'Failed';

				fputcsv($file, [
					$i++,
					$par->name ?? '-',
					$row->transaction_number ?? '-',
					$sch->school_name ?? '-',
					$row->created_at ? \Carbon\Carbon::parse($row->created_at)->format('d-M-Y h:i A') : '-',
					$row->amount,
					$statusText,
				]);
			}

			fputcsv($file, ['', 'Total No. of Records: ' . $topup->count(), '', '', '', 'Total Amount: ' . $topup->sum('amount') . ' QAR', '']);

			fclose($file);
		};

		return response()->stream($callback, 200, $headers);
	}
       


        public function topuplist_parentschangeStatus(Request $request)
        {
            $topup = ParentTopup_Model::findOrFail($request->id);
            $topup->transaction_number = $request->input('transaction_number');
            $topup->payment_status = $request->input('payment_status');
            $topup->save();

            return redirect()->route('admin.topuplist_parents')->with('success', 'Transaction updated successfully!');
        }



        ///////////////////////////////---Credit Transfer---////////////////////////////

      /* public function credit_transfer()
        {
            $school = School_Model::get();
            $parent = SchoolParent_Model::get();

            $data = SchoolStudent_Model::whereNotNull('wallet_balance')
                ->orderBy('updated_at', 'desc')
                ->orderBy('wallet_balance', 'desc')
                ->get();

            return view('admin.credit_transfer.index', compact('school', 'data', 'parent'));
        } */
		
		public function credit_transfer(Request $request)
{
    $school = School_Model::get();
    $parent = SchoolParent_Model::get();

    $query = SchoolStudent_Model::whereNotNull('wallet_balance')
        ->orderBy('updated_at', 'desc')
        ->orderBy('wallet_balance', 'desc');

    if ($request->filled('from_date')) {
        $query->whereDate('updated_at', '>=', \Carbon\Carbon::parse($request->from_date));
    }
    if ($request->filled('to_date')) {
        $query->whereDate('updated_at', '<=', \Carbon\Carbon::parse($request->to_date));
    }
    if ($request->filled('student_name')) {
        $query->where('student_name', 'like', '%' . $request->student_name . '%');
    }
    if ($request->filled('parent_name')) {
        $parentIds = SchoolParent_Model::where('name', 'like', '%' . $request->parent_name . '%')->pluck('id');
        $query->whereIn('parent_id', $parentIds);
    }
    if ($request->filled('status')) {
        $query->where('wallet_payment_status', $request->status);
    }
    if ($request->filled('transaction_type')) {
        $query->where('transaction_type', $request->transaction_type);
    }

    $data = $query->paginate(10);

    return view('admin.credit_transfer.index', compact('school', 'data', 'parent'));
}



}
