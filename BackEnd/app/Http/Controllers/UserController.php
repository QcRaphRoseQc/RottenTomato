<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UserRequest;
use PhpParser\Node\Stmt\TryCatch;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    //index
    public function index()
    {
       //dont return field password
         $users = User::all()->except('password');
    }
    public function register(UserRequest $request)
    {

        //check if the user exists if it does not return an error
        if(User::where('login', $request->login)->first()){
            return response()->json([
                'status' => 'Error',
                'message' => 'User already exists'
            ], 405);
        }
         
        try {
            $user = User::create([
                'login' => $request['login'],
                'password' => bcrypt($request['password']),
                'email' => $request['email'],
                'last_name' => $request['last_name'],
                'first_name' => $request['first_name'],
                'role_id' => $request['role_id']
            ]);
        }
        catch(Exception $e){
            abort(422,'Invalid data');
        }
        

        return response()->json([
			'status' => 'Success',
			'message' => 'User created',
			'data' => [
                'token' => $user->createToken('API Token')->plainTextToken,
                'id' => $user->id,
                'login' => $user->login,
                'email' => $user->email,
                'last_name' => $user->last_name,
                'first_name' => $user->first_name,
                'role_id' => $user->role_id
            ]
		], 201);
    }

    public function login(Request $request)
    {
        if (!Auth::attempt($request->toArray())) {
            return response()->json([
                'status' => 'Error',
                'message' => 'Invalid credentials'
            ], 401);
        }

        return response()->json([
			'status' => 'Success',
			'message' => 'Logged in',
			'data' => [
                'token' => Auth::user()->createToken('API Token')->plainTextToken,
                'id' => Auth::user()->id,
                'login' => Auth::user()->login,
                'email' => Auth::user()->email,
                'last_name' => Auth::user()->last_name,
                'first_name' => Auth::user()->first_name,
                'role_id' => Auth::user()->role_id
            ]
		], 200);
    }


    public function show(Request $request) {
       
        if(!User::find($request->id)) {
            return response()->json([
                'status' => 'error',
                'message' => 'This user does not exist',
            ], 404);
        }

        if(!Auth::check() || Auth::user()->id != $request->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'You cannot access other users informations',
            ], 401);
        }

        

        else {
            $user = User::find($request->id);
            return response()->json([
                'status' => 'Success',
                'message' => 'User found',
                'data' => $user
            ], 200);
       
        }
    }

    //showInfo
    public function showInfo(Request $request) {
        //retourner tout les info sur l'utilisateur sauf le mot de passe
        if(!User::find($request->id)) {
            return response()->json([
                'status' => 'error',
                'message' => 'This user does not exist',
            ], 404);
        }
        else{
            $user = User::find($request->id);
            return response()->json([
                'status' => 'Success',
                'message' => 'User found',
                'data' => $user
            ], 200);
        }
        }

    public function update(Request $request) {

        
            $userId = Auth::user()->id;
            $user = User::find($userId);            
            $user->email = $request->email;
            $user->last_name = $request->last_name;
            $user->first_name = $request->first_name;

            if(empty($request->email)||empty($request->last_name) ||empty($request->first_name)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'You must fill all the fields(email, last_name, first_name)',
                ], 422);
            }

            $user->save();
            return response()->json([
                'status' => 'Success',
                'message' => 'User updated',
                'data' => $user
            ], 200);
       
        
    }


    public function updatePassword(Request $request) {

        
            $user = User::find(Auth::user()->id);

            $old_password = ($request->old_password);          

            $new_password = ($request->new_password);           

            $confirm_password = ($request->confirm_password);
            

            if(!Hash::check($old_password, $user->password)){
                
                return response()->json([
                    'status' => 'error',
                    'message' => 'Wrong old password',
                ], 401);
            }

            if(Hash::check($new_password, $user->password)){
                
                return response()->json([
                    'status' => 'error',
                    'message' => 'New password is the same as the old one',
                ], 401);
            }

            if($new_password != $confirm_password){
                
                return response()->json([
                    'status' => 'error',
                    'message' => 'New password and confirmation password do not match',
                ], 401);
            }

            $user->password = bcrypt($new_password);
            $user->save();
            return response()->json([
                'status' => 'Success',
                'message' => 'User password updated',
                'data' => $user
            ], 200);
        
    }


    public function logout(Request $request) {
        auth()->user()->tokens()->delete();

        return [
            'message' => 'Logged out'
        ];
    }  


}

//insert into user
//insert into user (login, password, email, last_name, first_name, role_id) values ('admin', 'admin1234', 'admin@gmail.com', 'admin', 'admin', 1);
//insert into user (login, password, email, last_name, first_name, role_id) values ('user', 'user1234', 'user@gmail.com', 'user', 'user', 2);

