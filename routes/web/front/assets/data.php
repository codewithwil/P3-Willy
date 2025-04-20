
<?php

use App\Http\Controllers as ctr;
use Illuminate\Support\Facades\Route;

Route::post("/getBranch", [ctr\API\Front\FrontC::class, 'getBranch'])->name("getBranch");