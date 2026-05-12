<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\VehicleController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::get('/fahrzeuge',          [VehicleController::class, 'index'])->name('vehicles.index');
Route::get('/fahrzeug/{vehicle}', [VehicleController::class, 'show'])->name('vehicles.show');

Route::get('/leistungen',  [PageController::class, 'services'])->name('services');
Route::get('/ueber-uns',   [PageController::class, 'about'])->name('about');
Route::get('/impressum',   [PageController::class, 'impressum'])->name('legal.impressum');
Route::get('/datenschutz', [PageController::class, 'datenschutz'])->name('legal.datenschutz');

Route::get('/kontakt',   [ContactController::class, 'show'])->name('contact');
Route::post('/kontakt',  [ContactController::class, 'store'])->name('contact.store');
