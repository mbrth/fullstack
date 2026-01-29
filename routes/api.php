<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\AnalyzeController;
use App\Http\Controllers\DashboardController;

// Routes publiques (authentification)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Routes protégées par authentification
Route::middleware('auth:sanctum')->group(function () {
    // Authentification
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    // Gestion des avis (CRUD)
    Route::apiResource('reviews', ReviewController::class);
    Route::post('/reviews/{review}/reanalyze', [ReviewController::class, 'reanalyze']);

    // Analyse IA manuelle
    Route::post('/analyze', [AnalyzeController::class, 'analyze']);

    // Tableau de bord
    Route::get('/dashboard/stats', [DashboardController::class, 'stats']);
});

