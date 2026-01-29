<?php

use Illuminate\Support\Facades\Route;

// Redirection vers le frontend
Route::get('/', function () {
    return redirect('/frontend/index.html');
});

// Route pour faciliter l'accès direct
Route::get('/login', function () {
    return redirect('/frontend/index.html');
});

Route::get('/register', function () {
    return redirect('/frontend/index.html');
});

Route::get('/dashboard', function () {
    return redirect('/frontend/dashboard.html');
});

Route::get('/reviews', function () {
    return redirect('/frontend/reviews.html');
});
