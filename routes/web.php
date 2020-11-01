<?php

use App\Http\Controllers\FavoritesController;
use App\Http\Controllers\ProfilesController;
use App\Http\Controllers\RepliesController;
use App\Http\Controllers\ThreadsController;
use App\Http\Controllers\UserNotificationsController;
use App\Http\Controllers\ThreadSubscriptionsController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UsersController;
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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/threads',[ThreadsController::class,'index']);


Route::get('/threads/{channel}/{thread}',[ThreadsController::class,'show']);
Route::get('/threads/create',[ThreadsController::class, 'create']);
Route::post('/threads',[ThreadsController::class, 'store']);

Route::get('/threads/{channel}/{thread}/replies', [RepliesController::class,'index']);
Route::post('/threads/{channel}/{thread}/replies', [RepliesController::class,'store']);

Route::delete('/replies/{reply}', [RepliesController::class,'destroy']);
Route::patch('/replies/{reply}', [RepliesController::class, 'update']);

Route::post('/threads/{channel}/{thread}/subscriptions', [ThreadSubscriptionsController::class,'store'])->middleware('auth');
Route::delete('/threads/{channel}/{thread}/subscriptions', [ThreadSubscriptionsController::class,'destroy'])->middleware('auth');

Route::get('/threads/{channel}',[ThreadsController::class,'index']);

Route::delete('/replies/{reply}/favorites', [FavoritesController::class,'destroy'] );
Route::post('/replies/{reply}/favorites', [FavoritesController::class,'store'] );


Route::get('/profiles/{user}', [ProfilesController::class, 'show'])->name('profile');
Route::delete('/threads/{channel}/{thread}', [ThreadsController::class, 'destroy']);
Route::get('/profiles/{user}/notifications', [UserNotificationsController::class,'index']);
Route::delete('/profiles/{user}/notifications/{notification}', [UserNotificationsController::class,'destroy']);

Route::get('api/users', [UsersController::class, 'index']);

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
