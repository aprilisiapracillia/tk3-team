<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $data = [
            "username" => $request->username,
            "password" => $request->password
        ];
        $login = Auth::attempt($data);
        if ($login) {
            $user = Auth::user();
            $user->setRememberToken(Str::random(100));
            $user->save();

            return response()->json([
                'status' => 200,
                'message' => 'Login Berhasil',
                'data' => $user
            ]);
        }else{
            return response()->json([
                'status' => 404,
                'message' => 'Username atau Password Tidak Ditemukan!'
            ]);
        }
    }
}
