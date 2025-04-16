<?php


use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ReviewController;

Route::get('/', function () {
    return view('welcome');
});
Route::middleware('guest')->group(function ()
{
    Route::get('/signUp', [UserController::class, 'getSignUp'])->name('signUp');
    Route::post('/signUp', [UserController::class, 'signUp'])->name('signUp.post');
    Route::get('/login', [UserController::class, 'getLogin'])->name('login');
    Route::post('/login', [UserController::class, 'login'])->name('login.submit');
});
Route::middleware('auth')->group(function ()
{
    Route::get('/catalog', [ProductController::class, 'getCatalog'])->name('catalog');
    Route::get('/profile', [UserController::class, 'getProfile'])->name('profile');
    Route::get('/logout', [UserController::class, 'logout'])->name('logout');
    Route::get('/edit-profile', [UserController::class, 'getEditProfile'])->name('editProfile.form');
    Route::post('/edit-profile', [UserController::class, 'editProfile'])->name('editProfile.submit');
    Route::get('/cart', [CartController::class, 'getCart'])->name('cart');
    Route::post('/add-product', [CartController::class, 'addProduct'])->name('addProduct');
    Route::post('/decrease-product', [CartController::class, 'decreaseProduct'])->name('decreaseProduct');

    Route::get('/product-review', [\App\Http\Controllers\ReviewController::class, 'getProduct'])->name('product.show');
    Route::post('/add-review/{product}', [\App\Http\Controllers\ReviewController::class, 'addReview'])->name('review.store');
    Route::get('/create-order', [OrderController::class, 'getCheckoutForm'])->name('createOrder');
    Route::post('create-order', [OrderController::class, 'handleCheckout'])->name('createOrder.submit');
    Route::get('/user-orders', [OrderController::class, 'getAll'])->name('user-orders');
});
