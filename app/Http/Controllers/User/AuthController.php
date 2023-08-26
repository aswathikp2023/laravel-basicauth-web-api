<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Models\Company;
use App\Models\Employee;

class AuthController extends Controller
{
    public function __construct(){
        $this->middleware('auth:api',['except' => ['login','adminlogout','allemployees']]);
    }

    public function login(Request $request) 
    {
        if(!$request->email || !$request->password){
            return response()->json([
                'status' => false,
                'message' => 'Please enter valid credentials!'
            ], 422);
        };
    
    
        $user = User::where('email', $request->email)
                    ->where('is_admin',1)
                    ->first();
    
        if(!$user){
            return response()->json([
                'status' => false,
                'message' => 'User not found'
            ], 404);
        };
    

        if (! Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Provided credentials do not match'
            ], 404);
        }
        
        $user->tokens()->delete();
        $token = $user->createToken('user_login_token')->plainTextToken;

        return response()->json([
            'status' => true, 
            'message' => 'User logged in successfully',  
            'user' => $user, 
            'token' => $token], 201
        );
    }

   public function allemployees(Request $request){
            $data = Employee::With('company')->get()->toArray();
            return response()->json([
                'status' => true, 
                'message' => 'Fetched all employees',  
                'data' => $data
                ], 200
            );
   }

    public function adminlogout(Request $request)
    {   
        if (auth('sanctum')->user()->tokens()->delete()) {
            return response()->json(['success' =>'logout_success'],200); 
        }else{
            return response()->json(['error' =>'api.something_went_wrong'], 500);
        }
    }
}
