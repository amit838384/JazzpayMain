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

use Illuminate\Support\Facades\Mail;
use App\Mail\SchoolUserInviteMail; 
use App\Mail\ParentInviteMail; 
use Illuminate\Support\Facades\Auth;

class SchoolDashboardController extends Controller
{
        public function __construct()
    {
        $this->middleware('auth');
        
    }

	public function invite_users(){		
		$id = Auth::user()->id; 
		$school_id = Auth::user()->school_id;
		$users = User::where('school_id', $school_id)->get();		
		$school = School_Model::where('id', $school_id)->first();
		return view('admin.schooladmin.invite_user.index', compact('school','users'));
	}



        public function invite_users_store(Request $request)
        {
            
            $request->validate([
                'school_id' => 'required',
                'email'     => 'required|string|email',
                'name'      => 'required|string',
            ]);

            $inviteCode = rand(111111, 999999);

            $user               = new User();
            $user->school_id    = $request->input('school_id');
            $user->email        = $request->input('email');
            $user->name         = $request->input('name');
            $user->role         = 'schooladmin';
            $user->invite_code  = $inviteCode;

            $user->password = Hash::make(Str::random(32));

            $user->save();

            Mail::to($user->email)->send(new SchoolUserInviteMail($user));

            return redirect()->route('admin.school_invite_users')
                            ->with('success', 'User invited successfully.');
        }


        
        ////////////////////////////////////////////
        ////////////--School Users--///////////////
        ////////////////////////////////////////////


        public function manage_users()
        {

             $id = Auth::user()->id; 
             $school_id = Auth::user()->school_id;  

            $data = User::where('school_id', $school_id)->get();
            $school = School_Model::where('id', $school_id)->first();
            
            return view('admin.schooladmin.schoolusers.index', compact('data','school'));
        }

 

        public function schooluserschangeStatus($id)
        {
            $school = User::findOrFail($id);
            $school->status = $school->status == 1 ? 0 : 1;
            $school->save();

            return redirect()->back()->with('success', 'Status updated successfully!');
        }

      
          ////////////////////////////////////////////
        ////////////--School Parents--///////////////
        ////////////////////////////////////////////

        public function parents()
        {

              $school_id = Auth::user()->school_id;  
              
                $school = School_Model::get();
                $data = SchoolParent_Model::where('school_id',$school_id)->where('view', 1)->get();

            return view('admin.schooladmin.schoolparents.index', compact('school','data'));
        }

       

            public function parents_store(Request $request): mixed
            {

                // echo "<pre>"; print_r($request->all()); die;
                $request->validate([
                    'school_id' => 'required',
                    'email' => 'required|string|email|max:200',
                    'mobile' => 'required|string|max:15',
                ]);

                $school = new SchoolParent_Model();
                $school->email = $request->input('email');
                $school->mobile = $request->input('mobile');
                $school->school_id = $request->input('school_id');

                $school->role = "Parent";
                $school->invite_code = rand(111111, 999999);
                $school->sent_date = now(); 
                $school->accepted_date = null;
                $school->view = 0;


                $school->save();
                Mail::to($school->email)->send(new ParentInviteMail($school));

                return redirect()->route('admin.school_parents')->with('success', 'Parent added successfully!');
            }


            public function parents_update(Request $request, $id)
        {
            $school = SchoolParent_Model::findOrFail($id);
            $school->school_name = $request->input('schoolname');
            $school->address = $request->input('address');
            $school->save();

            return redirect()->route('admin.parents')->with('success', 'School updated successfully!');
        }

        public function parentschangeStatus($id)
        {
            $school = SchoolParent_Model::findOrFail($id);
            $school->status = $school->status == 1 ? 0 : 1;
            $school->save();

            return redirect()->back()->with('success', 'Status updated successfully!');
        }


        ////////////////////////////////////////////
        ////////////--School Students--///////////////
        ////////////////////////////////////////////

        public function students()
        {
            $school_id = Auth::user()->school_id;  
            $school = School_Model::where('id', $school_id)->get();   // school

            $parent = SchoolParent_Model::get(); // parent


            $data = SchoolStudent_Model::where('school_id', $school_id )->get();  // student

            return view('admin.schooladmin.schoolstudents.index', compact('school','parent','data'));
        }

       

            public function students_store(Request $request)
            {
                
                // echo "<pre>"; print_r($request->all()); die;
                $request->validate([
                    'school_id' => 'required',
                    'parent_id' => 'required',
                    'student_name' => 'required|string|max:200',
                    'grade' => 'required|string|max:200',
                    'gender' => 'required|string|max:200',
                    'dob' => 'required|max:200',
                    'wallet_balance' => 'required|max:200',
                    'spend_limit' => 'required|max:200',
                    'admission_no' => 'required|max:200',


                ]);

                $school = new SchoolStudent_Model();
                $school->school_id = $request->input('school_id');
                $school->parent_id = $request->input('parent_id');
                $school->student_name = $request->input('student_name');
                $school->grade = $request->input('grade');
                $school->gender = $request->input('gender');
                $school->dob = $request->input('dob');
                $school->wallet_balance = $request->input('wallet_balance');
                $school->spend_limit = $request->input('spend_limit');
                $school->admission_no = $request->input('admission_no');

                $school->save();

               return redirect()->back()->with('success', 'Student added successfully!');
            }


            public function students_update(Request $request, $id)
        {
            $school = SchoolStudent_Model::findOrFail($id);
            $school->school_name = $request->input('schoolname');
            $school->address = $request->input('address');
            $school->save();

            return redirect()->route('admin.school_students')->with('success', 'Student updated successfully!');
        }

        public function studentschangeStatus($id)
        {
            $school = SchoolStudent_Model::findOrFail($id);
            $school->status = $school->status == 1 ? 0 : 1;
            $school->save();

            return redirect()->back()->with('success', 'Status updated successfully!');
        }

        ////////////////////////////////////////////
        ////////////--School Pre-orders--///////////////
        ////////////////////////////////////////////
		
        public function SchoolPreOrders()
        { 
            $cafeteria_id = Auth::user()->cafeteria_id;  
            $school_id = Auth::user()->school_id;  

            $data = PreOrder_Model::where('school_id', auth()->user()->school_id)->get();	
            // echo "<pre>"; print_r($data); die;	
            $dish = Dish_Model::get();
            $student = SchoolStudent_Model::where('school_id', auth()->user()->school_id)->get();
            $school = School_Model::get();
            $cafeteria = Cafeteria_Model::where('school_id', auth()->user()->school_id)->get();
            return view('admin.schooladmin.preorder.index', compact('data','dish','student','school','cafeteria'));
        }	

        public function SchoolOnsite()
        { 
            $cafeteria_id = Auth::user()->cafeteria_id;  
            $school_id = Auth::user()->school_id;  

            $data = PreOrder_Model::where('school_id', auth()->user()->school_id)->get();	
            // echo "<pre>"; print_r($data); die;	
            $dish = Dish_Model::get();
            $student = SchoolStudent_Model::where('school_id', auth()->user()->school_id)->get();
            $school = School_Model::get();
            $cafeteria = Cafeteria_Model::where('school_id', auth()->user()->school_id)->get();
            return view('admin.schooladmin.onsite.index', compact('data','dish','student','school','cafeteria'));
        }	
		


            ////////////////////////////////////////////
           //////////--consumption_by_school--/////////
          ////////////////////////////////////////////

        public function consumption_by_school()
        { 
            $cafeteria_id = Auth::user()->cafeteria_id;  
            $school_id = Auth::user()->school_id;  
            $data = PreOrder_Model::where('school_id', auth()->user()->school_id)->get();		
            $dish = Dish_Model::get();
            $student = SchoolStudent_Model::where('school_id', auth()->user()->school_id)->get();
            $school = School_Model::get();
            $cafeteria = Cafeteria_Model::where('school_id', auth()->user()->school_id)->get();
            return view('admin.schooladmin.consumption.index', compact('data','dish','student','school','cafeteria'));
        }

        

		
}
