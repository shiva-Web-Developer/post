<?php

use App\Http\Controllers\PostController;
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


Route::group(['middleware' => ['Deleteback']], function(){
    Route::get('/', function () {
        return view('auth.login');
    });
});

Route::get('/user-create',[PostController::class,"create"])->name('user.create');
Route::post('/user-store', [PostController::class, 'store'])->name('user.store');
Route::post('/user-login',[PostController::class,"login"])->name('user.login');

Route::group(['middleware' => ['User']], function(){
    Route::get('/home',[PostController::class,"home"])->name('user.home');
    Route::get('/create-post',[PostController::class,"createpost"])->name('create.post');
    Route::post('/post-save',[PostController::class,"poststore"])->name('post.save');
    Route::get('/posts/edit/{id}',[PostController::class,"edit"])->name('edit.post');
    Route::post('/post-edit',[PostController::class,"postedit"])->name('post.edit');
    Route::get('/posts/delete/{id}',[PostController::class,"postdelete"])->name('post.delete');
});

Route::get('/logout',[PostController::class,"logout"])->name('user.logout');



