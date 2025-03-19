<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ServerMetricController;

Route::post('/metrics', [ServerMetricController::class, 'store']);