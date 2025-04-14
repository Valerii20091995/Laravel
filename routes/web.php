<?php


use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;


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
    Route::get('/cart', [CartController::class, 'getCart'])->name('cart');
    Route::post('/add-product', [CartController::class, 'addProductToCart'])->name('addProduct');
    Route::post('/decrease-product', [CartController::class, 'decreaseProductFromCart'])->name('decreaseProduct');
    Route::get('/profile', [UserController::class, 'getProfile'])->name('profile');
    Route::get('/edit-profile', [UserController::class, 'getEditProfile'])->name('editProfile.form');
    Route::post('/edit-profile', [UserController::class, 'editProfile'])->name('editProfile.submit');
    Route::get('/logout', [UserController::class, 'logout'])->name('logout');
    Route::get('/catalog', [ProductController::class, 'getCatalog']);
    Route::get('/product/{id}', [ProductController::class, 'getProduct'])->name('product');
    Route::post('/add-review', [ProductController::class, 'addReview'])->name('addReview');
    Route::get('/create-order', [OrderController::class, 'getCheckoutForm'])->name('createOrder');
    Route::post('create-order', [OrderController::class, 'handleCheckout'])->name('createOrder.submit');
    Route::get('/user-orders', [OrderController::class, 'getAll'])->name('user-orders');
});
