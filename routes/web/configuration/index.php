

<?php

use Illuminate\Support\Facades\Route;

Route::group(["prefix" => "/company", "as"     => "company."], __DIR__ . "/assets/company.php");
Route::group(["prefix" => "/category", "as"     => "category."], __DIR__ . "/assets/category.php");
Route::group(["prefix" => "/member", "as"     => "member."], __DIR__ . "/assets/member.php");