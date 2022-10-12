<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    public function __construct()
    {
        $this->TBLUSERS_COL_USER_NAME = config("constants.TBLUSERS_COL_USER_NAME");
        $this->TBLUSERS_COL_USER_MIDDLENAME = config("constants.TBLUSERS_COL_USER_MIDDLENAME");
        $this->TBLUSERS_COL_USER_LASTNAME = config("constants.TBLUSERS_COL_USER_LASTNAME");
        $this->TBLUSERS_COL_USER_EMAIL = config("constants.TBLUSERS_COL_USER_EMAIL");
        $this->TBLUSERS_COL_USER_IMAGE = config("constants.TBLUSERS_COL_USER_IMAGE");
        $this->TBLUSERS_COL_USER_PASSWORD = config("constants.TBLUSERS_COL_USER_PASSWORD");
        $this->MSG_USER_CREATED = config("constants.MSG_USER_CREATED");
        $this->SUCCESS_STATUS_CODE = config("constants.SUCCESS_STATUS_CODE");
        $this->ERROR_STATUS_CODE = config("constants.ERROR_STATUS_CODE");
        $this->SUCCESS_MSG = config("constants.SUCCESS_MSG");
        $this->ERROR_MSG = config("constants.ERROR_MSG");
    }

    /** 
     * Display message
     */
    public function message(){
        return "hello";
    }

    /**
     * User Create Account
     */
    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            $this->TBLUSERS_COL_USER_NAME => 'required|string',
            $this->TBLUSERS_COL_USER_MIDDLENAME => 'required|string',
            $this->TBLUSERS_COL_USER_LASTNAME => 'required|string',
            $this->TBLUSERS_COL_USER_EMAIL => 'required|string|email|unique:users',
            $this->TBLUSERS_COL_USER_IMAGE => 'required|mimes:jpg,png,jpeg',
            $this->TBLUSERS_COL_USER_PASSWORD => 'required|string'
        ]);

        if($validator->fails()) 
        {
            return jsonResponseData($this->ERROR_STATUS_CODE , $validator->messages()->first(), null);
        }
       
        if(!empty($request->image))
        {
            $image = $request->file('image');
            $name = time().'.'.$image->getClientOriginalExtension();
            $destination_path = public_path('/images');
            $image->move($destination_path, $name);
            $userimage = 'images/'.$name;
        }

        $user = new User([
            $this->TBLUSERS_COL_USER_NAME => $request->name,
            $this->TBLUSERS_COL_USER_MIDDLENAME => $request->middle_name,
            $this->TBLUSERS_COL_USER_LASTNAME => $request->last_name,
            $this->TBLUSERS_COL_USER_EMAIL => $request->email,
            $this->TBLUSERS_COL_USER_IMAGE => $userimage,
            $this->TBLUSERS_COL_USER_PASSWORD => Hash::make($request->password)
        ]);
        //$token = $user->createToken('auth_token')->plainTextToken;
        $user->save();
       
        $requestData = [$this->TBLUSERS_COL_USER_NAME => $request->name,$this->TBLUSERS_COL_USER_MIDDLENAME => $request->middle_name,$this->TBLUSERS_COL_USER_LASTNAME =>$request->last_name];
        return jsonResponseData($this->SUCCESS_STATUS_CODE , $this->MSG_USER_CREATED, $requestData);
    }

    /**
     * Sign In
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            $this->TBLUSERS_COL_USER_EMAIL => 'required|string|email',
            $this->TBLUSERS_COL_USER_PASSWORD => 'required|string',
        ]);

        if($validator->fails()) 
        {
            return jsonResponseData($this->ERROR_STATUS_CODE , $validator->messages()->first(), null);
        }   
        $credentials = request(['email', 'password']);
        if(!Auth::attempt($credentials))
        {
            return jsonResponseData($this->ERROR_STATUS_CODE , 'Unauthorized',null);
        }
        $user = $request->user();
        $token = $user->createToken('auth_token')->plainTextToken;
        // print_r($token);
        // die();
        $userData = ['access_token' => $token];
        return jsonResponseData($this->SUCCESS_STATUS_CODE , "Successfully Login", $userData);
        
    }

    /**
     * User Authentication
     */
    public function user(Request $request){
        return $request->user();
    }
}
