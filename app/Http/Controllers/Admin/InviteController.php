<?php
namespace App\Http\Controllers\Admin;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
///////--Models--///////////

use App\Models\School_Model;
use App\Models\SchoolUser_Model;
use App\Models\SchoolParent_Model;
use App\Models\SchoolStudent_Model;

use App\Models\User;


use Illuminate\Support\Facades\Mail;
use App\Mail\SchoolUserInviteMail; 



class InviteController extends Controller
{ 
    public function showSignup(Request $request)
    {
        $code = $request->get('id');
        $user = User::where('invite_code', $code)->first();

        if (!$user) {
            return redirect('/')->with('error', 'Invalid invite code.');
        }

        return view('auth.register', ['user' => $user]);

    }



      public function invite_users_store(Request $request)
    {
        
        $data = $request->validate([
            'invite_code'          => 'required',
            'email'                => 'required|email',
            'name'                 => 'required|string|max:255',
            'password'             => 'required|min:8|confirmed',
        ]);

        $user = User::where('email', $data['email'])->firstOrFail();

        $user->update([
            'email'        => $data['email'],
            'name'         => $data['name'],
            'password'     => Hash::make($data['password']),
            'invite_code'  => $data['invite_code'],   
        ]);

        Auth::login($user);

        return redirect()->route('admin')
                        ->with('success', 'Account created successfully.');
    }
	
	
	public function parentshowSignup(Request $request)
	{
		$id = $request->input('id');
		return view('admin.parent.signup');
	}
      
}
