<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
///////////////////--Model--//////////////////////////
use App\Models\Cafeteria\Cafeteria_Model;
use App\Models\Cafeteria\CafeteriaUser_Model;
use App\Models\School_Model;


use App\Models\ManageCafe\Menu_Model;
use App\Models\ManageCafe\Meal_Model;
use App\Models\ManageCafe\Dish_Model;
use App\Models\ManageCafe\DishCategory_Model;
use App\Models\ManageCafe\Ingredients_Model;




class ManageCafeController extends Controller
{
	
	public function __construct()
    {
        $this->middleware('auth');
    }
	
	/* public function menu(){
		$menu = Menu_Model::orderby('id','DESC')->get();
		$cafe = Cafeteria_Model::orderby('id','DESC')->get();
		// echo "<pre>"; print_r($cafe); die;
		$school = School_Model::orderby('id','DESC')->get();
		return view('admin.menu.index', compact('menu','cafe', 'school'));
	} */
	
	
	public function menu(Request $request)
	{
		$cafe   = Cafeteria_Model::orderby('id', 'DESC')->get();
		$school = School_Model::orderby('id', 'DESC')->get();

		$query = Menu_Model::orderby('id', 'DESC');

		if ($request->filled('school_id')) {
			$query->where('school_id', $request->school_id);
		}

		if ($request->filled('cafeteria_id')) {
			$query->where('cafeteria_id', $request->cafeteria_id);
		}

		$menu = $query->paginate(10);

		return view('admin.menu.index', compact('menu', 'cafe', 'school'));
	}
	


	public function menu_store(Request $request){
		
		$request->validate([
			'school_id'     => 'required',
			'cafeteria_id'  => 'required',
			'month'         => 'required|max:250',
			'year'          => 'required|max:250',
			'menu'          => 'required',
		]);

		$cafeteriaUser = new Menu_Model();
		$cafeteriaUser->school_id = $request->input('school_id');
		$cafeteriaUser->cafeteria_id = $request->input('cafeteria_id');
		$cafeteriaUser->month = $request->input('month');
		$cafeteriaUser->year = $request->input('year');

		// try {
		// 	$file = $request->file('menu');
		// 	$filename = time() . '_' . $file->getClientOriginalName();
		// 	$path = 'jazzpay/menu/' . $filename;

		// 	// Upload to S3
		// 	Storage::disk('s3')->put($path, file_get_contents($file));

		// 	// Save the full URL
		// 	$cafeteriaUser->menu_upload = Storage::disk('s3')->url($path);

		// } catch (\Exception $e) {
		// 	// Optional: log the error
		// 	Log::error('File upload failed: ' . $e->getMessage());

		// 	// Redirect with error message
		// 	return redirect()->back()->with('error', 'File upload failed. Please try again.');
		// }

				$file = $request->file('menu');
            $uploadprofileurl = $this->s3uploadfile($file, 'menu');
			$cafeteriaUser->menu_upload = $uploadprofileurl;

		$cafeteriaUser->save();

		return redirect()->route('admin.menu')
						->with('success', 'Menu uploaded and saved successfully!');
	}


	public function menu_update(Request $request, $id)
	{
		$request->validate([
			'school_id' => 'required|integer',
			'cafeteria_id' => 'required|integer',
			'month' => 'required|string',
			'year' => 'required|integer',
			'menu' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
		]);

		$menu = Menu_Model::findOrFail($id);

		$menu->fill($request->only(['school_id', 'cafeteria_id', 'month', 'year']));

		// if ($request->hasFile('menu')) {
		// 	$file = $request->file('menu');
		// 	$filename = time() . '_' . $file->getClientOriginalName();

		// 	try {
		// 		// Try S3 first
		// 		$path = 'jazzpay/menu/' . $filename;
		// 		Storage::disk('s3')->put($path, file_get_contents($file), 'public');
		// 		$url = Storage::disk('s3')->url($path);
		// 	} catch (\Exception $e) {
		// 		// Fall back to local storage
		// 		$path = $file->storeAs('uploads/menu', $filename, 'public');
		// 		$url = asset('storage/' . $path);
		// 	}

		// 	$menu->menu_upload = $url;
		// }

			$file = $request->file('menu');
            $uploadprofileurl = $this->s3uploadfile($file, 'menu');
			$menu->menu_upload = $uploadprofileurl;

		$menu->save();

		return redirect()->route('admin.menu')->with('success', 'Menu updated successfully!');
	}


 
	public function menu_delete($id){
		
		$menu = Menu_Model::findOrFail($id);
		if ($menu->menu_upload) {
			$parsedUrl = parse_url($menu->menu_upload);
			$path = ltrim($parsedUrl['path'], '/'); 
			Storage::disk('s3')->delete($path);
		}		
		$menu->delete();
		return redirect()->route('admin.menu')->with('success', 'Menu deleted successfully!');
	}

        

   ////////////////////////////--Dish Category--///////////////////////////////////////

	/* public function dish_category(){
		$dish = DishCategory_Model::orderby('id','DESC')->get();
		$cafe = Cafeteria_Model::get();

		return view('admin.dish_category.index', compact('dish','cafe'));
	} */
	
	public function dish_category(Request $request)
	{
		$cafe = Cafeteria_Model::get();

		$query = DishCategory_Model::orderby('id', 'DESC');

		if ($request->filled('name')) {
			$query->where('name', 'like', '%' . $request->name . '%');
		}

		if ($request->filled('cafeteria_id')) {
			$query->where('cafeteria_id', $request->cafeteria_id);
		}

		$dish = $query->paginate(10);

		return view('admin.dish_category.index', compact('dish', 'cafe'));
	}


	public function dish_category_store(Request $request){
		
		$request->validate([
			'name'           => 'required',
			'meal_type'      => 'nullable|max:250',
			'cafeteria_id'   => 'required',
		]);

		$cafeteriaUser = new DishCategory_Model();
		$cafeteriaUser->cafeteria_id = $request->input('cafeteria_id');
		$cafeteriaUser->name = $request->input('name');
		$cafeteriaUser->meal_type = $request->input('meal_type');
		$cafeteriaUser->save();

		return redirect()->route('admin.dish_category')
						->with('success', 'Dish category and saved successfully!');
	}

	public function dish_category_update(Request $request, $id){
		$request->validate([
			'name'           => 'required',
			'meal_type'      => 'nullable|max:250',
			'cafeteria_id'   => 'required',
		]);
		$menu = DishCategory_Model::findOrFail($id);

		$menu->cafeteria_id = $request->cafeteria_id;
		$menu->name = $request->name;
		$menu->meal_type = $request->meal_type;

		$menu->save();

		return redirect()->route('admin.dish_category')->with('success', 'Dish category updated successfully!');
	}

	public function dish_categorychangeStatus($id){
		$menu = DishCategory_Model::findOrFail($id);
		// Toggle status
		$menu->status = $menu->status == 1 ? 0 : 1;
		$menu->save();
		return redirect()->back()->with('success', 'Dish category status updated successfully!');
	}
	//////////////////////////////////////--ingredients---//////////////////////////////////////////
        
	public function ingredients_category(){
		$dish = Ingredients_Model::orderby('id','desc')->get();
		return view('admin.ingredients.index', compact('dish'));
	}


	public function ingredients_category_store(Request $request){
		$request->validate([
			'name'           => 'required',
		]);

		$cafeteriaUser = new Ingredients_Model();
		$cafeteriaUser->name = $request->input('name');

		$cafeteriaUser->save();

		return redirect()->route('admin.ingredients_category')
						->with('success', 'Ingredients and saved successfully!');
	}

	public function ingredients_category_update(Request $request, $id){
		$request->validate([
			'name'           => 'required',
		]);
		$menu = Ingredients_Model::findOrFail($id);
		$menu->name = $request->name;
		$menu->save();

		return redirect()->route('admin.ingredients_category')->with('success', 'Ingredients updated successfully!');
	}

	public function ingredients_categorychangeStatus($id){
		$menu = Ingredients_Model::findOrFail($id);

		// Toggle status
		$menu->status = $menu->status == 1 ? 0 : 1;

		$menu->save();

		return redirect()->back()->with('success', 'Ingredients status updated successfully!');
	}
	//////////////////////////////////////--Meal category---//////////////////////////////////////////

	/* public function meal_category(){
		$dish = Meal_Model::get();
		return view('admin.meal_category.index', compact('dish'));
	} */
	
	public function meal_category(Request $request)
	{
		$query = Meal_Model::query();

		if ($request->filled('name')) {
			$query->where('name', 'like', '%' . $request->name . '%');
		}

		$dish = $query->paginate(10);

		return view('admin.meal_category.index', compact('dish'));
	}


	public function meal_category_store(Request $request){
		$request->validate([
			'name' => 'required',
		]);
		$cafeteriaUser = new Meal_Model();
		$cafeteriaUser->name = $request->input('name');
		$cafeteriaUser->save();
		return redirect()->route('admin.meal_category')
					->with('success', 'Meal saved successfully!');
	}


        //////////////////////////////////////--Dish---//////////////////////////////////////////

	/* public function dish(){
		$dish = Dish_Model::orderby('id','desc')->get();       // dish 
		$cafe = Cafeteria_Model::get();   // cafetria
		$dishcategory = DishCategory_Model::get(); //dish category
		$ingredients = Ingredients_Model::get(); 

		return view('admin.dish.index', compact('dish','cafe', 'dishcategory', 'ingredients', 'dishcategory'));
	} */
	
	
	public function dish(Request $request)
	{
		$cafe        = Cafeteria_Model::get();
		$dishcategory = DishCategory_Model::get();
		$ingredients  = Ingredients_Model::get();

		$query = Dish_Model::orderby('id', 'desc');

		if ($request->filled('cafeteria_id')) {
			$query->where('cafeteria_id', $request->cafeteria_id);
		}

		if ($request->filled('name')) {
			$query->where('dish_name', 'like', '%' . $request->name . '%');
		}

		if ($request->filled('category_id')) {
			$query->where('dish_category_id', $request->category_id);
		}

		$dish = $query->paginate(10);

		return view('admin.dish.index', compact('dish', 'cafe', 'dishcategory', 'ingredients'));
	}
	
	
	public function dish_export_pdf(Request $request)
	{
		$cafe         = Cafeteria_Model::get();
		$dishcategory = DishCategory_Model::get();
	 
		$query = Dish_Model::orderby('id', 'desc');
	 
		if ($request->filled('cafeteria_id')) {
			$query->where('cafeteria_id', $request->cafeteria_id);
		}
		if ($request->filled('name')) {
			$query->where('dish_name', 'like', '%' . $request->name . '%');
		}
		if ($request->filled('category_id')) {
			$query->where('dish_category_id', $request->category_id);
		}
	 
		$dish = $query->get();
	 
		return view('admin.dish.export_pdf', compact('dish', 'cafe', 'dishcategory'));
	}
	 
	public function dish_export_excel(Request $request)
	{
		$cafe         = Cafeteria_Model::get();
		$dishcategory = DishCategory_Model::get();
	 
		$query = Dish_Model::orderby('id', 'desc');
	 
		if ($request->filled('cafeteria_id')) {
			$query->where('cafeteria_id', $request->cafeteria_id);
		}
		if ($request->filled('name')) {
			$query->where('dish_name', 'like', '%' . $request->name . '%');
		}
		if ($request->filled('category_id')) {
			$query->where('dish_category_id', $request->category_id);
		}
	 
		$dish = $query->get();
	 
		$filename = 'Dishes-' . date('d-M-Y') . '.csv';
	 
		$headers = [
			'Content-Type'        => 'text/csv',
			'Content-Disposition' => 'attachment; filename="' . $filename . '"',
			'Pragma'              => 'no-cache',
			'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
			'Expires'             => '0',
		];
	 
		$callback = function () use ($dish, $cafe, $dishcategory) {
			$file = fopen('php://output', 'w');
			fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF)); // UTF-8 BOM
	 
			fputcsv($file, ['Sr No', 'Dish Name', 'Description', 'Price', 'Category', 'Cafeteria', 'Status']);
	 
			$i = 1;
			foreach ($dish as $row) {
				$cat  = $dishcategory->firstWhere('id', $row->dish_category_id);
				$cafe_name = $cafe->firstWhere('id', $row->cafeteria_id);
				fputcsv($file, [
					$i++,
					$row->dish_name,
					$row->description ?? '-',
					$row->price,
					$cat->name ?? '-',
					$cafe_name->cafeteria_name ?? '-',
					$row->status == 1 ? 'Active' : 'Inactive',
				]);
			}
			fclose($file);
		};
	 
		return response()->stream($callback, 200, $headers);
	}
	 
	public function dish_bulk_import(Request $request)
	{
		$request->validate([
			'cafeteria_id' => 'required|integer',
			'file'         => 'required|file|mimes:csv,txt,xlsx',
		]);
	 
		$cafeteriaId = $request->cafeteria_id;
		$file        = $request->file('file');
		$handle      = fopen($file->getRealPath(), 'r');
	 
		// Skip header row
		$header = fgetcsv($handle);
	 
		$imported = 0;
		$errors   = [];
		$rowNum   = 1;
	 
		while (($row = fgetcsv($handle)) !== false) {
			$rowNum++;
	 
			// Expected CSV columns:
			// dish_name, category_name, description, price, serving_of, calories, protein, carbohydrates, fats, food_type
			if (count($row) < 2) continue;
	 
			$dishName    = trim($row[0] ?? '');
			$categoryName = trim($row[1] ?? '');
			$description = trim($row[2] ?? '');
			$price       = trim($row[3] ?? '0');
			$serving_of  = trim($row[4] ?? '');
			$calories    = trim($row[5] ?? '');
			$protein     = trim($row[6] ?? '');
			$carbs       = trim($row[7] ?? '');
			$fats        = trim($row[8] ?? '');
			$food_type   = trim($row[9] ?? '');
	 
			if (empty($dishName)) {
				$errors[] = "Row {$rowNum}: Dish name is required.";
				continue;
			}
	 
			// Find category by name for this cafeteria
			$category = DishCategory_Model::where('cafeteria_id', $cafeteriaId)
				->where('name', $categoryName)
				->first();
	 
			if (!$category && !empty($categoryName)) {
				// Create category if it doesn't exist for this cafeteria
				$category = DishCategory_Model::create([
					'cafeteria_id' => $cafeteriaId,
					'name'         => $categoryName,
					'status'       => 1,
					'view'         => 1,
				]);
			}
	 
			Dish_Model::create([
				'cafeteria_id'    => $cafeteriaId,
				'dish_category_id'=> $category ? $category->id : null,
				'dish_name'       => $dishName,
				'description'     => $description,
				'price'           => $price,
				'serving_of'      => $serving_of,
				'calories'        => $calories,
				'protein'         => $protein,
				'carbohydrates'   => $carbs,
				'fats'            => $fats,
				'food_type'       => $food_type,
				'status'          => 1,
				'view'            => 1,
			]);
	 
			$imported++;
		}
	 
		fclose($handle);
	 
		$message = "{$imported} dishes imported successfully.";
		if (!empty($errors)) {
			$message .= ' Errors: ' . implode(' | ', $errors);
		}
	 
		return redirect()->route('admin.dish')->with('success', $message);
	}
	 
	public function dish_import_sample()
	{
		$headers = [
			'Content-Type'        => 'text/csv',
			'Content-Disposition' => 'attachment; filename="dish_import_sample.csv"',
		];
	 
		$callback = function () {
			$file = fopen('php://output', 'w');
			fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
			fputcsv($file, ['dish_name','category_name','description','price','serving_of','calories','protein','carbohydrates','fats','food_type']);
			fputcsv($file, ['Chicken Biryani','Lunch','Delicious chicken biryani','25','1 plate','450','30','60','15','nonveg']);
			fputcsv($file, ['Veg Burger','Breakfast','Fresh veg burger','15','1 piece','300','10','40','8','veg']);
			fclose($file);
		};
	 
		return response()->stream($callback, 200, $headers);
	}
	
	

	public function dishchangeStatus($id){
		$menu = Dish_Model::findOrFail($id);
		// Toggle status
		$menu->status = $menu->status == 1 ? 0 : 1;
		$menu->save();
		return redirect()->back()->with('success', 'status updated successfully!');
	}

	public function dish_delete($id){
		$menu = Dish_Model::findOrFail($id);
		$menu->delete();
		return redirect()->back()->with('success', 'Dish deleted successfully!');
	}
	
	public function getCategoriesByCafeteria($cafeteria_id){
		$categories = DishCategory_Model::where('cafeteria_id', $cafeteria_id)->get();
		return response()->json($categories);
	}

	public function dish_store(Request $request){
	 
		$request->validate([
			'name'          => 'required|string|max:255',
			'cafeteria_id'  => 'required|integer',
			'category_id'   => 'required|integer',
			'desc'          => 'nullable|string',
			'ingredients'   => 'nullable|array',
			'price'         => 'required|string|max:255',
			'serving_of'    => 'required|string|max:255',
			'calories'      => 'nullable|string|max:255',
			'Protein'       => 'nullable|string|max:255',
			'Carbohydrate'  => 'nullable|string|max:255',
			'fats'          => 'nullable|string|max:255',
			'image'         => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
		]);
	 
		try {
			$dish = new Dish_Model();
			$dish->dish_name        = $request->name;
			$dish->cafeteria_id     = $request->cafeteria_id;
			$dish->dish_category_id = $request->category_id;
			$dish->ingredients_id   = $request->ingr_id;
			// $dish->description      = $request->filled('desc')
			// 	? $request->desc
			// 	: ($request->filled('ingredients') ? implode(',', $request->ingredients) : null);

			// Ingredients -> description column
        $dish->description = $request->filled('ingredients')
            ? implode(',', $request->ingredients)
            : null;

        // Description text -> new desc2 column
        $dish->desc2 = $request->filled('desc') ? $request->desc : null;
			$dish->price          = $request->price;
			$dish->serving_of     = $request->serving_of;
			$dish->calories       = $request->calories;
			$dish->protein        = $request->Protein;
			$dish->carbohydrates  = $request->Carbohydrate;
			$dish->fats           = $request->fats;
			$dish->show_in_pos    = $request->has('show_in_pos') ? 1 : 0;
			$dish->status         = 1;
			$dish->view           = 0;
	 
			if ($request->hasFile('image')) {
				$file = $request->file('image');
				$uploadprofileurl = $this->s3uploadfile($file, 'dish');
				$dish->image = $uploadprofileurl;
			}
	 
			$dish->save();
	 
			return redirect()->route('admin.dish')
			->with('success', 'Dish saved successfully!');
		} catch (\Exception $e) {
			Log::error('Dish Save Error: ' . $e->getMessage());
			return redirect()->back()->with('error', 'Something went wrong while saving the dish.');
		}
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
	
	public function dish_update(Request $request, $id)
{
    $request->validate([
        'name'          => 'required|string|max:255',
        'cafeteria_id'  => 'required|integer',
        'category_id'   => 'required|integer',
        'desc'          => 'nullable|string',
        'ingredients'   => 'nullable|array',
        'ingr_id'       => 'nullable|integer',
        'price'         => 'required|string|max:255',
        'serving_of'    => 'required|string|max:255',
        'calories'      => 'nullable|string|max:255',
        'Protein'       => 'nullable|string|max:255',
        'Carbohydrate'  => 'nullable|string|max:255',
        'fats'          => 'nullable|string|max:255',
        'food_type'     => 'nullable|string|max:255',
        'image'         => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    try {
        $dish = Dish_Model::findOrFail($id);
        $dish->dish_name        = $request->name;
        $dish->cafeteria_id     = $request->cafeteria_id;
        $dish->dish_category_id = $request->category_id;
        $dish->ingredients_id   = $request->ingr_id;

        // Ingredients -> description column (keep existing if none submitted here)
        $dish->description = $request->filled('ingredients')
            ? implode(',', $request->ingredients)
            : $dish->description;

        // Description text -> desc2 column
        $dish->desc2 = $request->desc;

        $dish->price            = $request->price;
        $dish->serving_of       = $request->serving_of;
        $dish->calories         = $request->calories;
        $dish->protein          = $request->Protein;
        $dish->carbohydrates    = $request->Carbohydrate;
        $dish->fats             = $request->fats;
        $dish->food_type        = $request->food_type;
        $dish->show_in_pos      = $request->has('show_in_pos') ? 1 : 0;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $uploadprofileurl = $this->s3uploadfile($file, 'dish');
            $dish->image = $uploadprofileurl;
        }

        $dish->save();

        return redirect()->route('admin.dish')
            ->with('success', 'Dish updated successfully!');
    } catch (\Exception $e) {
        \Log::error('Dish Update Error: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Something went wrong while updating the dish.');
    }
}

}