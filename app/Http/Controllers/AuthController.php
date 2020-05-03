<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Auth;

use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Facades\JWTFactory;
use Tymon\JWTAuth\Exceptions\JWTExceptions;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Tymon\JWTAuth\PayloadFactory;
use Tymon\JWTAuth\JWTManager as JWT;

class AuthController extends Controller
{
    public function register(Request $request){

        $validator = Validator::make($request->json()->all(),[
            'name' => 'required|string|max:255',
            'phone' => 'required|max:12|unique:users',
            'password' => 'required|string|min:5',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(),400);
        }

        $user = User::create([
            'name' => $request->json()->get('name'),
            'phone' => $request->json()->get('phone'),
            'password' => Hash::make($request->json()->get('password'))
        ]);

        //$token = JWTAuth::fromUser($user);

        return response()->json(compact('user'), 201);
    }

    public function login(Request $request){

        $credentials = $request->json()->all();

        try{
            if(! $token  = JWTAuth::attempt($credentials)){
                return response()->json(['invalid_credentials'], 400);
            }
        } catch (JWTException $e){
            return response()->json(['could_not_create_token'], 500);
        }
        $user = Auth::user()->toArray();
        return response()->json(compact('token','user'));
    }
}
