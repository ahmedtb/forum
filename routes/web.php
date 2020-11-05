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
use App\Http\Controllers\Api\UserAvatarController;
use App\Http\Controllers\RegisterConfirmationController;

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

Route::get('/threads',[ThreadsController::class,'index'])->name('threads');;


Route::get('/threads/{channel}/{thread}',[ThreadsController::class,'show']);
Route::get('/threads/create',[ThreadsController::class, 'create']);

Route::post('/threads',[ThreadsController::class, 'store'])->middleware('must-be-confirmed');

Route::get('/threads/{channel}/{thread}/replies', [RepliesController::class,'index']);
Route::post('/threads/{channel}/{thread}/replies', [RepliesController::class,'store']);

Route::post('locked-threads/{thread}', 'App\Http\Controllers\LockedThreadsController@store')->name('locked-threads.store')->middleware('admin');
Route::delete('locked-threads/{thread}', 'App\Http\Controllers\LockedThreadsController@destroy')->name('locked-threads.destroy')->middleware('admin');


Route::delete('/replies/{reply}', [RepliesController::class,'destroy'])->name('replies.destroy');
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

Route::get('/register/confirm', [RegisterConfirmationController::class,'index'] )->name('register.confirm');

Route::post('/replies/{reply}/best', 'App\Http\Controllers\BestRepliesController@store')->name('best-replies.store');

Route::get('api/users', [UsersController::class, 'index']);
Route::post('api/users/{user}/avatar', [UserAvatarController::class,'store'])->middleware('auth')->name('avatar');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
