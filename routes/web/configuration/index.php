

<?php

use Illuminate\Support\Facades\Route;

Route::group(["prefix" => "/company", "as"     => "company."], __DIR__ . "/assets/company.php");
Route::group(["prefix" => "/category", "as"     => "category."], __DIR__ . "/assets/category.php");
Route::group(["prefix" => "/unit", "as"     => "unit."], __DIR__ . "/assets/unit.php");
Route::group(["prefix" => "/building", "as"     => "building."], __DIR__ . "/assets/building.php");
Route::group(["prefix" => "/rooms", "as"     => "rooms."], __DIR__ . "/assets/rooms.php");
Route::group(["prefix" => "/supplier", "as"     => "supplier."], __DIR__ . "/assets/supplier.php");
Route::group(["prefix" => "/typeVehicle", "as"     => "typeVehicle."], __DIR__ . "/assets/typeVehicle.php");