<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Exception;
use Tymon\JWTAuth\Exceptions\JWTException;
use Activation;
use Sentinel;

class UserController extends Controller
{

    /**
     * Create a new UserController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function register(Request $request)
    {
        // Validating the Input
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:50',
            'second_name' => 'required|string|max:50',
            'email' => 'required|string|email|max:50|unique:users',
            'password' => 'required|string|min:3',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        // Getting Input Value
        $credentials = [
            'first_name' => $request->first_name,
            'second_name' => $request->second_name,
            'email' => $request->email,
            // 'password' => Hash::make($request->get('password')),
            'password' => bcrypt($request->password),
        ];
        // Registering The User using the default auth not Sentinel
        $user = User::create($credentials);

        // Assign Role
        if ($request->role == 'admin') {

            // gettting the user id coz sentinel Activation::create uses a user return by Sentinel only not the default auth.
            $sentinelUser =  Sentinel::findById($user->id);
            $activation = Activation::create($sentinelUser); // activate the user

            // Assigning the user the "Admin" Role
            $role = Sentinel::findRoleBySlug('admin');
            $role->users()->attach($user);
        } elseif ($request->role == 'user') {

            // Assigning the user the "User" Role
            $role = Sentinel::findRoleBySlug('user');
            $role->users()->attach($user);
        } else {
            // Do nothing
        }

        // Creating Json Web Token "jwt"
        $token = JWTAuth::fromUser($user);

        return response()->json(compact('user', 'token'), 201);
    }

    public function login(Request $request)
    {
        $reposne = ['result' => false];
        // Getting Input Value
        $credentials = $request->only('email', 'password');

        // Login and and Creating Json Web Token "jwt" 
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 400);
            } else {
                $reposne = [
                    'result' => true,
                    'token' => [
                        'access_token' => $token,
                        'token_type' => 'bearer',
                        'expires_in' => auth()->factory()->getTTL() * 60
                    ]
                ];
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        // return response()->json(compact('token'));
        return response()->json($reposne);
    }
}
