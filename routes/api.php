<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::group(['middleware' => 'auth:sanctum'], function(){
    Route::get('products', [ProductController::class, 'index']);

    Route::group(['prefix' => 'cart', 'controller' => CartController::class], function(){
        Route::get('/', 'index');
        Route::post('/{product}', 'store');
        Route::put('/{product}', 'update');
        Route::delete('/deleteAll', 'deleteAllProducts');
        Route::get('/order/check-out', 'checkOut');
    });

    Route::group(['prefix' => 'orders', 'controller' => OrderController::class], function(){
        Route::get('/', 'index');
        // Route::post('/', 'store');
        // Route::put('/{product}', 'update');
        // Route::delete('/deleteAll', 'deleteAllProducts');
    });


    Route::post('logout', [AuthController::class, 'logout']);
});


Route::group(['prefix' => 'categories'], function () {
    Route::get('/', [CategoryController::class, 'index']);
    Route::post('/', [CategoryController::class, 'store']);
    Route::get('/{category:slug}', [CategoryController::class, 'show']);
    Route::put('/{category:slug}', [CategoryController::class, 'update']);
    Route::delete('/{category:slug}', [CategoryController::class, 'destroy']);
    Route::delete('/{category:slug}/force_delete', [CategoryController::class, 'forceDestroy']);
});
