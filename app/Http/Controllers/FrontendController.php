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
use App\Models\Product\Whishlist_Model;
use Illuminate\Support\Facades\DB;

class FrontendController extends Controller
{

    public function index()
    {
       

        $data = app('categories');
        $userId = Session::get('userid'); 

        $conditions = [
            'all' => ['status' => '1','sub_cat_id' => 1], 
            'men' => ['status' => '1','sub_cat_id' => 1, 'sub_sub_cat_id' => 1], 
            'women' => ['status' => '1','sub_cat_id' => 1, 'sub_sub_cat_id' => 2], 
        ];
        
        $productData = []; 
        
        foreach ($conditions as $key => $condition) {
            $products = Product_Model::where($condition)->get();
        
            $productLedger = []; 
        
            foreach ($products as $product) {
                $variants = Product_varient::where('product_sub_id', $product->id)->get();
        
                $variantDetails = []; 
                foreach ($variants as $variant) {
                    $colorVariant = Color_Model::find($variant->color_value);
        
                    $variantDetails[] = [
                        'varient_id' => $variant->id,
                        'product_id' => $variant->product_sub_id,
                        'color_value' => $variant->color_value,
                        'quantity' => $variant->quantity,
                        'price' => $variant->price,
                        'pro_images' => $variant->pro_images,
                        'color_name' => $colorVariant ? $colorVariant->color_name : 'N/A',
                    ];
                }
        
                $productLedger[] = [
                    'product_id' => $product->id,
                    'cat_id' => $product->cat_id,
                    'sub_cat_id' => $product->sub_cat_id,
                    'sub_sub_cat_id' => $product->sub_sub_cat_id,
                    'prod_name' => $product->prod_name,
                    'lense' => $product->lense,
                    'discount' => $product->discount,
                    'variants' => $variantDetails,
                ];
            }
        
            $productData[$key] = $productLedger;
        }
        
        $data = app('categories');
        $data['products'] = $productData['all'];
        $data['productsmen'] = $productData['men']; 
        $data['productswomen'] = $productData['women']; 



            
    //    $productLedgerArray = collect($productLedger)->toArray();
    
    //    dd($productLedgerArray);

    $data['subsubsmall'] = SubSubCategory::whereIn('id', [15, 16, 17])->get();
    $data['categorytwo'] = MainCategory::whereIn('id', [1,2])->get();
    $data['cartCount'] = Cart_Model::where('userid', $userId)->where('view', 1)->count();

    // $data['currency'] = Currency_Model::get();

    /////////////////////////---Tranding---//////////////////////////////

        return view('frontend.index', $data);
    }


    public function productDetail($id)
    {
        $prodID = base64_decode($id);
        $userId = Session::get('userid'); 
        $products = Product_Model::where('id', $prodID)->get();
    
        $productLedger = [];
    
        foreach ($products as $product) {
            $variants = Product_varient::where('product_sub_id', $product->id)->get();
    
            $variantDetails = [];
            foreach ($variants as $variant) {
                $colorVariant = Color_Model::find($variant->color_value);
    
                $variantDetails[] = [
                    'varient_id' => $variant->id,
                    'product_id' => $variant->product_sub_id,
                    'color_value' => $variant->color_value,
                    'quantity' => $variant->quantity,
                    'price' => $variant->price,
                    'pro_images' => $variant->pro_images,
                    'color_name' => $colorVariant ? $colorVariant->color_name : 'N/A',
                ];
            }
    
            $productLedger[] = [
                'product_id' => $product->id,
                'cat_id' => $product->cat_id,
                'sub_cat_id' => $product->sub_cat_id,
                'sub_sub_cat_id' => $product->sub_sub_cat_id,
                'prod_name' => $product->prod_name,
                'overview' => $product->overview,
                'shipping_custom' => $product->shipping_custom,
                'lense' => $product->lense,
                'discount' => $product->discount,
                'variants' => $variantDetails,
            ];
        }
    
        $data = app('categories'); 
        $data['products'] = $productLedger;

        $data['cartCount'] = Cart_Model::where('userid', $userId)->where('view', 1)->count();
//            $productLedgerArray = collect($productLedger)->toArray();

//    dd($productLedgerArray);
    
        return view('frontend.product-detail', $data);
    }


    public function Cart()
    {
        $userId = Session::get('userid'); 
    
        $data['cartdata'] = Cart_Model::where('userid', $userId)
        ->where('status', 1)
        ->where('view', 1)
        ->get();
    
        $data['productdata'] = Product_Model::get();
        $data['colordata'] = Color_Model::get();
        
        $data['subtotal'] = $data['cartdata']->sum(function ($item) use ($data) {
            $product = $data['productdata']->firstWhere('id', $item->product_id);
            
            if (isset($product->discount) && $product->discount > 0) {
                return $item->qty * $item->price * ((100 - $product->discount) / 100);
            }
        
            return $item->qty * $item->price;
        });
    
        $categoriesData = app('categories');
        $data = array_merge($data, $categoriesData);
            $data['cartCount'] = Cart_Model::where('userid', $userId)->where('view', 1)->count();
        return view('frontend.cart', $data);
    }

    // whishlist

    public function wishlist()
    {
        $userId = Session::get('userid'); 
    
        $data['cartdata'] = Whishlist_Model::where('userid', $userId)
        ->where('status', 1)
        ->where('view', 1)
        ->get();
    
        $data['productdata'] = Product_Model::get();
        $data['colordata'] = Color_Model::get();
        
        $data['subtotal'] = $data['cartdata']->sum(function ($item) use ($data) {
            $product = $data['productdata']->firstWhere('id', $item->product_id);
            
            if (isset($product->discount) && $product->discount > 0) {
                return $item->qty * $item->price * ((100 - $product->discount) / 100);
            }
        
            return $item->qty * $item->price;
        });
    
        $categoriesData = app('categories');
        $data = array_merge($data, $categoriesData);
            $data['cartCount'] = Whishlist_Model::where('userid', $userId)->where('view', 1)->count();
        return view('frontend.wishlist', $data);
    }
    

    public function productListing($id)
{
    $subcatid = base64_decode($id);

    $products = Product_Model::where('sub_sub_cat_id', $subcatid)->where('status', '1')->get();

    $productLedger = []; 

    foreach ($products as $product) {
        $variants = Product_varient::where('product_sub_id', $product->id)->get();

        $variantDetails = []; 
        foreach ($variants as $variant) {
            $colorVariant = Color_Model::find($variant->color_value);

            $variantDetails[] = [
                'varient_id' => $variant->id,
                'product_id' => $variant->product_sub_id,
                'color_value' => $variant->color_value,
                'quantity' => $variant->quantity,
                'price' => $variant->price,
                'pro_images' => $variant->pro_images,
                'color_name' => $colorVariant ? $colorVariant->color_name : 'N/A',
            ];
        }

        $productLedger[] = [
            'product_id' => $product->id,
            'cat_id' => $product->cat_id,
            'sub_cat_id' => $product->sub_cat_id,
            'sub_sub_cat_id' => $product->sub_sub_cat_id,
            'prod_name' => $product->prod_name,
            'lense' => $product->lense,
            'discount' => $product->discount,
            'variants' => $variantDetails,
        ];
    }

    $data = app('categories');
    $data['products'] = $productLedger;
    $userId = Session::get('userid'); 
            $data['cartCount'] = Cart_Model::where('userid', $userId)->where('view', 1)->count();

            $data['subsubcat'] = SubSubCategory::get();


//    $productLedgerArray = collect($productLedger)->toArray();

//    dd($productLedgerArray);
    return view('frontend.product-listing', $data);
}

    
public function allGender()
{
    echo "test"; die;
}

public function registerform(Request $request)
{
   
    $userdata = User_Model::create([
        'f_name' => $request->regfname,
        'l_name' => $request->reglname,
        'email' => $request->regemail,
        'password' => bcrypt($request->regpassword),
    ]);

        Session::put('userid', $userdata->id);
        Session::put('f_name', $userdata->f_name);
        Session::put('l_name', $userdata->l_name);
        Session::put('email', $userdata->email);

    return response()->json(['success' => true, 'message' => 'User registered successfully!']);
}



public function loginform(Request $request)
{


    $userdata = User_Model::where('email', $request->singinemail)->first();

    if ($userdata && Hash::check($request->singinpassword, $userdata->password)) {
        
        Session::put('userid', $userdata->id);
        Session::put('f_name', $userdata->f_name);
        Session::put('l_name', $userdata->l_name);
        Session::put('email', $userdata->email);

        return response()->json(['success' => true, 'message' => 'Login successful!']);
    } else {
        return response()->json(['success' => false, 'message' => 'Invalid email or password.'], 401);
    }
}




public function logoutuser(Request $request)
{

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('mainpage'); 
}


public function productListingAll(Request $request)
{
    // Validate the request parameters
    $request->validate([
        'category' => 'nullable|integer',
        'sub_category' => 'nullable|integer',
        'price_min' => 'nullable|numeric|min:0',
        'price_max' => 'nullable|numeric|max:500',
    ]);

    $query = Product_Model::where('status', '1');

    if ($request->has('category') && !empty($request->category)) {
        $query->where('cat_id', $request->category);
    }

    if ($request->has('sub_category') && !empty($request->sub_category)) {
        $query->where('sub_cat_id', $request->sub_category);
    }

    // Get products with their variants
    $products = $query->with(['variants' => function($query) use ($request) {
        if ($request->has('price_min') && $request->has('price_max')) {
            $query->whereBetween('price', [$request->price_min, $request->price_max]);
        }
    }])->get();

    $productLedger = [];

    foreach ($products as $product) {
        $variantDetails = $product->variants->map(function($variant) {
            $colorVariant = Color_Model::find($variant->color_value);
            return [
                'varient_id' => $variant->id,
                'product_id' => $variant->product_sub_id,
                'color_value' => $variant->color_value,
                'quantity' => $variant->quantity,
                'price' => $variant->price,
                'pro_images' => $variant->pro_images,
                'color_name' => $colorVariant ? $colorVariant->color_name : 'N/A',
            ];
        })->toArray();

        $productLedger[] = [
            'product_id' => $product->id,
            'cat_id' => $product->cat_id,
            'sub_cat_id' => $product->sub_cat_id,
            'sub_sub_cat_id' => $product->sub_sub_cat_id,
            'prod_name' => $product->prod_name,
            'lense' => $product->lense,
            'discount' => $product->discount,
            'variants' => $variantDetails,
        ];
    }

    $data = app('categories');
    $data['products'] = $productLedger;

 
    $userId = Session::get('userid');
    $data['cartCount'] = Cart_Model::where('userid', $userId)->where('view', 1)->count();

    $data['subsubcat'] = SubSubCategory::get();


    return view('frontend.product-listing-all', $data);
}



public function productListingbyCategory($id)
{
    // echo "test"; die;
    $catId = $id;

    $products = Product_Model::where('cat_id', $catId)->get();

    $productLedger = []; 

    foreach ($products as $product) {
        $variants = Product_varient::where('product_sub_id', $product->id)->get();

        $variantDetails = []; 
        foreach ($variants as $variant) {
            $colorVariant = Color_Model::find($variant->color_value);

            $variantDetails[] = [
                'varient_id' => $variant->id,
                'product_id' => $variant->product_sub_id,
                'color_value' => $variant->color_value,
                'quantity' => $variant->quantity,
                'price' => $variant->price,
                'pro_images' => $variant->pro_images,
                'color_name' => $colorVariant ? $colorVariant->color_name : 'N/A',
            ];
        }

        $productLedger[] = [
            'product_id' => $product->id,
            'cat_id' => $product->cat_id,
            'sub_cat_id' => $product->sub_cat_id,
            'sub_sub_cat_id' => $product->sub_sub_cat_id,
            'prod_name' => $product->prod_name,
            'lense' => $product->lense,
            'discount' => $product->discount,
            'variants' => $variantDetails,
        ];
    }

    $data = app('categories');
    $data['products'] = $productLedger;

    $userId = Session::get('userid'); 
            $data['cartCount'] = Cart_Model::where('userid', $userId)->where('view', 1)->count();

            $data['subsubcat'] = SubSubCategory::get();
    return view('frontend.product-listing-category', $data);
}



public function validateEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $user = User_Model::where('email', $request->email)->first();

        if ($user) {
            session(['reset_email' => $request->email]);
            return response()->json(['success' => true, 'message' => 'Email is valid']);
        } else {
            return response()->json(['success' => false, 'message' => 'Email not found']);
        }
    }


    public function changePasswordUser(Request $request)
    {
        $request->validate(['password' => 'required|min:6']);
        $email = session('reset_email');

        if (!$email) {
            return response()->json(['success' => false, 'message' => 'Session expired. Please try again.']);
        }

        $user = User_Model::where('email', $email)->first();

        if ($user) {
            $user->password = Hash::make($request->password);
            $user->save();

            session()->forget('reset_email');
            return response()->json(['success' => true, 'message' => 'Password changed successfully']);
        } else {
            return response()->json(['success' => false, 'message' => 'User not found']);
        }
    }


    public function orderhistory()
    {

        $userId = Session::get('userid'); 
        
        $data['cartdata'] = Cart_Model::where('userid', $userId)->where('view', ['2','3'])
        ->where('status', 1)
        ->get();
    
        $data['productdata'] = Product_Model::get();
        $data['colordata'] = Color_Model::get();
    
        $data['subtotal'] = $data['cartdata']->sum(function ($item) {
            return $item->qty * $item->price;
        });
    
        $categoriesData = app('categories');
        $data = array_merge($data, $categoriesData);
            $data['cartCount'] = Cart_Model::where('userid', $userId)->where('view', 1)->count();
        return view('frontend.orderhistory',$data);
    }



    //////////////////////////productbyfilter////////////////
    public function productListingByPrice(Request $request)
    {
        $min_price = $request->query('min_price', 0);  
        $max_price = $request->query('max_price', 500); 
    
        $products = Product_Model::get(); 
    
        $productLedger = [];
    
        foreach ($products as $product) {
            $variants = Product_varient::where('product_sub_id', $product->id)
                ->whereRaw('CAST(price AS DECIMAL(10, 2)) >= ?', [$min_price])
                ->whereRaw('CAST(price AS DECIMAL(10, 2)) <= ?', [$max_price])
                ->whereNotNull('price')
                ->get();
    
            $variantDetails = [];
    
            foreach ($variants as $variant) {
                $colorVariant = Color_Model::find($variant->color_value);
    
                $variantDetails[] = [
                    'varient_id' => $variant->id,
                    'product_id' => $variant->product_sub_id,
                    'color_value' => $variant->color_value,
                    'quantity' => $variant->quantity,
                    'price' => $variant->price,
                    'pro_images' => $variant->pro_images,
                    'color_name' => $colorVariant ? $colorVariant->color_name : 'N/A',
                ];
            }
    
            $productLedger[] = [
                'product_id' => $product->id,
                'cat_id' => $product->cat_id,
                'sub_cat_id' => $product->sub_cat_id,
                'sub_sub_cat_id' => $product->sub_sub_cat_id,
                'prod_name' => $product->prod_name,
                'lense' => $product->lense,
                'discount' => $product->discount,
                'variants' => $variantDetails,
            ];
        }
        $html = '';

        // echo "<pre>"; print_r($productLedger);  echo "</pre>"; exit;
    
        foreach($productLedger as $product) {
            $variant = $product['variants'][0] ?? null;
            $images = $variant ? explode(',', $variant['pro_images']) : [];
            $originalPrice = $variant['price'] ?? 0;
            $discount = $product['discount'] ?? 0;
            $discountedPrice = $originalPrice - ($originalPrice * $discount / 100);
            $attributeID = $variant['varient_id'] ?? '';
            $colorname = $variant['color_name'] ?? '';
            $variyantprice = $variant['price'] ?? '';

            if(!empty($images))
{    
            $html .= '<div class="col-6 col-md-4 col-lg-3 col-xl-3 col-xxl-3">
                <div class="product">
                    <figure class="product-media">
                        '.($discount > 0 ? '<span class="product-label label-new">'. $discount .'% OFF</span>' : '').'
                        <a href="'.route('product-detaildata', base64_encode($product['product_id'])).'">
                            '.(!empty($images) ? 
                                '<img src="'.asset('assets/productimage/'.$images[0]).'" alt="Product image" class="product-image" style="width:393px; height:393px;">'.
                                (isset($images[1]) ? '<img src="'.asset('assets/productimage/'.$images[1]).'" alt="Product image hover" class="product-image-hover" style="width:393px; height:393px;">' : '') 
                                : '<img src="'.asset('assets/default-product-image.jpg').'" alt="Default image" class="product-image">
                            ').'
                        </a>
                        <div class="product-itemw">
                            <input type="hidden" value="'. $product['product_id'] .'" class="productidw">
                            <input type="hidden" value="'. $product['prod_name'] .'" class="prod_namew">
                            <input type="hidden" value="'. $attributeID .'" class="attributeIDw">
                            <input type="hidden" value="'. $colorname .'" class="colornamew">
                            <input type="hidden" value="1" class="qtyw">
                            <input type="hidden" value="'. $variyantprice .'" class="variyantpricew">
                            '.(session('userid') ? 
                                '<div class="product-action-vertical">
                                    <button class="btn-product-icon btn-wishlist btn-expandable" onClick="wishlistdetail(this)" style="border: none;"><span>add to wishlist</span></button>
                                </div>' : 
                                '<div class="product-action-vertical">
                                    <button class="btn-product-icon btn-wishlist btn-expandable" data-toggle="modal" data-target="#rightModal" style="border: none;"><span>add to wishlist</span></button>
                                </div>').'
                        </div>
                        <div class="row product-action">
                            <div class="col-md-6">
                                <a href="#" class="btn-lenses"><span>Neutral</span></a>
                            </div>
                            <div class="col-md-6">
                                <a href="#" class="btn-lenses"><span>Prescription</span></a>
                            </div>
                        </div>
                    </figure>
                    <div class="product-body">
                        <div class="row">
                            <h3 class="col-md-6 product-title">
                                <a href="'.route('product-detaildata', base64_encode($product['product_id'])).'">'. $product['prod_name'] .'</a>
                            </h3>
                            <div class="col-md-6 product-price justify-content-end">
                                <div class="product-price trending-price">
                                    '.($discount > 0 ? 
                                        '<span style="text-decoration: line-through; color: red;">€'. number_format($originalPrice, 2) .'</span>
                                        <span style="color: green; font-weight: bold;">€'. number_format($discountedPrice, 2) .'</span>' : 
                                        '<span>€'. number_format($originalPrice, 2) .'</span>').'
                                </div>
                            </div>
                        </div>
                        <div class="product-nav product-nav-dots pb-3">
                            '.implode('', array_map(function($variant) {
                                return '<a href="javascript:void(0);" class="color-selector" 
                                    data-product-id="'. $variant['product_id'] .'"  
                                    data-varient-id="'. $variant['varient_id'] .'" 
                                    data-image="'. $variant['pro_images'] .'" 
                                    data-price="'. $variant['price'] .'" 
                                    data-color-id="'. $variant['color_value'] .'" 
                                    data-color-name="'. $variant['color_name'] .'" 
                                    style="background: '. $variant['color_name'] .'; border: 1px solid grey;">
                                    <span class="sr-only">'. $variant['color_name'] .'</span>
                                </a>';
                            }, $product['variants'])).'
                        </div>
                        <div class="product-action product-action-transparent product-item">
                            <input type="hidden" value="'. $product['product_id'] .'" class="productid">
                            <input type="hidden" value="'. $product['prod_name'] .'" class="prod_name">
                            <input type="hidden" value="'. $attributeID .'" class="attributeID">
                            <input type="hidden" value="'. $colorname .'" class="colorname">
                            <input type="hidden" value="1" class="qty">
                            <input type="hidden" value="'. $variyantprice .'" class="variyantprice">
                            '.(session('userid') ? 
                                '<button type="button" class="btn-product btn-cart" onClick="cartdetail(this)">
                                    <span>Add to Cart</span>
                                </button>' :
                                '<button type="button" class="btn-product btn-cart" data-toggle="modal" data-target="#rightModal">
                                    <span>Add to Cart</span>
                                </button>').'
                        </div>
                    </div>
                </div>
            </div>';
        }
        }
        // echo "<pre>"; print_r($html); die;
    
        return response()->json(['html' => $html]);
    }
    
    
 

    public function filterBySubsubcategory(Request $request)
    {
        $subSubCatId = $request->get('subsubcat_id');
    
        // Get products directly from the database
        $products = DB::table('tbl_product')->where('sub_sub_cat_id', $subSubCatId)->get();
    
        $productLedger = [];
        $html = '';
    
        foreach ($products as $product) {
            // Get variants for each product
            $variants = DB::table('tbl_varient')->where('product_sub_id', $product->id)->get();
    
            $variantDetails = [];
    
            foreach ($variants as $variant) {
                // Get color details directly from the colors table
                $colorVariant = DB::table('tbl_color_varient')->where('id', $variant->color_value)->first();
    
                $variantDetails[] = [
                    'varient_id' => $variant->id,
                    'product_id' => $variant->product_sub_id,
                    'color_value' => $variant->color_value,
                    'quantity' => $variant->quantity,
                    'price' => $variant->price,
                    'pro_images' => $variant->pro_images,
                    'color_name' => $colorVariant ? $colorVariant->color_name : 'N/A',
                ];
            }
    
            $productLedger[] = [
                'product_id' => $product->id,
                'cat_id' => $product->cat_id,
                'sub_cat_id' => $product->sub_cat_id,
                'sub_sub_cat_id' => $product->sub_sub_cat_id,
                'prod_name' => $product->prod_name,
                'lense' => $product->lense,
                'discount' => $product->discount,
                'variants' => $variantDetails,
            ];
        }
    
        foreach ($productLedger as $product) {
            $variant = $product['variants'][0] ?? null;
            $images = $variant ? explode(',', $variant['pro_images']) : [];
            $originalPrice = $variant['price'] ?? 0;
            $discount = $product['discount'] ?? 0;
            $discountedPrice = $originalPrice - ($originalPrice * $discount / 100);
            $attributeID = $variant['varient_id'] ?? '';
            $colorname = $variant['color_name'] ?? '';
            $variyantprice = $variant['price'] ?? 0;
    
            $html .= '<div class="col-6 col-md-4 col-lg-4 col-xl-4 col-xxl-4">
            <div class="product">
                <figure class="product-media">
                    '.($discount > 0 ? '<span class="product-label label-new">'. $discount .'% OFF</span>' : '').'
                    <a href="'.route('product-detaildata', base64_encode($product['product_id'])).'">
                        '.(!empty($images) ? 
                            '<img src="'.asset('assets/productimage/'.$images[0]).'" alt="Product image" class="product-image" style="width:393px; height:393px;">'.
                            (isset($images[1]) ? '<img src="'.asset('assets/productimage/'.$images[1]).'" alt="Product image hover" class="product-image-hover" style="width:393px; height:393px;">' : '') 
                            : '<img src="'.asset('assets/default-product-image.jpg').'" alt="Default image" class="product-image">
                        ').'
                    </a>
                    <div class="product-itemw">
                        <input type="hidden" value="'. $product['product_id'] .'" class="productidw">
                        <input type="hidden" value="'. $product['prod_name'] .'" class="prod_namew">
                        <input type="hidden" value="'. $attributeID .'" class="attributeIDw">
                        <input type="hidden" value="'. $colorname .'" class="colornamew">
                        <input type="hidden" value="1" class="qtyw">
                        <input type="hidden" value="'. $variyantprice .'" class="variyantpricew">
                        '.(session('userid') ? 
                            '<div class="product-action-vertical">
                                <button class="btn-product-icon btn-wishlist btn-expandable" onClick="wishlistdetail(this)" style="border: none;"><span>add to wishlist</span></button>
                            </div>' : 
                            '<div class="product-action-vertical">
                                <button class="btn-product-icon btn-wishlist btn-expandable" data-toggle="modal" data-target="#rightModal" style="border: none;"><span>add to wishlist</span></button>
                            </div>').'
                    </div>
                    <div class="row product-action">
                        <div class="col-md-6">
                            <a href="#" class="btn-lenses"><span>Neutral</span></a>
                        </div>
                        <div class="col-md-6">
                            <a href="#" class="btn-lenses"><span>Prescription</span></a>
                        </div>
                    </div>
                </figure>
                <div class="product-body">
                    <div class="row">
                        <h3 class="col-md-6 product-title">
                            <a href="'.route('product-detaildata', base64_encode($product['product_id'])).'">'. $product['prod_name'] .'</a>
                        </h3>
                        <div class="col-md-6 product-price justify-content-end">
                            <div class="product-price trending-price">
                                '.($discount > 0 ? 
                                    '<span style="text-decoration: line-through; color: red;">€'. number_format($originalPrice, 2) .'</span>
                                    <span style="color: green; font-weight: bold;">€'. number_format($discountedPrice, 2) .'</span>' : 
                                    '<span>€'. number_format($originalPrice, 2) .'</span>').'
                            </div>
                        </div>
                    </div>
                    <div class="product-nav product-nav-dots pb-3">
                        '.implode('', array_map(function($variant) {
                            return '<a href="javascript:void(0);" class="color-selector" 
                                data-product-id="'. $variant['product_id'] .'"  
                                data-varient-id="'. $variant['varient_id'] .'" 
                                data-image="'. $variant['pro_images'] .'" 
                                data-price="'. $variant['price'] .'" 
                                data-color-id="'. $variant['color_value'] .'" 
                                data-color-name="'. $variant['color_name'] .'" 
                                style="background: '. $variant['color_name'] .'; border: 1px solid grey;">
                                <span class="sr-only">'. $variant['color_name'] .'</span>
                            </a>';
                        }, $product['variants'])).'
                    </div>
                    <div class="product-action product-action-transparent product-item">
                        <input type="hidden" value="'. $product['product_id'] .'" class="productid">
                        <input type="hidden" value="'. $product['prod_name'] .'" class="prod_name">
                        <input type="hidden" value="'. $attributeID .'" class="attributeID">
                        <input type="hidden" value="'. $colorname .'" class="colorname">
                        <input type="hidden" value="1" class="qty">
                        <input type="hidden" value="'. $variyantprice .'" class="variyantprice">
                        '.(session('userid') ? 
                            '<button type="button" class="btn-product btn-cart" onClick="cartdetail(this)">
                                <span>Add to Cart</span>
                            </button>' :
                            '<button type="button" class="btn-product btn-cart" data-toggle="modal" data-target="#rightModal">
                                <span>Add to Cart</span>
                            </button>').'
                    </div>
                </div>
            </div>
        </div>';
        }
    
        return response()->json(['html' => $html]);
    }
    
    

//////////////////////////-End here-/////////////////////////////



///////////////////////--chatbot--/////////////////////////

public function chatbot()
{
    return view('chatbot.index');
}

public function chatbotlogin()
{
    return view('chatbot.login');

}
}
