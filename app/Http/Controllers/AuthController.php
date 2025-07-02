<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\User;



class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
        ]);

        if ($validated->fails()) {
            return response()->json(['errors' => $validated->errors()], 422);
        }
        $user = User::create([
            'email' => $request->email,
            'password' => sha1($request->password),
            'api_key' => Str::random(60),
        ]);

        return response()->json(['user' => $user], 201);
    }
    public function login(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validated->fails()) {
            return response()->json(['errors' => $validated->errors()], 422);
        }
        $user = User::where('email', $request->email)->first();
        if (!$user || sha1($request->password) !== $user->password) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }
        return response()->json(['user' => $user], 200);
    }
}

