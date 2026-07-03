<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\BannerModel;


class HomeController extends Controller
{

    public function __construct()
    {
    
    }
       public function home()
    {
       
        return view('frontend.index');
    }

}
