

<?php

use Illuminate\Support\Facades\Route;

Route::group(["prefix" => "/locations", "as"     => "locations."], __DIR__ . "/assets/locations.php");
Route::group(["prefix" => "/presences", "as"     => "presences."], __DIR__ . "/assets/presences.php");