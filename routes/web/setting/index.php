

<?php

use Illuminate\Support\Facades\Route;

Route::group(["prefix" => "/branch", "as"     => "branch."], __DIR__ . "/assets/branch.php");