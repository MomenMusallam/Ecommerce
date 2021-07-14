<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ApiAuthController;
use App\Http\Controllers\API\BookmarkController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\CartController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\RatingController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

// our routes to be protected will go in here
Route::group(['prefix'=> 'user', 'middleware' => ['cors', 'json.response' , 'auth:api'] ], function () {
    Route::get('/logout', [ApiAuthController::class,'logout']);
    Route::get('/show', [ApiAuthController::class,'show']);
    Route::put('/update',[ApiAuthController::class,'updateUserInfo']);
    Route::put('/changepass',[ApiAuthController::class,'updateUserPass']);
    //Bookmark
    Route::post('/add/bookmark', [BookmarkController::class,'store']);
    Route::get('/bookmark', [BookmarkController::class,'show']);
    Route::delete('/bookmark/{id}', [BookmarkController::class,'destroy']);
    //Cart
    Route::post('/add/cart', [CartController::class,'store']);
    Route::get('/cart', [CartController::class,'show']);
    Route::put('/cart/{id}', [CartController::class,'update']);
    Route::delete('/cart/{id}', [CartController::class,'destroy']);
    //Order
    Route::post('/add/order', [OrderController::class,'store']);
    Route::get('/show/order/{id}/product', [OrderController::class,'showProductOfOrder']);
    Route::get('/show/order', [OrderController::class,'showOrderOfUSer']);

});

// our routes to be public will go in here
Route::group(['prefix'=> 'user', 'middleware' => ['cors', 'json.response']], function () {
    Route::post('/login', [ApiAuthController::class,'login']);
    Route::post('/register',[ApiAuthController::class,'register']);
});

Route::group([ 'middleware' => ['cors', 'json.response']], function () {
    //product
        Route::get('products' , [ProductController::class , 'index']);
        Route::get('product/{id}' , [ProductController::class , 'show']);
        //categories
        Route::get('/categories' , [CategoryController::class , 'index']);
        Route::get('category/{id}/product' , [CategoryController::class , 'show']);
});
