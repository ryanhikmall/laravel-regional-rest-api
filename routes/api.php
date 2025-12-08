<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProvinceController;
use App\Http\Controllers\Api\CityController;
use App\Http\Controllers\Api\DistrictController;
use App\Http\Controllers\Api\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// --- AUTH ROUTES (PUBLIC) ---
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// --- PROTECTED ROUTES (Harus Login / Punya Token) ---
// PENTING: Gunakan 'auth:sanctum', bukan 'auth:api'
Route::middleware(['auth:sanctum'])->group(function () {
    
    // Auth Check
    Route::get('me', [AuthController::class, 'me']);
    
    // Logout (Gunakan POST agar lebih aman, atau GET jika dipaksa modul)
    Route::post('/logout', [AuthController::class, 'logout']); 
    
    // Refresh (Hanya jika Anda sudah buat fungsinya di AuthController)
    // Route::post('/refresh', [AuthController::class, 'refresh']); 

    // --- DATA MASTER ---
    
    // Province Routes
    Route::get('province', [ProvinceController::class, 'index']);
    Route::post('province', [ProvinceController::class, 'create']);
    Route::get('province/{id}', [ProvinceController::class, 'detail']);
    Route::put('province/{id}', [ProvinceController::class, 'update']);
    Route::delete('province/{id}', [ProvinceController::class, 'delete']);

    // City Routes
    Route::get('city', [CityController::class, 'index']); 
    Route::get('city/province/{province_id}', [CityController::class, 'getByProvince']);
    Route::post('city', [CityController::class, 'create']); 
    Route::get('city/{id}', [CityController::class, 'detail']); 
    Route::put('city/{id}', [CityController::class, 'update']); 
    Route::delete('city/{id}', [CityController::class, 'delete']); 

    // District Routes
    Route::get('district', [DistrictController::class, 'index']); 
    Route::get('district/city/{city_id}', [DistrictController::class, 'getByCity']); 
    Route::post('district', [DistrictController::class, 'create']); 
    Route::get('district/{id}', [DistrictController::class, 'detail']); 
    Route::put('district/{id}', [DistrictController::class, 'update']); 
    Route::delete('district/{id}', [DistrictController::class, 'delete']); 
});