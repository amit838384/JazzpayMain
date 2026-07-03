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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

use App\Helpers\sms;


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

        /* public function invite_users()
        {
            // echo "test"; die;
            $school = School_Model::get();
            $users = User::where('role', 'schooladmin')->get();

            return view('admin.invite_user.index', compact('school','users'));
        } */
		
		
		public function invite_users(Request $request)
		{
			$school = School_Model::get();

			$query = User::where('role', 'schooladmin');

			if ($request->filled('email')) {
				$query->where('email', 'like', '%' . $request->email . '%');
			}

			if ($request->filled('school_id')) {
				$query->where('school_id', $request->school_id);
			}

			$users = $query->paginate(10);

			return view('admin.invite_user.index', compact('school', 'users'));
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
                'email' => 'required|email|unique:users,email',
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


       /*  public function schoolusers()
        {
            $data = User::where('role', 'schooladmin')->get();
            $school = School_Model::get();
            
            return view('admin.schoolusers.index', compact('data','school'));
        } */
		
		public function schoolusers(Request $request)
		{
			$school = School_Model::get();

			$query = User::where('role', 'schooladmin');

			if ($request->filled('name')) {
				$query->where('name', 'like', '%' . $request->name . '%');
			}

			if ($request->filled('school_id')) {
				$query->where('school_id', $request->school_id);
			}

			$data = $query->paginate(10);

			return view('admin.schoolusers.index', compact('data', 'school'));
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

       /*  public function parents()
        {
            $school = School_Model::get();
            $data = SchoolParent_Model::get();
            return view('admin.schoolparents.index', compact('school','data'));
        } */
		
		
		public function parents(Request $request)
		{
			$school = School_Model::get();

			$query = SchoolParent_Model::query();

			if ($request->filled('mobile')) {
				$query->where('mobile', 'like', '%' . $request->mobile . '%');
			}

			if ($request->filled('name')) {
				$query->where('name', 'like', '%' . $request->name . '%');
			}

			if ($request->filled('email')) {
				$query->where('email', 'like', '%' . $request->email . '%');
			}

			if ($request->filled('school_id')) {
				$query->where('school_id', $request->school_id);
			}

			if ($request->filled('status')) {
				$query->where('status', $request->status);
			}

			$data = $query->paginate(10);

			$accepted = SchoolParent_Model::where('status', 1)->count();
			$sent     = SchoolParent_Model::where('status', 0)->count();

			return view('admin.schoolparents.index', compact('school', 'data', 'accepted', 'sent'));
		}
       

        //    public function parents_store(Request $request)
        //     {
        //         echo "dddd"; die;
        //         $request->validate([
        //             'school_id' => 'required',
        //             'name'      => 'required|string|max:200',
        //             'email'     => 'required|string|email|max:200',
        //             'mobile'    => 'required|string|max:15',
        //             'balance'   => 'required|numeric|max:999999',
        //         ]);

        //         $school = new SchoolParent_Model();
        //         $school->name = $request->name;
        //         $school->email = $request->email;
        //         $school->mobile = $request->mobile;
        //         $school->topup_balance = $request->balance;
        //         $school->school_id = $request->school_id;

        //         $school->role = "Parent";
        //         $school->invite_code = rand(111111, 999999);
        //         $school->sent_date = now();
        //         $school->accepted_date = null;

        //         $school->save();

        //         // Send invite email
        //         Mail::to($school->email)->send(new ParentInviteMail($school));

        //         return redirect()
        //             ->route('admin.parents')
        //             ->with('success', 'Parent added successfully and invitation sent!');
        //     }

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
            $school->name       = $request->name;
            $school->email      = $request->email;
            $school->mobile     = $request->mobile;
            $school->topup_balance = $request->balance;
            $school->school_id  = $request->school_id;
            $school->role       = "Parent";
            $school->invite_code = rand(111111, 999999);
            $school->sent_date  = now();
            $school->accepted_date = null;
            $school->save();

            Log::info('Parent created', [
                'parent_id'   => $school->id,
                'name'        => $school->name,
                'mobile'      => $school->mobile,
                'email'       => $school->email,
                'invite_code' => $school->invite_code,
            ]);

            // ── Send invite email ──────────────────────────────────────────────
            try {
                Mail::to($school->email)->send(new ParentInviteMail($school));
                Log::info('Invite email sent', [
                    'parent_id' => $school->id,
                    'email'     => $school->email,
                ]);
            } catch (\Throwable $e) {
                Log::error('Invite email FAILED', [
                    'parent_id' => $school->id,
                    'email'     => $school->email,
                    'error'     => $e->getMessage(),
                ]);
            }

            // ── Send invite SMS ───────────────────────────────────────────────
            $smsMessage = "Dear {$school->name}, you have been invited to join the school portal. "
                        . "Your invite code is: {$school->invite_code}. "
                        . "Please use this code to register your account.";

            Log::info('Attempting SMS', [
                'parent_id' => $school->id,
                'mobile'    => $school->mobile,
                'message'   => $smsMessage,
            ]);

            $smsSent = send_sms($school->mobile, $smsMessage);

            if ($smsSent) {
                Log::info('Invite SMS sent successfully', [
                    'parent_id' => $school->id,
                    'mobile'    => $school->mobile,
                ]);
            } else {
                Log::warning('Invite SMS FAILED', [
                    'parent_id' => $school->id,
                    'mobile'    => $school->mobile,
                    'hint'      => 'Check SMSALA_TOKEN and SMSALA_SENDER_ID in .env, and review SMSala API logs above.',
                ]);
            }

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
		
		public function parents_reset_password(Request $request, $id)
		{
			$request->validate([
				'password' => 'required|string|min:6|confirmed',
			]);

			$parent = SchoolParent_Model::findOrFail($id);
			$parent->password = Hash::make($request->password);
			$parent->save();

			return redirect()->route('admin.parents')->with('success', 'Password reset successfully for ' . $parent->name . '.');
		}


        ////////////////////////////////////////////
        ////////////--School Students--///////////////
        ////////////////////////////////////////////

        /* public function students()
        {
            $school = School_Model::get();   // school
            $parent = SchoolParent_Model::get(); // parent
            $data = SchoolStudent_Model::get();  // student
            return view('admin.schoolstudents.index', compact('school','parent','data'));
        } */
		
		
		public function students(Request $request)
		{
			$school = School_Model::get();
			$parent = SchoolParent_Model::get();
		 
			$query = SchoolStudent_Model::query();
		 
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
			if ($request->filled('school_id')) {
				$query->where('school_id', $request->school_id);
			}
			if ($request->filled('status')) {
				$query->where('status', $request->status);
			}
		 
			$data = $query->orderBy('id', 'desc')->paginate(10);
		 
			// Load restricted foods for current page students only (no N+1)
			$studentIds      = $data->pluck('id');
			$restrictedFoods = DB::table('tbl_restricted_food_by_student')
				->whereIn('student_id', $studentIds)
				->where('status', 1)
				->get(['student_id', 'name'])
				->groupBy('student_id');
		 
			return view('admin.schoolstudents.index', compact('school', 'parent', 'data', 'restrictedFoods'));
		}
		
		public function students_verify(Request $request, $id)
		{
			$request->validate([
				'verified' => 'required|in:0,1',
			]);

			$student = SchoolStudent_Model::findOrFail($id);
			$student->verified = $request->verified;
			$student->save();

			return redirect()->back()->with('success', 'Student verification status updated successfully!');
		}
		

       

            public function students_store(Request $request)
            {

                // echo "<pre>"; print_r($request->all()); die;
                $request->validate([
                    'school_id' => 'required',
                    // 'parent_id' => 'required',
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

                return redirect()->route('admin.students')->with('success', 'Student added successfully!');
            }

            public function students_update_new(Request $request, $id)
            {
                $request->validate([
                    'school_id'      => 'required',
                    'student_name'   => 'required|string|max:200',
                    'grade'          => 'required|string|max:200',
                    'gender'         => 'required|string|max:200',
                    'dob'            => 'required|max:200',
                    'wallet_balance' => 'required|max:200',
                    'spend_limit'    => 'required|max:200',
                    'admission_no'   => 'required|max:200',
                ]);

                $student = SchoolStudent_Model::findOrFail($id);
                $student->school_id      = $request->input('school_id');
                $student->parent_id      = $request->input('parent_id');
                $student->student_name   = $request->input('student_name');
                $student->grade          = $request->input('grade');
                $student->gender         = $request->input('gender');
                $student->dob            = $request->input('dob');
                $student->wallet_balance = $request->input('wallet_balance');
                $student->spend_limit    = $request->input('spend_limit');
                $student->admission_no   = $request->input('admission_no');
                if ($request->filled('verified')) {
                    $student->verified = $request->input('verified');
                }
                $student->save();

                return redirect()->route('admin.students')->with('success', 'Student updated successfully!');
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
