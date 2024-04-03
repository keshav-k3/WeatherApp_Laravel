<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\ForecastController;

Route::get('/', function () {
    return view('forecast');
});

Route::get('/forecast', [ForecastController::class, 'showForecast'])->name('forecast');
