<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Cafeteria\Cafeteria_Model;
use App\Models\ManageCafe\Dish_Model;
use App\Models\ManageCafe\MenuAddon_Model;
use App\Models\ManageCafe\MenuAddonDate_Model;

class MenuAddonController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $cafe   = Cafeteria_Model::orderby('cafeteria_name')->get();
        $dishes = Dish_Model::orderby('dish_name')->get();

        $query = MenuAddon_Model::with(['dates', 'dish' => function ($q) {}])
            ->orderBy('id', 'desc');

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        if ($request->filled('cafeteria_id')) {
            $query->where('cafeteria_id', $request->cafeteria_id);
        }
        if ($request->filled('dish_id')) {
            $query->where('dish_id', $request->dish_id);
        }
        if ($request->filled('from_date') || $request->filled('to_date')) {
            $query->whereHas('dates', function ($q) use ($request) {
                if ($request->filled('from_date')) {
                    $q->whereDate('available_date', '>=', $request->from_date);
                }
                if ($request->filled('to_date')) {
                    $q->whereDate('available_date', '<=', $request->to_date);
                }
            });
        }

        $addons = $query->paginate(10);

        return view('admin.menu_addon.index', compact('addons', 'cafe', 'dishes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'dish_id'      => 'required|integer',
            'cafeteria_id' => 'required|integer',
            'name'         => 'required|string|max:255',
            'dates'        => 'required|array|min:1',
            'dates.*'      => 'required|date',
        ]);

        $addon = MenuAddon_Model::create([
            'dish_id'      => $request->dish_id,
            'cafeteria_id' => $request->cafeteria_id,
            'name'         => $request->name,
            'status'       => 1,
        ]);

        foreach ($request->dates as $date) {
            MenuAddonDate_Model::create([
                'menu_addon_id'  => $addon->id,
                'available_date' => $date,
            ]);
        }

        return redirect()->route('admin.menu_addon')->with('success', 'Addon added successfully!');
    }

    public function update(Request $request, $id)
	{
		$request->validate([
			'dish_id'      => 'required|integer',
			'cafeteria_id' => 'required|integer',
			'name'         => 'required|string|max:255',
			'dates'        => 'nullable|array',
			'dates.*'      => 'nullable|date',
			'existing_dates'   => 'nullable|array',
			'existing_dates.*' => 'nullable|date',
		]);

		$addon = MenuAddon_Model::findOrFail($id);
		$addon->update([
			'dish_id'      => $request->dish_id,
			'cafeteria_id' => $request->cafeteria_id,
			'name'         => $request->name,
		]);

		// Merge locked existing dates with any newly added dates
		$existingDates = array_filter($request->input('existing_dates', []));
		$newDates      = array_filter($request->input('dates', []));
		$allDates      = array_unique(array_merge($existingDates, $newDates));

		if (empty($allDates)) {
			return redirect()->back()->with('error', 'At least one available date is required.');
		}

		// Replace all dates with the merged set (locked + new)
		MenuAddonDate_Model::where('menu_addon_id', $addon->id)->delete();
		foreach ($allDates as $date) {
			MenuAddonDate_Model::create([
				'menu_addon_id'  => $addon->id,
				'available_date' => $date,
			]);
		}

		return redirect()->route('admin.menu_addon')->with('success', 'Addon updated successfully!');
	}

    public function changeStatus($id)
    {
        $addon = MenuAddon_Model::findOrFail($id);
        $addon->status = $addon->status == 1 ? 0 : 1;
        $addon->save();

        return redirect()->back()->with('success', 'Status updated successfully!');
    }

    public function delete($id)
    {
        $addon = MenuAddon_Model::findOrFail($id);
        $addon->delete(); // dates cascade-delete via FK
        return redirect()->back()->with('success', 'Addon deleted successfully!');
    }

    // AJAX: get dish + available dates given a dish_id, for use in edit modal pre-fill (optional helper)
    public function getDishDates($dishId)
    {
        $addons = MenuAddon_Model::where('dish_id', $dishId)->with('dates')->get();
        return response()->json($addons);
    }
}