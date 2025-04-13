

<?php

use Illuminate\Support\Facades\Route;

Route::group(["prefix" => "/stockReport", "as"     => "stockReport."], __DIR__ . "/assets/stockReport.php");