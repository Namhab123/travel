<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\clients\HomeController;
use Illuminate\Notifications\Action;
use App\Http\Controllers\clients\AboutController;
use App\Http\Controllers\clients\ToursController;
use App\Http\Controllers\clients\ToursGuideController;
use App\Http\Controllers\clients\ToursDetailsController;
use App\Http\Controllers\clients\SearchController;
use App\Http\Controllers\clients\DestinationController;
use App\Http\Controllers\clients\ContactController;
use App\Http\Controllers\clients\BlogController;
use App\Http\Controllers\clients\BlogDetailController;
use App\Http\Controllers\clients\LoginController;
use App\Http\Controllers\clients\LoginGoogleController;
use App\Http\Controllers\clients\UserProfileController;

/* use Illuminate\Support\Facades\Mail; */




/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

/*Route::get('/', function () {
    return view('welcome');
});
*/
Route::get(uri: '/', action: [HomeController::class, 'index'])->name (name:'home');
Route::get(uri: '/about', action: [AboutController::class, 'index'])->name (name:'about');
Route::get(uri: '/tours_guide', action: [ToursGuideController::class, 'index'])->name (name:'tours_guide');
Route::get(uri: '/tours_details/{id?}', action: [ToursDetailsController::class, 'index'])->name (name:'tours_details');
Route::get(uri: '/search', action: [SearchController::class, 'index'])->name (name:'search');
Route::get(uri: '/destination', action: [DestinationController::class, 'index'])->name (name:'destination');
Route::get(uri: '/contact', action: [ContactController::class, 'index'])->name (name:'contact');
Route::get(uri: '/blogs', action: [BlogController::class, 'index'])->name (name:'blogs');
Route::get(uri: '/blogs_detail', action: [BlogDetailController::class, 'index'])->name (name:'blogs_detail');


//handle login
Route::get(uri: '/login', action: [LoginController::class, 'index'])->name (name:'login');
Route::post(uri: '/register', action: [LoginController::class, 'register'])->name (name:'register');
Route::post(uri: '/login', action: [LoginController::class, 'login'])->name (name:'user-login');
Route::get(uri: '/logout', action: [LoginController::class, 'logout'])->name (name:'logout');
Route::get(uri: 'activate-account/{token}', action: [LoginController::class, 'activateAccount'])->name (name:'activate.account');

//login with GG
Route::get('auth/google', action: [LoginGoogleController::class,'redirectToGoogle'])->name('login-google');
Route::get('auth/google/callback',action: [LoginGoogleController::class, 'handleGoogleCallback']);


//hendle get tour
Route::get(uri: '/tours', action: [ToursController::class, 'index'])->name (name:'tours');
Route::get(uri: '/filter-tours', action: [ToursController::class, 'filterTours'])->name (name:'filter-tours');

//handle user profile
Route::get(uri: '/user-profile', action: [UserProfileController::class, 'index'])->name (name:'user-profile');
Route::post(uri: '/user-profile', action: [UserProfileController::class, 'update'])->name (name:'update-user-profile');
Route::post(uri: '/change-password-profile', action: [UserProfileController::class, 'changePassword'])->name (name:'change-password');
Route::post(uri: '/change-avatar-profile', action: [UserProfileController::class, 'changeAvatar'])->name (name:'change-avatar');


