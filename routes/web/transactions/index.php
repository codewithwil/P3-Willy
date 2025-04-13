

<?php

use Illuminate\Support\Facades\Route;

Route::group(["prefix" => "/commodities", "as"     => "commodities."], __DIR__ . "/assets/commodities.php");
Route::group(["prefix" => "/loanings", "as"     => "loanings."], __DIR__ . "/assets/loanings.php");
Route::group(["prefix" => "/loaningsApps", "as"     => "loaningsApps."], __DIR__ . "/assets/loaningsApps.php");
Route::group(["prefix" => "/services", "as"     => "services."], __DIR__ . "/assets/services.php");
Route::group(["prefix" => "/serviceApps", "as"     => "serviceApps."], __DIR__ . "/assets/serviceApps.php");
Route::group(["prefix" => "/outCommod", "as"     => "outCommod."], __DIR__ . "/assets/outCommod.php");
Route::group(["prefix" => "/outCommodApps", "as"     => "outCommodApps."], __DIR__ . "/assets/outCommodApps.php");
Route::group(["prefix" => "/comeCommod", "as"     => "comeCommod."], __DIR__ . "/assets/comeCommod.php");
Route::group(["prefix" => "/comeCommodApps", "as"     => "comeCommodApps."], __DIR__ . "/assets/comeCommodApps.php");
Route::group(["prefix" => "/serviceV", "as"     => "serviceV."], __DIR__ . "/assets/serviceV.php");