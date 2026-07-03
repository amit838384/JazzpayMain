<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\SchoolParent_Model;

use App\Models\SchoolStudent_Model;
use App\Models\School_Model;
use App\Models\Restrictedfood_Model;
use App\Models\Restrictedfoodbystudent_Model;

use App\Models\ManageCafe\Ingredients_Model;


use App\Models\Dashboard_Model;

use App\Models\Grade_Model;
use App\Models\ParentTopup_Model;

use App\Models\Feedback_Model;


use App\Models\Cafeteria\Cafeteria_Model;
use App\Models\ManageCafe\DishCategory_Model;
use App\Models\ManageCafe\Dish_Model;
use App\Models\PreOrder_Model;

use App\Models\Checkout_Model;


use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;


use App\Models\StudentCredit_Model;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;


use App\Models\Order_Model;
use App\Models\OrderItem_Model;

/////

use App\Models\PayForService\Plan_Model;
use App\Models\PayForService\Subscription_Model;
use App\Models\PayForService\SubscriptionPause_Model;
use Illuminate\Support\Facades\Storage;


class ParentController extends Controller
{
	protected $parent;

    public function __construct()
    {
        $this->parent = auth('parent_api')->user();
    }	
	
	public function get_parent_details($invite){
		
		$parent = SchoolParent_Model::where('invite_code', $invite)->first();
		if (!$parent) {
			return response()->json([
				'status' => false,
				'message' => 'Parent not found with this invite code.',
			]);
		}

		$details = [
			'Phone No' => $parent->mobile,
			'Invite Code' => $parent->invite_code,
		];

		return response()->json([
			'status' => True,
			'message' => 'Get Parent details successful',
			'user' => $details
		]);
	}


///////////////////////--signup--///////////////////////

	public function parent_signup(Request $request){
		$validator = Validator::make($request->all(), [
			'name' => 'required|string',
			'phone' => 'required|string',
			'password' => 'required|string',
			'invite_code' => 'required|string',
		]);
		
		if ($validator->fails()) {
			$errors = $validator->errors();
			$messages = [];

			if ($errors->has('name')) {
				$messages[] = $errors->first('name');
			}
			if ($errors->has('phone')) {
				$messages[] = $errors->first('phone');
			}
			if ($errors->has('password')) {
				$messages[] = $errors->first('password');
			}
			if ($errors->has('invite_code')) {
				$messages[] = $errors->first('invite_code');
			}

			return response()->json([
				'status' => false,
				'message' => implode(' ', $messages)
			]);
		}

        // Compare digits only, so "+97450180606" and "97450180606" both match
			$normalizedPhone = preg_replace('/[^0-9]/', '', $request->phone);

			$parent = SchoolParent_Model::whereRaw("REPLACE(REPLACE(mobile, '+', ''), ' ', '') = ?", [$normalizedPhone])
				->where('invite_code', $request->invite_code)
				->first();
		
		if (!$parent) {
			return response()->json([
				'status'  => false,
				'message' => 'Parent not found or invite code incorrect.'
			]);
		}

        $parent->name = $request->name;
        // $parent->mobile = $request->phone;
        $parent->password = Hash::make($request->password); 
        $parent->invite_code = $request->invite_code;
        $parent->accepted_date = Carbon::now();
        $parent->view = 1;

        $parent->save();

        $token = JWTAuth::fromUser($parent);

        return response()->json([
			'status' => True,
            'message' => 'Signup successful',
            'token' => $token,
            'user' => $parent
        ]);
    }

//////////////////--Login--////////////////////////////

    public function parent_login(Request $request)
    {
		// echo "dfdf"; die;
 		$validator = Validator::make($request->all(), [
			'phone' => 'required|string',
			'password' => 'required|string',
		]);
		
		if ($validator->fails()) {
			$errors = $validator->errors();
			$messages = [];

			if ($errors->has('phone')) {
				$messages[] = $errors->first('phone');
			}
			if ($errors->has('password')) {
				$messages[] = $errors->first('password');
			}

			return response()->json([
				'status' => false,
				'message' => implode(' ', $messages)
			]);
		}

        $parent = SchoolParent_Model::where('mobile', $request->phone)->first();

        if (!$parent) {
            return response()->json([
				'status' => false,
				'message' => 'Parent not found.'
			]);
        }

        if (!Hash::check($request->password, $parent->password)) {
            return response()->json([
				'status' => false,
				'message' => 'Invalid password.'
			]);
        }

        $token = JWTAuth::fromUser($parent);

        return response()->json([
			'status' => True,
            'message' => 'Login successful',
            'token' => $token,
            'user' => $parent
        ]);
    }

//////////////////--Send Otp to Email--////////////////////////

	public function parent_forgot_password(Request $request){
		
		$request->validate([
			'email' => 'required|string|email',
		]);

		$parent = SchoolParent_Model::where('email', $request->email)->first();
		
		if (!$parent) {
			return response()->json([
				'status' => false,
				'message' => 'Parent not found or email is incorrect.'				
			]);
		}

		$forget_otp = rand(111111, 999999);

		$parent->forget_otp = $forget_otp;
		$parent->save();

		Mail::raw("Your OTP is: $forget_otp", function ($message) use ($parent) {
			$message->to($parent->email)
					->subject('Parent Login OTP');
		});

		return response()->json([
			'status' => True,
			'message' => 'OTP sent to parent email successfully.',
	 
		]);
	}


	public function parent_change_password(Request $request){
		
		try {
			$request->validate([
				'otp' => 'required',
				'password' => 'required|min:6',
			]);
		} catch (ValidationException $e) {
			return response()->json($e->errors(), 200);
		}

		$parent = SchoolParent_Model::where('forget_otp', $request->otp)->first();

		if (!$parent) {
			return response()->json([
				'status' => false,
				'message' => 'OTP is incorrect.',
			]);
		}

		$parent->password = Hash::make($request->password);
		$parent->forget_otp = null;
		$parent->save();

		return response()->json([
			'status' => True,
			'message' => 'Password has been changed successfully.',
		]);
	}

	public function dashboard(){
		
		$parent = auth('parent_api')->user();

		if (!$parent) {
			return response()->json([
				'status'  => false,
				'message' => 'User not found or token invalid.',
			]);
		}
		
		$balance = $parent->topup_balance;
		
		$students = SchoolStudent_Model::
			where('parent_id', $parent->id)
			->where('status', 1)
			->get(['student_name', 'wallet_balance']);

		$accounts = $students->map(function ($student) {
			return [
				'name' => $student->student_name,
				'balance' => $student->wallet_balance ? 'QAR ' . $student->wallet_balance : 'QAR 0'
			];
		});

		return response()->json([
			'status' => true,
			'total_balance' => 'QAR ' . $balance,
			'menu' => [
				[
					'label' => 'Family',
					'color' => '#F78C6B'
				],
				[
					'label' => 'Pre Order',
					'color' => '#4CAF50'
				],
				[
					'label' => 'Credit Transfer',
					'color' => '#F5C144'
				],
				[
					'label' => 'Pay For Service',
					'color' => '#4CAF50'
				],
				[
					'label' => 'History',
					'color' => '#F5C144'
				],
				[
					'label' => 'Top Up',
					'color' => '#F78C6B'
				]
			],
			'cafeteria_balance' => [
				'title' => 'Balance available to use in cafeteria',
				'accounts' => $accounts
			],
			'footer' => [
				'label' => 'User Guide',
				'url' 	=> 'https://youtube.com/playlist?list=PLyIlACi2jBQoJ8QtrXdc7i0gAQa1vvNAi&si=1yrBNwuCYaeGj4El'
			]
		]);
	}

	public function student_list(){

		 $parent = auth('parent_api')->user(); 

		if (!$parent) {
			return response()->json([
				'status'  => false,
				'message' => 'User not found or token invalid.',
			]);
		}

		$students = SchoolStudent_Model::where('parent_id', $parent->id)->get();

		$data = [];

		foreach ($students as $student) {
			$resfood = Restrictedfoodbystudent_Model::where('student_id', $student->id)->pluck('name')->toArray();

			$data[] = [
				'id'                 => $student->id,
				'name'               => $student->student_name,
				'grade'              => $student->grade,
				'gender'             => $student->gender,
				'dob'                => $student->dob,
				'daily_spend_limit'  => $student->spend_limit,
				'admission_no'       => $student->admission_no,
				'image'              => $student->image ?? '', 
				'restricted_food'    => $resfood,
			];
		}

		return response()->json([
			'status' => true,
			'data'   => $data,
		]);
	}


	public function student_detail(Request $request)
	{
		$parent = auth('parent_api')->user();

		if (!$parent) {
			return response()->json([
				'status' => false,
				'message' => 'User not found or token invalid.',
			]);
		}

		$student = SchoolStudent_Model::where('id', $request->studentID)->first();

		if (!$student) {
			return response()->json([
				'status' => false,
				'message' => 'Student not found.',
			]);
		}

		$resfood = Restrictedfood_Model::where('parent_id', $parent->id)->get();

		$restricted_food = [];

		if (!$resfood->isEmpty()) {
			foreach ($resfood as $item) {
				$restricted_food[] = $item->name ?? '';
			}
		}

		$data = [
			'id'    => $student->id,
			'name' => $student->student_name,
			'grade' => $student->grade,
			'gender' => $student->gender,
			'dob' => $student->dob,
			'daily_spend_limit' => $student->spend_limit,
			'admission_no'   => $student->admission_no,
			'image' => '',
			'restricted_food' => $restricted_food
		];

		return response()->json([
			'status' => true,
			'data' => $data,
		]);
	}




	public function parent_profile()
	{
		$parent = auth('parent_api')->user();
		
		if (!$parent) {
			return response()->json([
				'status' => false,
				'message' => 'User not found or token invalid.',
			]);
		}

		$parentdetail = SchoolParent_Model::where('id', $parent->id)->first();
		$school = School_Model::where('id', $parent->school_id)->first();

		$school_name = $school ? $school->school_name : null;

		$data = [
			'school_name' => $school_name,
			'id' => $parentdetail->id,
			'name' => $parentdetail->name,
			'mobile' => $parentdetail->mobile,
			'email' => $parentdetail->email,
			'image' => '',
			'link' => 'https://youtube.com/playlist?list=PLyIlACi2jBQoJ8QtrXdc7i0gAQa1vvNAi&si=hwAkfn-wPdzvVnDr',	 
			'wallet_balance' => $parentdetail->topup_balance,	   

		];

		return response()->json([
			'status' => true,
			'data' => $data,
		]);
	}


/////////////////////--Feedback--///////////////////////////////

	public function parent_feedback(Request $request)
	{
		// echo "<pre>"; print_r($request->all()); die;
		$msg = $request->message;

		Feedback_Model::insert([
			'parent_id' => $this->parent->id,
			'message' => $msg,
		]);

		return response()->json([
			'status' => true,
			'message' => "Feedback saved successfully",
		]);
	}


/////////////////////////////--add student--/////////////////////
	// public function add_student(Request $request)
	// {
	// 	$parent = auth('parent_api')->user();

	// 	if (!$parent) {
	// 		return response()->json([
	// 			'status' => false,
	// 			'message' => 'User not found or token invalid.',
	// 		]);
	// 	}

	// 	$school = School_Model::where('id', $parent->school_id)->first();

	// 	if (!$school) {
	// 		return response()->json([
	// 			'status' => false,
	// 			'message' => 'School not found.',
	// 		]);
	// 	}

	// 	if ($request->has('id') && !empty($request->id)) {
	// 		$student = SchoolStudent_Model::where('id', $request->id)
	// 			->where('parent_id', $parent->id) // security: make sure this student belongs to the parent
	// 			->first();

	// 		if (!$student) {
	// 			return response()->json([
	// 				'status' => false,
	// 				'message' => 'Student not found or unauthorized.',
	// 			]);
	// 		}
	// 	} else {
	// 		$student = new SchoolStudent_Model();
	// 		$student->parent_id = $parent->id;
	// 		$student->school_id = $school->id;
	// 	}

	// 	// Set or update values
	// 	$student->student_name  = $request->name;
	// 	$student->grade         = $request->grade;
	// 	$student->gender        = $request->gender;
	// 	$student->dob           = $request->dob;
	// 	$student->spend_limit   = $request->daily_spend_limit;
	// 	$student->admission_no  = $request->admission_no;

	// 	$student->save();

	// 	return response()->json([
	// 		'status' => true,
	// 		'message' => $request->id ? 'Student updated successfully.' : 'Student added successfully.',
	// 		'student' => $student
	// 	], 200);
	// }

	public function add_student(Request $request)
{
    $parent = auth('parent_api')->user();

    if (!$parent) {
        return response()->json([
            'status' => false,
            'message' => 'User not found or token invalid.',
        ]);
    }

    $school = School_Model::where('id', $parent->school_id)->first();

    if (!$school) {
        return response()->json([
            'status' => false,
            'message' => 'School not found.',
        ]);
    }

    // --- UPDATE CASE ---
    if ($request->has('id') && !empty($request->id)) {

        $student = SchoolStudent_Model::where('id', $request->id)
            ->where('parent_id', $parent->id)
            ->first();

        if (!$student) {
            return response()->json([
                'status' => false,
                'message' => 'Student not found or unauthorized.',
            ]);
        }

    } else {
        // --- CREATE CASE ---
        $student = new SchoolStudent_Model();
        $student->parent_id = $parent->id;
        $student->school_id = $school->id;
    }

    // Basic fields
    $student->student_name  = $request->name;
    $student->grade         = $request->grade;
    $student->gender        = $request->gender;
    $student->dob           = $request->dob;
    $student->spend_limit   = $request->daily_spend_limit;
    $student->admission_no  = $request->admission_no;

    // ------------------------------
    // 🔥 IMAGE UPLOAD TO S3 LOGIC
    // ------------------------------
   if ($request->hasFile('image')) {
    $imageUrl = $this->s3uploadfile($request->file('image'));
    $student->image = $imageUrl;
}

    $student->save();

    return response()->json([
        'status' => true,
        'message' => $request->id ? 'Student updated successfully.' : 'Student added successfully.',
        'student' => $student
    ], 200);
}

public function s3uploadfile($file, $folder = ''){
        try {
            if (!$file) {
                throw new \Exception('No file provided for upload.');
            }
            $fileName = time() . '_' . $file->getClientOriginalName();
            $path = Storage::disk('s3')->putFileAs($folder, $file, $fileName);
            return Storage::disk('s3')->url($path);
        } catch (\Exception $e) {
            Log::error("S3 File upload error: " . $e->getMessage());
            return null;
        }
    }

//////////////////--All grade---///////////////////////
	public function grade_all()
	{
		$grades = Grade_Model::pluck('grade'); // only get the grade column values as an array

		return response()->json([
			'status' => true,
			'message' => 'Get Grade.',
			'data' => $grades
		], 200); 
	}


//////////////////--All restricted_food---///////////////////////


	public function restricted_food()
	{
		$restric = Ingredients_Model::pluck('name');

		return response()->json([
			'status' => true,
			'message' => 'Get Restricted food.',
			'data' => $restric
		], 200); 
	}




///////////////--add student daily spend limit---////////////////

	public function update_spend_limit_student(Request $request)
	{
		if (!$this->parent) {
			return response()->json([
				'status' => false,
				'message' => 'User not found or token invalid.',
			]);
		}

		// Validate request data
		$request->validate([
			'studentID' => 'required',
			'money' => 'required|numeric|min:0'
		]);

		$student = SchoolStudent_Model::where('id',$request->studentID)->first();
		if (!$student) {
			return response()->json([
				'status' => false,
				'message' => 'Student not found.',
			]);
		}

		$student->spend_limit = $request->money;
		$student->save();

		return response()->json([
			'status' => true,
			'message' => 'Spend limit updated successfully.',
		], 200);
	}



///////////////////--add Restricted food by student--////////////////////////


	// public function add_restricted_food(Request $request)
	// {
	// 	$parent = auth('parent_api')->user();

	// 	if (!$parent) {
	// 		return response()->json([
	// 			'status' => false,
	// 			'message' => 'User not found or token invalid.',
	// 		]);
	// 	}

	// 	$foods = explode(',', $request->food);

	// 	foreach ($foods as $food) {
	// 		$food = trim($food); 

	// 		if (!empty($food)) {
	// 			$resfood = new Restrictedfoodbystudent_Model();
	// 			$resfood->student_id = $request->id;
	// 			$resfood->name = $food;
	// 			$resfood->save();
	// 		}
	// 	}

	// 	return response()->json([
	// 		'status'  => true,
	// 		'message' => 'Restricted food items added successfully.',
	// 	], 200);
	// }


	public function add_restricted_food(Request $request)
{
    $parent = auth('parent_api')->user();

    if (!$parent) {
        return response()->json([
            'status'  => false,
            'message' => 'User not found or token invalid.',
        ]);
    }

    $foods = explode(',', $request->food);
	

    foreach ($foods as $food) {
        $food = trim($food);

        if (!empty($food)) {
            // Skip if this food already exists for the student (case-insensitive)
            $exists = Restrictedfoodbystudent_Model::where('student_id', $request->id)
                ->whereRaw('LOWER(name) = ?', [strtolower($food)])
                ->exists();

            if ($exists) {
                continue; // already added, don't insert again
            }

            $resfood = new Restrictedfoodbystudent_Model();
            $resfood->student_id = $request->id;
            $resfood->name = $food;
            $resfood->save();
        }
    }

    return response()->json([
        'status'  => true,
        'message' => 'Restricted food items added successfully.',
    ], 200);
}






////////////////////////////////--Parent Topup--////////////////////////
	public function topup(Request $request)
	{
		$parent = auth('parent_api')->user();

		if (!$parent) {
			return response()->json([
				'status' => false,
				'message' => 'User not found or token invalid.',
			]);
		}

		$topup = new ParentTopup_Model();

		$topup->transaction_number = rand(100000, 999999);
		$topup->parent_id  = $parent->id;
		$topup->amount     = $request->amount;
		$topup->payment_status = 1;

		$parent->topup_balance += $topup->amount;
		$parent->save();

		$topup->save();

		return response()->json([
			'status' => true,
			'message' => 'Topup is pending please do payment for success',
		], 200);
	}

	public function add_topu_wallet_balance(Request $request)
	{
		$parent = auth('parent_api')->user();

		if (!$parent) {
			return response()->json([
				'status' => false,
				'message' => 'User not found or token invalid.',
			]);
		}

		$request->validate([
			'studentID' => 'required',
			'balance' => 'required|numeric|min:1'
		]);

		$amount = $request->balance;
		// echo $parent->topup_balance; die;

		// Check if parent has enough balance
		if ($parent->topup_balance < $amount) {
			return response()->json([
				'status' => false,
				'message' => 'Insufficient top-up balance.',
			]);
		}

		$student = SchoolStudent_Model::find($request->studentID);

		if (!$student) {
			return response()->json([
				'status' => false,
				'message' => 'Student not found.',
			]);
		}

		// Transfer amount to student wallet
		$student->wallet_balance += $amount;
		$student->transaction_id = rand(100000, 999999);
		$student->transaction_type = "Parent Transfer";
		$student->save();

		// Deduct from parent balance
		$parent->topup_balance -= $amount;
		$parent->save();

		$studentCredit = new StudentCredit_Model();
		$studentCredit->transaction_id = rand(100000, 999999);
		$studentCredit->student_id = $student->id;
		$studentCredit->amount = $amount;
		$studentCredit->save();
		return response()->json([
			'status' => true,
			'message' => 'Wallet Balance Updated Successfully.',
		], 200);
	}


//////////////////////--Student to Parent Transfer--////////////////////////////


	public function student_to_parent_transfer(Request $request)
	{
		$parent = auth('parent_api')->user();

		if (!$parent) {
			return response()->json([
				'status' => false,
				'message' => 'User not found or token invalid.',
			]);
		}

		$request->validate([
			'studentID' => 'required',
			'balance'   => 'required|numeric|min:1'
		]);

		$amount = $request->balance;

		$student = SchoolStudent_Model::find($request->studentID);

		if (!$student) {
			return response()->json([
				'status' => false,
				'message' => 'Student not found.',
			]);
		}

		if ($student->wallet_balance < $amount) {
			return response()->json([
				'status' => false,
				'message' => 'Insufficient Credit.',
			]);
		}

		$parent->topup_balance += $amount;
		$parent->save();

		$student->wallet_balance -= $amount;
		$student->save();

		return response()->json([
			'status' => true,
			'message' => 'Wallet Balance Updated Successfully.',
			'parent_balance' => $parent->topup_balance . ' QAR',
			'student_balance' => $student->wallet_balance . ' QAR',
		], 200);
	}


	public function get_topu_wallet_balance()
	{
		$parent = auth('parent_api')->user();

		if (!$parent) {
			return response()->json([
				'status'  => false,
				'message' => 'User not found or token invalid.',
			]);
		}

		$students = SchoolStudent_Model::where('parent_id', $parent->id)->get();

		$data = [];

		foreach ($students as $student) {

			$data[] = [
				'id'                 => $student->id,
				'name'               => $student->student_name,
				'wallet_balance' => (string) ($student->wallet_balance ?? 0),
			];
		}

		
		return response()->json([
			'status' => true,
			'data'   => $data,
			'parent wallet' => (string) $parent->topup_balance, 
		]);
	}

	public function get_topup()
	{
		$parent = auth('parent_api')->user();
		if (!$parent) {
			return response()->json([
				'status'  => false,
				'message' => 'User not found or token invalid.',
			]);
		}

		$parentTopups = ParentTopup_Model::where('parent_id', $parent->id)->get();

		$data = [];

		foreach ($parentTopups as $par) {
			$data[] = [
				'id'            => $par->id,
				'amount'        => $par->amount . ' QAR',
				'date'          => strtolower(\Carbon\Carbon::parse($par->created_at)->format('d F Y h:i A')),
				'status'        => $par->payment_status == 1 ? 'Success' : 'Pending',
				'status_color'  => $par->payment_status == 1 ? '#28a745' : '#dc3545', 
				'transactionid' => $par->transaction_number,
			];
		}


		return response()->json([
			'status' => true,
			'data'   => $data,
			'wallet_balance'   => (string)$parent->topup_balance,
		]);
	}



/////////////////////////////////////////////
/////////////////////////////////////////////
///////////////--Pre Orders--///////////////
/////////////////////////////////////////////
/////////////////////////////////////////////



// public function category_wise_dish(Request $request)
// {
//     $parent = auth('parent_api')->user();

//     if (!$parent) {
//         return response()->json([
//             'status'  => false,
//             'message' => 'User not found or token invalid.',
//         ]);
//     }

//     $request->validate([
//         'category_id' => 'required',
//         'student_id'  => 'required',
//         'date'        => 'required',
//     ]);

//     $dishes = Dish_Model::where('dish_category_id', $request->category_id)->get();
//     $data = [];

//     foreach ($dishes as $dish) {

// 		$preOrderItem = PreOrder_Model::where('parent_id', $parent->id)
// 			->where('student_id', $request->student_id)
// 			->where('dish_id', $dish->id)
// 			->where('date', $request->date)
// 			->where('payment_status', 0)
// 			->select('qty')
// 			->first();

// 		$qty = $preOrderItem ? (int)$preOrderItem->qty : 0;

// 		// ✅ Append food_type to name
// 		$foodType = '';

// 		if ($dish->food_type) {
// 			$types = explode(',', $dish->food_type);              
// 			$types = array_map(function ($t) {
// 				return ucfirst(strtolower(trim($t))); 
// 			}, $types);

// 			$foodType = "\n" . implode(', ', $types);
// 		}


// 		$data[] = [
// 			'id'    => $dish->id,
// 			'name'  => $dish->dish_name . $foodType,
// 			'price' => $dish->price,
// 			'image' => $dish->image,
// 			'qty'   => $qty,
			
// 		];
// 	}

//     $total_qty = PreOrder_Model::where('parent_id', $parent->id)
//         ->where('date', $request->date)
//         ->where('payment_status', 0)
//         ->sum('qty');

//     return response()->json([
//         'status'         => true,
//         'message'        => 'Dishes fetched successfully by category.',
//         'data'           => $data,
//         'total_dish_qty' => (int)$total_qty,
//     ], 200);
// }

public function category_wise_dish(Request $request)
{
    $parent = auth('parent_api')->user();

    if (!$parent) {
        return response()->json([
            'status'  => false,
            'message' => 'User not found or token invalid.',
        ]);
    }

    $request->validate([
        'category_id' => 'required',
        'student_id'  => 'required',
        'date'        => 'required',
    ]);

    $dishes = Dish_Model::where('dish_category_id', $request->category_id)->where('show_in_pos',0)->get();
    $data = [];

    foreach ($dishes as $dish) {

		$preOrderItem = PreOrder_Model::where('parent_id', $parent->id)
			->where('student_id', $request->student_id)
			->where('dish_id', $dish->id)
			->where('date', $request->date)
			->where('payment_status', 0)
			->select('qty')
			->first();

		$qty = $preOrderItem ? (int)$preOrderItem->qty : 0;

		// ✅ Append food_type to name
		$foodTypeText = '';
		$foodTypesArray = [];


		if (!empty($dish->food_type)) {
			$types = explode(',', $dish->food_type);

			$types = array_map(function ($t) {
				return ucfirst(strtolower(trim($t)));
			}, $types);

			// 1️⃣ New line food type for name (Flutter supports \n)
			$foodTypeText = "\n" . implode(', ', $types);

			// 2️⃣ Addons array
			// 2️⃣ Addons array
			$foodTypesArray = $types;
		}


		$data[] = [
			'id'    => $dish->id,
			'name'  => $dish->dish_name ?? '',
			'price' => $dish->price,
			'image' => $dish->image,
			'qty'   => $qty,
			'calories' => $dish->calories,
			'protein' => $dish->protein,
			'carbohydrates' => $dish->carbohydrates,
			'fats' => $dish->fats,
			'ingredients' => $dish->description,
			'addons' => $foodTypesArray,		
		];
	}

    $total_qty = PreOrder_Model::where('parent_id', $parent->id)
        ->where('date', $request->date)
        ->where('payment_status', 0)
        ->sum('qty');

    return response()->json([
        'status'         => true,
        'message'        => 'Dishes fetched successfully by category.',
        'data'           => $data,
        'total_dish_qty' => (int)$total_qty,
    ], 200);
}






//////////////////////////--PreOrders----//////////////////////////
	public function all_category()
	{
		$parent = auth('parent_api')->user();

		if (!$parent) {
			return response()->json([
				'status'  => false,
				'message' => 'User not found or token invalid.',
			]);
		}

		$alldish = Cafeteria_Model::where('school_id', $parent->school_id)->first();

		if (!$alldish) {
			return response()->json([
				'status' => false,
				'message' => 'No cafeteria assigned to this school.',
			]);
		}

		$categories = DishCategory_Model::where('cafeteria_id', $alldish->id)->where('status', 1)->get();

		$data = [];

		foreach ($categories as $cat) {
			$data[] = [
				'id'         => $cat->id,
				'name'       => $cat->name,
				'meal_type'  => $cat->meal_type,
			];
		}

		$students = SchoolStudent_Model::where('parent_id', $parent->id)->get();

		$studentdata = [];

		foreach ($students as $student) {
			$studentdata[] = [
				'id'           => $student->id,
				'name'         => $student->student_name,
				'admission_no' => $student->admission_no,
			];
		}

		return response()->json([
			'status'  => true,
			'message' => 'Get All category.',
			'data'    => $data,
			'Student' => $studentdata,
		], 200);
	}


// public function pre_order(Request $request)
// {
//     $parent = auth('parent_api')->user();

//     if (!$parent) {
//         return response()->json([
//             'status' => false,
//             'message' => 'User not found or token invalid.',
//         ]);
//     }

//     $request->validate([
//         'student_id' => 'required',
//         'dish_id'    => 'required',
//         'date'       => 'required',
//         'qty'        => 'required|integer|min:1',
//     ]);

//     $dish = Dish_Model::find($request->dish_id);
//     if (!$dish) {
//         return response()->json([
//             'status' => false,
//             'message' => 'Dish not found.',
//         ]);
//     }

//     $student = SchoolStudent_Model::find($request->student_id);
//     if (!$student) {
//         return response()->json([
//             'status' => false,
//             'message' => 'Student not found.',
//         ]);
//     }

//     $qty   = (int)$request->qty;
//     $price = (float)$dish->price;
//     $total = $qty * $price;


//     $existing = PreOrder_Model::where([
//         'student_id' => $student->id,
//         'dish_id'    => $dish->id,
//         'date'       => $request->date,
// 		'payment_status' => 0, 
//     ])->first();

//     if ($existing) {

//         // Update Qty + Total
//         $existing->qty         += $qty;
//         $existing->total_price  = $existing->qty * $price;
//         $existing->save();

//         $total_qty    = $existing->qty;
//         $currentTotal = $existing->total_price;

//     } else {

//         $transactionNo = 'SD' . rand(111111, 999999);

//         $newOrder = PreOrder_Model::create([
//             'transaction_no'    => $transactionNo,
//             'parent_id'         => $parent->id,
//             'student_id'        => $student->id,
//             'school_id'         => $parent->school_id,
//             'cafeteria_id'      => $dish->cafeteria_id,
//             'dish_id'           => $dish->id,
//             'date'              => $request->date,
//             'qty'               => $qty,
//             'dish_price'        => $price,
//             'total_price'       => $total,
//             'total_amount'      => $total,
//             'discount'          => 0,
//             'after_discount'    => $total,
//             'wallet_used'       => 0,
//             'payable'           => $total,
//             'payment_type'      => 'wallet',
//             'payment_status'    => 0,
//         ]);

//         $total_qty    = $qty;
//         $currentTotal = $total;
//     }

//     $parentBalance = is_numeric($parent->topup_balance) ? (float)$parent->topup_balance : 0;
//     $pay_by_wallet = $parentBalance >= $currentTotal ? '1' : '0';

//     $data = [
//         'name' => $student->student_name ?? '',
//         'qty'  => (string)$qty,
//     ];

//     return response()->json([
//         'status'          => true,
//         'message'         => 'Pre-order booked successfully.',
//         'data'            => $data,
//         'total_dish_qty'  => (int)$total_qty,
//         'total_amount'    => number_format($currentTotal, 2, '.', ''),
//         'pay_by_wallet'   => (string)$pay_by_wallet,
//     ], 200);
// }


//chnage kiya
// public function pre_order(Request $request)
// {
//     $parent = auth('parent_api')->user();

//     if (!$parent) {
//         return response()->json([
//             'status' => false,
//             'message' => 'User not found or token invalid.',
//         ]);
//     }

//     $request->validate([
//         'student_id' => 'required',
//         'dish_id'    => 'required',
//         'date'       => 'required',
//         'qty'        => 'required|integer|min:1',
//     ]);

//     $dish = Dish_Model::find($request->dish_id);
//     if (!$dish) {
//         return response()->json([
//             'status' => false,
//             'message' => 'Dish not found.',
//         ]);
//     }

//     $student = SchoolStudent_Model::find($request->student_id);
//     if (!$student) {
//         return response()->json([
//             'status' => false,
//             'message' => 'Student not found.',
//         ]);
//     }

//     $qty   = (int)$request->qty;
//     $price = (float)$dish->price;
//     $total = $qty * $price;

//     $existing = PreOrder_Model::where([
//         'student_id'     => $student->id,
//         'dish_id'        => $dish->id,
//         'date'           => $request->date,
//         'payment_status' => 0,
//     ])->first();

//     if ($existing) {
//         // Update Qty + Total
//         $existing->qty        += $qty;
//         $existing->total_price = $existing->qty * $price;
//         $existing->save();
//     } else {
//         $transactionNo = 'SD' . rand(111111, 999999);

//         PreOrder_Model::create([
//             'transaction_no'    => $transactionNo,
//             'parent_id'         => $parent->id,
//             'student_id'        => $student->id,
//             'school_id'         => $parent->school_id,
//             'cafeteria_id'      => $dish->cafeteria_id,
//             'dish_id'           => $dish->id,
//             'date'              => $request->date,
//             'qty'               => $qty,
//             'dish_price'        => $price,
//             'total_price'       => $total,
//             'total_amount'      => $total,
//             'discount'          => 0,
//             'after_discount'    => $total,
//             'wallet_used'       => 0,
//             'payable'           => $total,
//             'payment_type'      => 'wallet',
//             'payment_status'    => 0,
//         ]);
//     }

//     // 🧮 Calculate total quantity for all dishes by this parent on this date
//     $total_qty = PreOrder_Model::where('parent_id', $parent->id)
//         ->where('date', $request->date)
//         ->where('payment_status', 0)
//         ->sum('qty');

//     // 🧾 Calculate total payable amount for the parent on this date
//     $currentTotal = PreOrder_Model::where('parent_id', $parent->id)
//         ->where('date', $request->date)
//         ->where('payment_status', 0)
//         ->sum('total_price');

//     // 💰 Check if wallet has enough balance
//     $parentBalance = is_numeric($parent->topup_balance) ? (float)$parent->topup_balance : 0;
//     $pay_by_wallet = $parentBalance >= $currentTotal ? '1' : '0';

//     $data = [
//         'name' => $student->student_name ?? '',
//         'qty'  => (string)$qty,
//     ];

//     return response()->json([
//         'status'          => true,
//         'message'         => 'Pre-order booked successfully.',
//         'data'            => $data,
//         'total_dish_qty'  => (int)$total_qty,
//         'total_amount'    => number_format($currentTotal, 2, '.', ''),
//         'pay_by_wallet'   => (string)$pay_by_wallet,
//     ], 200);
// }
public function pre_order(Request $request)
{

	// echo "<pre>"; print_r($request->all()); die;
    $parent = auth('parent_api')->user();

    if (!$parent) {
        return response()->json([
            'status' => false,
            'message' => 'User not found or token invalid.',
        ]);
    }

    $request->validate([
        'student_id' => 'required',
        'dish_id'    => 'required',
        'date'       => 'required',
        'qty'        => 'required|integer|min:1',
    ]);

    $dish = Dish_Model::find($request->dish_id);
    if (!$dish) {
        return response()->json([
            'status' => false,
            'message' => 'Dish not found.',
        ]);
    }

    $student = SchoolStudent_Model::find($request->student_id);
    if (!$student) {
        return response()->json([
            'status' => false,
            'message' => 'Student not found.',
        ]);
    }

    $qty   = (int)$request->qty;
    $price = (float)$dish->price;
    $total = $qty * $price;

    $existing = PreOrder_Model::where([
        'student_id'     => $student->id,
        'dish_id'        => $dish->id,
        'date'           => $request->date,
        'payment_status' => 0,
    ])->first();

    if ($existing) {
        // Update Qty + Total
        $existing->qty        += $qty;
        $existing->total_price = $existing->qty * $price;
        $existing->save();
    } else {
        $transactionNo = 'SD' . rand(111111, 999999);

        PreOrder_Model::create([
            'transaction_no'    => $transactionNo,
            'parent_id'         => $parent->id,
            'student_id'        => $student->id,
            'school_id'         => $parent->school_id,
            'cafeteria_id'      => $dish->cafeteria_id,
            'dish_id'           => $dish->id,
            'date'              => $request->date,
            'qty'               => $qty,
            'dish_price'        => $price,
            'total_price'       => $total,
            'total_amount'      => $total,
            'discount'          => 0,
            'after_discount'    => $total,
            'wallet_used'       => 0,
            'payable'           => $total,
            'payment_type'      => 'wallet',
            'payment_status'    => 0,
			'addons'           => $request->addons,
        ]);
    }

    // 🧮 Calculate total quantity for all dishes by this parent on this date
    $total_qty = PreOrder_Model::where('parent_id', $parent->id)
        ->where('date', $request->date)
        ->where('payment_status', 0)
        ->sum('qty');

    // 🧾 Calculate total payable amount for the parent on this date
    $currentTotal = PreOrder_Model::where('parent_id', $parent->id)
        ->where('date', $request->date)
        ->where('payment_status', 0)
        ->sum('total_price');

    // 💰 Check if wallet has enough balance
    $parentBalance = is_numeric($parent->topup_balance) ? (float)$parent->topup_balance : 0;
    $pay_by_wallet = $parentBalance >= $currentTotal ? '1' : '0';

    $data = [
        'name' => $student->student_name ?? '',
        'qty'  => (string)$qty,
    ];

    return response()->json([
        'status'          => true,
        'message'         => 'Pre-order booked successfully.',
        'data'            => $data,
        'total_dish_qty'  => (int)$total_qty,
        'total_amount'    => number_format($currentTotal, 2, '.', ''),
        'pay_by_wallet'   => (string)$pay_by_wallet,
    ], 200);
}

// addons-updates
public function addons_updates(Request $request)
{
    $parent = auth('parent_api')->user();

    if (!$parent) {
        return response()->json([
            'status' => false,
            'message' => 'User not found or token invalid.',
        ]);
    }

    $request->validate([
        'student_id' => 'required',
        'dish_id'    => 'required',
        'date'       => 'required',
    ]);
	// echo "dfvgv"; die;


    $dish = Dish_Model::find($request->dish_id);
    if (!$dish) {
        return response()->json([
            'status' => false,
            'message' => 'Dish not found.',
        ]);
    }

    $student = SchoolStudent_Model::find($request->student_id);
    if (!$student) {
        return response()->json([
            'status' => false,
            'message' => 'Student not found.',
        ]);
    }

    $qty   = (int)$request->qty;
    $price = (float)$dish->price;
    $total = $qty * $price;

    // ✅ Handle addons
    $addons = $request->addons ?? null;
    if (is_array($addons)) {
        $addons = json_encode($addons);
    }

    $existing = PreOrder_Model::where([
        'student_id'     => $student->id,
        'dish_id'        => $dish->id,
        'date'           => $request->date,
        'payment_status' => 0,
    ])->first();

    if ($existing) {
        // ✅ Update qty + addons
        $existing->qty          += $qty;
        $existing->total_price   = $existing->qty * $price;
        $existing->addons        = $addons;   // 🔥 UPDATE ADDONS
        $existing->save();
    } else {
        $transactionNo = 'SD' . rand(111111, 999999);

        PreOrder_Model::create([
            'transaction_no' => $transactionNo,
            'parent_id'      => $parent->id,
            'student_id'     => $student->id,
            'school_id'      => $parent->school_id,
            'cafeteria_id'   => $dish->cafeteria_id,
            'dish_id'        => $dish->id,
            'date'           => $request->date,
            'qty'            => $qty,
            'dish_price'     => $price,
            'total_price'    => $total,
            'total_amount'   => $total,
            'discount'       => 0,
            'after_discount' => $total,
            'wallet_used'    => 0,
            'payable'        => $total,
            'payment_type'   => 'wallet',
            'payment_status' => 0,
            'addons'         => $addons, // 🔥 SAVE ADDONS
        ]);
    }

    // Totals
    $total_qty = PreOrder_Model::where('parent_id', $parent->id)
        ->where('date', $request->date)
        ->where('payment_status', 0)
        ->sum('qty');

    $currentTotal = PreOrder_Model::where('parent_id', $parent->id)
        ->where('date', $request->date)
        ->where('payment_status', 0)
        ->sum('total_price');

    $parentBalance = (float)($parent->topup_balance ?? 0);
    $pay_by_wallet = $parentBalance >= $currentTotal ? '1' : '0';

    return response()->json([
        'status'         => true,
        'message'        => 'Pre-order booked successfully.',
        'data'           => [
            'name' => $student->student_name ?? '',
            'qty'  => (string)$qty,
        ],
        'total_dish_qty' => (int)$total_qty,
        'total_amount'   => number_format($currentTotal, 2, '.', ''),
        'pay_by_wallet'  => (string)$pay_by_wallet,
    ], 200);
}


// public function pre_order_cart_details(Request $request)
// {
//     $parent = auth('parent_api')->user();

//     if (!$parent) {
//         return response()->json([
//             'status' => false,
//             'message' => 'User not found or token invalid.',
//         ]);
//     }

//     // fetch all unpaid order items for this parent
//     $orderItems = \DB::table('tbl_order_items')
//         ->join('tbl_orders', 'tbl_orders.id', '=', 'tbl_order_items.order_id')
//         ->join('tbl_dish', 'tbl_dish.id', '=', 'tbl_order_items.dish_id')
//         ->join('tbl_school_student', 'tbl_school_student.id', '=', 'tbl_orders.student_id')
//         ->where('tbl_orders.parent_id', $parent->id)
//         ->where('tbl_orders.payment_status', 0)
//         ->select(
//             'tbl_order_items.id as id',
//             'tbl_dish.dish_name',
//             'tbl_order_items.qty',
//             'tbl_school_student.student_name',
//             'tbl_orders.date',
//             'tbl_order_items.dish_price',
//             'tbl_orders.student_id',
//             'tbl_order_items.dish_id'
//         )
//         ->get();

//     $data = [];
//     $totalAmount = 0;

//     foreach ($orderItems as $item) {
//         $pricePerUnit = $item->dish_price ?? 0;
//         $itemTotal = $item->qty * $pricePerUnit;
//         $totalAmount += $itemTotal;

//         $formattedDate = '';
//         if (!empty($item->date)) {
//             try {
//                 $formattedDate = \Carbon\Carbon::parse($item->date)->format('d M Y');
//             } catch (\Exception $e) {
//                 $formattedDate = $item->date;
//             }
//         }

//         $data[] = [
//             'id'           => $item->id,
//             'dish_name'    => $item->dish_name ?? '',
//             'qty'          => $item->qty,
//             'student_name' => $item->student_name ?? '',
//             'date'         => $formattedDate,
//             'total_price'  => number_format($itemTotal, 2),
//             'student_id'   => $item->student_id ?? '',
//             'dish_id'      => $item->dish_id ?? '',
//         ];
//     }

//     $pay_by_wallet = ($parent->topup_balance < $totalAmount) ? '0' : '1';

//     return response()->json([
//         'status'        => true,
//         'message'       => 'Cart Details.',
//         'data'          => $data,
//         'total_amount'  => number_format($totalAmount, 2),
//         'pay_by_wallet' => $pay_by_wallet,
//     ], 200);
// }

public function pre_order_cart_details(Request $request)
{
    $parent = auth('parent_api')->user();

    if (!$parent) {
        return response()->json([
            'status' => false,
            'message' => 'User not found or token invalid.',
        ]);
    }

    $orderItems = PreOrder_Model::with(['dish', 'student'])
        ->where('parent_id', $parent->id)
        ->where('payment_status', 0)
        ->get();



	

    $data = [];
    $totalAmount = 0;

    foreach ($orderItems as $item) {
		// echo "<pre>"; print_r();
        $itemTotal = $item->total_price ?? ($item->qty * $item->dish_price);
        $totalAmount += $itemTotal;

        $formattedDate = '';
        if (!empty($item->date)) {
            try {
                $formattedDate = \Carbon\Carbon::parse($item->date)->format('d M Y');
            } catch (\Exception $e) {
                $formattedDate = $item->date;
            }
        }

        // Handle food_type
       $foodType = '';

		if ($item->dish->food_type) {
			$types = explode(',', $item->dish->food_type);
			$types = array_map(function ($t) {
				return ucfirst(strtolower(trim($t)));
			}, $types);

			$foodType = " : " . implode(', ', $types);
		}

		$addons_bydish = [];

		if (!empty($item->dish->food_type)) {
			$addons_bydish = array_map(function ($t) {
				return ucfirst(strtolower(trim($t)));
			}, explode(',', $item->dish->food_type));
		}

        $data[] = [
            'id'           => $item->id,
            'dish_name'    => $item->dish->dish_name ?? '', 
            'qty'          => $item->qty,
            'student_name' => $item->student->student_name ?? '',
            'date'         => $formattedDate,
            'total_price'  => number_format($itemTotal, 2),
            'student_id'   => $item->student_id ?? '',
            'dish_id'      => $item->dish_id ?? '',
            'selected_addons'      => $item->addons ?? '',
            'addons'     		   => $addons_bydish ?? '',
        ];
    }
	// die;
    $pay_by_wallet = ($parent->topup_balance < $totalAmount) ? '0' : '1';

    return response()->json([
        'status'        => true,
        'message'       => 'Cart Details.',
        'data'          => $data,
        'total_amount'  => number_format($totalAmount, 2),
        'pay_by_wallet' => $pay_by_wallet,
    ], 200);
}



	// public function checkout(Request $request)
	// {
	// 	// echo "teat"; die;

	// 	$parent = auth('parent_api')->user();

	// 	if (!$parent) {
	// 		return response()->json([
	// 			'status' => false,
	// 			'message' => 'User not found or token invalid.',
	// 		]);
	// 	}

	// 	$request->validate([
	// 		'payment_type' => 'required',
	// 	]);

	// 	if ($request->payment_type == 'wallet') {
	// 		$preorders = PreOrder_Model::where('parent_id', $parent->id)
	// 		->where('payment_status', 0)
	// 		->whereNull('payment_type')
	// 		->get();

	// 		$totalAmount = $preorders->sum('total_price');

	// 		$parent->topup_balance -= $totalAmount;
	// 		$parent->save();


	// 		foreach ($preorders as $pre) {

	// 			$checkout = new Checkout_Model;
	// 			$checkout->parent_id = $parent->id;
	// 			$checkout->student_id = $pre->student_id;
	// 			$checkout->school_id = $pre->school_id;
	// 			$checkout->dish_id = $pre->dish_id;
	// 			$checkout->date = $pre->date;
	// 			$checkout->qty = $pre->qty;
	// 			$checkout->dish_price = $pre->dish_price;
	// 			$checkout->total_price = $pre->total_price;
	// 			$checkout->payment_type = $request->payment_type;
	// 			$checkout->payment_status = 1;
	// 			$checkout->save();
	// 		}


	// 		$changestatus = PreOrder_Model::where('parent_id', $parent->id)
	// 			->where('payment_status', 0)
	// 			->get();

	// 		foreach ($changestatus as $change) {
	// 			$change->payment_status = 1;
	// 			$change->payment_type = 'wallet';
	// 			$change->save();
	// 		}
	// 	}

	// 	// $parent->topup_balance =

	// 	return response()->json([
	// 		'status' => true,
	// 		'message' => 'Booked successfully.',
	// 	], 200);
	// }

	public function checkout(Request $request)
{
    $parent = auth('parent_api')->user();

    if (!$parent) {
        return response()->json([
            'status' => false,
            'message' => 'User not found or token invalid.',
        ]);
    }

    $request->validate([
        'payment_type' => 'required',
    ]);

    if ($request->payment_type == 'wallet') {

        $preorders = PreOrder_Model::where('parent_id', $parent->id)
		->where('payment_status', 0)
		->get();
		// echo "<pre"; print_r($preorders); die;
        if ($preorders->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No items available for checkout.',
            ]);
        }

        $totalAmount = $preorders->sum('total_price');

        // Check wallet balance
        if ($parent->topup_balance < $totalAmount) {
            return response()->json([
                'status' => false,
                'message' => 'Insufficient balance in wallet.',
                'required_amount' => number_format($totalAmount, 2),
                'wallet_balance' => number_format($parent->topup_balance, 2),
            ], 400);
        }

        // Deduct wallet balance
        $parent->topup_balance -= $totalAmount;
        $parent->save();

        // Store each preorder in Checkout_Model
        foreach ($preorders as $pre) {
            $checkout = new Checkout_Model;
            $checkout->parent_id = $parent->id;
            $checkout->student_id = $pre->student_id;
            $checkout->school_id = $pre->school_id;
            $checkout->dish_id = $pre->dish_id;
            $checkout->date = $pre->date;
            $checkout->qty = $pre->qty;
            $checkout->dish_price = $pre->dish_price;
            $checkout->total_price = $pre->total_price;
            $checkout->payment_type = $request->payment_type;
            $checkout->payment_status = 1;
            $checkout->save();
        }

        // Update preorder items status
        $changestatus = PreOrder_Model::where('parent_id', $parent->id)
            ->where('payment_status', 0)
            ->get();

        foreach ($changestatus as $change) {
            $change->payment_status = 1;
            $change->payment_type = 'wallet';
            $change->save();
        }
    }

    return response()->json([
        'status' => true,
        'message' => 'Booked successfully.',
    ], 200);
}


	public function student_list_preorders()
	{
		$parent = auth('parent_api')->user();

		if (!$parent) {
			return response()->json([
				'status'  => false,
				'message' => 'User not found or token invalid.',
			]);
		}

		$students = SchoolStudent_Model::where('parent_id', $parent->id)->get();

		$data = [];

		foreach ($students as $student) {
			$resfood = Restrictedfoodbystudent_Model::where('student_id', $student->id)->pluck('name')->toArray();

			$data[] = [
				'id'                 => $student->id,
				'name'               => $student->student_name,
				'admission_no'       => $student->admission_no
			];
		}

		return response()->json([
			'status' => true,
			'data'   => $data,
		]);
	}

// public function dish_decrease(Request $request)
// {
//     $parent = auth('parent_api')->user();

//     if (!$parent) {
//         return response()->json([
//             'status' => false,
//             'message' => 'User not found or token invalid.',
//         ]);
//     }

//     $request->validate([
//         'student_id' => 'required',
//         'dish_id'    => 'required',
//         'date'       => 'required',
//     ]);

//     // Find unpaid preorder entry for this parent+student+dish+date
//     $preOrder = PreOrder_Model::where('parent_id', $parent->id)
//         ->where('student_id', $request->student_id)
//         ->where('dish_id', $request->dish_id)
//         ->where('date', $request->date)
//         ->where('payment_status', 0)
//         ->first();

//     if (!$preOrder) {
//         return response()->json([
//             'status'  => false,
//             'message' => 'Dish not found in pre-order for this student/date.',
//         ]);
//     }

//     // Decrease qty
//     $preOrder->qty = $preOrder->qty - 1;

//     if ($preOrder->qty <= 0) {
//         $preOrder->delete();
//         $qtyAfterUpdate = 0;
//     } else {
//         // Recalculate total_price and all amount fields
//         $newTotal = $preOrder->qty * $preOrder->dish_price;
//         $preOrder->total_price   = $newTotal;
//         // $preOrder->total_amount  = $newTotal;
//         // $preOrder->after_discount = $newTotal;
//         // $preOrder->payable = $newTotal;
//         $preOrder->save();
//         $qtyAfterUpdate = $preOrder->qty;
//     }

//     $total_qty = PreOrder_Model::where('parent_id', $parent->id)
//         ->where('date', $request->date)
//         ->where('payment_status', 0)
//         ->sum('qty');

//     $totalAmount = PreOrder_Model::where('parent_id', $parent->id)
//         ->where('date', $request->date)
//         ->where('payment_status', 0)
//         ->sum('total_price');

//     // Wallet rule
//     $pay_by_wallet = ($parent->topup_balance < $totalAmount) ? '0' : '1';

//     return response()->json([
//         'status'         => true,
//         'message'        => ($qtyAfterUpdate == 0 ? 'Dish removed from order (qty was 0).' : 'Dish quantity decreased successfully.'),
//         'qty'            => $qtyAfterUpdate,
//         'total_dish_qty' => (int)$total_qty,
//         'total_amount'   => number_format($totalAmount, 2),
//         'pay_by_wallet'  => $pay_by_wallet,
//     ], 200);
// }


public function dish_decrease(Request $request)
{
    $parent = auth('parent_api')->user();

    if (!$parent) {
        return response()->json([
            'status' => false,
            'message' => 'User not found or token invalid.',
        ]);
    }

    $request->validate([
        'student_id' => 'required',
        'dish_id'    => 'required',
        'date'       => 'required',
    ]);

    // Find unpaid preorder entry for this parent+student+dish+date
    $preOrder = PreOrder_Model::where('parent_id', $parent->id)
        ->where('student_id', $request->student_id)
        ->where('dish_id', $request->dish_id)
        ->where('date', $request->date)
        ->where('payment_status', 0)
        ->first();

    if (!$preOrder) {
        return response()->json([
            'status'  => false,
            'message' => 'Dish not found in pre-order for this student/date.',
        ]);
    }

    // Decrease quantity
    $preOrder->qty = $preOrder->qty - 1;

    if ($preOrder->qty <= 0) {
        $preOrder->delete();
        $qtyAfterUpdate = 0;
    } else {
        // Recalculate total_price
        $newTotal = $preOrder->qty * $preOrder->dish_price;
        $preOrder->total_price = $newTotal;
        $preOrder->save();
        $qtyAfterUpdate = $preOrder->qty;
    }

    // Calculate total quantity & amount based on student_id (no date filter)
    $total_qty = PreOrder_Model::where('parent_id', $parent->id)
        ->where('student_id', $request->student_id)
        ->where('payment_status', 0)
        ->sum('qty');

    $totalAmount = PreOrder_Model::where('parent_id', $parent->id)
        ->where('student_id', $request->student_id)
        ->where('payment_status', 0)
        ->sum('total_price');

    // Check if parent can pay from wallet
    $pay_by_wallet = ($parent->topup_balance < $totalAmount) ? '0' : '1';

    return response()->json([
        'status'         => true,
        'message'        => ($qtyAfterUpdate == 0 ? 'Dish removed from order (qty was 0).' : 'Dish quantity decreased successfully.'),
        'qty'            => $qtyAfterUpdate,
        'total_dish_qty' => (int)$total_qty,
        'total_amount'   => number_format($totalAmount, 2),
        'pay_by_wallet'  => $pay_by_wallet,
    ], 200);
}


//////////////////////////////////////////////////////--Parent History---/////////////////////////////////////

	public function credit_transfer_history(Request $request)
	{
		$parent = auth('parent_api')->user();

		if (!$parent) {
			return response()->json([
				'status' => false,
				'message' => 'User not found or token invalid.',
			]);
		}

		$studentId = (int) $request->id;

		if ($studentId !== 0) {
			// Filter by specific student ID
			$students = SchoolStudent_Model::where('parent_id', $parent->id)
						->where('id', $studentId)
						->get();
		} else {
			// Get all students for parent
			$students = SchoolStudent_Model::where('parent_id', $parent->id)->get();
		}

		$history = [];

		foreach ($students as $stud) {
			$credits = StudentCredit_Model::where('student_id', $stud->id)->get();

			foreach ($credits as $credit) {
				$history[] = [
					'student_id'     => $credit->student_id,
					'name'           => $stud->student_name,
					'amount'         => $credit->amount ?? null,
					'transferred_at' => $credit->created_at->format('Y-M-d h:i:A'),
				];
			}
		}

		return response()->json([
			'status'  => true,
			'message' => 'Credit transfer history fetched successfully.',
			'data'    => $history,
		]);
	}

	public function pre_order_history(Request $request)
	{
		$parent = auth('parent_api')->user();

		if (!$parent) {
			return response()->json([
				'status'  => false,
				'message' => 'User not found or token invalid.',
			]);
		}

		$studentId = (Int) $request->id;

		$query = PreOrder_Model::where('parent_id', $parent->id)->orderby('id','DESC');

		if (!empty($studentId) && $studentId != 0) {
			$query->where('student_id', $studentId);
		}

		$preorders = $query->get();
		$history = [];
		

		foreach ($preorders as $pre) {
			$student = SchoolStudent_Model::find($pre->student_id);
			$dish = Dish_Model::find($pre->dish_id);

		// echo "<pre>"; print_r($pre); die;

			$history[] = [
				'Ordered_On'     => $pre->date,
				'Student_Name'   => $student ? $student->student_name : 'N/A',
				'Dish_Name' => ($dish ? $dish->dish_name : 'N/A') . ' : ' . ($pre->addons ?? ''),
				'Payment_Status' => (string)$pre->payment_status,
				'Status_Color'   => $pre->payment_status == 1 ? '#28a745' : '#dc3545',
				'qty'            => 'x' . $pre->qty,
				'Dish_Price'     => $pre->dish_price . ' QAR',
				'Payment'        => $pre->payment_type . ' (QAR ' . $pre->total_price . ')',
				'Date'           => $pre->created_at->format('Y-M-d h:i:A'),
				'calories'		 => $dish->calories,
				'protein'		 => $dish->protein,
				'carbohydrates'	 => $dish->carbohydrates,
				'fats'			 => $dish->fats,

			];
		}

		return response()->json([
			'status'  => true,
			'message' => 'Pre-order history fetched successfully.',
			'data'    => $history,
		]);
	}
	
	
	public function consumptions_history(Request $request){
		
		$parent = auth('parent_api')->user();

		if (!$parent) {
			return response()->json([
				'status'  => false,
				'message' => 'User not found or token invalid.',
			]);
		}
		$studentId = (Int) $request->id;

		$query = PreOrder_Model::where('parent_id', $parent->id);

		if (!empty($studentId) && $studentId != 0) {
			$query->where('student_id', $studentId);
		}

		$preorders = $query->get();
		$consumptions = [];		

		foreach ($preorders as $pre) {
			$student = SchoolStudent_Model::find($pre->student_id);
			$dish = Dish_Model::find($pre->dish_id);

			$consumptions[] = [
				'Ordered_On'     => $pre->date,
				'Student_Name'   => $student ? $student->student_name : 'N/A',
				'Dish_Name'      => $dish ? $dish->dish_name : 'N/A',
				'Payment_Status' => (string)$pre->payment_status,
				'Status_Color'   => $pre->payment_status == 1 ? '#28a745' : '#dc3545',
				'qty'            => 'x' . $pre->qty,
				'Dish_Price'     => $pre->dish_price . ' QAR',
				'Payment'        => $pre->payment_type . ' (QAR ' . $pre->total_price . ')',
				'Date'           => $pre->created_at->format('Y-M-d h:i:A'),
			];
		}

		return response()->json([
			'status'  => true,
			'message' => 'Consumptions history fetched successfully.',
			'data'    => $consumptions,
		]);
	}



	public function updateEmail(Request $request)
	{
		$parent = auth('parent_api')->user();

		if (!$parent) {
			return response()->json([
				'status' => false,
				'message' => 'User not found or token invalid.',
			]);
		}

		$request->validate([
			'email' => 'required',
		]);

		$parent->email = $request->email;
		$parent->save();

		return response()->json([
			'status' => true,
			'message' => 'Email updated successfully.',
		]);
	}



	public function deleteaccount()
	{
		$parent = auth('parent_api')->user();

		if (!$parent) {
			return response()->json([
				'status' => false,
				'message' => 'User not found or token invalid.',
			]);
		}


		return response()->json([
			'status' => true,
			'message' => 'Account Deleted Successfully.',
		]);
	}


	public function wallettransaction()
	{
		$parent = auth('parent_api')->user();

		if (!$parent) {
			return response()->json([
				'status' => false,
				'message' => 'User not found or token invalid.',
			]);
		}

		$transactions = [];

		// 1. ParentTopup_Model transactions
		$topups = ParentTopup_Model::where('parent_id', $parent->id)->get();
		foreach ($topups as $topup) {
			$transactions[] = [
				'date'    => $topup->created_at->format('d-M-Y h:i A'),
				'amount'  => (string) $topup->amount,
				'color_amount'   => '#FF0000',
				'message' => 'Top up into wallet',
			];
		}

		// 2. Get student IDs for this parent using SchoolStudent_Model
		$studentIDs = SchoolStudent_Model::where('parent_id', $parent->id)->pluck('id');

		// 3. StudentCredit_Model transactions
		$credits = StudentCredit_Model::whereIn('student_id', $studentIDs)->get();
		foreach ($credits as $credit) {
			$transactions[] = [
				'date'    => $credit->created_at->format('d-M-Y h:i A'),
				'amount'  => (string) $credit->amount,
				'color_amount'   => '#FF0000',
				'message' => 'Transaction in student wallet',
			];
		}

		// 4. PreOrder_Model transactions
		$preorders = PreOrder_Model::where('parent_id', $parent->id)->get();
		foreach ($preorders as $pre) {
			$transactions[] = [
				'date'    => $pre->created_at->format('d-M-Y h:i A'),
				'amount'  => (string) $pre->total_price,
				'color_amount'   => '#FF0000',
				'message' => 'Paid for Pre order',
			];
		}

		// 5. Sort all transactions by date descending
		usort($transactions, function ($a, $b) {
			return strtotime($b['date']) <=> strtotime($a['date']);
		});

		return response()->json([
			'status'  => true,
			'message' => 'Wallet transactions fetched successfully.',
			'data'    => $transactions,
		]);
	}
	
	
	
	/////////////////--child to child transfer --//////////////
	
	
	public function child_money_transfer(Request $request)
{
    $parent = auth('parent_api')->user();

    if (!$parent) {
        return response()->json([
            'status' => false,
            'message' => 'User not found or token invalid.',
        ]);
    }

    $student_senderID = (int) $request->student_senderID;
    $student_reciverID = (int) $request->student_reciverID;
    $money = (float) $request->money;

    if ($student_senderID === $student_reciverID) {
        return response()->json([
            'status' => false,
            'message' => 'Sender and receiver cannot be the same student.',
        ]);
    }

    $sender = SchoolStudent_Model::where('parent_id', $parent->id)
        ->where('id', $student_senderID)
        ->first();

    $receiver = SchoolStudent_Model::where('parent_id', $parent->id)
        ->where('id', $student_reciverID)
        ->first();

    if (!$sender || !$receiver) {
        return response()->json([
            'status' => false,
            'message' => 'Both students must belong to the same parent.',
        ]);
    }

    if ($sender->wallet_balance < $money) {
        return response()->json([
            'status' => false,
            'message' => 'Insufficient balance in sender’s wallet.',
        ]);
    }
    DB::beginTransaction();
    try {
        $sender->wallet_balance -= $money;
        $sender->save();

        $receiver->wallet_balance += $money;
        $receiver->save();

        DB::commit();

        return response()->json([
            'status' => true,
            'message' => 'Money transferred successfully.',
            'sender_balance' => $sender->wallet_balance,
            'receiver_balance' => $receiver->wallet_balance
        ]);
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'status' => false,
            'message' => 'Transfer failed. ' . $e->getMessage(),
        ]);
    }
}

	

////////////////////////////////--Pay for services--/////////////////////////////

	public function listPlans(Request $request)
	{

		$parent = auth('parent_api')->user();


		if (!$parent) {
			return response()->json([
				'status' => false,
				'message' => 'User not found or token invalid.',
			]);
		}

		$cafe_detail = Cafeteria_Model::where('school_id',$parent->school_id)->first();

		$plans = Plan_Model::where('cafeteria_id',$cafe_detail->id)
			->where('active', 1)
			->get();

		return response()->json([
			'success' => true,
			'data' => $plans
		]);
	}


	 public function subscribe(Request $request)
    {

		$parent = auth('parent_api')->user();

		// echo "<pre>"; print_r($parent); die;

		
		if (!$parent) {
			return response()->json([
				'status' => false,
				'message' => 'User not found or token invalid.',
			]);
		}

		$cafe_detail = Cafeteria_Model::where('school_id',$parent->school_id)->first();


        $request->validate([
            'plan_id' => 'required|integer',
            'student_id' => 'required|integer',
            'payment_status' => 'required|string' 
        ]);

        $plan = Plan_Model::find($request->plan_id);
        if (!$plan) {
            return response()->json(['success' => false, 'message' => 'Plan not found']);
        }

		$topup_balance = $parent->topup_balance;
		$final_amt = $plan->price;

		// 1️⃣ Not enough balance
		if ($topup_balance < $final_amt) {
			return response()->json([
				'success' => false,
				'message' => 'Insufficient balance. Please top-up your wallet.'
			]);
		}

		 // 2️⃣ Deduct balance (>= case)
		$new_balance = $topup_balance - $final_amt;

		// Update parent balance
		$parent->topup_balance = $new_balance;
		$parent->save();

        DB::beginTransaction();

        try {
           $startDate = Carbon::now();
		   $endDate = Carbon::now()->addDays($plan->duration_days - 1); 


            $subscription = Subscription_Model::create([
                'parent_id' => $parent->id,
                'student_id' => $request->student_id,
                'school_id' => $parent->school_id,
                'cafeteria_id' => $cafe_detail->id,
                'plan_id' => $request->plan_id,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'duration_days' => $plan->duration_days,
                'price' => $plan->price,
                'status' => 'active',
                'remaining_days' => $plan->duration_days,
                'paused_days_count' => 0,
                'auto_renew' => $plan->auto_renew,
                'payment_status' => $request->payment_status,
            ]);


			


            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Subscription created successfully',
                'data' => $subscription
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }


	// public function mySubscriptions()
    // {
		
	// 	$parent = auth('parent_api')->user();


	// 	if (!$parent) {
	// 		return response()->json([
	// 			'status' => false,
	// 			'message' => 'User not found or token invalid.',
	// 		]);
	// 	}


	// 		$subs = Subscription_Model::with(['plan', 'student'])
	// 			->where('parent_id', $parent->id)
	// 			->orderBy('id', 'desc')
	// 			->get();

	// 		return response()->json([
	// 			'success' => true,
	// 			'data' => $subs
	// 		]);
    // }


// public function mySubscriptions()
// {
//     $parent = auth('parent_api')->user();

//     if (!$parent) {
//         return response()->json([
//             'status' => false,
//             'message' => 'User not found or token invalid.',
//         ]);
//     }

//     $subs = Subscription_Model::with(['plan', 'student'])
//         ->where('parent_id', $parent->id)
//         ->orderBy('id', 'desc')
//         ->get()
//         ->map(function ($subscription) {

//             $today = Carbon::now()->startOfDay();
//             $startDate = Carbon::parse($subscription->start_date)->startOfDay();
//             $endDate = Carbon::parse($subscription->end_date)->startOfDay();

//             $totalDays = $startDate->diffInDays($endDate) + 1;

//             if ($today->lt($startDate)) {
//                 // Not started yet
//                 $remaining = $totalDays;
//                 $status = 'upcoming';
//                 $daysUntilStart = $today->diffInDays($startDate);
//                 $note = "Starts in {$daysUntilStart} days";

//             } elseif ($today->between($startDate, $endDate)) {
//                 // Active plan
//                 $remaining = $today->diffInDays($endDate, false) + 1; // ✅ include today
//                 $remaining = max(0, $remaining);
//                 $status = 'active';
//                 $note = "Active — {$remaining} days left";

//             } else {
//                 // Expired plan
//                 $remaining = 0;
//                 $status = 'expired';
//                 $note = "Expired on {$endDate->format('d M Y')}";
//             }

//             // Assign calculated values
//             $subscription->total_days = $totalDays;
//             $subscription->remaining_days_dynamic = $remaining;
//             $subscription->status_dynamic = $status;
//             $subscription->note = $note;

//             return $subscription;
//         });

//     return response()->json([
//         'success' => true,
//         'data' => $subs
//     ]);
// }

// public function mySubscriptions(Request $request)
// {
//     $parent = auth('parent_api')->user();

//     if (!$parent) {
//         return response()->json([
//             'status' => false,
//             'message' => 'User not found or token invalid.',
//         ]);
//     }

//     $studentId = $request->student_id ?? 0;

//     $query = Subscription_Model::with(['plan', 'student'])
//         ->where('parent_id', $parent->id);

//     if ($studentId > 0) {
//         $query->where('student_id', $studentId);
//     }

//     $subs = $query->orderBy('id', 'desc')
//         ->get()
//         ->map(function ($subscription) {
//             $today = Carbon::now()->startOfDay();
//             $startDate = Carbon::parse($subscription->start_date)->startOfDay();
//             $endDate = Carbon::parse($subscription->end_date)->startOfDay();

//             $totalDays = $startDate->diffInDays($endDate) + 1;
//             $remaining = 0;
//             $status = '';
//             $note = '';

//             if ($today->lt($startDate)) {
//                 $remaining = $totalDays;
//                 $status = 'upcoming';
//                 $daysUntilStart = $today->diffInDays($startDate);
//                 $note = "Starts in {$daysUntilStart} days";
//             } elseif ($today->between($startDate, $endDate)) {
//                 $remaining = $today->diffInDays($endDate) + 1;
//                 $status = 'active';
//                 $note = "Active — {$remaining} days left";
//             } else {
//                 $status = 'expired';
//                 $note = "Expired on {$endDate->format('d M Y')}";
//             }

//             // ❗ Check if paused today
//             $pausedToday = SubscriptionPause_Model::where('subscription_id', $subscription->id)
//                 ->whereDate('pause_date', $today)
//                 ->exists();

//             return [
//                 'id' => $subscription->id,
//                 'student_id' => $subscription->student_id,
//                 'plan_id' => $subscription->plan_id,
//                 'start_date' => $subscription->start_date,
//                 'end_date' => $subscription->end_date,
//                 'duration_days' => $totalDays,
//                 'price' => $subscription->price,
//                 'status' => $status,
//                 'renew' => $subscription->auto_renew,
//                 'remaining_days' => $remaining,
//                 'paused_days_count' => $subscription->paused_days_count,
//                 'note' => $note,
//                 'student_name' => $subscription->student->student_name ?? '',
//                 'student_dob' => $subscription->student->dob ?? '',
//                 'paused' => $pausedToday ? 1 : 0,
//             ];
//         });

//     return response()->json([
//         'success' => true,
//         'data' => $subs
//     ]);
// }

public function mySubscriptions(Request $request)
{
    $parent = auth('parent_api')->user();

    if (!$parent) {
        return response()->json([
            'status' => false,
            'message' => 'User not found or token invalid.',
        ]);
    }

    $studentId = $request->student_id ?? 0;

    $query = Subscription_Model::with(['plan', 'student'])
        ->where('parent_id', $parent->id);

    if ($studentId > 0) {
        $query->where('student_id', $studentId);
    }

    $subs = $query->orderBy('id', 'desc')
        ->get()
        ->map(function ($subscription) {

            $today = Carbon::now()->startOfDay();
            $startDate = Carbon::parse($subscription->start_date)->startOfDay();
            $endDate = Carbon::parse($subscription->end_date)->startOfDay();

            $totalDays = $startDate->diffInDays($endDate) + 1;
            $remaining = 0;
            $status = '';
            $note = '';

            // Get all pause dates for this subscription
            $pausedDates = SubscriptionPause_Model::where('subscription_id', $subscription->id)
                ->orderBy('pause_date', 'asc')
                ->pluck('pause_date')
                ->map(function($d){
                    return Carbon::parse($d)->format('Y-m-d');
                })
                ->toArray();

            if ($today->lt($startDate)) {
                $remaining = $totalDays;
                $status = 'upcoming';
                $daysUntilStart = $today->diffInDays($startDate);
                $note = "Starts in {$daysUntilStart} days";

            } elseif ($today->between($startDate, $endDate)) {
                $remaining = $today->diffInDays($endDate) + 1;
                $status = 'active';
                $note = "Active — {$remaining} days left";

            } else {
                $status = 'expired';
                $note = "Expired on {$endDate->format('d M Y')}";
            }

            // ✔️ Append paused dates to note (if exist)
            if (!empty($pausedDates)) {
                $note .= ", paused dates: " . implode(',', $pausedDates);
            }

            // Check paused today
            $pausedToday = in_array($today->format('Y-m-d'), $pausedDates);

            return [
                'id' => $subscription->id,
                'student_id' => $subscription->student_id,
                'plan_id' => $subscription->plan_id,
                'start_date' => $subscription->start_date,
                'end_date' => $subscription->end_date,
                'duration_days' => $totalDays,
                'price' => $subscription->price,
                'status' => $status,
                'renew' => $subscription->auto_renew,
                'remaining_days' => $remaining,
                'paused_days_count' => $subscription->paused_days_count,
                'note' => $note, // UPDATED
                'student_name' => $subscription->student->student_name ?? '',
                'student_dob' => $subscription->student->dob ?? '',
                'paused' => $pausedToday ? 1 : 0,
            ];
        });

    return response()->json([
        'success' => true,
        'data' => $subs
    ]);
}





public function pauseSubscription(Request $request)
{
	
	$parent = auth('parent_api')->user();


	if (!$parent) {
		return response()->json([
			'status' => false,
			'message' => 'User not found or token invalid.',
		]);
	}

	$request->validate([
		'subscription_id' => 'required|integer',
		'pause_date' => 'required|date',
		'reason' => 'nullable|string'
	]);

	$subscription = Subscription_Model::find($request->subscription_id);
	if (!$subscription) {
		return response()->json(['success' => false, 'message' => 'Subscription not found']);
	}

	$pauseDate = Carbon::parse($request->pause_date);
	$now = Carbon::now();

	if ($pauseDate->isToday() && $now->greaterThan($now->copy()->setTime(14, 0))) {
		return response()->json(['success' => false, 'message' => 'Cannot pause after 6 AM today']);
	}

	$exists = SubscriptionPause_Model::where('subscription_id', $subscription->id)
		->where('pause_date', $pauseDate->format('Y-m-d'))
		->exists();

	if ($exists) {
		return response()->json(['success' => false, 'message' => 'Already paused for this date']);
	}

	SubscriptionPause_Model::create([
		'subscription_id' => $subscription->id,
		'pause_date' => $pauseDate->format('Y-m-d'),
		'requested_by' =>$parent->id,
		'requested_at' => now(),
		'status' => 'approved',
		'reason' => $request->reason
	]);

	$subscription->end_date = Carbon::parse($subscription->end_date)->addDay()->format('Y-m-d');
	$subscription->paused_days_count += 1;
	$subscription->save();

	return response()->json([
		'success' => true,
		'message' => 'Subscription paused and extended successfully'
	]);
}



// 	public function renewSubscription(Request $request)
// {
//     $parent = auth('parent_api')->user();

//     if (!$parent) {
//         return response()->json([
//             'success' => false,
//             'message' => 'User not found',
//         ]);
//     }

//     $subscription = Subscription_Model::with('plan')
//         ->where('id', $request->subscription_id)
//         ->where('parent_id', $parent->id)
//         ->first();

//     if (!$subscription) {
//         return response()->json([
//             'success' => false,
//             'message' => 'Subscription not found',
//         ]);
//     }

//     $plan = $subscription->plan;
// 	$subscription_count = $subscription->subscription_count;
// 	$subscription_count_add = $subscription_count + 1;

//     if (!$plan) {
//         return response()->json([
//             'success' => false,
//             'message' => 'Plan data missing',
//         ]);
//     }

//     $topup_balance = $parent->topup_balance;  
//     $renew_amount = $plan->price;

//     if ($topup_balance < $renew_amount) {
//         return response()->json([
//             'success' => false,
//             'message' => 'Insufficient wallet balance. Please top-up.',
//         ]);
//     }

//     $today = Carbon::now()->startOfDay();
//     $end    =Carbon::parse($subscription->end_date)->startOfDay();

//     if ($today <= $end) {
//         $newStart = $end->copy()->addDay();
//     } else {
//         $newStart = $today;
//     }

//     $newEnd = $newStart->copy()->addDays($plan->duration - 1);

//     $subscription->start_date             = $newStart->format('Y-m-d');
//     $subscription->end_date   			  = $newEnd->format('Y-m-d');
//     $subscription->auto_renew      		  = 1;
//     $subscription->paused_days_count      = 0;
//     $subscription->subscription_count     = $subscription_count_add;
//     $subscription->status                 = 'active';
//     $subscription->save();

	
//     $parent->topup_balance = $topup_balance - $renew_amount;
//     $parent->save();

//     return response()->json([
//         'success' => true,
//         'message' => 'Plan renewed successfully',
//         'wallet_balance' => $parent->topup_balance,
//         'subscription' => $subscription,
//     ]);
// }


public function renewSubscription(Request $request)
{
    $parent = auth('parent_api')->user();

    if (!$parent) {
        return response()->json([
            'success' => false,
            'message' => 'User not found',
        ]);
    }

    $subscription = Subscription_Model::with('plan')
        ->where('id', $request->subscription_id)
        ->where('parent_id', $parent->id)
        ->first();

    if (!$subscription) {
        return response()->json([
            'success' => false,
            'message' => 'Subscription not found',
        ]);
    }

    $plan = $subscription->plan;

    if (!$plan) {
        return response()->json([
            'success' => false,
            'message' => 'Plan data missing',
        ]);
    }

    $topup_balance = $parent->topup_balance;  
    $renew_amount = $plan->price;

    if ($topup_balance < $renew_amount) {
        return response()->json([
            'success' => false,
            'message' => 'Insufficient wallet balance. Please top-up.',
        ]);
    }

    $today = Carbon::now()->startOfDay();
    $end   = Carbon::parse($subscription->end_date)->startOfDay();

    if ($today <= $end) {
        // running plan — do NOT renew
        return response()->json([
            'success' => false,
            'message' => 'Plan is still active. You can renew only after it ends.',
            'subscription_end_date' => $subscription->end_date
        ]);
    }

    $newStart = $today;
    $newEnd   = $newStart->copy()->addDays($plan->duration_days - 1);

    $subscription->start_date        	= $newStart->format('Y-m-d');
    $subscription->end_date          	= $newEnd->format('Y-m-d');
	$subscription->auto_renew      	 	= 1;
    $subscription->paused_days_count 	= 0;
    $subscription->subscription_count 	= $subscription->subscription_count + 1;
    $subscription->status            	= 'active';
    $subscription->save();

    /** ---------- DEDUCT WALLET ---------- **/
    $parent->topup_balance = $topup_balance - $renew_amount;
    $parent->save();

    return response()->json([
        'success' => true,
        'message' => 'Plan renewed successfully',
        'wallet_balance' => $parent->topup_balance,
        'subscription' => $subscription,
    ]);
}


///////////////////////--Calling Chatbot--////////////////////////////////

	public function parent_list_ai()
	{
		// Get all parents
		$parents = SchoolParent_Model::get();

		if ($parents->isEmpty()) {
			return response()->json([
				'status'  => false,
				'message' => 'No parents found.',
			], 404);
		}

		$data = [];

		foreach ($parents as $parent) {
			$data[] = [
				'id'     => $parent->id,
				'name'   => $parent->name,
				'mobile' => $parent->mobile,
				'email'  => $parent->email,
			];
		}

		return response()->json([
			'status' => true,
			'data'   => $data,
		]);
	}


	public function parent_ByID(Request $request)
	{
		// Validate request
		$request->validate([
			'id' => 'required|integer'
		]);

		// Find parent by ID
		$parent = SchoolParent_Model::find($request->id);

		if (!$parent) {
			return response()->json([
				'status'  => false,
				'message' => 'Parent not found.',
			], 404);
		}

		$data = [
			'id'     => $parent->id,
			'name'   => $parent->name,
			'mobile' => $parent->mobile,
			'email'  => $parent->email,
		];

		return response()->json([
			'status' => true,
			'data'   => $data,
		]);
	}




	public function student_list_ai(Request $request)
{
    // Get parent ID from request
    $parentId = $request->input('id');

    if (!$parentId) {
        return response()->json([
            'status'  => false,
            'message' => 'Parent ID is required.',
        ], 400);
    }

    // Find parent by ID
    $parent = SchoolParent_Model::find($parentId);

    if (!$parent) {
        return response()->json([
            'status'  => false,
            'message' => 'Parent not found.',
        ], 404);
    }

    // Get all students for this parent
    $students = SchoolStudent_Model::where('parent_id', $parent->id)->get();

    $data = [];

    foreach ($students as $student) {
        $data[] = [
            'id'                => $student->id,
            'name'              => $student->student_name,
            'grade'             => $student->grade,
            'gender'            => $student->gender,
            'dob'               => $student->dob,
            'daily_spend_limit' => $student->spend_limit,
            'wallet_balance'    => $student->wallet_balance,
        ];
    }

    return response()->json([
        'status' => true,
        'data'   => $data,
    ]);
}




	
	public function items_list_ai(Request $request)
{
    // Get parent ID from request
    $parentId = $request->input('id');

    if (!$parentId) {
        return response()->json([
            'status'  => false,
            'message' => 'Parent ID is required.',
        ], 400);
    }

    // Find parent by ID
    $parent = SchoolParent_Model::find($parentId);

    if (!$parent) {
        return response()->json([
            'status'  => false,
            'message' => 'Parent not found.',
        ], 404);
    }

    // Find cafeteria for this parent's school
    $cafeteria = Cafeteria_Model::where('school_id', $parent->school_id)->first();

    if (!$cafeteria) {
        return response()->json([
            'status'  => false,
            'message' => 'Cafeteria not found for this school.',
        ], 404);
    }

    // Get all dishes for this cafeteria
    $dishes = Dish_Model::where('cafeteria_id', $cafeteria->id)->get();

    $data = [];

    foreach ($dishes as $dish) {
        $data[] = [
            'id'             => $dish->id,
            'name'           => $dish->dish_name,
            'description'    => $dish->description,
            'price'          => $dish->price,
            'food_type'      => $dish->food_type,
            'serving_of'     => $dish->serving_of,
            'calories'       => $dish->calories,
            'protein'        => $dish->protein,
            'carbohydrates'  => $dish->carbohydrates,
        ];
    }

    return response()->json([
        'status' => true,
        'data'   => $data,
    ]);
}


 /////////////////////////--END--//////////////////////////////
}



