<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Http\Requests\AdminProfileRequest;
use App\Models\User;




class ProfileController extends Controller
{
    public function __construct()
    {
       $this->middleware('auth');
      
    }
    public function index()
    {
        return view('admin.profile');
    }


    public function changePassword(AdminProfileRequest $request)
    {
        $user = Auth::user();

    $hashedPassword = Hash::make($request->password);

    $user->update(['password' => $hashedPassword]);

    return redirect()->back()->with('success', 'Password updated successfully.');


       
    }
    
}
