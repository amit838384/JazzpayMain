<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProfessionRequest;
use App\Http\Requests\UpdateProfessionRequest;
use App\Models\Admin\Profession;
use App\Models\Api\sub_profession;


use Illuminate\Http\Request;

class ProfessionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
       $this->middleware('permission:create-profession|edit-profession|delete-profession', ['only' => ['index','show']]);
       $this->middleware('permission:create-profession', ['only' => ['create','store']]);
       $this->middleware('permission:edit-profession', ['only' => ['edit','update']]);
       $this->middleware('permission:delete-profession', ['only' => ['destroy']]);
    }

    public function index()
    {
    $data = Profession::latest()->paginate(30);

    $subprofession['profession'] = sub_profession::all();

    return view('admin.profession.index', [
        'profession' => $data,
        'subprofession' => $subprofession
    ]);
    }


    public function create()
    {
        return view('admin.profession.create');
    }


    public function store(StoreProfessionRequest $request)
       {
        // dd($request->all());
        profession::create($request->all());
        return redirect()->route('profession.index')
                ->withSuccess('Profession is added successfully.');
    }

    public function show(Profession $Profession)
    {
       
        return view('admin.profession.show', [
            'Profession' => $Profession
        ]);
    }


    public function edit(profession $profession)
    {
        return view('admin.profession.edit', [
            'profession' => $profession
        ]);
    }

    public function update(UpdateProfessionRequest $request, profession $profession)
    {
        $profession->update($request->all());
        return redirect()->route('profession.index')
                ->withSuccess('profession is updated successfully.');
    }


    public function destroy(profession $profession)
    {
        $profession->delete();
        return redirect()->route('profession.index')
                ->withSuccess('profession is deleted successfully.');
    }

    public function updateStatus(Request $request, $id)
    {
        $profession = Profession::findOrFail($id);

        $profession->status = $request->input('status');
        $profession->save();

        return redirect()->route('profession.index')->with('success', 'Status updated successfully.');
    }



    public function subprofession(profession $profession)
    {
        // echo $profession;
         $profser = $profession->id; 

        $data['profession'] = Profession::findOrFail($profser);


        $data['professionser'] = sub_profession::where('prof_id', $profser)->get();
        
        return view('admin.profession.subprofession', $data);

    }
    //////////////////////-end here-////////////////////////
}
