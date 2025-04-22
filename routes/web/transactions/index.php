<?php

use Illuminate\Support\Facades\Route;

Route::group(["prefix" => "/order", "as"    => "order."], __DIR__ . "/assets/order.php");