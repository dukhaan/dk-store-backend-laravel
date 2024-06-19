<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductCategoryController;
use App\Models\ProductCategory;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\TransactionItemController;
use App\Http\Controllers\TransactionDetailController;
use App\Http\Controllers\SessionController;

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

Route::get('products', [ProductController::class, 'all']);
Route::put('products/{id}', [ProductController::class, 'update']);
Route::post('products', [ProductController::class, 'store']);
Route::delete('products/{id}', [ProductController::class, 'destroy']);
Route::get('categories', [ProductCategoryController::class, 'all']);
Route::post('categories', [ProductCategoryController::class, 'store']);
Route::delete('categories/{id}', [ProductCategoryController::class, 'destroy']);
Route::put('categories/{id}', [ProductCategoryController::class, 'update']);
Route::post('register', [UserController::class, 'register']);
Route::post('login', [UserController::class, 'login']);
Route::post('transaction', [TransactionController::class, 'store']);
Route::put('transaction/{id}', [TransactionController::class, 'update']);
Route::delete('transaction/{id}', [TransactionController::class, 'destroy']);
Route::get('transactionItem', [TransactionItemController::class, 'all']);
Route::post('transactionItem', [TransactionItemController::class, 'store']);
Route::delete('transactionItem/{id}', [TransactionItemController::class, 'destroy']);
Route::put('transactionItem/{id}', [TransactionItemController::class, 'update']);


Route::middleware('auth:sanctum')->group(function () {
    Route::get('user', [UserController::class, 'fetch']);
    Route::post('user', [UserController::class, 'updateProfile']);
    Route::post('logout', [UserController::class, 'logout']);

    Route::get('transaction', [TransactionController::class, 'all']);
    Route::post('checkout', [TransactionController::class, 'checkout']);
});

Route::group(['middleware' => 'web'], function () {
    Route::get('session/get', [SessionController::class, 'accessSessionData']);
    Route::post('session/set', [SessionController::class, 'storeSessionData']);
    Route::delete('session/remove', [SessionController::class, 'deleteSessionData']);
});
