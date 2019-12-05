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

    /**
     * Create a User
     * @param  Request $request
     * @return [type]
     */
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

        try {
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

            $reposne = [
                'result' => true,
                'user' => $user,
                'token' => [
                    'access_token' => $token,
                    'token_type' => 'bearer',
                    'expires_in' => auth()->factory()->getTTL() * 60
                ]
            ];
        } catch (Exception $e) {
            $reposne['error'] = 'Something Went Wrong!!';
        }

        // return response()->json(compact('user', 'token'), 201);
        return response()->json($reposne);
    }

    public function login(Request $request)
    {
        $reposne = ['result' => false];
        // Getting Input Value
        $credentials = $request->only('email', 'password');

        // Login and and Creating Json Web Token "jwt" 
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'There is no user record corresponding to this identifier'], 401);
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

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        $reposne = ['result' => false];
        $user = auth()->user();

        if ($user) {
            $reposne['result'] = true;
            $reposne['user'] = $user;
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return response()->json($reposne);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['result' => true, 'message' => 'Successfully logged out']);
    }
}
