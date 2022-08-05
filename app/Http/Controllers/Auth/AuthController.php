<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    //
    public function signup(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|max:255',
            ]);

            if($validator->fails()) {
                return response([
                    'errors' => $validator->errors()->all()
                ], 422);
            }

            $password = Hash::make($request->password);
            $remember_token = Str::random(env('TOKEN_LENGTH'));

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $password,
                'remember_token' => $remember_token,
            ]);

            return response()->json([
                'status_code' => 200,
                'message' => 'Registration Successful',
            ]);


        } catch (Exception $errors) {

            return response()->json([
                'status_code' => 500,
                'message' => 'Error Occured in Registration',
                'error' => $errors,
            ]);
        }
    }
}
