<?php

use App\Http\Controllers\API\Auth\AuthC;
use App\Http\Controllers\API\Dashboard\DashboardC;
use App\Http\Controllers\API\Front\FrontC;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::middleware(['guest'])->group(function(){
    Route::get('/login', [AuthC::class, 'index'])->name('login');
    Route::get('/register', [AuthC::class, 'regis'])->name('regis');
    Route::post('/login', [AuthC::class, 'login']);
    Route::post('/register', [AuthC::class, 'register'])->name('register');
});


Route::middleware(['auth'])->group(function(){
    Route::get('/dashboard', [DashboardC::class, 'index'])->name('dashboard');
    Route::get('/order', [FrontC::class, 'index'])->name('order');
    Route::get('/logout', [AuthC::class, 'logout'])->name('logout');

    //nested route
    Route::group(["prefix" => "/people", "as" => "people."], __DIR__ . "/web/people/index.php");
    Route::group(["prefix" => "/setting", "as" => "setting."], __DIR__ . "/web/setting/index.php");
    Route::group(["prefix" => "/configuration", "as" => "configuration."], __DIR__ . "/web/configuration/index.php");
    // Route::group(["prefix" => "/transactions", "as" => "transactions."], __DIR__ . "/web/transactions/index.php");
    Route::group(["prefix" => "/report", "as" => "report."], __DIR__ . "/web/report/index.php");
});