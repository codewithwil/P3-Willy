<?php

use App\Http\Controllers as ctr;
use Illuminate\Support\Facades\Route;

Route::get("/", [ctr\API\Resources\Member\MemberC::class, 'indexBack'])->name("indexBack");
Route::get("/invoice", [ctr\API\Resources\Member\MemberC::class, 'invoice'])->name("invoice");