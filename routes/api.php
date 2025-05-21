<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SqlLaravel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/register',[AuthController::class, 'register'] );
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);


Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('userseps', 'App\Http\Controllers\UsersepsController');
    Route::apiResource('healthcenters', 'App\Http\Controllers\HealthcenterController');
    Route::apiResource('Specialtydoctors', 'App\Http\Controllers\SpecialtydoctorsController');
    Route::apiResource('doctors', 'App\Http\Controllers\DoctorsController');
    Route::apiResource('quotes', 'App\Http\Controllers\QuotesController');
});








































// //compound queries
// Route::get('/usergeteps', [SqlLaravel::class, 'getUsersEps']);
// Route::get('/quotesuser', [SqlLaravel::class, 'getQuotesUser']);
// Route::get('/doctorsspecialty', [SqlLaravel::class, 'getDoctorsSpecialty']);
// Route::get('/doctorsquotes', [SqlLaravel::class, 'getDoctorsQuotes']);
// Route::get('/activedDctors', [SqlLaravel::class, 'getdActivedDctors']);

