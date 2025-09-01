<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
use Illuminate\Support\Str;
use App\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {

        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        $username = $request->input('username');
        $password = $request->input('password');


        if (Auth::attempt(['email' => $username, 'password' => $password])) {
            $user = Auth::user();
            $user->api_token = Str::random(60);
            $user->save();

            return response()->json([
                'success' => true,
                'token' => $user->api_token,
                'user_id' => $user->id,
            ], 200);
        }
        else{
            $message ='Authentication failed';
        }

        return response()->json([
                'success' => false,
                'message' => $message
            ], 401);

        
        }
}