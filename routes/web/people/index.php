

<?php

use Illuminate\Support\Facades\Route;

Route::group(["prefix" => "/users", "as"     => "users."], __DIR__ . "/assets/users.php");