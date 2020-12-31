<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
class UserController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth:sanctum')->only(['user','passwordReset','logout']);
    }
    public function store(Request $request){
        $rules = [
            'name'=> 'required',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed'
        ];
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            return response(['error'=>$validator->errors()->all()], 422);
        }
        $userExists = User::where('email', $request->email)->first();
        if($userExists){
            return response(['error' => 'Email has already been taken, please try anotherone']);
        }
        $data = $request->all();
        $data['password'] = Hash::make($request->password);
        $data['admin'] = false;
        $user = User::create($data);

        return response($user);
    }
    public function login(Request $request){
        $rules =[
            'email' => 'required|email',
            'password'=> 'required|min:6'
        ];
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()){
            return response(["error" => "Wrong email and/or password"],404);
        }

        $user = User::where('email','=',$request->email)->firstOrFail();

        if(! Hash::check($request->password, $user->password)){
            return response(["error" => "Credentials do not match"], 404);
        }
        //dd($user->admin);
            $token = $user->createToken('token',['role' => $user->admin === 1 ? 'admin' : 'user']);
        
        return response(['token' => $token->plainTextToken], 200);
    }
    public function logout(Request $request){
        $request->user()->tokens()->delete();
        return response(["message" => "User logged out."]);
    }
    public function user(Request $request){
            $user = $request->user();
            if($user->tokenCan('admin')){
                $user['scope'] = array('admin');
            }
            if($user->tokenCan('user')){
                $user['scope'] = array('user');
            }

        return response(['user' => $user]);
    }
}
