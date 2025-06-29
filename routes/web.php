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
use App\Http\Controllers\clients\BlogDetailController;
use App\Http\Controllers\clients\LoginController;
use App\Http\Controllers\clients\LoginGoogleController;
use App\Http\Controllers\clients\LoginFacebookController;
use App\Http\Controllers\clients\UserProfileController;
use App\Http\Controllers\clients\BookingController;
use App\Http\Controllers\clients\TourBookedController;
use App\Http\Controllers\clients\PayPalController;
use App\Http\Controllers\clients\MyTourController;

use App\Http\Controllers\admin\AdminManagementController;
use App\Http\Controllers\admin\BookingManagementController;
use App\Http\Controllers\admin\ContactManagementController;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\LoginAdminController;
use App\Http\Controllers\admin\ToursManagementController;
use App\Http\Controllers\admin\UserManagementController;





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
Route::get(uri: '/destination', action: [DestinationController::class, 'index'])->name (name:'destination');
Route::get(uri: '/tours_guide', action: [ToursGuideController::class, 'index'])->name (name:'tours_guide');
Route::get(uri: '/blogs_detail', action: [BlogDetailController::class, 'index'])->name (name:'blogs_detail');



//get Tour detail and handle submit reviews
Route::get(uri: '/tours_details/{id?}', action: [ToursDetailsController::class, 'index'])->name (name:'tours_details');
Route::post('/checkBooking', [BookingController::class, 'checkBooking'])->name('checkBooking')->middleware('checkLoginClient');
Route::post('/reviews', [ToursDetailsController::class, 'reviews'])->name('reviews')->middleware('checkLoginClient');



//handle login
Route::get(uri: '/login', action: [LoginController::class, 'index'])->name (name:'login');
Route::post(uri: '/register', action: [LoginController::class, 'register'])->name (name:'register');
Route::post(uri: '/login', action: [LoginController::class, 'login'])->name (name:'user-login');
Route::get(uri: '/logout', action: [LoginController::class, 'logout'])->name (name:'logout');
Route::get(uri: 'activate-account/{token}', action: [LoginController::class, 'activateAccount'])->name (name:'activate.account');

//login with GG
Route::get('auth/google', action: [LoginGoogleController::class,'redirectToGoogle'])->name('login-google');
Route::get('auth/google/callback',action: [LoginGoogleController::class, 'handleGoogleCallback']);

//login with Facebook
Route::get('auth/facebook', action: [LoginFacebookController::class,'redirectToFacebook'])->name('login-facebook');
Route::get('auth/facebook/callback',action: [LoginGoogleController::class, 'handleFacebookCallback']);

//hendle get tour
Route::get(uri: '/tours', action: [ToursController::class, 'index'])->name (name:'tours');
Route::get(uri: '/filter-tours', action: [ToursController::class, 'filterTours'])->name (name:'filter-tours');

//handle user profile
Route::get(uri: '/user-profile', action: [UserProfileController::class, 'index'])->name (name:'user-profile');
Route::post(uri: '/user-profile', action: [UserProfileController::class, 'update'])->name (name:'update-user-profile');
Route::post(uri: '/change-password-profile', action: [UserProfileController::class, 'changePassword'])->name (name:'change-password');
Route::post(uri: '/change-avatar-profile', action: [UserProfileController::class, 'changeAvatar'])->name (name:'change-avatar');

//handle booking
Route::post(uri: '/booking/{id?}', action: [BookingController::class, 'index'])->name (name:'booking');
Route::post('/create-booking', [BookingController::class, 'createBooking'])->name('create-booking');
Route::get('/booking', [BookingController::class, 'handlePaymentMomoCallback'])->name('handlePaymentMomoCallback');

//Payment with paypal
Route::get('create-transaction', [PayPalController::class, 'createTransaction'])->name('createTransaction');
Route::get('process-transaction', [PayPalController::class, 'processTransaction'])->name('processTransaction');
Route::get('success-transaction', [PayPalController::class, 'successTransaction'])->name('successTransaction');
Route::get('cancel-transaction', [PayPalController::class, 'cancelTransaction'])->name('cancelTransaction');


//Payment with Momo
Route::post('/create-momo-payment', [BookingController::class, 'createMomoPayment'])->name('createMomoPayment');

//Tour booked
Route::get('/tour-booked', [TourBookedController::class, 'index'])->name('tour-booked')->middleware('checkLoginClient');
Route::post('/cancel-booking', [TourBookedController::class, 'cancelBooking'])->name('cancel-booking');
/* Route::post('/set-booking-period', [TourBookedController::class, 'setBookingPeriod'])->name('set-booking-period'); */

//My tour
Route::get('/my-tours', [MyTourController::class, 'index'])->name('my-tours')->middleware('checkLoginClient');


//Contact
Route::get(uri: '/contact', action: [ContactController::class, 'index'])->name (name:'contact');
Route::post('/create-contact', [ContactController::class, 'createContact'])->name('create-contact');


//Search 
Route::get(uri: '/search', action: [SearchController::class, 'index'])->name (name:'search');
Route::get('/search-voice-text', [SearchController::class, 'searchTours'])->name('search-voice-text');


//ADMIN
// Routes without middleware
Route::prefix('admin')->group(function () {
    Route::get('/login', [LoginAdminController::class, 'index'])->name('admin.login');
    Route::post('/login-account', [LoginAdminController::class, 'loginAdmin'])->name('admin.login-account');
    Route::get('/logout', [LoginAdminController::class, 'logout'])->name('admin.logout');

});

Route::prefix('admin')->middleware('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    //Management admin
    Route::get('/admin', [AdminManagementController::class, 'index'])->name('admin.admin');
    Route::post('/update-admin', [AdminManagementController::class, 'updateAdmin'])->name('admin.update-admin');
    Route::post('/update-avatar', [AdminManagementController::class, 'updateAvatar'])->name('admin.update-avatar');

    //Handler management user
    Route::get('/users', [UserManagementController::class, 'index'])->name('admin.users');
    Route::post('/active-user', [UserManagementController::class, 'activeUser'])->name('admin.active-user');
    Route::post('/status-user', [UserManagementController::class, 'changeStatus'])->name('admin.status-user');

    //Management Tours
    Route::get('/tours', [ToursManagementController::class, 'index'])->name('admin.tours');

    Route::get('/page-add-tours', [ToursManagementController::class, 'pageAddTours'])->name('admin.page-add-tours');
    Route::post('/add-tours', [ToursManagementController::class, 'addTours'])->name('admin.add-tours');
    Route::post('/add-images-tours', [ToursManagementController::class, 'addImagesTours'])->name('admin.add-images-tours');
    Route::post('/add-timeline', [ToursManagementController::class, 'addTimeline'])->name('admin.add-timeline');

    Route::post('/delete-tour', [ToursManagementController::class, 'deleteTour'])->name('admin.delete-tour');

    Route::get('/tour-edit', [ToursManagementController::class, 'getTourEdit'])->name('admin.tour-edit');
    Route::post('/edit-tour', [ToursManagementController::class, 'updateTour'])->name('admin.edit-tour');
    Route::post('/add-temp-images', [ToursManagementController::class, 'uploadTempImagesTours'])->name('admin.add-temp-images');
    

    //Management Booking
    Route::get('/booking', [BookingManagementController::class, 'index'])->name('admin.booking');
    Route::post('/confirm-booking', [BookingManagementController::class, 'confirmBooking'])->name('admin.confirm-booking');
    Route::get('/booking-detail/{id?}', [BookingManagementController::class, 'showDetail'])->name('admin.booking-detail');
    Route::post('/finish-booking', [BookingManagementController::class, 'finishBooking'])->name('admin.finish-booking');
    Route::match(['get', 'post'],'/received-money', [BookingManagementController::class, 'receiviedMoney'])->name('admin.received');

    //Send mail pdf
    Route::post('/admin/send-pdf', [BookingManagementController::class, 'sendPdf'])->name('admin.send.pdf');

    //Contact management
    Route::get('/contact', [ContactManagementController::class, 'index'])->name('admin.contact');
    Route::post('/reply-contact', [ContactManagementController::class, 'replyContact'])->name('admin.reply-contact');

});


