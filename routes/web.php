<?php
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostCommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PostTagController;
use App\Http\Controllers\UserComment;
use App\Http\Controllers\UserController;
use App\Models\Author;
use App\Models\User;
use Illuminate\Auth\Events\Login;

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

// Route::get('/', function () {
//     return view('home');
// // });
// Route::get('manual/login',[UserController::class,'getLogin']);
// Route::post('manual/login',[UserController::class,'postLogin'])->name('login');
// Route::get('manual/register',[UserController::class,'getRegister']);
// Route::post('manual/register',[UserController::class,'postRegister'])->name('register');
Route::get('/',[HomeController::class , 'home'])->name('home');

// Route::get('/contact', function () {
//     return view('contact');
// });
Auth::routes();
Route::view('/contact', [HomeController::class,'contact'])->name('contact');
Route::get('/secret',[HomeController::class,'secret'])
->name('secret')
->middleware('can:home.secret');
Route::resource('posts', PostController::class);

Route::get('/posts/tag/{tag}',[PostTagController::class,'index'])->name('posts.tag.index');
Auth::routes();
Route::resource('posts.comments',PostCommentController::class)->only(['index','store']);
Route::resource('users',UserController::class)->only(['show','edit','update']);
Route::get('/home', [HomeController::class, 'home'])->name('home');
Route::resource('users.comments',UserComment::class)->only('store');