<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Auth\Events\Registered;

class AuthController extends Controller
{

    public function register(Request $request)
    {
        $registrationData = $request->all();

        $validate = Validator::make($registrationData, [
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required',
            'nomor_telepon' => 'required|string',
            'image' => 'required'

        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);
        
        $uploadFolder = 'users';
        $image = $request->file('image');
        $image_uploaded_path = $image->store($uploadFolder, 'public');

        $registrationData['password'] = bcrypt($request->password);
        $registrationData['image'] = basename($image_uploaded_path);

        $user = User::create($registrationData);

        event(new Registered($user));

        return response()->json([
            'message' => 'Successfully created user!'
        ], 200);
    }

    // login
    public function login (Request $request){
        $loginData = $request->all();

        $validate = Validator::make($loginData, [
            'email' => 'required|email:rfc,dns',
            'password' => 'required'
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);

        if(!Auth::attempt($loginData))
            return response(['message' => 'Invalid Credentials'], 401);

        $user = Auth::user();

        if(!$user->hasVerifiedEmail()){
            return response([
                'message' => 'Email Not Verified',
                'data' => null
            ], 401);
        }

        
        
        $token = $user->createToken('Authentication Token')->accessToken;

        return response([
            'message' => 'Authenticated',
            'user' => $user,
            'token_type' => 'Bearer',
            'access_token' => $token

        ]);
    }

    public function logout (Request $request){
        $request->user()->token()->revoke();
        return response()->json([
        'message' => 'Successfully logged out'
        ]);
    }

    public function update(Request $request, $id)
    {

        $user = User::find($id);
        if(is_null($user)){
            return response([
                'message' => 'User Not Found',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'name' => 'required|string',
            'nomor_telepon' => 'required|string',
            'image' => 'required'

        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $uploadFolder = 'users';
        $image = $request->file('image');
        $image_uploaded_path = $image->store($uploadFolder, 'public');


        $user->name = $updateData['name'];
        $user->nomor_telepon = $updateData['nomor_telepon'];
        $user->image = basename($image_uploaded_path);


        if($user->save()){
            return response([
                'message' => 'Update User Success',
                'data' => $user
            ], 200);
        }

        

        return response([
            'message' => 'Update User Failed',
            'data' => null
        ], 400);
    }

    // show by id
    public function show($id)
    {
        $user = User::find($id);
        if(is_null($user)){
            return response([
                'message' => 'User Not Found',
                'data' => null
            ], 404);
        }
        return response([
            'message' => 'User Found',
            'user' => $user
        ], 200);

    }


}