<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Laravel\Passport\Token;
use Illuminate\Http\Request;
use Laravel\Passport\Passport;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Repositories\User\Useraccess;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;



class AuthController extends Controller
{
    private $_request;
    private $_userobj;

    public function __construct(Request $request)
    {
        $this->_request = $request;
        $this->_userobj = new Useraccess();
    }

    public function register()
    {

        if ($this->_request->isMethod('post')) {

            $validateUser = Validator::make(
                $this->_request->all(),
                [
                    'email' => 'unique:users,email|required|email',
                    'password' => 'required',
                ]
            );
            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            $postedData = $this->_request->input();
            try {
                $register = $this->_userobj->userRegister($postedData);
                if ($register['return'] == 'success') {
                    return response()->json([
                        'status' => true,
                        'message' => 'User Created Successfully',
                        'User id' => $register['id'],
                        'email' => $register['email']
                    ], 200);
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => "User Not Created"
                    ], 500);
                }
            } catch (\Throwable $th) {
                return response()->json([
                    'status' => false,
                    'message' => $th->getMessage()
                ], 500);
            }
        }
    }

    public function loginUser(Request $request)
    {
        $validateUser = Validator::make(
            $request->all(),
            [
                'email' => 'required|email',
                'password' => 'required'
            ]
        );

        if ($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validateUser->errors()
            ], 401);
        }
        $user = User::where('email', '=', $request['email'])->where('password', '=', md5($request['password']))->first();
           // echo 'Data: <pre>' .print_r($user,true). '</pre>'; die;
        try {
            if (!empty($user)) {
                return response()->json([
                    'status' => true,
                    'message' => 'User Logged In Successfully',
                    'token' => $user->createToken("API TOKEN")->accessToken
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized'
                ], 401);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function userInfo()
    {
        try {
            $user = Auth::user();

            $id = $user->id;
            $email = $user->email;

            if ($user) {
                return response()->json([
                    'status' => true,
                    'message' => 'User found',
                    'user id' => $id,
                    'email' => $email,
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'User not found'
                ], 401);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
