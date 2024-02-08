<?php

use App\Http\Controllers\DeviceController;
use App\Http\Controllers\PreorderController;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

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

Route::get('/devices', [DeviceController::class, 'index']);
Route::get('/devices/{device}', [DeviceController::class, 'show']);
Route::post('/preorder', PreorderController::class);
Route::middleware('auth:sanctum')->group(function (Router $router) {
    $router->apiResource('devices', DeviceController::class, ['except' => ['index', 'show']]);
    $router->get('/devices/{device}/po-customers', [DeviceController::class, 'preorderCustomers']);
});
