

<?php

use Illuminate\Support\Facades\Route;

Route::group(["prefix" => "/branch", "as"    => "branch."], __DIR__ . "/assets/branch.php");
Route::group(["prefix" => "/member", "as"    => "member."], __DIR__ . "/assets/member.php");
Route::group(["prefix" => "/promo", "as"     => "promo."], __DIR__ . "/assets/promo.php");
Route::group(["prefix" => "/service", "as"   => "service."], __DIR__ . "/assets/service.php");