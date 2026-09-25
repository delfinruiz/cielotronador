<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\PolicyController;
use Illuminate\Support\Facades\Route;

Route::get('/', LandingController::class)->name('landing');

Route::post('/contacto', ContactController::class)->name('contacto');

Route::get('/politica-de-privacidad', [PolicyController::class, 'privacidad'])->name('politica.privacidad');
Route::get('/politica-de-cookies', [PolicyController::class, 'cookies'])->name('politica.cookies');
Route::get('/politica-de-datos', [PolicyController::class, 'datos'])->name('politica.datos');
