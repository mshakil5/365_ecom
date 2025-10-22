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
Route::get('/products/latest', [FrontendController::class, 'latestProducts'])->name('products.latest');
Route::get('/product/{id}', [FrontendController::class, 'showProduct'])->name('product.show');
Route::get('/contact', [FrontendController::class, 'contact'])->name('contact');
Route::post('/contact', [FrontendController::class, 'storeContact'])->name('contact.store');

Route::get('/privacy-policy', [FrontendController::class, 'privacyPolicy'])->name('privacy-policy');
Route::get('/terms-and-conditions', [FrontendController::class, 'termsAndConditions'])->name('terms-and-conditions');
Route::get('/frequently-asked-questions', [FrontendController::class, 'frequentlyAskedQuestions'])->name('faq');

Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');

Route::group(['prefix' =>'manager/', 'middleware' => ['auth', 'is_manager']], function(){
    Route::get('/dashboard', [HomeController::class, 'managerHome'])->name('manager.dashboard');
});

Route::group(['prefix' =>'user/', 'middleware' => ['auth', 'is_user']], function(){
    Route::get('/dashboard', [HomeController::class, 'userHome'])->name('user.dashboard');
});