<?php

namespace App\Http\Controllers;
use App\Models\Product\Cart_Model;
use Illuminate\Support\Facades\Session;  // Import session facade if not already imported

use Illuminate\Http\Request;

use App\Models\Category\MainCategory;
use App\Models\Category\SubCategory;
use App\Models\Category\SubSubCategory;
use App\Models\Color\Color_Model;
use App\Models\Currency\Currency_Model;
use App\Models\Product\Product_Model;
use App\Models\Product\Product_varient;
use App\Models\Users\User_Model;
use Illuminate\Support\Facades\Hash;
use App\Models\Product\Order_Model_Detail;
use App\Models\Product\Order_Model;


class FrontendController extends Controller
{

    public function index()
    {
    //    echo "test"; die;

       return view('welcome');
    }


//////////////////////////-End here-/////////////////////////////

}
