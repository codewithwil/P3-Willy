

<?php

use Illuminate\Support\Facades\Route;

Route::group(["prefix" => "/profile", "as"     => "profile."], __DIR__ . "/assets/profile.php");
Route::group(["prefix" => "/data", "as"     => "data."], __DIR__ . "/assets/data.php");
