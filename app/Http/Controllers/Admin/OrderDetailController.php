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

use App\Models\Cafeteria\Cafeteria_Model;
use App\Models\ManageCafe\Dish_Model;
use App\Models\PreOrder_Model;

use App\Models\User;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;


use Illuminate\Support\Facades\Mail;
use App\Mail\SchoolUserInviteMail; 
use App\Mail\ParentInviteMail; 
use App\Http\Controllers\Admin\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class OrderDetailController extends Controller
{
        public function __construct()
    {
        $this->middleware('auth');
        
    }

/*     public function pre_orders()
    {
        // echo "test"; die;
        $data = PreOrder_Model::get();
        $dish = Dish_Model::get();
        $student = SchoolStudent_Model::get();
        $school = School_Model::get();
        $cafeteria = Cafeteria_Model::get();
        return view('admin.onsite.index', compact('data','dish','student','school','cafeteria'));
    } */
	
	
	public function pre_orders(Request $request)
	{
		$dish      = Dish_Model::get();
		$student   = SchoolStudent_Model::get();
		$school    = School_Model::get();
		$cafeteria = Cafeteria_Model::get();

		$query = PreOrder_Model::orderBy('id', 'desc');

		if ($request->filled('from_date')) {
			try { $query->whereDate('date', '>=', \Carbon\Carbon::parse($request->from_date)); } catch(\Exception $e){}
		}
		if ($request->filled('to_date')) {
			try { $query->whereDate('date', '<=', \Carbon\Carbon::parse($request->to_date)); } catch(\Exception $e){}
		}
		if ($request->filled('parent_number')) {
			$parentIds  = SchoolParent_Model::where('mobile', 'like', '%' . $request->parent_number . '%')->pluck('id');
			$studentIds = SchoolStudent_Model::whereIn('parent_id', $parentIds)->pluck('id');
			$query->whereIn('student_id', $studentIds);
		}
		if ($request->filled('student_name')) {
			$studentIds = SchoolStudent_Model::where('student_name', 'like', '%' . $request->student_name . '%')->pluck('id');
			$query->whereIn('student_id', $studentIds);
		}
		if ($request->filled('school_id')) {
			$query->where('school_id', $request->school_id);
		}
		if ($request->filled('cafeteria_id')) {
			$query->where('cafeteria_id', $request->cafeteria_id);
		}
		if ($request->filled('status')) {
			$query->where('payment_status', $request->status);
		}

		// ── Handle exports ──────────────────────────────────────
		if ($request->filled('export')) {
			$rows = $query->get();

			if ($request->export === 'pdf') {
				return view('admin.pre_orders.export_pdf',
					compact('rows', 'dish', 'student', 'school', 'cafeteria'));
			}

			if ($request->export === 'excel') {
				$filename = 'PreOrders-' . date('d-M-Y') . '.csv';
				$headers  = [
					'Content-Type'        => 'text/csv',
					'Content-Disposition' => 'attachment; filename="' . $filename . '"',
					'Pragma'              => 'no-cache',
					'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
					'Expires'             => '0',
				];
				$callback = function () use ($rows, $dish, $student, $school, $cafeteria) {
					$file = fopen('php://output', 'w');
					fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
					fputcsv($file, ['ID','Invoice Number','Date','Dish Name','Student Name','School','Cafeteria','Payment Mode','Payment Status','Credits','Card','Addons']);
					$i = 1;
					foreach ($rows as $row) {
						$di  = $dish->firstWhere('id', $row->dish_id);
						$stu = $student->firstWhere('id', $row->student_id);
						$sch = $school->firstWhere('id', $row->school_id);
						$caf = $cafeteria->firstWhere('id', $row->cafeteria_id)
							?? $cafeteria->firstWhere('school_id', $row->school_id);
						fputcsv($file, [
							$i++,
							$row->transaction_no ?? '--',
							$row->date,
							$di->dish_name ?? '-',
							$stu->student_name ?? '-',
							$sch->school_name ?? '-',
							$caf->cafeteria_name ?? '-',
							$row->payment_type ?? '--',
							$row->payment_status == 1 ? 'Yes' : 'No',
							$row->total_price,
							$row->pos_type ?? '--',
							$row->addons ?? '-',
						]);
					}
					fclose($file);
				};
				return response()->stream($callback, 200, $headers);
			}
		}

		$data = $query->paginate(10);

		return view('admin.pre_orders.index', compact('data', 'dish', 'student', 'school', 'cafeteria'));
	}


	public function pre_orders_invalid($id)
	{
		$order = PreOrder_Model::find($id);

		if (!$order) {
			return redirect()->back()->with('error', 'Pre-order not found.');
		}

		$order->payment_status = 2; // 2 = Invalid/Failed
		$order->save();

		return redirect()->back()->with('success', 'Pre-order marked as invalid successfully.');
	}

	public function pre_orders_refund($id)
	{
		$order = PreOrder_Model::find($id);

		if (!$order) {
			return redirect()->back()->with('error', 'Pre-order not found.');
		}

		DB::beginTransaction();
		try {
			$order->payment_status = 3; // 3 = Refunded
			$order->save();

			// Refund the amount back to student's wallet balance
			if ($order->total_price > 0 && $order->student_id) {
				DB::table('tbl_school_student')
					->where('id', $order->student_id)
					->increment('wallet_balance', $order->total_price);
			}

			DB::commit();
			return redirect()->back()->with('success', 'Pre-order refunded successfully.');
		} catch (\Exception $e) {
			DB::rollBack();
			\Log::error('Pre-order refund failed: ' . $e->getMessage());
			return redirect()->back()->with('error', 'Refund failed. Please try again.');
		}
	}
	
	

        public function statistics()
        {
            // echo "test"; die;
            $data = PreOrder_Model::get();
            $dish = Dish_Model::get();
            $student = SchoolStudent_Model::get();
            $school = School_Model::get();
            $cafeteria = Cafeteria_Model::get();
            return view('admin.statistics.index', compact('data','dish','student','school','cafeteria'));
        }

            public function sadaq_payment()
        {
            // echo "test"; die;
            $data = PreOrder_Model::get();
            $dish = Dish_Model::get();
            $student = SchoolStudent_Model::get();
            $school = School_Model::get();
            $cafeteria = Cafeteria_Model::get();
            return view('admin.sadaq_payment.index', compact('data','dish','student','school','cafeteria'));
        }

 

            public function school_update(Request $request, $id)
        {
            $school = School_Model::findOrFail($id);
            $school->school_name = $request->input('schoolname');
            $school->address = $request->input('address');
            $school->save();

            return redirect()->route('admin.school')->with('success', 'School updated successfully!');
        }

       public function pre_orders_chnagestatus(Request $request)
        {
            $request->validate([
                'status' => 'required|in:1,2',
                'transaction' => 'required|string|max:255',
            ]);

            $data = PreOrder_Model::where('transaction_no', $request->transaction)->first();

            if (!$data) {
                return redirect()->back()->with('error', '❌ Transaction number not found.');
            }

            $data->payment_status = $request->status;
            $data->save();

            return redirect()->back()->with('success', '✅ Transaction updated successfully.');
        }



        ////////////////////////////////--ONSITE SALES--///////////////////////////////

         /* public function onsitesales()
    {
        $data = PreOrder_Model::get();
        $dish = Dish_Model::get();
        $student = SchoolStudent_Model::get();
        $school = School_Model::get();
        $cafeteria = Cafeteria_Model::get();
        return view('admin.onsite.index', compact('data','dish','student','school','cafeteria'));
    } */
	
	public function onsitesales(Request $request)
{
    $student   = SchoolStudent_Model::get();
    $school    = School_Model::get();
    $cafeteria = Cafeteria_Model::get();
    $dish      = Dish_Model::get();

    $query = DB::table('tbl_orders as o')
        ->leftJoin('tbl_order_items as oi', 'oi.order_id', '=', 'o.id')
        ->orderBy('o.id', 'desc')
        ->select('o.*', 'oi.dish_id', 'oi.qty', 'oi.dish_price');

    if ($request->filled('from_date')) {
        try { $query->whereDate('o.date', '>=', \Carbon\Carbon::parse($request->from_date)); } catch(\Exception $e){}
    }
    if ($request->filled('to_date')) {
        try { $query->whereDate('o.date', '<=', \Carbon\Carbon::parse($request->to_date)); } catch(\Exception $e){}
    }
    if ($request->filled('invoice_number')) {
        $query->where('o.transaction_no', 'like', '%' . $request->invoice_number . '%');
    }
    if ($request->filled('school_id')) {
        $query->where('o.school_id', $request->school_id);
    }
    if ($request->filled('cafeteria_id')) {
        $query->where('o.cafeteria_id', $request->cafeteria_id);
    }
    if ($request->filled('payment_mode')) {
        $query->where('o.payment_type', $request->payment_mode);
    }
    if ($request->filled('student_name')) {
        $studentIds = SchoolStudent_Model::where('student_name', 'like', '%' . $request->student_name . '%')->pluck('id');
        $query->whereIn('o.student_id', $studentIds);
    }
    if ($request->filled('parent_number')) {
        $parentIds  = SchoolParent_Model::where('mobile', 'like', '%' . $request->parent_number . '%')->pluck('id');
        $studentIds = SchoolStudent_Model::whereIn('parent_id', $parentIds)->pluck('id');
        $query->whereIn('o.student_id', $studentIds);
    }

    // Handle exports
    if ($request->filled('export')) {
        $rows = $query->get();

        if ($request->export === 'pdf') {
            return view('admin.onsite.export_pdf',
                compact('rows', 'dish', 'student', 'school', 'cafeteria'));
        }

        if ($request->export === 'excel') {
            $filename = 'OnsiteSales-' . date('d-M-Y') . '.csv';
            $headers  = [
                'Content-Type'        => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                'Pragma'              => 'no-cache',
                'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
                'Expires'             => '0',
            ];
            $callback = function () use ($rows, $dish, $student, $school, $cafeteria) {
                $file = fopen('php://output', 'w');
                fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
                fputcsv($file, ['Invoice Number','Date','Dish Name','Student Name','School','Cafeteria','Payment Mode','Payment Status','Credits (QAR)','Card']);
                foreach ($rows as $row) {
                    $di  = $dish->firstWhere('id', $row->dish_id);
                    $stu = $student->firstWhere('id', $row->student_id);
                    $sch = $school->firstWhere('id', $row->school_id);
                    $caf = $cafeteria->firstWhere('id', $row->cafeteria_id);
                    fputcsv($file, [
                        $row->transaction_no ?? '--',
                        $row->date,
                        $di->dish_name    ?? '-',
                        $stu->student_name ?? '-',
                        $sch->school_name  ?? '-',
                        $caf->cafeteria_name ?? '-',
                        $row->payment_type ?? '--',
                        $row->payment_status == 1 ? 'Success' : 'Pending',
                        $row->grand_total,
                        $row->creditcard ? 'Yes' : 'No',
                    ]);
                }
                fclose($file);
            };
            return response()->stream($callback, 200, $headers);
        }
    }

    $data = $query->paginate(10);

    return view('admin.onsite.index', compact('data', 'dish', 'student', 'school', 'cafeteria'));
}


public function onsitesales_view($id)
{
    $order = DB::table('tbl_orders')->where('id', $id)->first();

    if (!$order) {
        abort(404, 'Order not found.');
    }

    $items = DB::table('tbl_order_items')
        ->leftJoin('tbl_dish', 'tbl_dish.id', '=', 'tbl_order_items.dish_id')
        ->where('tbl_order_items.order_id', $id)
        ->select(
            'tbl_dish.dish_name',
            'tbl_order_items.dish_price',
            'tbl_order_items.qty',
            'tbl_order_items.total_price'
        )
        ->get();

    $student = SchoolStudent_Model::find($order->student_id);

    return view('admin.onsite.view_details', compact('order', 'items', 'student'));
}


public function onsitesales_invalid($id)
{
    $order = DB::table('tbl_orders')->where('id', $id)->first();

    if (!$order) {
        return redirect()->back()->with('error', 'Order not found.');
    }

    DB::table('tbl_orders')->where('id', $id)->update([
        'payment_status' => 2, // 2 = Failed/Invalid
        'updated_at'     => now(),
    ]);

    return redirect()->back()->with('success', 'Order marked as invalid successfully.');
}

public function onsitesales_refund($id)
{
    $order = DB::table('tbl_orders')->where('id', $id)->first();

    if (!$order) {
        return redirect()->back()->with('error', 'Order not found.');
    }

    DB::beginTransaction();
    try {
        // Mark order as refunded
        DB::table('tbl_orders')->where('id', $id)->update([
            'payment_status' => 3, // 3 = Refunded
            'updated_at'     => now(),
        ]);

        // Refund wallet_used back to student's wallet balance
        if ($order->wallet_used > 0 && $order->student_id) {
            DB::table('tbl_school_student')
                ->where('id', $order->student_id)
                ->increment('wallet_balance', $order->wallet_used);
        }

        DB::commit();
        return redirect()->back()->with('success', 'Order refunded successfully.');
    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('Refund failed: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Refund failed. Please try again.');
    }
}



/* public function onsitesales_excel(Request $request)
{
    $student   = SchoolStudent_Model::get();
    $school    = School_Model::get();
    $cafeteria = Cafeteria_Model::get();
    $dish      = Dish_Model::get();

    $query = DB::table('tbl_orders as o')
        ->leftJoin('tbl_order_items as oi', 'oi.order_id', '=', 'o.id')
        ->orderBy('o.id', 'desc')
        ->select('o.*', 'oi.dish_id', 'oi.qty', 'oi.dish_price');

    if ($request->filled('from_date')) {
        try { $query->whereDate('o.date', '>=', \Carbon\Carbon::parse($request->from_date)); } catch(\Exception $e){}
    }
    if ($request->filled('to_date')) {
        try { $query->whereDate('o.date', '<=', \Carbon\Carbon::parse($request->to_date)); } catch(\Exception $e){}
    }
    if ($request->filled('invoice_number')) {
        $query->where('o.transaction_no', 'like', '%' . $request->invoice_number . '%');
    }
    if ($request->filled('school_id')) {
        $query->where('o.school_id', $request->school_id);
    }
    if ($request->filled('cafeteria_id')) {
        $query->where('o.cafeteria_id', $request->cafeteria_id);
    }
    if ($request->filled('payment_mode')) {
        $query->where('o.payment_type', $request->payment_mode);
    }
    if ($request->filled('student_name')) {
        $studentIds = SchoolStudent_Model::where('student_name', 'like', '%' . $request->student_name . '%')->pluck('id');
        $query->whereIn('o.student_id', $studentIds);
    }
    if ($request->filled('parent_number')) {
        $parentIds  = SchoolParent_Model::where('mobile', 'like', '%' . $request->parent_number . '%')->pluck('id');
        $studentIds = SchoolStudent_Model::whereIn('parent_id', $parentIds)->pluck('id');
        $query->whereIn('o.student_id', $studentIds);
    }

    $rows = $query->get();

    // Build item summary
    $itemSummary = [];
    foreach ($rows as $row) {
        $di       = $dish->firstWhere('id', $row->dish_id);
        $dishName = $di->dish_name ?? '-';
        $qty      = $row->qty ?? 1;
        $itemSummary[$dishName] = ($itemSummary[$dishName] ?? 0) + $qty;
    }
    arsort($itemSummary);

    $cafeFilter = $request->filled('cafeteria_id')
        ? ($cafeteria->firstWhere('id', $request->cafeteria_id)->cafeteria_name ?? 'All Cafeterias')
        : 'All Cafeterias';

    $fromDate = $request->from_date ?? '';
    $toDate   = $request->to_date ?? '';

    $spreadsheet = new Spreadsheet();

    // ==================== SHEET 1: Onsite Data ====================
    $sheet1 = $spreadsheet->getActiveSheet();
    $sheet1->setTitle('Onsite Data');

    $sheet1->mergeCells('A1:M1');
    $sheet1->setCellValue('A1', $cafeFilter);
    $sheet1->getStyle('A1')->applyFromArray([
        'font'      => ['bold' => true, 'size' => 13, 'color' => ['rgb' => 'FFFFFF']],
        'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '2E2E7A']],
        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
    ]);

    $sheet1->mergeCells('A2:M2');
    $sheet1->setCellValue('A2', 'ONSITE ORDER DETAILS');
    $sheet1->getStyle('A2')->applyFromArray([
        'font'      => ['bold' => true, 'size' => 12, 'color' => ['rgb' => 'FFFFFF']],
        'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '2E2E7A']],
        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
    ]);

    $sheet1->mergeCells('A3:M3');
    $sheet1->setCellValue('A3', 'Booking Date Between ' . $fromDate . ' And ' . $toDate);
    $sheet1->getStyle('A3')->applyFromArray([
        'font'      => ['bold' => true],
        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
    ]);

    $colHeaders = [
        'A' => 'S.NO',     'B' => 'Booking Number', 'C' => 'Invoice Date',
        'D' => 'Invoice Time', 'E' => 'Admission Number', 'F' => 'Student Name',
        'G' => 'Class',    'H' => 'Item Names',      'I' => 'Add Ons',
        'J' => 'Quantity', 'K' => 'Payment Mode',    'L' => 'Amount',
        'M' => 'Smart Pay Card',
    ];
    foreach ($colHeaders as $col => $label) {
        $sheet1->setCellValue($col . '5', $label);
    }
    $sheet1->getStyle('A5:M5')->applyFromArray([
        'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
        'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '2E2E7A']],
        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
    ]);

    $rowNum = 6;
    $srNo   = 1;
    foreach ($rows as $row) {
        $di  = $dish->firstWhere('id', $row->dish_id);
        $stu = $student->firstWhere('id', $row->student_id);

        try {
            $dateObj = \Carbon\Carbon::parse($row->date);
            $dateStr = $dateObj->format('d-M-Y');
            $timeStr = $dateObj->format('h:i A');
        } catch (\Exception $e) {
            $dateStr = $row->date ?? '-';
            $timeStr = '-';
        }

        $sheet1->setCellValue('A' . $rowNum, $srNo++);
        $sheet1->setCellValue('B' . $rowNum, $row->transaction_no ?? '--');
        $sheet1->setCellValue('C' . $rowNum, $dateStr);
        $sheet1->setCellValue('D' . $rowNum, $timeStr);
        $sheet1->setCellValue('E' . $rowNum, $stu->admission_no ?? '-');
        $sheet1->setCellValue('F' . $rowNum, $stu->student_name ?? '-');
        $sheet1->setCellValue('G' . $rowNum, $stu->grade ?? '-');
        $sheet1->setCellValue('H' . $rowNum, $di->dish_name ?? '-');
        $sheet1->setCellValue('I' . $rowNum, $row->addons ?? '-');
        $sheet1->setCellValue('J' . $rowNum, $row->qty ?? 1);
        $sheet1->setCellValue('K' . $rowNum, $row->payment_type ?? '--');
        $sheet1->setCellValue('L' . $rowNum, $row->grand_total);
        $sheet1->setCellValue('M' . $rowNum, $row->creditcard ? 'Yes' : 'No');

        if ($rowNum % 2 == 0) {
            $sheet1->getStyle('A' . $rowNum . ':M' . $rowNum)->applyFromArray([
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F5F5F5']],
            ]);
        }
        $sheet1->getStyle('A' . $rowNum . ':M' . $rowNum)->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'DDDDDD']]],
        ]);

        $rowNum++;
    }

    foreach (range('A', 'M') as $col) {
        $sheet1->getColumnDimension($col)->setAutoSize(true);
    }

    // ==================== SHEET 2: Summary ====================
    $sheet2 = $spreadsheet->createSheet();
    $sheet2->setTitle('Summary');

    $sheet2->mergeCells('A1:C1');
    $sheet2->setCellValue('A1', 'ITEM WISE SUMMARY');
    $sheet2->getStyle('A1')->applyFromArray([
        'font'      => ['bold' => true, 'size' => 13, 'color' => ['rgb' => 'FFFFFF']],
        'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '2E2E7A']],
        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
    ]);

    $sheet2->setCellValue('A2', 'S.NO');
    $sheet2->setCellValue('B2', 'Item Name');
    $sheet2->setCellValue('C2', 'Quantity');
    $sheet2->getStyle('A2:C2')->applyFromArray([
        'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
        'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '2E2E7A']],
        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
    ]);

    $summaryRow = 3;
    $sNo = 1;
    foreach ($itemSummary as $itemName => $qty) {
        $sheet2->setCellValue('A' . $summaryRow, $sNo++);
        $sheet2->setCellValue('B' . $summaryRow, $itemName);
        $sheet2->setCellValue('C' . $summaryRow, $qty);
        if ($summaryRow % 2 == 0) {
            $sheet2->getStyle('A' . $summaryRow . ':C' . $summaryRow)->applyFromArray([
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F5F5F5']],
            ]);
        }
        $sheet2->getStyle('A' . $summaryRow . ':C' . $summaryRow)->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'DDDDDD']]],
        ]);
        $summaryRow++;
    }

    $sheet2->setCellValue('A' . $summaryRow, 'Total');
    $sheet2->setCellValue('C' . $summaryRow, array_sum($itemSummary));
    $sheet2->getStyle('A' . $summaryRow . ':C' . $summaryRow)->applyFromArray([
        'font'    => ['bold' => true],
        'fill'    => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E8E8F0']],
        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
    ]);

    foreach (['A', 'B', 'C'] as $col) {
        $sheet2->getColumnDimension($col)->setAutoSize(true);
    }

    // ==================== Output ====================
    /* $spreadsheet->setActiveSheetIndex(0);
    $filename = 'OnsiteSales-' . date('d-M-Y') . '.xlsx';
    $writer   = new Xlsx($spreadsheet);

    return response()->streamDownload(function () use ($writer) {
        $writer->save('php://output');
    }, $filename, [
        'Content-Type'  => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'Cache-Control' => 'max-age=0',
        'Pragma'        => 'public',
    ]); */
	
	
	/* $spreadsheet->setActiveSheetIndex(0);
	$filename = 'OnsiteSales-' . date('d-M-Y') . '.xlsx';
	$writer   = new Xlsx($spreadsheet);

	$tempFile = tempnam(sys_get_temp_dir(), 'xlsx');
	$writer->save($tempFile);

	return response()->download($tempFile, $filename, [
		'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
	])->deleteFileAfterSend(true); * /
	
} */


public function onsitesales_csv(Request $request)
{
    $student   = SchoolStudent_Model::get();
    $school    = School_Model::get();
    $cafeteria = Cafeteria_Model::get();
    $dish      = Dish_Model::get();

    $query = DB::table('tbl_orders as o')
        ->leftJoin('tbl_order_items as oi', 'oi.order_id', '=', 'o.id')
        ->orderBy('o.id', 'desc')
        ->select('o.*', 'oi.dish_id', 'oi.qty', 'oi.dish_price');

    if ($request->filled('from_date')) {
        try { $query->whereDate('o.date', '>=', \Carbon\Carbon::parse($request->from_date)); } catch(\Exception $e){}
    }
    if ($request->filled('to_date')) {
        try { $query->whereDate('o.date', '<=', \Carbon\Carbon::parse($request->to_date)); } catch(\Exception $e){}
    }
    if ($request->filled('invoice_number')) {
        $query->where('o.transaction_no', 'like', '%' . $request->invoice_number . '%');
    }
    if ($request->filled('school_id')) {
        $query->where('o.school_id', $request->school_id);
    }
    if ($request->filled('cafeteria_id')) {
        $query->where('o.cafeteria_id', $request->cafeteria_id);
    }
    if ($request->filled('payment_mode')) {
        $query->where('o.payment_type', $request->payment_mode);
    }
    if ($request->filled('student_name')) {
        $studentIds = SchoolStudent_Model::where('student_name', 'like', '%' . $request->student_name . '%')->pluck('id');
        $query->whereIn('o.student_id', $studentIds);
    }
    if ($request->filled('parent_number')) {
        $parentIds  = SchoolParent_Model::where('mobile', 'like', '%' . $request->parent_number . '%')->pluck('id');
        $studentIds = SchoolStudent_Model::whereIn('parent_id', $parentIds)->pluck('id');
        $query->whereIn('o.student_id', $studentIds);
    }

    $rows = $query->get();

    // Build item summary
    $itemSummary = [];
    foreach ($rows as $row) {
        $di       = $dish->firstWhere('id', $row->dish_id);
        $dishName = $di->dish_name ?? '-';
        $qty      = $row->qty ?? 1;
        $itemSummary[$dishName] = ($itemSummary[$dishName] ?? 0) + $qty;
    }
    arsort($itemSummary);

    $cafeFilter = $request->filled('cafeteria_id')
        ? ($cafeteria->firstWhere('id', $request->cafeteria_id)->cafeteria_name ?? 'All Cafeterias')
        : 'All Cafeterias';

    $filename = 'OnsiteSales-' . date('d-M-Y') . '.csv';

    $headers = [
        'Content-Type'        => 'text/csv; charset=UTF-8',
        'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        'Pragma'              => 'no-cache',
        'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
        'Expires'             => '0',
    ];

    $callback = function () use ($rows, $dish, $student, $cafeteria, $itemSummary, $cafeFilter, $request) {
        $file = fopen('php://output', 'w');
        fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF)); // UTF-8 BOM

        // ── Section 1: Onsite Data ──────────────────────────
        fputcsv($file, [$cafeFilter]);
        fputcsv($file, ['ONSITE ORDER DETAILS']);
        fputcsv($file, ['Booking Date Between ' . ($request->from_date ?? '') . ' And ' . ($request->to_date ?? '')]);
        fputcsv($file, []);

        fputcsv($file, [
            'S.NO', 'Booking Number', 'Invoice Date', 'Invoice Time',
            'Admission Number', 'Student Name', 'Class', 'Item Names',
            'Add Ons', 'Quantity', 'Payment Mode', 'Amount', 'Smart Pay Card',
        ]);

        $srNo = 1;
        foreach ($rows as $row) {
            $di  = $dish->firstWhere('id', $row->dish_id);
            $stu = $student->firstWhere('id', $row->student_id);

            try {
                $dateObj = \Carbon\Carbon::parse($row->date);
                $dateStr = $dateObj->format('d-M-Y');
                $timeStr = $dateObj->format('h:i A');
            } catch (\Exception $e) {
                $dateStr = $row->date ?? '-';
                $timeStr = '-';
            }

            fputcsv($file, [
                $srNo++,
                $row->transaction_no ?? '--',
                $dateStr,
                $timeStr,
                $stu->admission_no  ?? '-',
                $stu->student_name  ?? '-',
                $stu->grade         ?? '-',
                $di->dish_name      ?? '-',
                $row->addons        ?? '-',
                $row->qty           ?? 1,
                $row->payment_type  ?? '--',
                $row->grand_total,
                $row->creditcard ? 'Yes' : 'No',
            ]);
        }

        // ── Section 2: Item Wise Summary ────────────────────
        fputcsv($file, []);
        fputcsv($file, []);
        fputcsv($file, ['ITEM WISE SUMMARY']);
        fputcsv($file, ['S.NO', 'Item Name', 'Quantity']);

        $sNo = 1;
        foreach ($itemSummary as $itemName => $qty) {
            fputcsv($file, [$sNo++, $itemName, $qty]);
        }
        fputcsv($file, ['Total', '', array_sum($itemSummary)]);

        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}


    /* public function dish_sales()
    {
        $sales = PreOrder_Model::with('dish')
            ->select('dish_id',
                DB::raw('SUM(qty) as total_qty'),
                DB::raw('SUM(total_price) as total_amount')
            )
            ->groupBy('dish_id')
            ->get();

        // Sort for highest & lowest
        $highestSales = $sales->sortByDesc('total_qty')->take(10);
        $lowestSales = $sales->sortBy('total_qty')->take(10); 

        $soldDishIds = $sales->pluck('dish_id')->toArray();
        $noSales = Dish_Model::whereNotIn('id', $soldDishIds)->get();

        return view('admin.dish_sales.index', compact('highestSales', 'lowestSales', 'noSales'));
    } */
	
	
	public function dish_sales(Request $request)
{
    $orderType = $request->order_type; // 'pre_order', 'onsite', or empty (both)

    $fromDate = $request->filled('fdate') ? \Carbon\Carbon::parse($request->fdate)->format('Y-m-d') : null;
    $toDate   = $request->filled('tdate')   ? \Carbon\Carbon::parse($request->tdate)->format('Y-m-d')   : null;

    $preOrderSales = collect();
    $onsiteSales   = collect();

    // ── Pre Orders ──────────────────────────────────────────
    if ($orderType == '' || $orderType == 'pre_order') {
        $q = PreOrder_Model::with('dish')
            ->select('dish_id',
                DB::raw('SUM(qty) as total_qty'),
                DB::raw('SUM(total_price) as total_amount')
            )
            ->groupBy('dish_id');

        if ($fromDate)              $q->whereDate('created_at', '>=', $fromDate);
        if ($toDate)                $q->whereDate('created_at', '<=', $toDate);
        if ($request->school_id)    $q->where('school_id', $request->school_id);
        if ($request->cafeteria_id) $q->where('cafeteria_id', $request->cafeteria_id);

        $preOrderSales = $q->get();
    }

    // ── Onsite Orders ────────────────────────────────────────
    if ($orderType == '' || $orderType == 'onsite') {
        $q = DB::table('tbl_order_items')
            ->join('tbl_orders', 'tbl_orders.id', '=', 'tbl_order_items.order_id')
            ->select('tbl_order_items.dish_id',
                DB::raw('SUM(tbl_order_items.qty) as total_qty'),
                DB::raw('SUM(tbl_order_items.total_price) as total_amount')
            )
            ->groupBy('tbl_order_items.dish_id');

        if ($fromDate)               $q->whereDate('tbl_orders.created_at', '>=', $fromDate);
        if ($toDate)                 $q->whereDate('tbl_orders.created_at', '<=', $toDate);
        if ($request->school_id)     $q->where('tbl_orders.school_id', $request->school_id);
        if ($request->cafeteria_id)  $q->where('tbl_orders.cafeteria_id', $request->cafeteria_id);

        $onsiteSales = $q->get();
    }

    // ── Merge both ───────────────────────────────────────────
    $merged = collect();

    foreach ($preOrderSales as $item) {
        $merged->push(['dish_id' => $item->dish_id, 'total_qty' => $item->total_qty, 'total_amount' => $item->total_amount]);
    }
    foreach ($onsiteSales as $item) {
        $merged->push(['dish_id' => $item->dish_id, 'total_qty' => $item->total_qty, 'total_amount' => $item->total_amount]);
    }

    // Group by dish_id and sum
    $sales = $merged->groupBy('dish_id')->map(function($rows, $dishId) {
        return [
            'dish_id'      => $dishId,
            'total_qty'    => collect($rows)->sum('total_qty'),
            'total_amount' => collect($rows)->sum('total_amount'),
        ];
    })->values()->map(function($row) {
        $obj = (object) $row;
        $obj->dish = Dish_Model::find($row['dish_id']);
        return $obj;
    });

    $highestSales = $sales->sortByDesc('total_qty')->take(10);
    $lowestSales  = $sales->sortBy('total_qty')->take(10);
    $soldDishIds  = $sales->pluck('dish_id')->toArray();
    $noSales      = Dish_Model::whereNotIn('id', $soldDishIds)->get();

    $schools    = School_Model::all();
    $cafeterias = Cafeteria_Model::all();

    return view('admin.dish_sales.index', compact(
        'highestSales', 'lowestSales', 'noSales', 'schools', 'cafeterias'
    ));
}
	


    public function cafe_pos_report(Request $request)
    {
        $fromDate = $request->from_date 
            ? Carbon::parse($request->from_date)->startOfDay() 
            : Carbon::now()->subDays(7);

        $toDate = $request->to_date 
            ? Carbon::parse($request->to_date)->endOfDay() 
            : Carbon::now();

        $sales = DB::table('tbl_preorders as p')
            ->join('tbl_cafeteria as c', 'p.cafeteria_id', '=', 'c.id')
            ->leftJoin('users as u', 'p.cafeteria_id', '=', 'u.cafeteria_id')
            ->select(
                'c.cafeteria_name',
                'u.name as cafe_user',
                DB::raw('SUM(p.total_price) as total_sale')
            )
            ->whereBetween('p.created_at', [$fromDate, $toDate])
            ->groupBy('c.id', 'c.cafeteria_name', 'u.name')
            ->orderByDesc('total_sale')
            ->get();

        return view('admin.cafe_pos.index', compact('sales', 'fromDate', 'toDate'));
    }

    // public function brand_card_sales()
    // {
    //         $data = PreOrder_Model::get();
    //         $dish = Dish_Model::get();
    //         $student = SchoolStudent_Model::get();
    //         $school = School_Model::get();
    //         $cafeteria = Cafeteria_Model::get();
    //         return view('admin.onsite.index', compact('data','dish','student','school','cafeteria'));

    // }


public function brand_card_sales(Request $request)
{

    $fromDate = $request->from_date
        ? Carbon::parse($request->from_date)->startOfDay()
        : null;
    $toDate = $request->to_date
        ? Carbon::parse($request->to_date)->endOfDay()
        : null;

  $sales = DB::table('tbl_preorders')
    ->select(
        'id',
        'cafeteria_id',
        'dish_id',
        'student_id',
        'school_id',
        'date',
        'qty',
        'dish_price',
        'total_price',
        'transaction_no',
        'discount',
        'payment_type',
        'created_at'
    )
    ->when($fromDate && $toDate, function ($query) use ($fromDate, $toDate) {
        $query->whereBetween('tbl_preorders.created_at', [$fromDate, $toDate]);
    })
    ->orderBy('created_at', 'desc')
    ->get();




    foreach ($sales as $sale) {
        $cafeteria = DB::table('tbl_cafeteria')
            ->where('id', $sale->cafeteria_id)
            ->first();

        $sale->cafeteria_name = $cafeteria->cafeteria_name ?? '-';

        $user = DB::table('users')
            ->where('cafeteria_id', $cafeteria->id ?? 0)
            ->first();

        $sale->user_name = $user->name ?? '-';
        $sale->payment_status = $sale->payment_status ?? null;

         $stud = DB::table('tbl_school_student')
            ->where('id', $sale->student_id)
            ->first();

            $sale->stud_name = $stud->student_name;

         $dishes = DB::table('tbl_dish')
            ->where('id', $sale->dish_id)
            ->first();
    
         $sale->item_name = $dishes->dish_name;

         $sch = DB::table('tbl_school')
            ->where('id', $sale->school_id)
            ->first();
        
         $sale->school_name = $sch->school_name;



        // echo "<pre>"; print_r($sale);
    }

    //  die;


    return view('admin.brand_card_sale.index', compact('sales', 'fromDate', 'toDate'));
}

     
}
