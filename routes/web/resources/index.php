

<?php

use Illuminate\Support\Facades\Route;

Route::group(["prefix" => "/shift", "as"     => "shift."], __DIR__ . "/assets/shift.php");
Route::group(["prefix" => "/empShift", "as"     => "empShift."], __DIR__ . "/assets/empShift.php");
Route::group(["prefix" => "/brandMotor", "as"     => "brandMotor."], __DIR__ . "/assets/brandMotor.php");