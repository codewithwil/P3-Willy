
<?php

use App\Http\Controllers as ctr;
use Illuminate\Support\Facades\Route;


Route::get("/success", [ctr\API\People\Customer\CustomerC::class, 'success'])->name("payment.success");
Route::get("/{customerId}", [ctr\API\People\Customer\CustomerC::class, 'profile'])->name("profile");
Route::get("/historySaldo/{customerId}", [ctr\API\People\Customer\CustomerC::class, 'historySaldo'])->name("historySaldo");
Route::get("/topup/{customerId}", [ctr\API\People\Customer\CustomerC::class, 'topup'])->name("topup");
Route::post("/topupStore", [ctr\API\People\Customer\CustomerC::class, 'topupStore'])->name("topupStore");