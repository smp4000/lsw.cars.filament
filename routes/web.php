<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\VehicleController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::get('/fahrzeuge',          [VehicleController::class, 'index'])->name('vehicles.index');
Route::get('/fahrzeug/{vehicle}', [VehicleController::class, 'show'])->name('vehicles.show');

Route::get('/fahrzeug-id/{id}', function (int $id) {
    $vehicle = \App\Models\Vehicle::findOrFail($id);
    return redirect()->route('vehicles.show', $vehicle, 301);
})->where('id', '[0-9]+');

Route::get('/leistungen',  [PageController::class, 'services'])->name('services');
Route::get('/ueber-uns',   [PageController::class, 'about'])->name('about');
Route::get('/impressum',   [PageController::class, 'impressum'])->name('legal.impressum');
Route::get('/datenschutz', [PageController::class, 'datenschutz'])->name('legal.datenschutz');

Route::get('/kontakt',   [ContactController::class, 'show'])->name('contact');
Route::post('/kontakt',  [ContactController::class, 'store'])->name('contact.store');

Route::get('/sitemap.xml', function () {
    $vehicles = \App\Models\Vehicle::where('verfuegbar', true)->orderByDesc('updated_at')->get();
    return response()
        ->view('seo.sitemap', compact('vehicles'))
        ->header('Content-Type', 'application/xml');
})->name('sitemap');

