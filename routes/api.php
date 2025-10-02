<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\VNPayController;

use App\Http\Controllers\MoMoController;
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

// Route kiểm tra thông tin người dùng (cần auth)
Route::group(['middleware' => ['auth:api']], function () {
    Route::get('/profile', [AuthController::class, 'userProfile']);
    Route::post('/logout', [AuthController::class, 'logout']);
});

// Route API cho sản phẩm
Route::apiResource('products', ProductController::class);

Route::post('/cart/add', [CartController::class, 'addToCart']);  // Thêm sản phẩm
Route::get('/cart', [CartController::class, 'getCart']);         // Lấy giỏ hàng
Route::delete('/cart/remove/{id}', [CartController::class, 'removeFromCart']); // Xóa sản phẩm

Route::post('/orders', [OrderController::class, 'store']);
Route::get('/orders', [OrderController::class, 'index']);

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);


// 🟢 API VNPay (KHÔNG cần authentication vì người dùng thanh toán từ web)


Route::post('/vnpay-payment', [VNPayController::class, 'createPayment']);
Route::get('/vnpay-return', [VNPayController::class, 'vnpayReturn']);


Route::get('/products/category/{category}', [ProductController::class, 'getProductsByCategory']);
Route::get('/api/products/category/{categoryName}', [ProductController::class, 'getProductsByCategory']);


Route::post('/momo-payment', [MoMoController::class, 'createPayment']);
Route::get('/momo-return', [MoMoController::class, 'paymentReturn']);
Route::post('/momo-ipn', [MoMoController::class, 'ipn']);