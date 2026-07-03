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


use App\Models\Cafeteria\Cafeteria_Model;
use App\Models\Cafeteria\CafeteriaUser_Model;

use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Mail\SchoolUserInviteMail; 
use Illuminate\Support\Facades\Auth;
use App\Models\PreOrder_Model;

class CafeteriaDashboardController extends Controller
{
     public function __construct(){
        $this->middleware('auth');
    }

   
		public function cafeteria_students()
		{
			$cafeteriaadmin = Auth::user()->cafeteria_id;		

			$cafeteria = Cafeteria_Model::where('id',$cafeteriaadmin)->first();   // Cafeteria		

			$school = School_Model::where('id', $cafeteria->school_id)->first();   // school


			$parent = SchoolParent_Model::get(); // parent

			$student = SchoolStudent_Model::where('school_id', $school->id )->get();  //Student

			return view('admin.cafeteriaadmin.students.index', compact('school','parent','student'));
		}

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
			

			$categories = DishCategory_Model::where('cafeteria_id', $cafeteriaadmin)->get();
			$firstCategory = $categories->first();

			$items = Dish_Model::with('category')
				->where('cafeteria_id', $cafeteriaadmin)
				->get();
				
			$cafeteria = Cafeteria_Model::where('id',$cafeteriaadmin)->first();
			
			$student = SchoolStudent_Model::where('school_id',$school_id)->get();
			//$schoolid = $cafeteria['school_id'];
			
				

			return view('admin.cafeteriaadmin.onsite.index', compact('categories', 'items', 'firstCategory', 'student'));
		}


		public function cafeteria_pre_orders()
			{

					$userid = Auth::user()->id;
					$cafeteria_id = Auth::user()->cafeteria_id;
					$cafeteria = Cafeteria_Model::where('id',$cafeteria_id)->first(); 

					$preorder = PreOrder_Model::where('cafeteria_id',$cafeteria_id)->get();
					$dish = Dish_Model::get();
					$student = SchoolStudent_Model::get();
					$school = School_Model::get();
					$cafeteria = Cafeteria_Model::get();
					return view('admin.cafeteriaadmin.cafeteria_pre_orders.index', compact('preorder','dish','student','school','cafeteria'));
			}


	 public function cafeteria_cards()
		{

			$userid = Auth::user()->id;
			$school_id = Auth::user()->school_id;
		
			$school = School_Model::where('id',$school_id)->get();   // school
			$parent = SchoolParent_Model::get(); // parent
			$data = SchoolStudent_Model::where('school_id',$school_id)->get();  //student
			// echo "<pre>"; print_r($data); die;
			return view('admin.cafeteriaadmin.cards.index', compact('school','parent','data'));
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

	

            //////////////////////////////////////////////////////////////////////////////////
}
