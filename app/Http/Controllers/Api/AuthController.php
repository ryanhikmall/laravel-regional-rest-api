<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Carbon\Carbon;

// Jika kamu belum punya ApiFormatter, hapus baris use ini dan ganti return response()->json(...)
use App\Helpers\ApiFormatter; 

class AuthController extends Controller
{
    /**
     * Register User Baru (Fungsi yang sebelumnya hilang)
     */
    public function register(Request $request)
    {
        // 1. Validasi Input
        $validator = Validator::make($request->all(), [
            'name'      => 'required|string|max:255',
            'email'     => 'required|string|email|max:255|unique:users',
            'password'  => 'required|string|min:6', // Hapus 'confirmed' jika di Postman tidak kirim password_confirmation
        ]);

        if ($validator->fails()) {
            // Kita pakai response json standar agar aman jika ApiFormatter bermasalah
            return response()->json([
                'code' => 422,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        // 2. Buat User
        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => bcrypt($request->password),
        ]);

        // 3. Respon Sukses
        if($user) {
            return response()->json([
                'code' => 201,
                'message' => 'User Created Successfully',
                'data' => $user
            ], 201);
        }

        return response()->json([
            'code' => 409,
            'message' => 'User Registration Failed'
        ], 409);
    }

    /**
     * Login User dan dapatkan Token
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $credentials = $request->only('email', 'password');

        if (!$token = auth()->guard('api')->attempt($credentials)) {
            return response()->json([
                'code' => 401,
                'message' => 'Unauthorized',
                'error' => 'Invalid Credentials'
            ], 401);
        }

        return $this->respondWithToken($token, 'Login success');

    }

    /**
     * Dapatkan User yang sedang login (Me)
     */
    public function me()
    {
        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                 return response()->json(['message' => 'User not found'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Invalid Token'], 401);
        }

        return response()->json([
            'code' => 200,
            'message' => 'Success',
            'data' => $user
        ]);
    }

    /**
     * Logout User
     */
    public function logout()
    {
        auth()->guard('api')->logout();

        return response()->json([
            'code' => 200,
            'message' => 'Successfully logged out'
        ]);
    }

    /**
     * Refresh Token
     */
   public function refresh()
{
    return $this->respondWithToken(
        auth()->guard('api')->refresh(),
        'Token berhasil diperbarui'
    );
}


    /**
     * Helper response token
     */
    protected function respondWithToken($token, $message = 'Login Success')
{
    return response()->json([
        'code' => 200,
        'message' => $message,
        'data' => [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->guard('api')->factory()->getTTL() * 60
        ]
    ]);
}
}