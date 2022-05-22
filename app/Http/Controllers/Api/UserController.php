<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function login(Request $requset)
    {
        // dd($request->all());die;
        $user = User::where('email', $requset->email)->first();

        if ($user) {
            if (password_verify($requset->password, $user->password)) {
                return response()->json([
                    'success' => 1,
                    'message' => 'Selamat Datang ' . $user->name,
                    'user' => $user
                ]);
            }
            return $this->error('Password Salah!');
        }
        return $this->error('Email Tidak Terdaftar!');
    }

    public function register(Request $requset)
    {
        //nama, email, password
        $validasi = Validator::make($requset->all(), [
            'name' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required|min:6'
        ]);

        if ($validasi->fails()) {
            $val = $validasi->errors()->all();
            return $this->error($val[0]);
        }

        $user = User::create(array_merge($requset->all(), [
            'password' => bcrypt($requset->password)
        ]));

        if ($user) {
            return response()->json([
                'success' => 1,
                'message' => 'Berhasil Register',
                'user' => $user
            ]);
        }
        return $this->error('Register Gagal');
    }
    //function untuk return error
    public function error($pasan)
    {
        return response()->json([
            'success' => 0,
            'message' => $pasan
        ]);
    }
}
