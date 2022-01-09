<?php

use App\Http\Controllers\Admin\Auth\LoginController AS AdminLoginController;
use App\Http\Controllers\Admin\Auth\ForgotPasswordController AS AdminForgotPasswordController;
use App\Http\Controllers\Admin\Auth\ResetPasswordController AS AdminResetPasswordController;
use App\Http\Controllers\Admin\HomeController AS AdminHomeController;
use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\User\Auth\LoginController AS UserLoginController;
use App\Http\Controllers\User\Auth\RegisterController;
use App\Http\Controllers\User\Auth\ForgotPasswordController AS UserForgotPasswordController;
use App\Http\Controllers\User\Auth\ResetPasswordController AS UserResetPasswordController;
use App\Http\Controllers\User\HomeController AS UserHomeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

// Auth::routes();

Route::get('/home', [App\Http\Controllers\User\HomeController::class, 'index'])->name('home');

Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::group(['middleware' => 'guest:admin'], function () {
        // Authentication Routes...
        Route::get('login', [AdminLoginController::class, 'showLoginForm'])->name('login');
        Route::post('login', [AdminLoginController::class, 'login'])->name('login');

        // Password Reset Routes...
        Route::get('password/reset', [AdminForgotPasswordController::class ,'showLinkRequestForm'])->name('password.request');
        Route::post('password/email', [AdminForgotPasswordController::class ,'sendResetLinkEmail'])->name('password.email');
        Route::get('password/reset/{token}', [AdminResetPasswordController::class ,'showResetForm'])->name('password.reset');
        Route::post('password/reset', [AdminResetPasswordController::class ,'reset'])->name('password.update');
    });
    Route::group(['middleware' => 'admin.auth'], function () {
        Route::get('/', [AdminHomeController::class, 'index'])->name('dashboard');
        Route::post('logout', [AdminLoginController::class, 'logout'])->name('logout');
        Route::resource('posts', PostController::class);
        Route::resource('comments', CommentController::class);
    });
});


Route::group(['middleware' => 'guest'], function () {
    // Authentication Routes...
    Route::get('login', [UserLoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [UserLoginController::class, 'login'])->name('login');

    // Registration Routes...
    Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [RegisterController::class, 'register'])->name('register');

    // Password Reset Routes...
    Route::get('password/reset', [UserForgotPasswordController::class ,'showLinkRequestForm'])->name('password.request');
    Route::post('password/email', [UserForgotPasswordController::class ,'sendResetLinkEmail'])->name('password.email');
    Route::get('password/reset/{token}', [UserResetPasswordController::class ,'showResetForm'])->name('password.reset');
    Route::post('password/reset', [UserResetPasswordController::class ,'reset'])->name('password.update');
});
Route::group(['middleware' => 'auth'], function () {
    Route::get('/', [UserHomeController::class, 'index'])->name('dashboard');
    Route::post('/add-post', [UserHomeController::class, 'addPost'])->name('home.add-post');
    Route::post('/add-comment/{id}', [UserHomeController::class, 'addComment'])->name('home.add-comment');
    Route::post('logout', [UserLoginController::class, 'logout'])->name('logout');
});


// // Confirm Password (added in v6.2)
// Route::get('password/confirm', 'Auth\ConfirmPasswordController@showConfirmForm')->name('password.confirm');
// Route::post('password/confirm', 'Auth\ConfirmPasswordController@confirm');

// // Email Verification Routes...
// Route::get('email/verify', 'Auth\VerificationController@show')->name('verification.notice');
// Route::get('email/verify/{id}/{hash}', 'Auth\VerificationController@verify')->name('verification.verify'); // v6.x
// /* Route::get('email/verify/{id}', 'Auth\VerificationController@verify')->name('verification.verify'); // v5.x */
// Route::get('email/resend', 'Auth\VerificationController@resend')->name('verification.resend');

