<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        //Validate data
        $data = $request->only('firstName','lastName', 'email', 'password');
        $validator =  Validator::make($data, [
            'firstName' => 'required|string',
            'lastName' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6|max:50'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->sendError('error', $validator->messages(), 200);
        }

        //Request is valid, create new user
        $user = User::create([
            'firstName' => $request->firstName,
            'lastName' => $request->lastName,
            'email' => $request->email,
            'password' => \Hash::make($request->password)
        ]);

        //User created, return success response
        return response()->sendSuccess('Success Register',$user);
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');

        //valid credential
        $validator = Validator::make($credentials, [
            'email' => 'required|email',
            'password' => 'required|string|min:6|max:50'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->sendError('error', $validator->messages(), 200);
        }

        //Request is validated
        //Crean token
        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->sendError('Login credentials are invalid.',null,Response::HTTP_BAD_REQUEST);
            }
        } catch (JWTException $e) {
            return response()->sendError('Could not create token.',null,Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        //Token created, return with success response and jwt token
        return response()->sendSuccess('Success',['token'=>$token], 200);

    }

    public function logout(Request $request)
    {

        //Request is validated, do logout
        try {
            JWTAuth::invalidate($request->token);
            return response()->sendSuccess('User has been logged out',null);
        } catch (JWTException $exception) {
            return response()->sendError('Sorry, user cannot be logged out',null,Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function get_user(Request $request)
    {
        $user = JWTAuth::authenticate($request->token);

        return response()->sendSuccess('Success',$user);
    }
}
