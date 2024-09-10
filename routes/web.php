<?php

use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\api\ReportController;
use App\Http\Controllers\Api\InvoiceController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Middleware\TokenVerificationMiddleware;


// Api routes
Route::controller(UserController::class)->group(function () {
    Route::post('/register', 'userRegistration');
    Route::post('/login', 'userLogin');
    Route::post('/send-otp', 'sendOtp');
    Route::post('/verify-otp', 'verifyOtp');
});

Route::group(['middleware' => TokenVerificationMiddleware::class], function () {
    // user profile
    Route::controller(UserController::class)->group(function () {
        Route::get('/user/profile', 'getUser');
        Route::patch('/user/reset-password', 'resetPassword');
        Route::put('/user/profile', 'updateUser');
    });
    
    // logout routes
    Route::post('/logout', [UserController::class, 'userLogout']);
    
    // Customer
    Route::Resource('/customers', CustomerController::class);

    // Category
    Route::Resource('/categories', CategoryController::class);

    // Product
    Route::Resource('/products', ProductController::class);
    Route::post('/products/{product}', [ProductController::class, 'update']); // Update product with post, if PATCH OR PUT method not working

    // Invoice
    Route::Resource('/invoices', InvoiceController::class);

    // Report
    Route::get('/sales-report/{fromDate}/{toDate}', [ReportController::class, 'salesReport']);
    Route::get('/summary', [ReportController::class, 'summary']);
});


// Pages route
Route::get('/login-page', [AuthController::class, 'loginPage'])->name('login');
Route::get('/register-page', [AuthController::class, 'registerPage'])->name('register');
Route::get('/send-otp-page', [AuthController::class, 'sendOtpPage'])->name('send-otp');
Route::get('/verify-otp-page', [AuthController::class, 'verifyOtpPage'])->name('verify-otp');
Route::get('/reset-password-page', [AuthController::class, 'resetPasswordPage'])->name('reset-password');

Route::get('/dashboard', [DashboardController::class, 'dashboardPage'])->name('dashboard');
Route::get('/customer', [DashboardController::class, 'customerPage'])->name('customer');
Route::get('/category', [DashboardController::class, 'categoryPage'])->name('category');
Route::get('/product', [DashboardController::class, 'productPage'])->name('product');
Route::get('/sale', [DashboardController::class, 'salePage'])->name('sale');
Route::get('/invoice', [DashboardController::class, 'invoicePage'])->name('invoice');
Route::get('/report', [DashboardController::class, 'reportPage'])->name('report');
Route::get('/profile', [DashboardController::class, 'profilePage'])->name('profile');

Route::view('/', 'home');