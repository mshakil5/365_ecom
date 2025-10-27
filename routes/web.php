<?php

use App\Http\Controllers\ContactContoller;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\FrontendController;

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
Route::get('/category/{slug}', [FrontendController::class, 'showCategoryProducts'])->name('category.show');
Route::get('/sub-category/{slug}', [FrontendController::class, 'showSubCategoryProducts'])->name('subcategory.show');
Route::get('/products/latest', [FrontendController::class, 'latestProducts'])->name('products.latest');
Route::get('/product/{slug}', [FrontendController::class, 'showProduct'])->name('product.show');
Route::get('/contact', [FrontendController::class, 'contact'])->name('contact');
Route::post('/contact', [FrontendController::class, 'storeContact'])->name('contact.store');
Route::get('/product/customize/{product}', [FrontendController::class, 'customizeProduct'])->name('product.customize');

Route::get('/about-us', [FrontendController::class, 'aboutUs'])->name('about-us');
Route::get('/privacy-policy', [FrontendController::class, 'privacyPolicy'])->name('privacy-policy');
Route::get('/terms-and-conditions', [FrontendController::class, 'termsAndConditions'])->name('terms-and-conditions');
Route::get('/frequently-asked-questions', [FrontendController::class, 'frequentlyAskedQuestions'])->name('faq');

Route::get('/get-products/{ptype?}', [FrontendController::class, 'getDiffTypeProducts'])->name('getDiffTypeProducts');

// Wish list
Route::put('/wishlist/store', [FrontendController::class, 'storeWishlist'])->name('wishlist.store');
Route::get('/wishlist', [FrontendController::class, 'showWishlist'])->name('wishlist.index');

// Cart list
Route::put('/cart/store', [FrontendController::class, 'storeCart'])->name('cart.store');
Route::get('/cart', [FrontendController::class, 'showCart'])->name('cart.index');
Route::post('/cart/remove', [FrontendController::class, 'removeCartItem'])->name('cart.remove');

Route::get('/shop', [FrontendController::class, 'shop'])->name('frontend.shop');

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