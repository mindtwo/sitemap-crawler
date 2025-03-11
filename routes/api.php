<?php

use App\Http\Controllers\DomainController;
use App\Http\Controllers\LocationController;
use Illuminate\Support\Facades\Route;

Route::get('/v1/domains', [DomainController::class, 'index']);
Route::get('/v1/domains/{domain}/locations', [LocationController::class, 'index']);
