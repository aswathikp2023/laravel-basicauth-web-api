<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

use App\Models\Company;
use DataTables;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         return view('company.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('company.create_form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|unique:companies',
            'website' => 'required',
            'name' => 'required',
            'logo' => 'required|dimensions:min_width=100,min_height=100'
            // /image|mimes:jpeg,jpg,png,bmp,gif,svg
            // dimensions:min_width=100,min_height=200
        ]);

         if ($validator->fails()) {
            return redirect('company/create')
                        ->withErrors($validator)
                        ->withInput();
        }

        $file =  $request->logo;
        $fileName = time().'_'.$file->getClientOriginalName();
        $name = $file->storeAs('logos', $fileName, 'public');

        Company::insert([
            'name' => $request->name,
            'email' => $request->email,
            'website' => $request->website,
            'logo' => $name,
        ]);

        return back()->with('success','Company created successfully!');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
             $model = Company::select( ['id','name',
                            'email',
                            'logo',
                            'website']);
 

            $dt= Datatables::of($model)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
       
                            $btn = '<a href="'.url('company/'.$row->id.'/edit').'" class="edit btn btn-primary btn-sm">Edit</a>';
                            $deleteButton = "<button class='btn btn-sm btn-danger deleteUser' onclick='deleteUser(".$row->id.")' data-id='".$row->id."'>Delete</button>";

                            return $btn.' '.$deleteButton;
                    })
                    ->rawColumns(['action'])
                    ->make(true);

                    return $dt;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $company = Company::find($id);
        return view('company.edit',compact('company'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $company = Company::find($id);
        
        $validator = Validator::make($request->all(), [
            'email' => ['required', Rule::unique('companies')->ignore($id)],
            'website' => 'required',
            'name' => 'required',
            'logo' => 'sometimes|dimensions:min_width=100,min_height=100'
            // /image|mimes:jpeg,jpg,png,bmp,gif,svg
            // dimensions:min_width=100,min_height=200
        ]);

         if ($validator->fails()) {
            return redirect('company/'.$id.'/edit')
                        ->withErrors($validator)
                        ->withInput();
        }
        if($request->file('logo')){
            $file =  $request->logo;
            $fileName = time().'_'.$file->getClientOriginalName();
            $name = $file->storeAs('logos', $fileName, 'public');
 
            if(Storage::disk('public')->exists($company->logo)){
                Storage::disk('public')->delete($company->logo);
            }
            $company->logo = $name;
        }

        
        $company->name = $request->name;
        $company->email = $request->email;
        $company->website = $request->website;
        $company->save();

        return back()->with('success','Company updated successfully!');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $company = Company::find($id);
        if(Storage::disk('public')->exists($company->logo)){
            Storage::disk('public')->delete($company->logo);
        }
        if(Company::destroy($id)){
        return response()->json([
            'message' => 'Record is deleted',
            'status' => '200',
        ]);}
        else{
            return response()->json([
                'message' => 'Something went wrong',
                'status' => '400',
            ]);
        }
    }
}
