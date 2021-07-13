<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Traits\APITrait;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\User;
class ApiAuthController extends Controller
{
    use APITrait;
    public function register (Request $request) {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
        if ($validator->fails())
        {
            return $this->returnError($validator->errors()->first(), 422);
        }
        $request['password']=Hash::make($request['password']);
        $request['remember_token'] = Str::random(10);
        $user = User::create($request->toArray());
        $token = $user->createToken('Laravel Password Grant Client')->accessToken;
        $user->api_token = $token;
        return $this->returnData(true , 'Create User Done Successfully',$user, 200);
    }



    public function login (Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails())
        {
            return $this->returnError($validator->errors()->all(), 422);
        }
        $user = User::where('email', $request->email)->first();
        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $token = $user->createToken('User Login From App')->accessToken;
                $user->api_token = $token;
                return $this->returnData(true , 'User Login Successfully',$user, 200);
            } else {
                $response = ["message" => "Password mismatch"];
                return $this->returnError('Something is Wrong', 422);
            }
        } else {
            $response = ["message" =>'User does not exist'];
            return $this->returnError('Something is Wrong', 422);
        }
    }



    public function logout (Request $request) {
        $token = $request->user()->token();
        $token->revoke();
            return $this->returnSuccessMessage('successfully',200 );
//
    }
    public function show (Request $request)
    {
        return $this->returnData(true , 'User Login Successfully' , $request->user() , 200);
    }
}
