<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApartmentController;
use App\Http\Controllers\Api\ServiceController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/apartments', [ApartmentController::class, 'index']);
Route::get('apartments/{apartment:slug}', [ApartmentController::class, 'show']);
Route::post('apartments/{apartment:slug}/views', [ApartmentController::class, 'recordView']);
Route::post('apartments/{apartment:slug}/message', [ApartmentController::class, 'recordMessage']);
Route::get('search/apartments', [ApartmentController::class, 'search']);

Route::get('/services', [ServiceController::class, 'index']);