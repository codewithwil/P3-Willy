<?php

namespace App\Http\Controllers\API\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FrontC extends Controller
{
    public function index(){
        return view('front.page');
    }
}
