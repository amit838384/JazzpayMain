<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mail\SchoolUserInviteMail;
use Illuminate\Support\Facades\Mail;

///////--Models--///////////

use App\Models\School_Model;
use App\Models\SchoolUser_Model;
use App\Models\SchoolParent_Model;
use App\Models\SchoolStudent_Model;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

use App\Mail\ParentInviteMail;
class SchoolController extends Controller
{
        public function __construct()
    {
        $this->middleware('auth');
    }

    public function school()
    {
        // echo "test"; die;
        $data = School_Model::get();
        return view('admin.school.index', compact('data'));
    }

    public function school_create()
    {
      return view('admin.school.create');
    }

    public function school_store(Request $request)
    {
        $request->validate([
            'schoolname' => 'required|string|max:255',
            'address' => 'required|string|max:500',
        ]);
        
        $school = new School_Model();
        $school->school_name = $request->input('schoolname');
        $school->address = $request->input('address');
        $school->save();

        
        return redirect()->route('admin.school')->with('success', 'School added successfully!');
    }

            public function school_update(Request $request, $id)
        {
            $school = School_Model::findOrFail($id);
            $school->school_name = $request->input('schoolname');
            $school->address = $request->input('address');
            $school->save();

            return redirect()->route('admin.school')->with('success', 'School updated successfully!');
        }

        public function schoolchangeStatus($id)
        {
            $school = School_Model::findOrFail($id);
            $school->status = $school->status == 1 ? 0 : 1;
            $school->save();

            return redirect()->back()->with('success', 'Status updated successfully!');
        }

        ////////////////////////////////////////////
        ////////////--Invite Users--///////////////
        ////////////////////////////////////////////

        public function invite_users()
        {
            // echo "test"; die;
            $school = School_Model::get();
            $users = User::where('role', 'schooladmin')->get();

            return view('admin.invite_user.index', compact('school','users'));
        }


        // public function invite_users_store(Request $request)
        // {
            
            
        //     $request->validate([
        //         'school_id' => 'required',
        //         'email' => 'required|string|email',
        //         'name' => 'required|string',
        //     ]);

         
        //     $school = new SchoolUser_Model();
        //     $school->school_id = $request->input('school_id');
        //     $school->email = $request->input('email');
        //     $school->name = $request->input('name');
        //     $school->role = "schooladmin"; // FIXED
        //     $school->invite_code = rand(111111, 999999);

        //     $school->save();

           
        //     return redirect()->route('admin.invite_users')->with('success', 'User invited successfully.');
        // }

       public function invite_users_store(Request $request)
        {
            $request->validate([
                'school_id' => 'required',
                'email'     => 'required|string|email',
                'name'      => 'required|string',
            ]);

            $inviteCode = rand(111111, 999999);

            $school = new User();
            $school->school_id = $request->school_id;
            $school->email = $request->email;
            $school->name = $request->name;
            $school->role = 'schooladmin';
            $school->invite_code = $inviteCode;
            $school->password = Hash::make($inviteCode); // required
            $school->save();

            try {
                Mail::to($school->email)->send(new SchoolUserInviteMail($school, $inviteCode));
            } catch (\Exception $e) {
                \Log::error('Invite Email Failed: '.$e->getMessage());
            }

            return redirect()
                ->route('admin.invite_users')
                ->with('success', 'User invited successfully and email sent.');
        }
                
        ////////////////////////////////////////////
        ////////////--School Users--///////////////
        ////////////////////////////////////////////


        public function schoolusers()
        {
            $data = User::where('role', 'schooladmin')->get();
            $school = School_Model::get();
            
            return view('admin.schoolusers.index', compact('data','school'));
        }

 

        public function schooluserschangeStatus($id)
        {
            $school = SchoolUser_Model::findOrFail($id);
            $school->status = $school->status == 1 ? 0 : 1;
            $school->save();

            return redirect()->back()->with('success', 'Status updated successfully!');
        }

      
          ////////////////////////////////////////////
        ////////////--School Parents--///////////////
        ////////////////////////////////////////////

        public function parents()
        {
            $school = School_Model::get();
            $data = SchoolParent_Model::get();
            return view('admin.schoolparents.index', compact('school','data'));
        }

       

           public function parents_store(Request $request)
            {
                $request->validate([
                    'school_id' => 'required',
                    'name'      => 'required|string|max:200',
                    'email'     => 'required|string|email|max:200',
                    'mobile'    => 'required|string|max:15',
                    'balance'   => 'required|numeric|max:999999',
                ]);

                $school = new SchoolParent_Model();
                $school->name = $request->name;
                $school->email = $request->email;
                $school->mobile = $request->mobile;
                $school->topup_balance = $request->balance;
                $school->school_id = $request->school_id;

                $school->role = "Parent";
                $school->invite_code = rand(111111, 999999);
                $school->sent_date = now();
                $school->accepted_date = null;

                $school->save();

                // Send invite email
                Mail::to($school->email)->send(new ParentInviteMail($school));

                return redirect()
                    ->route('admin.parents')
                    ->with('success', 'Parent added successfully and invitation sent!');
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
            $school = School_Model::get();   // school
            $parent = SchoolParent_Model::get(); // parent
            $data = SchoolStudent_Model::get();  // student
            return view('admin.schoolstudents.index', compact('school','parent','data'));
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

                $school->save();

                return redirect()->route('admin.students')->with('success', 'Student added successfully!');
            }


            public function students_update(Request $request, $id)
        {
            $school = SchoolStudent_Model::findOrFail($id);
            $school->school_name = $request->input('schoolname');
            $school->address = $request->input('address');
            $school->save();

            return redirect()->route('admin.students')->with('success', 'Student updated successfully!');
        }

        public function studentschangeStatus($id)
        {
            $school = SchoolStudent_Model::findOrFail($id);
            $school->status = $school->status == 1 ? 0 : 1;
            $school->save();

            return redirect()->back()->with('success', 'Status updated successfully!');
        }
}
