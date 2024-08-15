<?php

namespace App\Http\Controllers;

use Validator;
use App\Models\User;
use App\Enums\HttpCodesEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Classes\ApiResponseClass;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|string|min:8',
            'c_password' => 'required|same:password',
        ]);

        if($validator->fails()){
            //return $this->sendError('Validation Error.', $validator->errors());
            return ApiResponseClass::throw($validator->errors(), 'Validation Error.', HttpCodesEnum::UNAUTHORIZED);
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] =  $user->createToken('MyApp')->plainTextToken;
        $success['name'] =  $user->name;

        //return $this->sendResponse($success, 'User register successfully.');
        return ApiResponseClass::sendResponse($success, HttpCodesEnum::CREATED);
    }

    /**
     * Login api
     *
     * @return \Illuminate\Http\Response | void
     */
    public function login(Request $request)
    {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
            $user = Auth::user(); 
            $success['token'] =  $user->createToken('MyApp')->plainTextToken; 
            $success['name'] =  $user->name;

            //return $this->sendResponse($success, 'User login successfully.');
            return ApiResponseClass::sendResponse($success,'User login successfully.');
        }
        else{
            //return $this->sendError('Unauthorized.', ['error'=>'Unauthorized']);
            return ApiResponseClass::throw('Unauthorized', 'Unauthorized.', HttpCodesEnum::UNAUTHORIZED);
        }
    }
}
