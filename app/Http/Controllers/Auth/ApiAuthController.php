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
use Laravel\Passport\HasApiTokens;
use Laravel\Passport\RefreshToken;
use Laravel\Passport\Token;

class ApiAuthController extends Controller
{
    use HasApiTokens;
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
//        $request['remember_token'] = Str::random(10);
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
//        $token = $request->user()->token();
//        $token->revoke();

        $user = $request->user();
        $tokens =  $user->tokens->pluck('id');
        Token::whereIn('id', $tokens)
            ->update(['revoked' => true]);
        return $this->returnSuccessMessage('logout successfully',200 );
    }


    public function show (Request $request)
    {
        return $this->returnData(true , 'User data' , $request->user() , 200);
    }


    public function updateUserInfo (Request $request)
    {
       $user = $request->user()->update($request->all());
//           return \response()->json($user);
        return $this->returnData(true , 'User data' , User::find($request->user()->id) , 200);
    }


    public function updateUserPass (Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required|string|min:6',
            'new_password' => 'required|string|min:6|confirmed',
        ]);
        if ($validator->fails())
        {
          return $this->returnError('something is wrong' , 403);
        }

        $user = $request->user();
        if ($user) {
            if (Hash::check($request->old_password, $user->password)) {
                $request['new_password']=Hash::make($request['new_password']);
                $user->password = $request['new_password'];
                if($user->save()){
                    $tokenUsed = $request->user()->token();
                    $tokens =  $user->tokens->pluck('id');
                    foreach ($tokens as $token){
                        if($token != $tokenUsed->id){
                            Token::where('id' , $token)
                                ->update(['revoked' => true]);
                        }
                    }
                    $user = User::find($request->user()->id) ;
                    return $this->returnData(true , 'password changed Successfully',$user, 200);
                }else {
                    return $this->returnError('Something is Wrong', 422);
                }
            } else {
                return $this->returnError('Something is Wrong', 422);
            }
    }
    }
}
