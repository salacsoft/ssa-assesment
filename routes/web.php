<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\AuthController;

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

Route::get("login", [AuthController::class, "login"])->name("auth.login")->middleware("guest");
Route::post("login", [AuthController::class, "authenticate"])->name("auth.authenticate");
Route::post("logout", [AuthController::class, "logout"])->name("auth.logout");


Route::get("register", [UserController::class, "register"])->name("users.register");
Route::post("register", [UserController::class, "create"])->name("users.create");
Route::get("users", [UserController::class, "index"])->name("users.index");
Route::get("users/{id}/show", [UserController::class, "show"])->name("users.show");
Route::get("users/{id}/edit", [UserController::class, "edit"])->name("users.edit");
Route::patch("users/{id}/update", [UserController::class, "update"])->name("users.update");
Route::softDeletes();
Route::redirect('/', 'users');

