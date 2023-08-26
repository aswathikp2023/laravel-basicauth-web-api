<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Employee;
use DataTables;
use Illuminate\Validation\Rule;

use Illuminate\Support\Facades\Validator;


class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('employee.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $company = Company::select('id','name')->get();
        return view('employee.create', compact('company'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|unique:employees',
            'phone' => 'required|unique:employees|numeric|digits:10',
            'firstname' => 'required',
            'lastname' => 'required',
            'company' => 'required|numeric'
        ]);

         if ($validator->fails()) {
            return redirect('employee/create')
                        ->withErrors($validator)
                        ->withInput();
        }
        Employee::insert([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'phone' => $request->phone,
            'company' => $request->company,
        ]);

        return back()->with('success','Employee created successfully!');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        
        $model = Employee::With('company')->get();
        $dt= Datatables::of($model)
        ->addIndexColumn()

        ->addColumn('action', function($row){

                $btn = '<a href="'.url('employee/'.$row->id.'/edit').'" class="edit btn btn-primary btn-sm">Edit</a>';
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
        $employee = Employee::find($id);
        $company = Company::select('id','name')->get();
        return view('employee.edit',compact('employee','company'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $employee = Employee::find($id);
        
        $validator = Validator::make($request->all(), [
            'email' => ['required', Rule::unique('employees')->ignore($id)],
            'phone' => ['required','numeric','digits:10', Rule::unique('employees')->ignore($id)],
            'firstname' => 'required',
            'lastname' => 'required',
            'company' => 'required|numeric'
        ]);

         if ($validator->fails()) {
            return redirect('employee/'.$id.'/edit')
                        ->withErrors($validator)
                        ->withInput();
        }
        
        $employee->update($request->all());

        return back()->with('success','Company updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $company = Employee::find($id);
        if(Employee::destroy($id)){
            return response()->json([
                'message' => 'Record is deleted',
                'status' => '200',
            ]);
        }
        else{
            return response()->json([
                'message' => 'Something went wrong',
                'status' => '400',
            ]);
        }
    }
}
