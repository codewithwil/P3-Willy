

<?php

use Illuminate\Support\Facades\Route;

Route::group(["prefix" => "/profile", "as"     => "profile."], __DIR__ . "/assets/profile.php");
