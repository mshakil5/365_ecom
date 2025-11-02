<?php

use App\Http\Controllers\ContactContoller;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CustomiserController;

// cache clear
Route::get('/clear', function() {
  Auth::logout();
  session()->flush();
  Artisan::call('cache:clear');
  Artisan::call('config:clear');
  Artisan::call('config:cache');
  Artisan::call('view:clear');
  return "Cleared!";
});

 Route::fallback(function () {
    return redirect('/');
});

require __DIR__.'/admin.php';

Auth::routes();

Route::get('/', [FrontendController::class, 'index'])->name('home');

Route::get('/products/{type?}', [FrontendController::class, 'showProducts'])->name('products.show');

Route::get('/product/{slug}', [FrontendController::class, 'showProduct'])->name('product.show');

Route::get('/search/products', [FrontendController::class, 'search'])->name('search.products');

Route::get('/contact', [FrontendController::class, 'contact'])->name('contact');
Route::post('/contact', [FrontendController::class, 'storeContact'])->name('contact.store');

Route::get('/cart/count', [FrontendController::class, 'getCount'])->name('cart.getCount');

Route::post('/cart/add-session', [FrontendController::class, 'addToSession'])->name('cart.addSession');

// product customization start
Route::get('/customize', [FrontendController::class, 'customize'])->name('customize.index');
Route::post('/customiser/add-to-session', [CustomiserController::class, 'addToSession'])
    ->name('customiser.add_to_session');
// product customization end

Route::get('/about-us', [FrontendController::class, 'aboutUs'])->name('about-us');
Route::get('/privacy-policy', [FrontendController::class, 'privacyPolicy'])->name('privacy-policy');
Route::get('/terms-and-conditions', [FrontendController::class, 'termsAndConditions'])->name('terms-and-conditions');
Route::get('/frequently-asked-questions', [FrontendController::class, 'frequentlyAskedQuestions'])->name('faq');



// Cart list
Route::put('/cart/store', [CartController::class, 'storeCart'])->name('cart.store');
Route::get('/cart', [CartController::class, 'showCart'])->name('cart.index');
Route::post('/cart/update', [CartController::class, 'updateCartItem'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'removeCartItem'])->name('cart.remove');
// checkout
Route::get('/checkout', [CheckoutController::class, 'checkout'])->name('checkout');
Route::post('/checkout', [CheckoutController::class, 'checkout'])->name('checkout.store');



Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');

Route::group(['prefix' =>'manager/', 'middleware' => ['auth', 'is_manager']], function(){
    Route::get('/dashboard', [HomeController::class, 'managerHome'])->name('manager.dashboard');
});

Route::group(['prefix' =>'user/', 'middleware' => ['auth', 'is_user', 'verified']], function(){
  
    Route::get('/dashboard', [HomeController::class, 'userHome'])->name('user.dashboard');

    Route::get('/profile', [UserController::class, 'userProfile'])->name('user.profile');

    Route::post('/update-profile', [UserController::class, 'updateProfile'])->name('user.profile.update');

    Route::get('/change-password', [UserController::class, 'changePassword'])->name('user.change-password');
    Route::post('/change-password', [UserController::class, 'updatePassword'])->name('user.update-password');

    Route::get('/orders', [OrderController::class, 'getOrders'])->name('orders.index');

    Route::get('/orders/{orderId}/details', [OrderController::class, 'showOrderUser'])->name('orders.details');

    Route::post('{orderId}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');

    Route::get('/orders/details', [OrderController::class, 'getOrderDetailsModal'])->name('orders.details.modal');

    Route::post('/order-return', [OrderController::class, 'returnStore'])->name('orders.return');
});